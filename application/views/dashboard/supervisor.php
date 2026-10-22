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
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .plant-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .metric-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 18px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -2px rgba(0,0,0,0.08);
        }
        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 8px;
            margin-right: 6px;
            transition: all 0.2s ease;
        }
        .nav-tabs .nav-link:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
        .nav-tabs .nav-link.active {
            background-color: #3b82f6;
            color: #ffffff;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-2">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand font-weight-bold d-flex align-items-center" href="<?= site_url('dashboard/supervisor'); ?>">
            <span class="mr-2 p-1 px-2 bg-primary text-white rounded"><i class="fas fa-industry"></i></span>
            <span>PlantHR <span class="text-primary font-weight-bold">Lite</span></span>
        </a>
        <div class="navbar-nav ml-auto align-items-center flex-row">
            <span class="navbar-text mr-3 text-white">
                <i class="fas fa-user-tie text-info mr-1"></i> <?= $this->session->userdata('full_name'); ?>
                <span class="badge badge-primary font-weight-bold ml-1">Supervisor</span>
            </span>
            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm font-weight-bold px-3">
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

    <!-- Summary Metrics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">SPL MENUNGGU VERIFIKASI</span>
                    <h3 class="font-weight-bold text-warning mb-0 mt-1"><?= count($pending_spl); ?></h3>
                    <small class="text-muted font-weight-bold">Membutuhkan Tindakan Review</small>
                </div>
                <div class="p-3 bg-warning text-white rounded-circle shadow-sm">
                    <i class="fas fa-clipboard-check fa-lg"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">ANGGOTA TIM DIVISI</span>
                    <h3 class="font-weight-bold text-primary mb-0 mt-1"><?= count($team_members); ?></h3>
                    <small class="text-muted font-weight-bold">Departemen: <?= $this->session->userdata('dept_name') ?: 'Operasional'; ?></small>
                </div>
                <div class="p-3 bg-primary text-white rounded-circle shadow-sm">
                    <i class="fas fa-users fa-lg"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">TOTAL KEPUTUSAN DIBUAT</span>
                    <h3 class="font-weight-bold text-success mb-0 mt-1"><?= count($decision_history); ?></h3>
                    <small class="text-muted font-weight-bold">Rekam Jejak Otorisasi Anda</small>
                </div>
                <div class="p-3 bg-success text-white rounded-circle shadow-sm">
                    <i class="fas fa-history fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs border-0 mb-3" id="supervisorTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                <i class="fas fa-inbox mr-1"></i> Verifikasi SPL Masuk
                <?php if (!empty($pending_spl)): ?>
                    <span class="badge badge-danger ml-1 font-weight-bold"><?= count($pending_spl); ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="team-tab" data-toggle="tab" href="#team" role="tab">
                <i class="fas fa-user-friends mr-1"></i> Kehadiran Tim Shift Hari Ini
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="history-tab" data-toggle="tab" href="#history" role="tab">
                <i class="fas fa-archive mr-1"></i> Riwayat Keputusan Lembur
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="supervisorTabContent">
        <!-- TAB 1: VERIFIKASI SPL MASUK -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-hourglass-half text-warning mr-1"></i> Permohonan Surat Perintah Lembur Menunggu Persetujuan
                        </h6>
                        <small class="text-muted">Periksa justifikasi kebutuhan pabrik dan tetapkan persetujuan (Approve) atau penolakan resmi (Reject).</small>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle small">
                        <thead class="thead-light">
                            <tr>
                                <th>No. SPL</th>
                                <th>Karyawan Pemohon</th>
                                <th>Divisi</th>
                                <th>Tanggal</th>
                                <th>Jam Lembur</th>
                                <th>Durasi Riil</th>
                                <th>Uraian Tugas / Target Lembur</th>
                                <th class="text-center" style="width: 170px;">Tindakan Otoritas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pending_spl)): ?>
                                <?php foreach ($pending_spl as $r): ?>
                                    <tr>
                                        <td><strong><?= $r->spl_number; ?></strong></td>
                                        <td>
                                            <div class="font-weight-bold text-dark"><?= $r->full_name; ?></div>
                                            <small class="text-muted font-mono"><i class="fas fa-id-card mr-1"></i><?= $r->nip; ?></small>
                                        </td>
                                        <td><span class="badge badge-light border"><?= $r->dept_name; ?></span></td>
                                        <td><?= date('d/m/Y', strtotime($r->overtime_date)); ?></td>
                                        <td><?= substr($r->start_time, 0, 5); ?> - <?= substr($r->end_time, 0, 5); ?></td>
                                        <td><span class="badge badge-info"><?= $r->total_hours; ?> Jam</span></td>
                                        <td><?= htmlspecialchars($r->reason); ?></td>
                                        <td class="text-center text-nowrap">
                                            <a href="<?= site_url('dashboard/approve_spl/' . $r->id); ?>" class="btn btn-success btn-sm font-weight-bold mr-1 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI tiket <?= $r->spl_number; ?>?');" title="Setujui (Approve)">
                                                <i class="fas fa-check mr-1"></i>Setujui
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm font-weight-bold btn-reject shadow-sm" data-id="<?= $r->id; ?>" data-spl="<?= $r->spl_number; ?>" data-name="<?= htmlspecialchars($r->full_name); ?>" title="Tolak (Reject)">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-3x text-success mb-2 d-block"></i>
                                        <strong>Semua Pengajuan Lembur Telah Ditindaklanjuti</strong>
                                        <div class="small mt-1">Tidak ada tiket SPL baru yang berstatus PENDING saat ini.</div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: KEHADIRAN TIM HARI INI -->
        <div class="tab-pane fade" id="team" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <div class="p-3 bg-white border-bottom">
                    <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-check text-success mr-1"></i> Status Kehadiran Personil Departemen</h6>
                    <small class="text-muted">Data log absensi staf di bawah supervisi Anda pada tanggal <?= date('d M Y'); ?>.</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
                                <th>Jadwal Shift</th>
                                <th>Jam Masuk (In)</th>
                                <th>Jam Pulang (Out)</th>
                                <th>Status Hari Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($team_members)): ?>
                                <?php foreach ($team_members as $m): ?>
                                    <tr>
                                        <td class="font-weight-bold font-mono"><?= $m->nip; ?></td>
                                        <td><strong><?= $m->full_name; ?></strong></td>
                                        <td><span class="badge badge-light border"><?= $m->role; ?></span></td>
                                        <td><?= $m->shift_name; ?> (<?= substr($m->start_time, 0, 5); ?> - <?= substr($m->end_time, 0, 5); ?>)</td>
                                        <td><?= !empty($m->clock_in) ? '<span class="text-success font-weight-bold">' . date('H:i:s', strtotime($m->clock_in)) . '</span>' : '-'; ?></td>
                                        <td><?= !empty($m->clock_out) ? '<span class="text-danger font-weight-bold">' . date('H:i:s', strtotime($m->clock_out)) . '</span>' : '<span class="text-muted font-italic">Sedang Bekerja</span>'; ?></td>
                                        <td>
                                            <?php if ($m->attendance_status == 'PRESENT'): ?>
                                                <span class="badge badge-success badge-status">HADIR TEPAT WAKTU</span>
                                            <?php elseif ($m->attendance_status == 'LATE'): ?>
                                                <span class="badge badge-warning badge-status">TERLAMBAT (+<?= $m->late_minutes; ?> mnt)</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary badge-status">BELUM CLOCK IN</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada anggota tim terdaftar pada divisi ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: RIWAYAT KEPUTUSAN SUPERVISOR -->
        <div class="tab-pane fade" id="history" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <div class="p-3 bg-white border-bottom">
                    <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-history text-primary mr-1"></i> Log Riwayat Keputusan Verifikasi Terkini</h6>
                    <small class="text-muted">Rekam jejak tiket lembur yang sudah Anda setujui atau tolak.</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>No. SPL</th>
                                <th>Karyawan</th>
                                <th>Tanggal Lembur</th>
                                <th>Durasi</th>
                                <th>Status Keputusan</th>
                                <th>Catatan / Keterangan</th>
                                <th>Waktu Verifikasi</th>
                                <th class="text-center">Cetak Slip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($decision_history)): ?>
                                <?php foreach ($decision_history as $h): ?>
                                    <tr>
                                        <td><strong><?= $h->spl_number; ?></strong></td>
                                        <td>
                                            <div class="font-weight-bold"><?= $h->full_name; ?></div>
                                            <small class="text-muted font-mono"><?= $h->nip; ?></small>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($h->overtime_date)); ?></td>
                                        <td><span class="badge badge-light border"><?= $h->total_hours; ?> Jam</span></td>
                                        <td>
                                            <?php if ($h->status == 'APPROVED'): ?>
                                                <span class="badge badge-success badge-status"><i class="fas fa-check-circle mr-1"></i>DISETUJUI</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger badge-status"><i class="fas fa-times-circle mr-1"></i>DITOLAK</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= !empty($h->rejection_note) ? '<span class="text-danger font-italic">"' . htmlspecialchars($h->rejection_note) . '"</span>' : htmlspecialchars($h->reason); ?>
                                        </td>
                                        <td><small class="text-muted font-mono"><?= date('d/m/Y H:i', strtotime($h->updated_at)); ?></small></td>
                                        <td class="text-center">
                                            <a href="<?= site_url('dashboard/print_spl/' . $h->id); ?>" target="_blank" class="btn btn-outline-dark btn-sm px-2 py-1 font-weight-bold" title="Cetak Dokumen Resmi SPL">
                                                <i class="fas fa-print"></i> Slip
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat keputusan lembur.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Penolakan SPL -->
<div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <form method="POST" action="<?= site_url('dashboard/reject_spl'); ?>">
                <input type="hidden" name="spl_id" id="reject_spl_id">
                <div class="modal-header bg-danger text-white py-3">
                    <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Penolakan Tiket Lembur
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning py-2 px-3 small border-0 mb-3">
                        Pengajuan SPL <strong id="lblSpl" class="text-dark"></strong> atas nama <strong id="lblEmp" class="text-dark"></strong> akan ditolak.
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold text-dark mb-1">
                            Alasan Penolakan Resmi <span class="text-danger">*</span>
                        </label>
                        <textarea name="rejection_note" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan secara jelas (contoh: Kapasitas shift malam sudah terpenuhi)..." required></textarea>
                        <small class="text-muted">Catatan ini akan langsung tampil di portal operator pemohon.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm font-weight-bold px-3" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold px-4 shadow-sm">
                        <i class="fas fa-times-circle mr-1"></i> Tolak Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Local Scripts -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('.btn-reject').on('click', function() {
        var id = $(this).data('id');
        var spl = $(this).data('spl');
        var name = $(this).data('name');
        
        $('#reject_spl_id').val(id);
        $('#lblSpl').text(spl);
        $('#lblEmp').text(name);
        $('#modalReject').modal('show');
    });

    // Solusi Masalah #5: Cegah Double-Submit Form di tingkat antarmuka klien
    $('form').on('submit', function() {
        var btn = $(this).find('button[type="submit"]');
        if (!$(this).data('submitting')) {
            $(this).data('submitting', true);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
        }
    });
});
</script>
</body>
</html>
