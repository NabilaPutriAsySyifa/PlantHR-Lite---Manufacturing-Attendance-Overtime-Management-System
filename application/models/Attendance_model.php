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

    /**
     * Evaluasi batas waktu (Time Window Auto-Lock) presensi masuk dan pulang berdasarkan shift kerja & SPL lembur
     */
    public function get_shift_window_status($employee_id, $shift = null, $active_attendance = null) {
        if (!$shift) {
            $shift = $this->get_employee_shift($employee_id);
        }
        if (!$shift) {
            return [
                'can_clock_in'    => true,
                'can_clock_out'   => true,
                'lock_in_reason'  => '',
                'lock_out_reason' => '',
                'shift_name'      => 'Shift Standar',
                'start_time'      => '08:00',
                'end_time'        => '17:00',
                'cutoff_in'       => '17:00',
                'cutoff_out'      => '18:30',
                'has_approved_ot' => false
            ];
        }

        $now_ts = time();
        $today  = date('Y-m-d');
        $hour   = (int) date('H');

        // Tentukan batas awal dan akhir shift saat ini
        if (!empty($shift->is_cross_day)) {
            // Shift lintas hari (contoh: 23:00 - 07:00)
            $end_hour = (int) substr($shift->end_time, 0, 2);
            if ($hour < $end_hour || ($hour == $end_hour && (int)date('i') <= 30)) {
                // Sesi dini hari / pagi (mulai kemarin malam)
                $shift_start_ts = strtotime(date('Y-m-d', strtotime('-1 day')) . ' ' . $shift->start_time);
                $shift_end_ts   = strtotime($today . ' ' . $shift->end_time);
            } else {
                // Sesi sore / malam (mulai malam ini)
                $shift_start_ts = strtotime($today . ' ' . $shift->start_time);
                $shift_end_ts   = strtotime(date('Y-m-d', strtotime('+1 day')) . ' ' . $shift->end_time);
            }
        } else {
            // Shift reguler hari yang sama (Pagi / Siang / Office)
            $shift_start_ts = strtotime($today . ' ' . $shift->start_time);
            $shift_end_ts   = strtotime($today . ' ' . $shift->end_time);
        }

        // Buka presensi masuk 2 jam sebelum jam masuk shift dimulai
        $earliest_in_ts = $shift_start_ts - (2 * 3600);
        // Batas maksimal presensi masuk: tepat saat jam shift berakhir
        $cutoff_in_ts   = $shift_end_ts;

        // Cek tiket lembur resmi (SPL) yang disetujui untuk hari ini
        $approved_ot = $this->db->select('*')
            ->from('overtime_requests')
            ->where('employee_id', $employee_id)
            ->where('overtime_date', $today)
            ->where('status', 'APPROVED')
            ->order_by('end_time', 'DESC')
            ->get()
            ->row();

        // Batas toleransi kepulangan normal: +90 menit (1.5 jam) setelah jam selesai shift
        $cutoff_out_ts = $shift_end_ts + (90 * 60);

        // Jika ada lembur yang disetujui, perpanjang batas Clock Out hingga +60 menit setelah jam lembur berakhir
        if ($approved_ot && !empty($approved_ot->end_time)) {
            $ot_end_ts = strtotime($today . ' ' . $approved_ot->end_time);
            if (!empty($shift->is_cross_day) && $ot_end_ts < $shift_start_ts) {
                $ot_end_ts = strtotime(date('Y-m-d', strtotime('+1 day')) . ' ' . $approved_ot->end_time);
            }
            if (($ot_end_ts + 3600) > $cutoff_out_ts) {
                $cutoff_out_ts = $ot_end_ts + 3600;
            }
        }

        // Evaluasi Kunci Otomatis Presensi Masuk (Clock In)
        $can_clock_in = true;
        $lock_in_reason = '';

        if ($now_ts < $earliest_in_ts) {
            $can_clock_in = false;
            $lock_in_reason = 'Presensi masuk belum dibuka. Jam masuk shift Anda adalah ' . date('H:i', $shift_start_ts) . ' WIB (baru dapat diakses mulai pukul ' . date('H:i', $earliest_in_ts) . ' WIB).';
        } elseif ($now_ts > $cutoff_in_ts) {
            $can_clock_in = false;
            $lock_in_reason = 'Akses Presensi Masuk (Clock In) telah dikunci karena jadwal shift Anda (' . $shift->shift_name . ') telah berakhir pada pukul ' . date('H:i', $shift_end_ts) . ' WIB. Anda dinyatakan Tidak Hadir / Mangkir hari ini. Silakan hubungi Supervisor atau HRD jika ada dispensasi dinas.';
        }

        // Evaluasi Kunci Otomatis Presensi Pulang (Clock Out)
        $can_clock_out = true;
        $lock_out_reason = '';

        if ($now_ts > $cutoff_out_ts) {
            $can_clock_out = false;
            $lock_out_reason = 'Akses Presensi Pulang (Clock Out) telah dikunci karena telah melewati batas toleransi kepulangan (' . date('H:i', $cutoff_out_ts) . ' WIB). Hubungi Supervisor atau HRD untuk verifikasi pencatatan kepulangan Anda.';
        }

        return [
            'can_clock_in'    => $can_clock_in,
            'can_clock_out'   => $can_clock_out,
            'lock_in_reason'  => $lock_in_reason,
            'lock_out_reason' => $lock_out_reason,
            'shift_name'      => $shift->shift_name,
            'start_time'      => date('H:i', $shift_start_ts),
            'end_time'        => date('H:i', $shift_end_ts),
            'earliest_in'     => date('H:i', $earliest_in_ts),
            'cutoff_in'       => date('H:i', $cutoff_in_ts),
            'cutoff_out'      => date('H:i', $cutoff_out_ts),
            'has_approved_ot' => !empty($approved_ot)
        ];
    }
}
