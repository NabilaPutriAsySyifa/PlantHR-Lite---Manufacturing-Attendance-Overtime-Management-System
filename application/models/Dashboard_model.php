<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function get_hr_summary() {
        $today = date('Y-m-d');
        $total_active = $this->db->where('is_active', 1)->count_all_results('employees');
        $today_present = $this->db->where('attendance_date', $today)->where_in('status', ['PRESENT', 'LATE'])->count_all_results('attendances');
        $today_late = $this->db->where('attendance_date', $today)->where('status', 'LATE')->count_all_results('attendances');
        
        // Tingkat kedisiplinan (On-Time Attendance Rate %)
        $on_time_rate = ($today_present > 0) ? round((($today_present - $today_late) / $today_present) * 100, 1) : 100;

        return [
            'total_employees' => $total_active,
            'total_dept'      => $this->db->count_all('departments'),
            'today_present'   => $today_present,
            'today_late'      => $today_late,
            'on_time_rate'    => $on_time_rate,
            'pending_spl'     => $this->db->where('status', 'PENDING')->count_all_results('overtime_requests'),
            'total_shifts'    => $this->db->count_all('shifts')
        ];
    }

    public function get_pending_spl() {
        return $this->db->select('ot.*, e.full_name, e.nip, d.dept_name')
            ->from('overtime_requests ot')
            ->join('employees e', 'e.id = ot.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->where('ot.status', 'PENDING')
            ->order_by('ot.id', 'DESC')
            ->get()
            ->result();
    }

    public function get_operator_attendances($employee_id) {
        return $this->db->where('employee_id', $employee_id)
            ->order_by('attendance_date', 'DESC')
            ->limit(14)
            ->get('attendances')
            ->result();
    }

    /**
     * Data Master Karyawan Lengkap untuk HR Admin
     */
    public function get_all_employees() {
        return $this->db->select('e.*, d.dept_name, d.dept_code, s.shift_name, s.start_time, s.end_time, s.is_cross_day')
            ->from('employees e')
            ->join('departments d', 'd.id = e.department_id', 'left')
            ->join('shifts s', 's.id = e.shift_id', 'left')
            ->order_by('e.role', 'ASC')
            ->order_by('e.full_name', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Tambah Karyawan Baru
     */
    public function insert_employee($data) {
        return $this->db->insert('employees', $data);
    }

    /**
     * Update Data Karyawan
     */
    public function update_employee($id, $data) {
        return $this->db->where('id', (int) $id)->update('employees', $data);
    }

    /**
     * Ubah status aktif/nonaktif karyawan
     */
    public function toggle_employee_status($id) {
        $emp = $this->db->get_where('employees', ['id' => (int) $id])->row();
        if ($emp) {
            $new_status = $emp->is_active ? 0 : 1;
            return $this->db->where('id', (int) $id)->update('employees', ['is_active' => $new_status]);
        }
        return false;
    }

    /**
     * Data Master Departemen dengan agregasi jumlah personil
     */
    public function get_departments_with_count() {
        return $this->db->select('d.*, COUNT(e.id) AS total_member')
            ->from('departments d')
            ->join('employees e', 'e.department_id = d.id AND e.is_active = 1', 'left')
            ->group_by('d.id')
            ->order_by('d.dept_code', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Data Master Pola Shift Kerja
     */
    public function get_all_shifts() {
        return $this->db->order_by('start_time', 'ASC')->get('shifts')->result();
    }

    /**
     * Filter Presensi Pabrik berdasarkan Tanggal dan/atau Departemen
     */
    public function get_filtered_attendances($date = null, $dept_id = null) {
        $target_date = !empty($date) ? $date : date('Y-m-d');

        $this->db->select('a.*, e.nip, e.full_name, d.dept_name, d.dept_code, s.shift_name, s.start_time, s.end_time')
            ->from('attendances a')
            ->join('employees e', 'e.id = a.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->join('shifts s', 's.id = e.shift_id')
            ->where('a.attendance_date', $target_date);

        if (!empty($dept_id)) {
            $this->db->where('e.department_id', (int) $dept_id);
        }

        return $this->db->order_by('a.clock_in', 'DESC')->get()->result();
    }

    /**
     * Visual Analytics: Tren Kehadiran 7 Hari Terakhir (Chart.js)
     */
    public function get_7days_attendance_trend() {
        $labels = [];
        $on_time = [];
        $late = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime($date));

            $present_cnt = $this->db->where('attendance_date', $date)->where('status', 'PRESENT')->count_all_results('attendances');
            $late_cnt = $this->db->where('attendance_date', $date)->where('status', 'LATE')->count_all_results('attendances');

            $on_time[] = $present_cnt;
            $late[] = $late_cnt;
        }

        return [
            'labels'  => $labels,
            'on_time' => $on_time,
            'late'    => $late
        ];
    }

    /**
     * Visual Analytics: Distribusi Jam Lembur per Departemen (Chart.js)
     */
    public function get_overtime_distribution_by_dept() {
        $res = $this->db->select('d.dept_name, COALESCE(SUM(ot.total_hours), 0) AS total_ot')
            ->from('departments d')
            ->join('employees e', 'e.department_id = d.id', 'left')
            ->join('overtime_requests ot', 'ot.employee_id = e.id AND ot.status = "APPROVED"', 'left')
            ->group_by('d.id')
            ->order_by('total_ot', 'DESC')
            ->get()
            ->result();

        $labels = [];
        $data = [];
        foreach ($res as $r) {
            $labels[] = $r->dept_name;
            $data[] = (float) $r->total_ot;
        }

        return [
            'labels' => $labels,
            'data'   => $data
        ];
    }

    /**
     * Detail Lembar Resmi SPL untuk Fitur Cetak Slip (Print View)
     */
    public function get_spl_by_id($id) {
        return $this->db->select('ot.*, e.nip, e.full_name, e.gender, e.base_salary, d.dept_name, d.dept_code, s.shift_name, s.start_time AS shift_start, s.end_time AS shift_end, app.full_name AS approver_name, app.nip AS approver_nip')
            ->from('overtime_requests ot')
            ->join('employees e', 'e.id = ot.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->join('shifts s', 's.id = e.shift_id')
            ->join('employees app', 'app.id = ot.approved_by', 'left')
            ->where('ot.id', (int) $id)
            ->get()
            ->row();
    }

    public function get_employee_by_id($id) {
        return $this->db->where('id', (int) $id)->get('employees')->row();
    }

    /**
     * Daftar Anggota Tim untuk Supervisor
     */
    public function get_supervisor_team($department_id) {
        return $this->db->select('e.*, s.shift_name, s.start_time, s.end_time, a.clock_in, a.clock_out, a.status AS attendance_status, a.late_minutes')
            ->from('employees e')
            ->join('shifts s', 's.id = e.shift_id', 'left')
            ->join('attendances a', 'a.employee_id = e.id AND a.attendance_date = CURDATE()', 'left')
            ->where('e.department_id', $department_id)
            ->where('e.is_active', 1)
            ->order_by('e.role', 'ASC')
            ->order_by('e.full_name', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Solusi Masalah #7: Rekapitulasi Periode Cut-Off Payroll Terpadu
     * Mengagregasi kehadiran, frekuensi & durasi terlambat, serta total kompensasi lembur PP 35/2021
     * dalam rentang tanggal fleksibel (misal tanggal 21 bulan lalu s/d 20 bulan ini).
     */
    public function get_payroll_cutoff_summary($start_date, $end_date, $dept_id = null) {
        $this->db->select('e.*, d.dept_name, d.dept_code, s.shift_name')
            ->from('employees e')
            ->join('departments d', 'd.id = e.department_id')
            ->join('shifts s', 's.id = e.shift_id')
            ->where('e.is_active', 1);

        if (!empty($dept_id)) {
            $this->db->where('e.department_id', (int) $dept_id);
        }

        $employees = $this->db->order_by('d.dept_code', 'ASC')->order_by('e.full_name', 'ASC')->get()->result();

        $this->load->model('Overtime_model');
        $result = [];

        foreach ($employees as $emp) {
            // Agregasi Kehadiran dalam rentang tanggal
            $att_stats = $this->db->select('
                COUNT(id) AS total_attendance,
                SUM(CASE WHEN status = "LATE" THEN 1 ELSE 0 END) AS total_late_count,
                COALESCE(SUM(late_minutes), 0) AS total_late_mins
            ')
            ->from('attendances')
            ->where('employee_id', $emp->id)
            ->where('attendance_date >=', $start_date)
            ->where('attendance_date <=', $end_date)
            ->get()
            ->row();

            // Agregasi Tiket Lembur APPROVED dalam rentang tanggal
            $ot_stats = $this->db->select('
                COUNT(id) AS total_spl_count,
                COALESCE(SUM(total_hours), 0) AS total_ot_hours
            ')
            ->from('overtime_requests')
            ->where('employee_id', $emp->id)
            ->where('status', 'APPROVED')
            ->where('overtime_date >=', $start_date)
            ->where('overtime_date <=', $end_date)
            ->get()
            ->row();

            // Hitung Upah Lembur PP 35/2021
            $ot_calc = $this->Overtime_model->calculate_pp35_overtime($ot_stats->total_ot_hours, $emp->base_salary);

            $result[] = (object) [
                'employee_id'        => $emp->id,
                'nip'                => $emp->nip,
                'full_name'          => $emp->full_name,
                'dept_name'          => $emp->dept_name,
                'dept_code'          => $emp->dept_code,
                'shift_name'         => $emp->shift_name,
                'base_salary'        => $emp->base_salary,
                'total_attendance'   => (int) $att_stats->total_attendance,
                'total_late_count'   => (int) $att_stats->total_late_count,
                'total_late_mins'    => (int) $att_stats->total_late_mins,
                'total_spl_count'    => (int) $ot_stats->total_spl_count,
                'total_ot_hours'     => (float) $ot_stats->total_ot_hours,
                'equivalent_hours'   => $ot_calc['equivalent_hours'],
                'hourly_rate'        => $ot_calc['hourly_rate'],
                'total_ot_pay'       => $ot_calc['estimated_pay'],
                'est_total_income'   => $emp->base_salary + $ot_calc['estimated_pay']
            ];
        }

        return $result;
    }
}
