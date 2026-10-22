<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends CI_Model {

    public function get_today_attendance($employee_id, $date) {
        return $this->db->get_where('attendances', [
            'employee_id' => $employee_id,
            'attendance_date' => $date
        ])->row();
    }

    /**
     * Cari presensi aktif karyawan (termasuk shift malam lintas hari)
     */
    public function get_active_attendance($employee_id) {
        $today = date('Y-m-d');

        // 1. Cek apakah ada record yang sudah clock in tapi belum clock out dalam 24 jam terakhir
        $open = $this->db->where('employee_id', $employee_id)
            ->where('clock_in IS NOT NULL', NULL, FALSE)
            ->where('clock_out IS NULL', NULL, FALSE)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('attendances')
            ->row();

        if ($open) {
            return $open;
        }

        // 2. Jika tidak ada, ambil record presensi tanggal hari ini
        return $this->get_today_attendance($employee_id, $today);
    }

    public function get_employee_shift($employee_id) {
        return $this->db->select('s.*')
            ->from('employees e')
            ->join('shifts s', 's.id = e.shift_id')
            ->where('e.id', $employee_id)
            ->get()
            ->row();
    }

    /**
     * Catat presensi masuk dengan kalkulasi telat cerdas (mendukung shift malam lintas hari)
     */
    public function record_clock_in($employee_id, $shift) {
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $hour = (int) date('H');
        $late_minutes = 0;
        $status = 'PRESENT';

        $attendance_date = $today;

        // Shift 3 Malam Lintas Hari (contoh: 23:00 - 07:00)
        if (!empty($shift->is_cross_day)) {
            // Jika clock in setelah tengah malam (antara 00:00 s/d 06:59), ini adalah shift malam yang mulai kemarin
            if ($hour < (int) substr($shift->end_time, 0, 2)) {
                $attendance_date = date('Y-m-d', strtotime('-1 day'));
                $shift_start = $attendance_date . ' ' . $shift->start_time;
            } else {
                // Clock in sebelum tengah malam (misal jam 22:45 atau 23:15)
                $shift_start = $today . ' ' . $shift->start_time;
            }
        } else {
            // Shift Normal (Pagi / Sore)
            $shift_start = $today . ' ' . $shift->start_time;
        }

        // Hitung menit keterlambatan jika masuk lewat dari jam mulai shift
        if (strtotime($now) > strtotime($shift_start)) {
            $late_seconds = strtotime($now) - strtotime($shift_start);
            $late_minutes = (int) ceil($late_seconds / 60);
            $status = 'LATE';
        }

        return $this->db->insert('attendances', [
            'employee_id'     => $employee_id,
            'attendance_date' => $attendance_date,
            'clock_in'        => $now,
            'status'          => $status,
            'late_minutes'    => $late_minutes
        ]);
    }

    public function record_clock_out($attendance_id) {
        return $this->db->where('id', $attendance_id)
            ->update('attendances', ['clock_out' => date('Y-m-d H:i:s')]);
    }

    /**
     * Rekap presensi seluruh pabrik hari ini untuk dashboard HR / Supervisor
     */
    public function get_today_factory_attendances() {
        return $this->db->select('a.*, e.nip, e.full_name, d.dept_name, s.shift_name, s.start_time, s.end_time')
            ->from('attendances a')
            ->join('employees e', 'e.id = a.employee_id')
            ->join('departments d', 'd.id = e.department_id')
            ->join('shifts s', 's.id = e.shift_id')
            ->where('a.attendance_date', date('Y-m-d'))
            ->order_by('a.clock_in', 'DESC')
            ->get()
            ->result();
    }
}
