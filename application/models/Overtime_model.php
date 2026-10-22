<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Overtime_model extends CI_Model {

    public function generate_spl_number($date) {
        $prefix = "SPL/" . date('Ym', strtotime($date)) . "/";
        $this->db->like('spl_number', $prefix, 'after');
        $this->db->from('overtime_requests');
        $count = $this->db->count_all_results();
        return $prefix . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    public function insert_spl($data) {
        return $this->db->insert('overtime_requests', $data);
    }

    public function get_employee_spl_history($employee_id) {
        return $this->db->where('employee_id', $employee_id)
            ->order_by('id', 'DESC')
            ->get('overtime_requests')
            ->result();
    }

    /**
     * Riwayat keputusan yang telah dibuat supervisor (Approved / Rejected)
     */
    public function get_supervisor_decision_history($supervisor_id, $limit = 20) {
        return $this->db->select('ot.*, e.full_name, e.nip, d.dept_name')
            ->from('overtime_requests ot')
            ->join('employees e', 'e.id = ot.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->where_in('ot.status', ['APPROVED', 'REJECTED'])
            ->where('ot.approved_by', $supervisor_id)
            ->order_by('ot.updated_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Arsip seluruh SPL pabrik untuk pemantauan HR Administrator
     */
    public function get_all_spl_archive($limit = 50) {
        return $this->db->select('ot.*, e.full_name, e.nip, e.base_salary, d.dept_name, app.full_name AS approver_name')
            ->from('overtime_requests ot')
            ->join('employees e', 'e.id = ot.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->join('employees app', 'app.id = ot.approved_by', 'left')
            ->order_by('ot.id', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    /**
     * Filter Arsip SPL berdasarkan status dan departemen
     */
    public function get_filtered_spl_archive($status = null, $dept_id = null, $date = null) {
        $this->db->select('ot.*, e.full_name, e.nip, e.base_salary, d.dept_name, app.full_name AS approver_name')
            ->from('overtime_requests ot')
            ->join('employees e', 'e.id = ot.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->join('employees app', 'app.id = ot.approved_by', 'left');

        if (!empty($status)) {
            $this->db->where('ot.status', $status);
        }
        if (!empty($dept_id)) {
            $this->db->where('e.department_id', (int) $dept_id);
        }
        if (!empty($date)) {
            $this->db->where('ot.overtime_date', $date);
        }

        return $this->db->order_by('ot.id', 'DESC')->get()->result();
    }

    /**
     * Hitung Pengali & Estimasi Upah Lembur Sesuai Regulasi Depnaker / PP No. 35/2021
     * Jam ke-1: 1.5x upah per jam
     * Jam ke-2 dst: 2.0x upah per jam
     * Upah per jam = Gaji Pokok / 173
     */
    public function calculate_pp35_overtime($hours, $base_salary = 4500000) {
        $hours = (float) $hours;
        if ($hours <= 0) {
            return [
                'equivalent_hours' => 0,
                'hourly_rate'      => 0,
                'estimated_pay'    => 0,
                'breakdown'        => '0 Jam'
            ];
        }
        
        if ($hours <= 1.0) {
            $eq_hours = $hours * 1.5;
            $breakdown = "{$hours} jam x 1.5x";
        } else {
            $first_hr = 1.0 * 1.5;
            $rem_hrs  = ($hours - 1.0) * 2.0;
            $eq_hours = $first_hr + $rem_hrs;
            $breakdown = "1 jam x 1.5x + " . ($hours - 1) . " jam x 2.0x";
        }
        
        $hourly_rate = round($base_salary / 173, 2);
        $estimated_pay = round($eq_hours * $hourly_rate);

        return [
            'equivalent_hours' => round($eq_hours, 2),
            'hourly_rate'      => $hourly_rate,
            'estimated_pay'    => $estimated_pay,
            'breakdown'        => $breakdown
        ];
    }

    /**
     * Solusi Masalah #5: Cegah Duplikasi Pengajuan Tiket Lembur (Anti Double-Submit)
     * Memastikan operator tidak dapat mengirim SPL ganda untuk tanggal yang sama jika tiket sebelumnya masih PENDING atau APPROVED.
     */
    public function has_active_spl($employee_id, $date) {
        return $this->db->where('employee_id', (int) $employee_id)
            ->where('overtime_date', $date)
            ->where_in('status', ['PENDING', 'APPROVED'])
            ->count_all_results('overtime_requests') > 0;
    }
}
