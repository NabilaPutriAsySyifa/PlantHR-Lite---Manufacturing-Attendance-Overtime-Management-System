<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Dashboard_model', 'Attendance_model', 'Overtime_model']);
    }

    /**
     * Dashboard HR Administrator
     * Monitoring metrik pabrik, rekap presensi terfilter, master data karyawan & shift,
     * analitik grafik Chart.js, serta simulasi biaya lembur regulasi PP 35/2021.
     */
    public function hr() {
        $this->allow_roles(['HR_ADMIN']);

        $filter_date   = $this->input->get('filter_date', TRUE);
        $filter_dept   = $this->input->get('filter_dept', TRUE);
        $filter_status = $this->input->get('filter_status', TRUE);
        
        // Periode Cut-Off Payroll (Default: tanggal 21 bulan lalu s/d 20 bulan ini atau tanggal 1 s/d hari ini)
        $cutoff_start  = $this->input->get('cutoff_start', TRUE);
        $cutoff_end    = $this->input->get('cutoff_end', TRUE);

        if (empty($filter_date)) {
            $filter_date = date('Y-m-d');
        }
        if (empty($cutoff_start)) {
            $cutoff_start = date('Y-m-01');
        }
        if (empty($cutoff_end)) {
            $cutoff_end = date('Y-m-d');
        }

        $data['title']           = 'Portal Administrator Utama (HR & Manajemen)';
        $data['summary']         = $this->Dashboard_model->get_hr_summary();
        $data['attendances']     = $this->Dashboard_model->get_filtered_attendances($filter_date, $filter_dept);
        $data['employees']       = $this->Dashboard_model->get_all_employees();
        $data['departments']     = $this->Dashboard_model->get_departments_with_count();
        $data['shifts']          = $this->Dashboard_model->get_all_shifts();
        $data['spl_archive']     = $this->Overtime_model->get_filtered_spl_archive($filter_status, $filter_dept, null);
        $data['trend_7days']     = $this->Dashboard_model->get_7days_attendance_trend();
        $data['ot_dist']         = $this->Dashboard_model->get_overtime_distribution_by_dept();
        $data['shift_dist']      = $this->Dashboard_model->get_shift_distribution();
        $data['ot_cost_dist']    = $this->Dashboard_model->get_overtime_cost_by_dept();
        
        // Solusi Masalah #7: Rekapitulasi Periode Cut-Off Payroll Terpadu
        $data['payroll_summary'] = $this->Dashboard_model->get_payroll_cutoff_summary($cutoff_start, $cutoff_end, $filter_dept);

        // Presensi Mandiri HR Admin
        $user_id                 = $this->session->userdata('user_id');
        $data['my_attendance']   = $this->Attendance_model->get_active_attendance($user_id);
        $data['my_shift']        = $this->Attendance_model->get_employee_shift($user_id);
        $data['shift_window']    = $this->Attendance_model->get_shift_window_status($user_id, $data['my_shift'], $data['my_attendance']);

        $data['filter_date']     = $filter_date;
        $data['filter_dept']     = $filter_dept;
        $data['filter_status']   = $filter_status;
        $data['cutoff_start']    = $cutoff_start;
        $data['cutoff_end']      = $cutoff_end;

        $this->load->view('dashboard/hr', $data);
    }

    /**
     * Dashboard Atasan / Supervisor
     * Verifikasi pengajuan lembur tim, riwayat keputusan, dan monitoring kehadiran bawahan
     */
    public function supervisor() {
        $this->allow_roles(['SUPERVISOR', 'HR_ADMIN']);
        $user_id       = $this->session->userdata('user_id');
        $department_id = $this->session->userdata('department_id') ? $this->session->userdata('department_id') : 1;

        $data['title']            = 'Portal Atasan & Supervisor (Verifikasi Lembur)';
        $data['pending_spl']      = $this->Dashboard_model->get_pending_spl();
        $data['decision_history'] = $this->Overtime_model->get_supervisor_decision_history($user_id, 25);
        $data['team_members']     = $this->Dashboard_model->get_supervisor_team($department_id);

        // Presensi Mandiri Supervisor
        $data['my_attendance']   = $this->Attendance_model->get_active_attendance($user_id);
        $data['my_shift']        = $this->Attendance_model->get_employee_shift($user_id);
        $data['shift_window']    = $this->Attendance_model->get_shift_window_status($user_id, $data['my_shift'], $data['my_attendance']);

        $this->load->view('dashboard/supervisor', $data);
    }

    /**
     * Portal Staf & Karyawan (Employee Self-Service / ESS)
     * Presensi mandiri (Shift Pagi/Sore/Malam lintas hari) & Pengajuan SPL
     */
    public function operator() {
        $this->allow_roles(['OPERATOR']);
        $user_id = $this->session->userdata('user_id');

        $data['title']            = 'Portal Staf & Karyawan (Presensi Mandiri & SPL)';
        $data['today_attendance'] = $this->Attendance_model->get_active_attendance($user_id);
        $data['my_attendances']   = $this->Dashboard_model->get_operator_attendances($user_id);
        $data['my_spl_list']      = $this->Overtime_model->get_employee_spl_history($user_id);
        $data['shift']            = $this->Attendance_model->get_employee_shift($user_id);
        $data['shift_window']     = $this->Attendance_model->get_shift_window_status($user_id, $data['shift'], $data['today_attendance']);

        $this->load->view('dashboard/operator', $data);
    }

    public function clock_in() {
        $this->allow_roles(['OPERATOR', 'SUPERVISOR', 'HR_ADMIN']);
        $user_id = $this->session->userdata('user_id');
        $role    = $this->session->userdata('role');
        $redirect_target = ($role === 'HR_ADMIN') ? 'dashboard/hr' : (($role === 'SUPERVISOR') ? 'dashboard/supervisor' : 'dashboard/operator');

        $active = $this->Attendance_model->get_active_attendance($user_id);
        if ($active && !empty($active->clock_in) && empty($active->clock_out)) {
            $this->session->set_flashdata('error', 'Anda telah melakukan Clock In dan belum melakukan Clock Out.');
            redirect($redirect_target);
            return;
        }

        if ($active && !empty($active->clock_in) && !empty($active->clock_out) && $active->attendance_date === date('Y-m-d')) {
            $this->session->set_flashdata('error', 'Presensi Anda untuk hari ini sudah lengkap (sudah Clock Out).');
            redirect($redirect_target);
            return;
        }

        $shift = $this->Attendance_model->get_employee_shift($user_id);
        $window = $this->Attendance_model->get_shift_window_status($user_id, $shift, $active);

        // Kunci Otomatis (Auto-Lock): Tolak jika di luar jendela waktu shift yang sah
        if (!$window['can_clock_in']) {
            $this->session->set_flashdata('error', $window['lock_in_reason']);
            redirect($redirect_target);
            return;
        }

        $this->Attendance_model->record_clock_in($user_id, $shift);

        $this->session->set_flashdata('success', 'Presensi masuk (Clock In) berhasil dicatat.');
        redirect($redirect_target);
    }

    /**
     * Aksi Presensi Pulang (Clock Out)
     */
    public function clock_out() {
        $this->allow_roles(['OPERATOR', 'SUPERVISOR', 'HR_ADMIN']);
        $user_id = $this->session->userdata('user_id');
        $role    = $this->session->userdata('role');
        $redirect_target = ($role === 'HR_ADMIN') ? 'dashboard/hr' : (($role === 'SUPERVISOR') ? 'dashboard/supervisor' : 'dashboard/operator');

        $active = $this->Attendance_model->get_active_attendance($user_id);

        if (!$active || empty($active->clock_in)) {
            $this->session->set_flashdata('error', 'Anda belum melakukan Clock In.');
            redirect($redirect_target);
            return;
        }

        if (!empty($active->clock_out)) {
            $this->session->set_flashdata('error', 'Anda sudah melakukan Clock Out sebelumnya.');
            redirect($redirect_target);
            return;
        }

        $shift = $this->Attendance_model->get_employee_shift($user_id);
        $window = $this->Attendance_model->get_shift_window_status($user_id, $shift, $active);

        // Kunci Otomatis (Auto-Lock): Tolak jika melewati batas toleransi kepulangan
        if (!$window['can_clock_out']) {
            $this->session->set_flashdata('error', $window['lock_out_reason']);
            redirect($redirect_target);
            return;
        }

        $this->Attendance_model->record_clock_out($active->id);
        $this->session->set_flashdata('success', 'Presensi pulang (Clock Out) berhasil dicatat. Terima kasih atas dedikasi dan kerja keras Anda.');
        redirect($redirect_target);
    }

    /**
     * Pengajuan Tiket Lembur (SPL)
     */
    public function submit_spl() {
        $this->allow_roles(['OPERATOR']);
        $this->form_validation->set_rules('overtime_date', 'Tanggal Lembur', 'required');
        $this->form_validation->set_rules('start_time', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('end_time', 'Jam Selesai', 'required');
        $this->form_validation->set_rules('reason', 'Alasan / Target Tugas', 'required|min_length[5]|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/operator');
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $date    = $this->input->post('overtime_date', TRUE);
        $start   = $this->input->post('start_time', TRUE);
        $end     = $this->input->post('end_time', TRUE);
        $reason  = trim($this->input->post('reason', TRUE));

        // Solusi Masalah #5: Cegah Pengajuan Ganda (Double Submission) untuk tanggal yang sama
        if ($this->Overtime_model->has_active_spl($user_id, $date)) {
            $this->session->set_flashdata('error', 'Duplikasi Dicegah: Anda telah memiliki permohonan lembur pada tanggal ' . date('d/m/Y', strtotime($date)) . ' yang masih berstatus PENDING atau APPROVED.');
            redirect('dashboard/operator');
            return;
        }

        $t_start = strtotime("{$date} {$start}");
        $t_end   = strtotime("{$date} {$end}");
        if ($t_end <= $t_start) {
            $t_end += 86400; // Jam lembur melintasi tengah malam
        }
        $diff = round(($t_end - $t_start) / 3600, 2);

        $this->Overtime_model->insert_spl([
            'spl_number'    => $this->Overtime_model->generate_spl_number($date),
            'employee_id'   => $user_id,
            'overtime_date' => $date,
            'start_time'    => $start,
            'end_time'      => $end,
            'total_hours'   => $diff,
            'reason'        => $reason,
            'status'        => 'PENDING'
        ]);

        $this->session->set_flashdata('success', 'Tiket pengajuan SPL berhasil dibuat dan diteruskan ke Supervisor untuk verifikasi.');
        redirect('dashboard/operator');
    }

    /**
     * Persetujuan SPL oleh Atasan
     */
    public function approve_spl($spl_id) {
        $this->allow_roles(['SUPERVISOR', 'HR_ADMIN']);
        $this->db->where('id', (int) $spl_id)
            ->where('status', 'PENDING')
            ->update('overtime_requests', [
                'status'      => 'APPROVED',
                'approved_by' => $this->session->userdata('user_id'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]);

        $this->session->set_flashdata('success', 'Surat Perintah Lembur (SPL) berhasil disetujui.');
        redirect('dashboard/supervisor');
    }

    /**
     * Penolakan SPL oleh Atasan disertai catatan
     */
    public function reject_spl() {
        $this->allow_roles(['SUPERVISOR', 'HR_ADMIN']);
        $spl_id = (int) $this->input->post('spl_id');
        $note   = trim($this->input->post('rejection_note', TRUE));

        if (empty($note)) {
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi agar operator memahami pertimbangannya.');
            redirect('dashboard/supervisor');
            return;
        }

        $this->db->where('id', $spl_id)
            ->where('status', 'PENDING')
            ->update('overtime_requests', [
                'status'         => 'REJECTED',
                'approved_by'    => $this->session->userdata('user_id'),
                'rejection_note' => $note,
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

        $this->session->set_flashdata('success', 'Pengajuan SPL telah ditolak dengan catatan resmi.');
        redirect('dashboard/supervisor');
    }

    /**
     * Cetak Lembar Resmi Surat Perintah Lembur (SPL)
     * Format cetak A4 siap audit pabrik dengan barcode, rincian upah PP 35/2021, dan kolom tanda tangan
     */
    public function print_spl($id) {
        $this->allow_roles(['OPERATOR', 'SUPERVISOR', 'HR_ADMIN']);
        $spl = $this->Dashboard_model->get_spl_by_id($id);

        if (!$spl) {
            show_404();
            return;
        }

        // Jika operator, pastikan hanya bisa mencetak tiket miliknya sendiri
        if ($this->session->userdata('role') === 'OPERATOR' && $spl->employee_id != $this->session->userdata('user_id')) {
            show_error('Anda tidak memiliki otorisasi untuk mencetak dokumen SPL karyawan lain.', 403);
            return;
        }

        $data['title'] = 'Cetak SPL - ' . $spl->spl_number;
        $data['spl']   = $spl;
        $data['calc']  = $this->Overtime_model->calculate_pp35_overtime($spl->total_hours, $spl->base_salary);

        $this->load->view('dashboard/print_spl', $data);
    }

    /**
     * Tambah Karyawan Baru (Master HR)
     */
    public function add_employee() {
        $this->allow_roles(['HR_ADMIN']);

        // Resolusi posisi dari opsi OTHER (manual) atau preset
        $pos = trim($this->input->post('position', TRUE));
        $pos_select = trim($this->input->post('position_select', TRUE));
        $custom_pos = trim($this->input->post('custom_position', TRUE));
        if ($pos_select === 'OTHER' && !empty($custom_pos)) {
            $_POST['position'] = $custom_pos;
        } elseif (empty($pos) && !empty($pos_select) && $pos_select !== 'OTHER') {
            $_POST['position'] = $pos_select;
        }

        $this->form_validation->set_rules('nip', 'NIP', 'required|is_unique[employees.nip]');
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim|max_length[120]');
        $this->form_validation->set_rules('position', 'Jabatan / Posisi Kerja', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'required|in_list[L,P]');
        $this->form_validation->set_rules('department_id', 'Departemen', 'required|numeric');
        $this->form_validation->set_rules('shift_id', 'Shift Kerja', 'required|numeric');
        $this->form_validation->set_rules('role', 'Peran Pengguna', 'required|in_list[OPERATOR,SUPERVISOR,HR_ADMIN]');
        $this->form_validation->set_rules('base_salary', 'Gaji Pokok', 'required|numeric');
        $this->form_validation->set_rules('join_date', 'Tanggal Bergabung', 'required');
        $this->form_validation->set_rules('email', 'Email Login', 'required|valid_email|is_unique[employees.email]');
        $this->form_validation->set_rules('password', 'Kata Sandi', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
            redirect('dashboard/hr#emp');
            return;
        }

        $this->Dashboard_model->insert_employee([
            'nip'           => strtoupper(trim($this->input->post('nip', TRUE))),
            'full_name'     => trim($this->input->post('full_name', TRUE)),
            'position'      => trim($this->input->post('position', TRUE)),
            'gender'        => $this->input->post('gender', TRUE),
            'department_id' => (int) $this->input->post('department_id', TRUE),
            'shift_id'      => (int) $this->input->post('shift_id', TRUE),
            'role'          => $this->input->post('role', TRUE),
            'base_salary'   => (float) $this->input->post('base_salary', TRUE),
            'join_date'     => $this->input->post('join_date', TRUE),
            'email'         => strtolower(trim($this->input->post('email', TRUE))),
            'password'      => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'is_active'     => 1
        ]);

        $this->session->set_flashdata('success', 'Data personil karyawan baru berhasil didaftarkan ke sistem pabrik.');
        redirect('dashboard/hr#emp');
    }

    /**
     * Update Data Karyawan (Edit Penempatan & Pola Shift)
     */
    public function edit_employee() {
        $this->allow_roles(['HR_ADMIN']);
        $id = (int) $this->input->post('id', TRUE);

        // Resolusi posisi dari opsi OTHER (manual) atau preset
        $pos = trim($this->input->post('position', TRUE));
        $pos_select = trim($this->input->post('position_select', TRUE));
        $custom_pos = trim($this->input->post('custom_position', TRUE));
        if ($pos_select === 'OTHER' && !empty($custom_pos)) {
            $_POST['position'] = $custom_pos;
        } elseif (empty($pos) && !empty($pos_select) && $pos_select !== 'OTHER') {
            $_POST['position'] = $pos_select;
        }

        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('position', 'Jabatan / Posisi Kerja', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('department_id', 'Departemen', 'required|numeric');
        $this->form_validation->set_rules('shift_id', 'Shift Kerja', 'required|numeric');
        $this->form_validation->set_rules('role', 'Peran Pengguna', 'required|in_list[OPERATOR,SUPERVISOR,HR_ADMIN]');
        $this->form_validation->set_rules('base_salary', 'Gaji Pokok', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('dashboard/hr#emp');
            return;
        }

        $update_data = [
            'full_name'     => trim($this->input->post('full_name', TRUE)),
            'position'      => trim($this->input->post('position', TRUE)),
            'department_id' => (int) $this->input->post('department_id', TRUE),
            'shift_id'      => (int) $this->input->post('shift_id', TRUE),
            'role'          => $this->input->post('role', TRUE),
            'base_salary'   => (float) $this->input->post('base_salary', TRUE),
        ];

        // Ganti password hanya jika diinputkan
        $new_pass = $this->input->post('password');
        if (!empty($new_pass)) {
            $update_data['password'] = password_hash($new_pass, PASSWORD_BCRYPT);
        }

        $this->Dashboard_model->update_employee($id, $update_data);
        $this->session->set_flashdata('success', 'Data penempatan dan profil karyawan berhasil diperbarui.');
        redirect('dashboard/hr#emp');
    }

    /**
     * Toggle Status Karyawan (Aktif / Nonaktif)
     */
    public function toggle_employee($id) {
        $this->allow_roles(['HR_ADMIN']);
        $this->Dashboard_model->toggle_employee_status($id);
        $this->session->set_flashdata('success', 'Status keaktifan personil berhasil diubah.');
        redirect('dashboard/hr#emp');
    }

    /**
     * Hapus Data Karyawan (Cegah Duplikasi / Salah Input)
     */
    public function delete_employee() {
        $this->allow_roles(['HR_ADMIN']);
        $employee_id = (int) $this->input->post('employee_id');

        if ($employee_id <= 0) {
            $this->session->set_flashdata('error', 'ID karyawan tidak valid.');
            redirect('dashboard/hr#emp');
            return;
        }

        // Proteksi: Cegah menghapus akun yang sedang login sendiri
        if ($employee_id === (int) $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
            redirect('dashboard/hr#emp');
            return;
        }

        $emp = $this->db->get_where('employees', ['id' => $employee_id])->row();
        if (!$emp) {
            $this->session->set_flashdata('error', 'Data karyawan tidak ditemukan.');
            redirect('dashboard/hr#emp');
            return;
        }

        $deleted_name = $emp->full_name;
        $deleted_nip  = $emp->nip;

        // Eksekusi penghapusan (relasi absensi & SPL terhapus bersih secara cascade)
        $this->db->where('id', $employee_id)->delete('employees');

        $this->session->set_flashdata('success', 'Data personil ' . $deleted_name . ' (' . $deleted_nip . ') berhasil dihapus secara permanen.');
        redirect('dashboard/hr#emp');
    }

    /**
     * Solusi Lupa Password: Reset Kata Sandi Karyawan oleh HR Administrator
     */
    public function reset_password() {
        $this->allow_roles(['HR_ADMIN']);
        $employee_id  = (int) $this->input->post('employee_id');
        $new_password = $this->input->post('new_password');

        if (empty($new_password) || strlen($new_password) < 6) {
            $this->session->set_flashdata('error', 'Gagal reset sandi: Kata sandi baru minimal harus 6 karakter.');
            redirect('dashboard/hr#emp');
            return;
        }

        $emp = $this->db->get_where('employees', ['id' => $employee_id])->row();
        if (!$emp) {
            $this->session->set_flashdata('error', 'Data personil tidak ditemukan.');
            redirect('dashboard/hr#emp');
            return;
        }

        $now = date('Y-m-d H:i:s');
        $this->db->where('id', $employee_id)->update('employees', [
            'password'            => password_hash($new_password, PASSWORD_BCRYPT),
            'password_changed_at' => $now,
            'updated_at'          => $now
        ]);

        $this->session->set_flashdata('success', 'BERHASIL: Kata sandi personil ' . $emp->full_name . ' (' . $emp->nip . ') telah berhasil diperbarui dan aktif seketika di database.');
        $this->session->set_flashdata('reset_success_emp_id', $employee_id);
        $this->session->set_flashdata('reset_success_emp_name', $emp->full_name);
        $this->session->set_flashdata('reset_success_emp_nip', $emp->nip);
        $this->session->set_flashdata('reset_success_time', date('d/m/Y H:i', strtotime($now)) . ' WIB');
        redirect('dashboard/hr#emp');
    }

    /**
     * Ekspor Data Presensi ke CSV / Excel (.xls)
     */
    public function export_attendance() {
        $this->allow_roles(['HR_ADMIN']);
        $filter_date = $this->input->get('filter_date', TRUE);
        $filter_dept = $this->input->get('filter_dept', TRUE);
        $format      = $this->input->get('format', TRUE);

        if (empty($filter_date)) {
            $filter_date = date('Y-m-d');
        }

        $attendances = $this->Dashboard_model->get_filtered_attendances($filter_date, $filter_dept);

        if ($format === 'excel') {
            $filename = "Rekap_Presensi_Pabrik_" . date('Ymd_His') . ".xls";
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=" . $filename);
            header("Pragma: no-cache");
            header("Expires: 0");

            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 10pt; }';
            echo 'th { background-color: #1B365D; color: #FFFFFF; font-weight: bold; border: 1px solid #000; padding: 6px; text-align: center; }';
            echo 'td { border: 1px solid #D0D0D0; padding: 5px; }';
            echo '.center { text-align: center; }';
            echo '.num { text-align: right; }';
            echo '</style></head><body>';
            echo '<h3>REKAP PRESENSI HARIAN PABRIK - PLANTHR LITE</h3>';
            echo '<p><strong>Tanggal Presensi:</strong> ' . date('d/m/Y', strtotime($filter_date)) . ' | <strong>Dicetak:</strong> ' . date('d/m/Y H:i') . ' WIB</p>';
            echo '<table><thead><tr>';
            echo '<th>NIP</th><th>Nama Karyawan</th><th>Departemen</th><th>Shift Kerja</th><th>Tanggal</th><th>Jam Masuk</th><th>Jam Pulang</th><th>Status</th><th>Terlambat (Mnt)</th><th>Catatan</th>';
            echo '</tr></thead><tbody>';
            foreach ($attendances as $row) {
                echo '<tr>';
                echo '<td class="center">' . htmlspecialchars($row->nip) . '</td>';
                echo '<td><strong>' . htmlspecialchars($row->full_name) . '</strong></td>';
                echo '<td>' . htmlspecialchars($row->dept_name) . '</td>';
                echo '<td>' . htmlspecialchars($row->shift_name) . '</td>';
                echo '<td class="center">' . $row->attendance_date . '</td>';
                echo '<td class="center">' . ($row->clock_in ? date('H:i:s', strtotime($row->clock_in)) : '-') . '</td>';
                echo '<td class="center">' . ($row->clock_out ? date('H:i:s', strtotime($row->clock_out)) : '-') . '</td>';
                echo '<td class="center">' . htmlspecialchars($row->status) . '</td>';
                echo '<td class="num">' . $row->late_minutes . '</td>';
                echo '<td>' . htmlspecialchars($row->notes ?: '-') . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></body></html>';
            exit;
        }

        $filename = "Rekap_Presensi_Pabrik_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        // Tambahkan BOM agar format UTF-8 terbaca dengan baik di Microsoft Excel
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($output, ['NIP', 'Nama Karyawan', 'Departemen', 'Shift Kerja', 'Tanggal Presensi', 'Jam Masuk (In)', 'Jam Pulang (Out)', 'Status Kehadiran', 'Keterlambatan (Menit)', 'Catatan']);

        foreach ($attendances as $row) {
            fputcsv($output, [
                $row->nip,
                $row->full_name,
                $row->dept_name,
                $row->shift_name . ' (' . substr($row->start_time, 0, 5) . ' - ' . substr($row->end_time, 0, 5) . ')',
                $row->attendance_date,
                $row->clock_in ? date('H:i:s', strtotime($row->clock_in)) : '-',
                $row->clock_out ? date('H:i:s', strtotime($row->clock_out)) : '-',
                $row->status,
                $row->late_minutes,
                $row->notes ?: '-'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Ekspor Data Lembur (SPL) ke CSV / Excel (.xls) dengan Kompensasi Upah PP 35/2021
     */
    public function export_overtime() {
        $this->allow_roles(['HR_ADMIN']);
        $filter_status = $this->input->get('filter_status', TRUE);
        $filter_dept   = $this->input->get('filter_dept', TRUE);
        $format        = $this->input->get('format', TRUE);

        $records = $this->Overtime_model->get_filtered_spl_archive($filter_status, $filter_dept);

        if ($format === 'excel') {
            $filename = "Rekap_Lembur_SPL_" . date('Ymd_His') . ".xls";
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=" . $filename);
            header("Pragma: no-cache");
            header("Expires: 0");

            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 10pt; }';
            echo 'th { background-color: #1B365D; color: #FFFFFF; font-weight: bold; border: 1px solid #000; padding: 6px; text-align: center; }';
            echo 'td { border: 1px solid #D0D0D0; padding: 5px; }';
            echo '.center { text-align: center; }';
            echo '.num { text-align: right; }';
            echo '.cur { mso-number-format:"\"Rp\"\\ \#\,\#\#0"; text-align: right; }';
            echo '</style></head><body>';
            echo '<h3>ARSIP SURAT PERINTAH LEMBUR (SPL) & KOMPENSASI PP 35/2021</h3>';
            echo '<p><strong>Tanggal Ekspor:</strong> ' . date('d/m/Y H:i') . ' WIB</p>';
            echo '<table><thead><tr>';
            echo '<th>Nomor SPL</th><th>NIP</th><th>Nama Karyawan</th><th>Departemen</th><th>Tanggal</th><th>Jam Mulai</th><th>Jam Selesai</th><th>Jam Riil</th><th>Jam Setara PP35</th><th>Estimasi Upah Lembur</th><th>Target Tugas</th><th>Status</th><th>Approver</th>';
            echo '</tr></thead><tbody>';
            foreach ($records as $r) {
                $calc = $this->Overtime_model->calculate_pp35_overtime($r->total_hours, $r->base_salary);
                echo '<tr>';
                echo '<td class="center">' . htmlspecialchars($r->spl_number) . '</td>';
                echo '<td class="center">' . htmlspecialchars($r->nip) . '</td>';
                echo '<td><strong>' . htmlspecialchars($r->full_name) . '</strong></td>';
                echo '<td>' . htmlspecialchars($r->dept_name) . '</td>';
                echo '<td class="center">' . date('d/m/Y', strtotime($r->overtime_date)) . '</td>';
                echo '<td class="center">' . substr($r->start_time, 0, 5) . '</td>';
                echo '<td class="center">' . substr($r->end_time, 0, 5) . '</td>';
                echo '<td class="num center">' . $r->total_hours . '</td>';
                echo '<td class="num center">' . $calc['equivalent_hours'] . '</td>';
                echo '<td class="cur">' . ($r->status === 'APPROVED' ? $calc['estimated_pay'] : 0) . '</td>';
                echo '<td>' . htmlspecialchars($r->reason) . '</td>';
                echo '<td class="center">' . $r->status . '</td>';
                echo '<td>' . htmlspecialchars($r->approver_name ?: '-') . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></body></html>';
            exit;
        }

        $filename = "Rekap_Lembur_SPL_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($output, [
            'Nomor SPL',
            'NIP',
            'Nama Karyawan',
            'Departemen',
            'Tanggal Lembur',
            'Jam Mulai',
            'Jam Selesai',
            'Total Jam Riil',
            'Jam Setara PP35',
            'Estimasi Upah Lembur (Rp)',
            'Target Tugas / Justifikasi',
            'Status',
            'Diverifikasi Oleh'
        ]);

        foreach ($records as $r) {
            $calc = $this->Overtime_model->calculate_pp35_overtime($r->total_hours, $r->base_salary);
            fputcsv($output, [
                $r->spl_number,
                $r->nip,
                $r->full_name,
                $r->dept_name,
                $r->overtime_date,
                substr($r->start_time, 0, 5),
                substr($r->end_time, 0, 5),
                $r->total_hours,
                $calc['equivalent_hours'],
                $r->status === 'APPROVED' ? $calc['estimated_pay'] : 0,
                $r->reason,
                $r->status,
                $r->approver_name ?: '-'
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Solusi Masalah #7: Ekspor Rekapitulasi Periode Cut-Off Payroll ke CSV / Excel (.xls)
     */
    public function export_payroll_cutoff() {
        $this->allow_roles(['HR_ADMIN']);
        $start_date = $this->input->get('cutoff_start', TRUE) ?: date('Y-m-01');
        $end_date   = $this->input->get('cutoff_end', TRUE) ?: date('Y-m-d');
        $dept_id    = $this->input->get('filter_dept', TRUE);
        $format     = $this->input->get('format', TRUE);

        $summary = $this->Dashboard_model->get_payroll_cutoff_summary($start_date, $end_date, $dept_id);

        if ($format === 'excel') {
            $filename = "Rekap_Payroll_Cutoff_" . date('Ymd_His') . ".xls";
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=" . $filename);
            header("Pragma: no-cache");
            header("Expires: 0");

            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 10pt; }';
            echo 'th { background-color: #1B365D; color: #FFFFFF; font-weight: bold; border: 1px solid #000; padding: 6px; text-align: center; }';
            echo 'td { border: 1px solid #D0D0D0; padding: 5px; }';
            echo '.center { text-align: center; }';
            echo '.num { text-align: right; }';
            echo '.cur { mso-number-format:"\"Rp\"\\ \#\,\#\#0"; text-align: right; }';
            echo '</style></head><body>';
            echo '<h3>REKAPITULASI PERIODE CUT-OFF PAYROLL PABRIK - PLANTHR LITE</h3>';
            echo '<p><strong>Periode Cut-Off:</strong> ' . date('d/m/Y', strtotime($start_date)) . ' s/d ' . date('d/m/Y', strtotime($end_date)) . '<br>';
            echo '<strong>Waktu Ekspor:</strong> ' . date('d/m/Y H:i:s') . ' WIB</p>';
            echo '<table><thead><tr>';
            echo '<th>NIP</th><th>Nama Karyawan</th><th>Departemen</th><th>Shift Reguler</th><th>Gaji Pokok Acuan</th><th>Hari Hadir</th><th>Freq Telat</th><th>Menit Telat</th><th>Total SPL</th><th>Jam Lembur Riil</th><th>Jam Setara PP35</th><th>Upah per Jam</th><th>Total Upah Lembur</th><th>Estimasi Total Pendapatan</th>';
            echo '</tr></thead><tbody>';
            foreach ($summary as $row) {
                echo '<tr>';
                echo '<td class="center">' . htmlspecialchars($row->nip) . '</td>';
                echo '<td><strong>' . htmlspecialchars($row->full_name) . '</strong></td>';
                echo '<td>' . htmlspecialchars($row->dept_name) . '</td>';
                echo '<td>' . htmlspecialchars($row->shift_name) . '</td>';
                echo '<td class="cur">' . $row->base_salary . '</td>';
                echo '<td class="num center">' . $row->total_attendance . '</td>';
                echo '<td class="num center">' . $row->total_late_count . '</td>';
                echo '<td class="num center">' . $row->total_late_mins . '</td>';
                echo '<td class="num center">' . $row->total_spl_count . '</td>';
                echo '<td class="num center">' . number_format($row->total_ot_hours, 2) . '</td>';
                echo '<td class="num center">' . number_format($row->equivalent_hours, 2) . '</td>';
                echo '<td class="cur">' . $row->hourly_rate . '</td>';
                echo '<td class="cur">' . $row->total_ot_pay . '</td>';
                echo '<td class="cur" style="font-weight: bold; background-color: #E8F5E9;">' . $row->est_total_income . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></body></html>';
            exit;
        }

        $filename = "Rekap_Payroll_Cutoff_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($output, [
            'NIP',
            'Nama Karyawan',
            'Departemen',
            'Shift Kerja Reguler',
            'Gaji Pokok Acuan (Rp)',
            'Total Hari Hadir',
            'Frekuensi Terlambat',
            'Total Menit Terlambat',
            'Total SPL Lembur',
            'Total Jam Lembur Riil',
            'Total Jam Setara PP35',
            'Upah per Jam (Rp)',
            'Total Upah Lembur (Rp)',
            'Estimasi Total Penerimaan (Gaji + Lembur)'
        ]);

        foreach ($summary as $row) {
            fputcsv($output, [
                $row->nip,
                $row->full_name,
                $row->dept_name,
                $row->shift_name,
                $row->base_salary,
                $row->total_attendance,
                $row->total_late_count,
                $row->total_late_mins,
                $row->total_spl_count,
                $row->total_ot_hours,
                $row->equivalent_hours,
                $row->hourly_rate,
                $row->total_ot_pay,
                $row->est_total_income
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Ekspor Data Master Karyawan ke CSV / Excel (.xls)
     */
    public function export_employees() {
        $this->allow_roles(['HR_ADMIN']);
        $format = $this->input->get('format', TRUE);

        $employees = $this->Dashboard_model->get_all_employees();

        if ($format === 'excel') {
            $filename = "Data_Master_Karyawan_" . date('Ymd_His') . ".xls";
            header("Content-Type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=" . $filename);
            header("Pragma: no-cache");
            header("Expires: 0");

            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>';
            echo 'table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 10pt; }';
            echo 'th { background-color: #1B365D; color: #FFFFFF; font-weight: bold; border: 1px solid #000; padding: 7px; text-align: center; }';
            echo 'td { border: 1px solid #D0D0D0; padding: 6px; }';
            echo '.center { text-align: center; }';
            echo '.num { text-align: right; }';
            echo '</style></head><body>';
            echo '<h3>DATA MASTER PERSONIL KARYAWAN PABRIK - PLANTHR LITE</h3>';
            echo '<p><strong>Total Personil:</strong> ' . count($employees) . ' Karyawan | <strong>Dicetak:</strong> ' . date('d/m/Y H:i') . ' WIB</p>';
            echo '<table><thead><tr>';
            echo '<th>No</th><th>NIP</th><th>Nama Lengkap</th><th>Jabatan / Posisi</th><th>Gender</th><th>Departemen</th><th>Alokasi Shift</th><th>Peran (Role)</th><th>Gaji Pokok (Rp)</th><th>Email Login</th><th>Status Akun</th><th>Terdaftar Pada</th>';
            echo '</tr></thead><tbody>';
            $no = 1;
            foreach ($employees as $row) {
                echo '<tr>';
                echo '<td class="center">' . $no++ . '</td>';
                echo '<td class="center"><strong>' . htmlspecialchars($row->nip) . '</strong></td>';
                echo '<td><strong>' . htmlspecialchars($row->full_name) . '</strong></td>';
                echo '<td>' . htmlspecialchars(!empty($row->position) ? $row->position : '-') . '</td>';
                echo '<td class="center">' . ($row->gender === 'L' ? 'Laki-laki' : 'Perempuan') . '</td>';
                echo '<td>' . htmlspecialchars($row->dept_name) . '</td>';
                echo '<td>' . htmlspecialchars($row->shift_name) . '</td>';
                echo '<td class="center">' . htmlspecialchars($row->role) . '</td>';
                echo '<td class="num">' . number_format($row->base_salary, 0, ',', '.') . '</td>';
                echo '<td>' . htmlspecialchars($row->email) . '</td>';
                echo '<td class="center" style="font-weight: bold; color: ' . ($row->is_active == 1 ? '#2E7D32' : '#C62828') . ';">' . ($row->is_active == 1 ? 'AKTIF' : 'NONAKTIF') . '</td>';
                echo '<td class="center">' . date('d/m/Y', strtotime($row->created_at)) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></body></html>';
            exit;
        }

        $filename = "Data_Master_Karyawan_" . date('Ymd_His') . ".csv";

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        fputcsv($output, [
            'No',
            'NIP',
            'Nama Lengkap',
            'Jabatan / Posisi',
            'Gender',
            'Departemen',
            'Alokasi Shift',
            'Peran (Role)',
            'Gaji Pokok (Rp)',
            'Email Login',
            'Status Akun',
            'Terdaftar Pada'
        ]);

        $no = 1;
        foreach ($employees as $row) {
            fputcsv($output, [
                $no++,
                $row->nip,
                $row->full_name,
                !empty($row->position) ? $row->position : '-',
                $row->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                $row->dept_name,
                $row->shift_name,
                $row->role,
                $row->base_salary,
                $row->email,
                $row->is_active == 1 ? 'AKTIF' : 'NONAKTIF',
                $row->created_at
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Hapus Arsip Surat Perintah Lembur (SPL) oleh HR Admin
     */
    public function delete_spl($id) {
        $this->allow_roles(['HR_ADMIN']);
        $id = (int) $id;

        $spl = $this->db->get_where('overtime_requests', ['id' => $id])->row();
        if (!$spl) {
            $this->session->set_flashdata('error', 'Data arsip SPL tidak ditemukan.');
            redirect('dashboard/hr#spl');
            return;
        }

        $this->db->where('id', $id)->delete('overtime_requests');
        $this->session->set_flashdata('success', 'Data tiket lembur No. ' . $spl->spl_number . ' berhasil dihapus dari arsip.');
        redirect('dashboard/hr#spl');
    }

    /**
     * Tambah Departemen Baru oleh HR Admin
     */
    public function add_department() {
        $this->allow_roles(['HR_ADMIN']);

        $this->form_validation->set_rules('dept_code', 'Kode Departemen', 'required|trim|strtoupper|is_unique[departments.dept_code]|max_length[10]');
        $this->form_validation->set_rules('dept_name', 'Nama Departemen', 'required|trim|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
            redirect('dashboard/hr#dept');
            return;
        }

        $dept_code = strtoupper(trim($this->input->post('dept_code', TRUE)));
        $dept_name = trim($this->input->post('dept_name', TRUE));

        $this->db->insert('departments', [
            'dept_code' => $dept_code,
            'dept_name' => $dept_name
        ]);

        $this->session->set_flashdata('success', 'Departemen baru (' . $dept_code . ' - ' . $dept_name . ') berhasil ditambahkan ke master pabrik.');
        redirect('dashboard/hr#dept');
    }

    /**
     * Tambah Pola Shift / Jam Kerja Baru (Office & Pabrik)
     */
    public function add_shift() {
        $this->allow_roles(['HR_ADMIN']);

        $this->form_validation->set_rules('shift_code', 'Kode Shift', 'required|trim|strtoupper|is_unique[shifts.shift_code]|max_length[10]');
        $this->form_validation->set_rules('shift_name', 'Nama Pola Shift', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('start_time', 'Jam Masuk', 'required');
        $this->form_validation->set_rules('end_time', 'Jam Pulang', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
            redirect('dashboard/hr#dept');
            return;
        }

        $shift_code   = strtoupper(trim($this->input->post('shift_code', TRUE)));
        $shift_name   = trim($this->input->post('shift_name', TRUE));
        $start_time   = trim($this->input->post('start_time', TRUE));
        $end_time     = trim($this->input->post('end_time', TRUE));
        $is_cross_day = $this->input->post('is_cross_day') ? 1 : 0;

        if (strlen($start_time) === 5) $start_time .= ':00';
        if (strlen($end_time) === 5) $end_time .= ':00';

        $this->db->insert('shifts', [
            'shift_code'   => $shift_code,
            'shift_name'   => $shift_name,
            'start_time'   => $start_time,
            'end_time'     => $end_time,
            'is_cross_day' => $is_cross_day
        ]);

        $this->session->set_flashdata('success', 'Pola Jam Kerja / Shift baru (' . $shift_code . ' - ' . $shift_name . ') berhasil ditambahkan.');
        redirect('dashboard/hr#dept');
    }

    /**
     * Endpoint API JSON untuk Live-Sync Real-Time Dashboard HR
     * Mengambil 100% data riil dari database (Summary metrik & 4 grafik analitik)
     */
    public function api_live_analytics() {
        $this->allow_roles(['HR_ADMIN']);

        $summary      = $this->Dashboard_model->get_hr_summary();
        $trend_7days  = $this->Dashboard_model->get_7days_attendance_trend();
        $ot_dist      = $this->Dashboard_model->get_overtime_distribution_by_dept();
        $shift_dist   = $this->Dashboard_model->get_shift_distribution();
        $ot_cost_dist = $this->Dashboard_model->get_overtime_cost_by_dept();

        $response = [
            'status'       => 'success',
            'timestamp'    => date('H:i:s') . ' WIB',
            'summary'      => $summary,
            'trend_7days'  => $trend_7days,
            'ot_dist'      => $ot_dist,
            'shift_dist'   => $shift_dist,
            'ot_cost_dist' => $ot_cost_dist
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
