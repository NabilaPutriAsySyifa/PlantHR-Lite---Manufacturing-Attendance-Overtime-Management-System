<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title; ?> | PlantHR Lite</title>
    <!-- Local Assets for 100% Offline Stability -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .plant-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .plant-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.06);
        }
        .clock-display {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #38bdf8;
            font-family: 'JetBrains Mono', monospace;
            font-size: 26px;
            font-weight: 800;
            padding: 8px 16px;
            border-radius: 10px;
            display: inline-block;
            letter-spacing: 2px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
        }
        .btn-clockin {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: 15px;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.2s ease;
        }
        .btn-clockin:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.35);
        }
        .btn-clockout {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: 15px;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.2s ease;
        }
        .btn-clockout:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(239, 68, 68, 0.35);
        }
        .badge-status {
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body>

<!-- Header Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-2">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand font-weight-bold d-flex align-items-center" href="<?= site_url('dashboard/operator'); ?>">
            <span class="mr-2 p-1 px-2 bg-primary text-white rounded"><i class="fas fa-industry"></i></span>
            <span>PlantHR <span class="text-primary font-weight-bold">Lite</span></span>
        </a>
        <div class="navbar-nav ml-auto align-items-center flex-row">
            <div class="d-none d-sm-block text-right mr-3 text-white">
                <div class="font-weight-bold small text-white"><?= $this->session->userdata('full_name'); ?> <span class="badge badge-info font-weight-bold ml-1">Staf / Karyawan</span></div>
                <small class="text-white-50"><i class="fas fa-id-badge mr-1"></i><?= $this->session->userdata('nip'); ?> &bull; <?= $this->session->userdata('shift_name'); ?></small>
            </div>
            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-danger btn-sm font-weight-bold px-3">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid px-lg-5 my-4">
    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fa-lg mr-2 text-success"></i>
            <div class="font-weight-bold"><?= $this->session->flashdata('success'); ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle fa-lg mr-2 text-danger"></i>
            <div><?= $this->session->flashdata('error'); ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Welcome & Shift Banner -->
    <div class="plant-card p-3 p-md-4 mb-4 bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="mb-3 mb-md-0">
            <span class="badge badge-primary px-2 py-1 mb-2 font-weight-bold">
                <i class="fas fa-id-card-alt mr-1"></i> PORTAL STAF &amp; KARYAWAN (PRESENSI MANDIRI &amp; SPL)
            </span>
            <h4 class="font-weight-bold text-dark m-0">Selamat Datang, <?= $this->session->userdata('full_name'); ?></h4>
            <p class="text-muted small mb-0 mt-1">
                Departemen: <strong><?= $this->session->userdata('dept_name'); ?></strong> &bull; Jabatan: <strong><?= $this->session->userdata('position') ?: 'Staf Pelaksana'; ?></strong> &bull; Jadwal Reguler: <strong><?= $this->session->userdata('shift_name'); ?></strong>
                <?php if (!empty($shift)): ?>
                    (<?= substr($shift->start_time, 0, 5); ?> - <?= substr($shift->end_time, 0, 5); ?> WIB<?= $shift->is_cross_day ? ' &bull; Lintas Hari' : ''; ?>)
                <?php endif; ?>
            </p>
            <small class="text-info font-weight-bold d-block mt-1">
                <i class="fas fa-info-circle mr-1"></i> Berlaku untuk staf seluruh lini divisi: Finance, Supply Chain (SCM), Produksi, QC, Maintenance, Warehouse/Logistik, dll.
            </small>
        </div>
        <div class="text-md-right">
            <div class="clock-display" id="liveClock">00:00:00 WIB</div>
            <div class="text-muted small mt-1 font-weight-bold"><?= date('l, d F Y'); ?></div>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Presensi & Formulir SPL -->
        <div class="col-lg-5 mb-4">
            <!-- Kartu Presensi Hari Ini -->
            <div class="plant-card mb-4 overflow-hidden">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark"><i class="fas fa-stopwatch text-primary mr-1"></i> Log Presensi Shift Hari Ini</span>
                    <span class="badge badge-info badge-status"><?= $this->session->userdata('shift_name'); ?></span>
                </div>
                <div class="p-4 text-center">
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="stat-box">
                                <small class="text-muted font-weight-bold d-block mb-1">JAM MASUK (CLOCK IN)</small>
                                <div class="h3 font-weight-bold text-dark mb-1 font-mono">
                                    <?= (!empty($today_attendance->clock_in)) ? date('H:i', strtotime($today_attendance->clock_in)) : '--:--'; ?>
                                </div>
                                <?php if (!empty($today_attendance) && $today_attendance->late_minutes > 0): ?>
                                    <span class="badge badge-danger badge-status">+<?= $today_attendance->late_minutes; ?> mnt Telat</span>
                                <?php elseif (!empty($today_attendance->clock_in)): ?>
                                    <span class="badge badge-success badge-status">Tepat Waktu</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary badge-status">Belum Presensi</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="stat-box">
                                <small class="text-muted font-weight-bold d-block mb-1">JAM PULANG (CLOCK OUT)</small>
                                <div class="h3 font-weight-bold text-dark mb-1 font-mono">
                                    <?= (!empty($today_attendance->clock_out)) ? date('H:i', strtotime($today_attendance->clock_out)) : '--:--'; ?>
                                </div>
                                <?php if (!empty($today_attendance->clock_out)): ?>
                                    <span class="badge badge-success badge-status">Selesai Kerja</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary badge-status">Belum Pulang</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Presensi -->
                    <?php if (empty($today_attendance) || empty($today_attendance->clock_in)): ?>
                        <?php if (!empty($shift_window) && !$shift_window['can_clock_in']): ?>
                            <div class="alert alert-warning border border-warning p-3 rounded mb-0 text-left shadow-sm">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge badge-danger px-2 py-1 mr-2"><i class="fas fa-lock mr-1"></i> DIKUNCI</span>
                                    <strong class="text-dark small">PRESENSI MASUK TIDAK TERSEDIA</strong>
                                </div>
                                <div class="small text-dark mt-1" style="line-height: 1.5;">
                                    <?= $shift_window['lock_in_reason']; ?>
                                </div>
                                <button type="button" class="btn btn-secondary btn-block mt-3 font-weight-bold" disabled>
                                    <i class="fas fa-lock mr-2"></i> CLOCK IN DIKUNCI (DI LUAR JADWAL SHIFT)
                                </button>
                            </div>
                        <?php else: ?>
                            <a href="<?= site_url('dashboard/clock_in'); ?>" class="btn btn-clockin btn-block shadow-sm">
                                <i class="fas fa-sign-in-alt mr-2"></i> CLOCK IN (MASUK KERJA)
                            </a>
                            <?php if (!empty($shift_window)): ?>
                                <small class="text-muted d-block mt-2">
                                    <i class="far fa-clock mr-1"></i> Jam Shift: <strong><?= $shift_window['start_time']; ?> - <?= $shift_window['end_time']; ?> WIB</strong> &bull; Batas Masuk: <strong><?= $shift_window['cutoff_in']; ?> WIB</strong>
                                </small>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php elseif (empty($today_attendance->clock_out)): ?>
                        <?php if (!empty($shift_window) && !$shift_window['can_clock_out']): ?>
                            <div class="alert alert-danger border border-danger p-3 rounded mb-0 text-left shadow-sm">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge badge-danger px-2 py-1 mr-2"><i class="fas fa-lock mr-1"></i> DIKUNCI</span>
                                    <strong class="text-danger small">PRESENSI PULANG DIKUNCI</strong>
                                </div>
                                <div class="small text-dark mt-1" style="line-height: 1.5;">
                                    <?= $shift_window['lock_out_reason']; ?>
                                </div>
                                <button type="button" class="btn btn-secondary btn-block mt-3 font-weight-bold" disabled>
                                    <i class="fas fa-lock mr-2"></i> CLOCK OUT DIKUNCI (MELEWATI BATAS TOLERANSI)
                                </button>
                            </div>
                        <?php else: ?>
                            <a href="<?= site_url('dashboard/clock_out'); ?>" class="btn btn-clockout btn-block shadow-sm" onclick="return confirm('Konfirmasi selesaikan jam kerja hari ini (Clock Out)?');">
                                <i class="fas fa-sign-out-alt mr-2"></i> CLOCK OUT (SELESAI PULANG)
                            </a>
                            <?php if (!empty($shift_window)): ?>
                                <small class="text-muted d-block mt-2">
                                    <i class="far fa-clock mr-1"></i> Jam Pulang Shift: <strong><?= $shift_window['end_time']; ?> WIB</strong>
                                    <?php if ($shift_window['has_approved_ot']): ?>
                                        <span class="badge badge-primary ml-1"><i class="fas fa-business-time mr-1"></i>SPL Lembur Aktif (Toleransi s/d <?= $shift_window['cutoff_out']; ?> WIB)</span>
                                    <?php else: ?>
                                        &bull; Toleransi s/d: <strong><?= $shift_window['cutoff_out']; ?> WIB</strong>
                                    <?php endif; ?>
                                </small>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-success border-0 shadow-sm py-3 m-0 d-flex align-items-center justify-content-center">
                            <i class="fas fa-check-circle fa-2x mr-3 text-success"></i>
                            <div class="text-left">
                                <div class="font-weight-bold">Presensi Shift Hari Ini Selesai</div>
                                <small class="text-muted">Terima kasih atas dedikasi dan kerja keras Anda hari ini!</small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kartu Form Pengajuan SPL -->
            <div class="plant-card">
                <div class="p-3 bg-light border-bottom">
                    <span class="font-weight-bold text-dark"><i class="fas fa-file-signature text-primary mr-1"></i> Pengajuan Lembur Mandiri (SPL)</span>
                </div>
                <div class="p-4">
                    <form method="POST" action="<?= site_url('dashboard/submit_spl'); ?>" id="splForm">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-secondary mb-1">Tanggal Rencana Lembur</label>
                            <input type="date" name="overtime_date" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-6">
                                <label class="small font-weight-bold text-secondary mb-1">Jam Mulai</label>
                                <input type="time" name="start_time" id="start_time" class="form-control" onchange="calculateDuration()" required>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-secondary mb-1">Jam Selesai</label>
                                <input type="time" name="end_time" id="end_time" class="form-control" onchange="calculateDuration()" required>
                            </div>
                        </div>

                        <div id="durationPreview" class="alert alert-info py-2 px-3 small border-0 d-none mb-3">
                            <i class="far fa-clock mr-1"></i> Estimasi durasi: <strong id="durationValue">0</strong> jam
                            <span id="crossDayNote" class="text-danger font-weight-bold ml-1 d-none">(Melewati Tengah Malam)</span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-secondary mb-1">Uraian / Target Tugas Lembur</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Contoh: Mengganti roll silinder converting lini 2 & kalibrasi sensor..." required></textarea>
                            <small class="text-muted font-italic">Tuliskan target operasional secara jelas untuk verifikasi Supervisor.</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Tiket Pengajuan SPL
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat Lembur & Rekap Absensi -->
        <div class="col-lg-7">
            <!-- Tabel Riwayat Pengajuan Lembur -->
            <div class="plant-card mb-4 overflow-hidden">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark"><i class="fas fa-clipboard-list text-primary mr-1"></i> Riwayat Pengajuan Lembur Saya</span>
                    <span class="badge badge-secondary badge-status"><?= count($my_spl_list); ?> Tiket</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>No. SPL</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Durasi</th>
                                <th>Target Tugas</th>
                                <th>Status</th>
                                <th class="text-center">Cetak Slip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($my_spl_list)): ?>
                                <?php foreach ($my_spl_list as $s): ?>
                                    <tr>
                                        <td><strong><?= $s->spl_number; ?></strong></td>
                                        <td><?= date('d/m/Y', strtotime($s->overtime_date)); ?></td>
                                        <td><?= substr($s->start_time, 0, 5); ?> - <?= substr($s->end_time, 0, 5); ?></td>
                                        <td><span class="badge badge-light border"><?= $s->total_hours; ?> Jam</span></td>
                                        <td>
                                            <?= htmlspecialchars($s->reason); ?>
                                            <?php if ($s->status == 'REJECTED' && !empty($s->rejection_note)): ?>
                                                <div class="mt-1 p-1 px-2 bg-danger text-white rounded small">
                                                    <i class="fas fa-comment-alt mr-1"></i> Alasan: <?= htmlspecialchars($s->rejection_note); ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($s->status == 'APPROVED'): ?>
                                                <span class="badge badge-success badge-status"><i class="fas fa-check-circle mr-1"></i>APPROVED</span>
                                            <?php elseif ($s->status == 'REJECTED'): ?>
                                                <span class="badge badge-danger badge-status"><i class="fas fa-times-circle mr-1"></i>REJECTED</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning badge-status"><i class="fas fa-clock mr-1"></i>PENDING</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($s->status == 'APPROVED'): ?>
                                                <a href="<?= site_url('dashboard/print_spl/' . $s->id); ?>" target="_blank" class="btn btn-outline-dark btn-sm px-2 py-1 font-weight-bold" title="Cetak Surat Perintah Lembur Resmi">
                                                    <i class="fas fa-print"></i> Slip
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="far fa-folder-open fa-2x mb-2 d-block text-black-50"></i>
                                        Belum ada permohonan lembur yang diajukan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Rekap Presensi Terakhir -->
            <div class="plant-card overflow-hidden">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark"><i class="fas fa-history text-primary mr-1"></i> Rekapitulasi Presensi Kehadiran Terakhir</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal Presensi</th>
                                <th>Jam Masuk (In)</th>
                                <th>Jam Pulang (Out)</th>
                                <th>Status Kehadiran</th>
                                <th>Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($my_attendances)): ?>
                                <?php foreach ($my_attendances as $a): ?>
                                    <tr>
                                        <td><strong><?= date('d/m/Y', strtotime($a->attendance_date)); ?></strong></td>
                                        <td><?= $a->clock_in ? '<span class="text-success font-weight-bold">' . date('H:i:s', strtotime($a->clock_in)) . '</span>' : '-'; ?></td>
                                        <td><?= $a->clock_out ? '<span class="text-danger font-weight-bold">' . date('H:i:s', strtotime($a->clock_out)) . '</span>' : '<span class="text-muted font-italic">Sedang Bekerja</span>'; ?></td>
                                        <td>
                                            <?php if ($a->status == 'PRESENT'): ?>
                                                <span class="badge badge-success badge-status">HADIR TEPAT</span>
                                            <?php elseif ($a->status == 'LATE'): ?>
                                                <span class="badge badge-warning badge-status">TERLAMBAT</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary badge-status"><?= $a->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $a->late_minutes > 0 ? '<span class="text-danger font-weight-bold font-mono">+' . $a->late_minutes . ' mnt</span>' : '<span class="text-success font-weight-bold font-mono">0 mnt</span>'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="far fa-calendar-times fa-2x mb-2 d-block text-black-50"></i>
                                        Belum ada rekaman log presensi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Local Scripts -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script>
// Jam Digital Berjalan Real-time
function updateLiveClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const clockEl = document.getElementById('liveClock');
    if (clockEl) {
        clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
    }
}
setInterval(updateLiveClock, 1000);
updateLiveClock();

// Kalkulasi Durasi Lembur di Form SPL & Deteksi Lintas Hari
function calculateDuration() {
    const start = document.getElementById('start_time').value;
    const end = document.getElementById('end_time').value;
    const preview = document.getElementById('durationPreview');
    const valEl = document.getElementById('durationValue');
    const crossDayNote = document.getElementById('crossDayNote');

    if (start && end) {
        const d1 = new Date(`2000-01-01T${start}:00`);
        let d2 = new Date(`2000-01-01T${end}:00`);
        let isCross = false;
        if (d2 <= d1) {
            d2 = new Date(`2000-01-02T${end}:00`); // Cross-day melewati tengah malam
            isCross = true;
        }
        const diffHours = ((d2 - d1) / (1000 * 60 * 60)).toFixed(2);
        valEl.textContent = diffHours;
        if (isCross) {
            crossDayNote.classList.remove('d-none');
        } else {
            crossDayNote.classList.add('d-none');
        }
        preview.classList.remove('d-none');
    }
}

// Solusi Masalah #5: Cegah Double-Submit Form di tingkat antarmuka klien
$(document).ready(function() {
    $('form').on('submit', function() {
        var btn = $(this).find('button[type="submit"]');
        if (!$(this).data('submitting')) {
            $(this).data('submitting', true);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim Pengajuan...');
        }
    });
});
</script>
</body>
</html>
