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
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --slate-800: #1e293b;
            --slate-900: #0f172a;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .plant-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }
        .metric-card {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.08);
        }
        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 11px 20px;
            border-radius: 8px;
            margin-right: 8px;
            transition: all 0.2s ease;
        }
        .nav-tabs .nav-link:hover {
            color: #0f172a;
            background-color: #e2e8f0;
        }
        .nav-tabs .nav-link.active {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.3px;
        }
        .code-block {
            background: #0f172a;
            color: #38bdf8;
            padding: 14px 18px;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.6;
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
        <a class="navbar-brand font-weight-bold d-flex align-items-center" href="<?= site_url('dashboard/hr'); ?>">
            <span class="mr-2 p-1 px-2 bg-primary text-white rounded"><i class="fas fa-industry"></i></span>
            <span>PlantHR <span class="text-primary font-weight-bolder">Lite</span></span>
        </a>
        <div class="navbar-nav ml-auto align-items-center flex-row">
            <span class="navbar-text mr-3 text-white">
                <i class="fas fa-user-shield text-warning mr-1"></i> <?= $this->session->userdata('full_name'); ?>
                <span class="badge badge-warning text-dark font-weight-bold ml-1">Administrator Utama</span>
            </span>
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

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-tachometer-alt text-primary mr-2"></i><?= $title; ?>
            </h4>
            <p class="text-muted small mb-0">Pusat Administrasi Sumber Daya Manusia, Pemantauan Presensi 3-Shift, Pengendalian Lembur, &amp; Analitik Operasional Pabrik</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex align-items-center" style="gap: 10px;">
            <span class="badge badge-white border p-2 px-3 text-muted font-weight-bold shadow-sm">
                <i class="far fa-calendar-alt mr-1 text-primary"></i> <?= date('l, d F Y'); ?>
            </span>
        </div>
    </div>

    <!-- Presensi Mandiri Administrator Utama (Self-Service Attendance) -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff;">
        <div class="card-body p-3 p-md-4 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between" style="gap: 15px;">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded-circle mr-3" style="background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(59, 130, 246, 0.3);">
                    <i class="fas fa-fingerprint fa-2x text-primary"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge badge-primary font-weight-bold mr-2">PRESENSI MANDIRI ADMINISTRATOR</span>
                        <small class="text-white-50 font-weight-bold">NIP: <?= $this->session->userdata('nip'); ?> &bull; Jadwal: <?= !empty($my_shift) ? $my_shift->shift_name . ' (' . substr($my_shift->start_time, 0, 5) . ' - ' . substr($my_shift->end_time, 0, 5) . ' WIB)' : 'Reguler'; ?></small>
                    </div>
                    <h5 class="font-weight-bold mb-0 text-white">Halo, <?= $this->session->userdata('full_name'); ?> <?= !empty($this->session->userdata('position')) ? '(' . $this->session->userdata('position') . ')' : '(Administrator Utama)'; ?></h5>
                    <small class="text-white-50">Sebagai Administrator Utama / Pengelola HR, kehadiran Anda juga tercatat di sistem presensi pabrik secara akuntabel.</small>
                </div>
            </div>
            
            <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
                <?php if (empty($my_attendance) || empty($my_attendance->clock_in)): ?>
                    <div class="text-lg-right mr-lg-2">
                        <span class="badge badge-warning text-dark font-weight-bold px-2 py-1"><i class="fas fa-clock mr-1"></i> Belum Presensi Masuk</span>
                        <div class="small text-white-50 mt-1">Waktu Server: <span class="text-warning font-weight-bold font-mono"><?= date('H:i'); ?> WIB</span></div>
                    </div>
                    <form method="POST" action="<?= site_url('dashboard/clock_in'); ?>" class="m-0">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="btn btn-success font-weight-bold px-4 py-2 shadow-sm d-flex align-items-center">
                            <i class="fas fa-sign-in-alt mr-2 fa-lg"></i> PRESENSI MASUK SEKARANG
                        </button>
                    </form>
                <?php elseif (!empty($my_attendance->clock_in) && empty($my_attendance->clock_out)): ?>
                    <div class="text-lg-right mr-lg-2">
                        <span class="badge badge-success font-weight-bold px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Sedang Bertugas</span>
                        <div class="small text-white-50 mt-1">Masuk: <strong class="text-white font-mono"><?= date('H:i', strtotime($my_attendance->clock_in)); ?> WIB</strong> (<?= $my_attendance->status === 'LATE' ? '<span class="text-warning font-weight-bold">Telat ' . $my_attendance->late_minutes . 'm</span>' : 'Tepat Waktu'; ?>)</div>
                    </div>
                    <form method="POST" action="<?= site_url('dashboard/clock_out'); ?>" class="m-0">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <button type="submit" class="btn btn-danger font-weight-bold px-4 py-2 shadow-sm d-flex align-items-center">
                            <i class="fas fa-sign-out-alt mr-2 fa-lg"></i> PRESENSI PULANG (CLOCK OUT)
                        </button>
                    </form>
                <?php else: ?>
                    <div class="d-flex align-items-center bg-dark p-2 px-3 rounded border border-secondary">
                        <i class="fas fa-calendar-check text-success mr-2 fa-lg"></i>
                        <div>
                            <span class="badge badge-success font-weight-bold">Presensi Hari Ini Selesai</span>
                            <div class="small text-white-50 mt-1 font-mono">
                                Masuk: <?= date('H:i', strtotime($my_attendance->clock_in)); ?> &bull; Pulang: <?= date('H:i', strtotime($my_attendance->clock_out)); ?> WIB
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 4 High Impact Metric Cards -->
    <div class="row mb-4">
        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">TOTAL KARYAWAN PABRIK</span>
                    <h3 class="font-weight-bold text-primary mb-0 mt-1" id="metric_total_employees"><?= $summary['total_employees']; ?></h3>
                    <small class="text-muted font-weight-bold">Personil Aktif Terdaftar</small>
                </div>
                <div class="p-3 bg-primary text-white rounded-circle shadow-sm">
                    <i class="fas fa-users fa-lg"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">DIVISI OPERASIONAL</span>
                    <h3 class="font-weight-bold text-info mb-0 mt-1" id="metric_total_dept"><?= $summary['total_dept']; ?></h3>
                    <small class="text-muted font-weight-bold"><?= $summary['total_shifts']; ?> Pola Shift Kerja (24/7)</small>
                </div>
                <div class="p-3 bg-info text-white rounded-circle shadow-sm">
                    <i class="fas fa-sitemap fa-lg"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">HADIR SHIFT HARI INI</span>
                    <h3 class="font-weight-bold text-success mb-0 mt-1" id="metric_today_present"><?= $summary['today_present']; ?></h3>
                    <small id="metric_today_late_text" class="<?= $summary['today_late'] > 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold'; ?>">
                        <?= $summary['today_late'] > 0 ? $summary['today_late'] . ' Terlambat Masuk' : '100% Tepat Waktu'; ?>
                    </small>
                </div>
                <div class="p-3 bg-success text-white rounded-circle shadow-sm">
                    <i class="fas fa-user-check fa-lg"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="metric-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="small font-weight-bold text-muted d-block">TIKET SPL PENDING</span>
                    <h3 class="font-weight-bold text-warning mb-0 mt-1" id="metric_pending_spl"><?= $summary['pending_spl']; ?></h3>
                    <small class="text-muted font-weight-bold">Menunggu Review SPV</small>
                </div>
                <div class="p-3 bg-warning text-white rounded-circle shadow-sm">
                    <i class="fas fa-file-invoice-dollar fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Analytics: Chart.js Section -->
    <div class="row mb-4">
        <!-- Grafik Tren Kedisiplinan 7 Hari -->
        <div class="col-lg-8 mb-3 mb-lg-0">
            <div class="plant-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-chart-line text-primary mr-2"></i>Tren Kedisiplinan Presensi Pabrik (7 Hari Terakhir)
                        </h6>
                        <small class="text-muted">Perbandingan personil hadir tepat waktu versus terlambat masuk shift.</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                            Tingkat Disiplin Hari Ini: <span class="text-success" id="metric_on_time_rate"><?= $summary['on_time_rate']; ?>%</span>
                        </span>
                        <span class="badge badge-light border text-muted font-weight-normal px-2 py-1 ml-2" id="liveSyncBadge" title="Data disinkronkan otomatis dari database secara berkala">
                            <i class="fas fa-circle text-success mr-1 fa-xs"></i><span id="liveSyncTime">Live Sync</span>
                        </span>
                    </div>
                </div>
                <div style="height: 250px;">
                    <canvas id="attendanceTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Distribusi Lembur per Departemen -->
        <div class="col-lg-4">
            <div class="plant-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-chart-pie text-info mr-2"></i>Distribusi Jam Lembur
                        </h6>
                        <small class="text-muted">Total jam lembur approved per divisi.</small>
                    </div>
                </div>
                <div style="height: 250px;">
                    <canvas id="otDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Analytics Baris 2: Komposisi Shift & Estimasi Biaya Lembur (Rp PP 35/2021) -->
    <div class="row mb-4">
        <!-- Grafik Komposisi Personil per Shift -->
        <div class="col-lg-5 mb-3 mb-lg-0">
            <div class="plant-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-users-cog text-warning mr-2"></i>Komposisi Personil per Shift
                        </h6>
                        <small class="text-muted">Keseimbangan kapasitas regu kerja 24/7 (Pagi, Sore, Malam).</small>
                    </div>
                    <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                        Pola 3-Shift
                    </span>
                </div>
                <div style="height: 250px;">
                    <canvas id="shiftDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Estimasi Biaya Upah Lembur (Rp) per Departemen -->
        <div class="col-lg-7">
            <div class="plant-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-money-bill-wave text-success mr-2"></i>Estimasi Biaya Upah Lembur per Departemen (PP No. 35/2021)
                        </h6>
                        <small class="text-muted">Akumulasi pengeluaran kompensasi upah lembur resmi (dalam Rupiah).</small>
                    </div>
                    <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                        Formula: Gaji / 173
                    </span>
                </div>
                <div style="height: 250px;">
                    <canvas id="otCostDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs border-0 mb-3" id="hrTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="att-tab" data-toggle="tab" href="#att" role="tab">
                <i class="fas fa-calendar-check mr-1"></i> Rekap Presensi Pabrik
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="cutoff-tab" data-toggle="tab" href="#cutoff" role="tab">
                <i class="fas fa-calculator mr-1"></i> Rekap Cut-Off Payroll
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="emp-tab" data-toggle="tab" href="#emp" role="tab">
                <i class="fas fa-id-badge mr-1"></i> Master Karyawan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="dept-tab" data-toggle="tab" href="#dept" role="tab">
                <i class="fas fa-building mr-1"></i> Departemen &amp; Shift
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="spl-tab" data-toggle="tab" href="#spl" role="tab">
                <i class="fas fa-file-invoice mr-1"></i> Arsip SPL &amp; Upah Lembur PP 35/2021
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="backup-tab" data-toggle="tab" href="#backup" role="tab">
                <i class="fas fa-database mr-1"></i> Pemeliharaan Database &amp; Otomasi
            </a>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="hrTabContent">
        <!-- TAB 1: PRESENSI PABRIK TERFILTER -->
        <div class="tab-pane fade show active" id="att" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <!-- Filter & Action Bar -->
                <div class="p-3 bg-white border-bottom">
                    <form method="GET" action="<?= site_url('dashboard/hr'); ?>" class="form-row align-items-center">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small text-muted font-weight-bold mb-1">TANGGAL PRESENSI</label>
                            <input type="date" name="filter_date" class="form-control form-control-sm" value="<?= $filter_date; ?>">
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small text-muted font-weight-bold mb-1">DEPARTEMEN / DIVISI</label>
                            <select name="filter_dept" class="form-control form-control-sm">
                                <option value="">Semua Departemen</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d->id; ?>" <?= ($filter_dept == $d->id) ? 'selected' : ''; ?>>
                                        <?= $d->dept_name; ?> (<?= $d->dept_code; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0 d-flex align-items-end" style="gap: 6px; padding-top: 22px;">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold px-3">
                                <i class="fas fa-filter mr-1"></i> Terapkan Filter
                            </button>
                            <a href="<?= site_url('dashboard/hr'); ?>" class="btn btn-outline-secondary btn-sm font-weight-bold px-2">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                        <div class="col-md-3 text-md-right mb-2 mb-md-0" style="padding-top: 22px;">
                            <div class="btn-group shadow-sm">
                                <a href="<?= site_url('dashboard/export_attendance?format=excel&filter_date=' . $filter_date . '&filter_dept=' . $filter_dept); ?>" class="btn btn-success btn-sm font-weight-bold px-3" title="Unduh Spreadsheet Format Microsoft Excel">
                                    <i class="fas fa-file-excel mr-1"></i> Unduh Excel (.xls)
                                </a>
                                <a href="<?= site_url('dashboard/export_attendance?filter_date=' . $filter_date . '&filter_dept=' . $filter_dept); ?>" class="btn btn-outline-success btn-sm font-weight-bold px-2 bg-white" title="Unduh Format CSV Baku">
                                    .CSV
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Karyawan</th>
                                <th>Departemen</th>
                                <th>Shift Kerja</th>
                                <th>Jam Masuk (In)</th>
                                <th>Jam Pulang (Out)</th>
                                <th>Status Kehadiran</th>
                                <th>Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($attendances)): ?>
                                <?php foreach ($attendances as $a): ?>
                                    <tr>
                                        <td class="font-weight-bold font-mono"><?= $a->nip; ?></td>
                                        <td><strong><?= $a->full_name; ?></strong></td>
                                        <td><span class="badge badge-light border"><?= $a->dept_name; ?></span></td>
                                        <td><?= $a->shift_name; ?> (<?= substr($a->start_time, 0, 5); ?> - <?= substr($a->end_time, 0, 5); ?>)</td>
                                        <td><?= !empty($a->clock_in) ? '<span class="text-success font-weight-bold">' . date('H:i:s', strtotime($a->clock_in)) . '</span>' : '-'; ?></td>
                                        <td><?= !empty($a->clock_out) ? '<span class="text-danger font-weight-bold">' . date('H:i:s', strtotime($a->clock_out)) . '</span>' : '<span class="text-muted font-italic">Sedang Bekerja</span>'; ?></td>
                                        <td>
                                            <?php if ($a->status == 'PRESENT'): ?>
                                                <span class="badge badge-success badge-status">HADIR TEPAT WAKTU</span>
                                            <?php elseif ($a->status == 'LATE'): ?>
                                                <span class="badge badge-warning badge-status">TERLAMBAT MASUK</span>
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
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="far fa-calendar-times fa-3x mb-2 d-block text-black-50"></i>
                                        Tidak ditemukan log presensi untuk kriteria tanggal &amp; departemen yang dipilih.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: REKAPITULASI PERIODE CUT-OFF PAYROLL TERPADU (SOLUSI MASALAH #7) -->
        <div class="tab-pane fade" id="cutoff" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <!-- Filter Periode Cut-Off -->
                <div class="p-3 bg-white border-bottom">
                    <form method="GET" action="<?= site_url('dashboard/hr'); ?>" class="form-row align-items-center">
                        <input type="hidden" name="filter_date" value="<?= $filter_date; ?>">
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small text-muted font-weight-bold mb-1">DARI TANGGAL CUT-OFF</label>
                            <input type="date" name="cutoff_start" class="form-control form-control-sm" value="<?= $cutoff_start; ?>">
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small text-muted font-weight-bold mb-1">SAMPAI TANGGAL CUT-OFF</label>
                            <input type="date" name="cutoff_end" class="form-control form-control-sm" value="<?= $cutoff_end; ?>">
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0">
                            <label class="small text-muted font-weight-bold mb-1">DEPARTEMEN</label>
                            <select name="filter_dept" class="form-control form-control-sm">
                                <option value="">Semua Departemen</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d->id; ?>" <?= ($filter_dept == $d->id) ? 'selected' : ''; ?>>
                                        <?= $d->dept_name; ?> (<?= $d->dept_code; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 mb-md-0 text-md-right" style="padding-top: 22px;">
                            <button type="submit" class="btn btn-primary btn-sm font-weight-bold px-3 mr-1">
                                <i class="fas fa-sync-alt mr-1"></i> Hitung
                            </button>
                            <div class="btn-group shadow-sm">
                                <a href="<?= site_url('dashboard/export_payroll_cutoff?format=excel&cutoff_start=' . $cutoff_start . '&cutoff_end=' . $cutoff_end . '&filter_dept=' . $filter_dept); ?>" class="btn btn-success btn-sm font-weight-bold px-3" title="Unduh Spreadsheet Format Microsoft Excel">
                                    <i class="fas fa-file-excel mr-1"></i> Unduh Excel (.xls)
                                </a>
                                <a href="<?= site_url('dashboard/export_payroll_cutoff?cutoff_start=' . $cutoff_start . '&cutoff_end=' . $cutoff_end . '&filter_dept=' . $filter_dept); ?>" class="btn btn-outline-success btn-sm font-weight-bold px-2 bg-white" title="Unduh Format CSV Baku">
                                    .CSV
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Info Alert Cutoff -->
                <div class="p-3 bg-light border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <span class="badge badge-primary font-weight-bold px-2 py-1 mr-2">PERIODE CUT-OFF PAYROLL</span>
                        <strong class="text-dark"><?= date('d F Y', strtotime($cutoff_start)); ?> s/d <?= date('d F Y', strtotime($cutoff_end)); ?></strong>
                        <div class="small text-muted mt-1">
                            <i class="fas fa-check-circle text-success mr-1"></i> 
                            Otomatis mengagregasi kehadiran shift, akumulasi keterlambatan, dan kompensasi upah lembur PP 35/2021 siap transfer payroll.
                        </div>
                    </div>
                    <?php 
                        $total_all_ot_pay = !empty($payroll_summary) ? array_sum(array_column($payroll_summary, 'total_ot_pay')) : 0;
                        $total_all_ot_hours = !empty($payroll_summary) ? array_sum(array_column($payroll_summary, 'total_ot_hours')) : 0;
                    ?>
                    <div class="text-md-right mt-2 mt-md-0">
                        <small class="text-muted font-weight-bold d-block">TOTAL BEBAN LEMBUR PERIODE INI</small>
                        <span class="h5 font-weight-bold text-success font-mono mb-0">Rp <?= number_format($total_all_ot_pay, 0, ',', '.'); ?></span>
                        <small class="text-muted d-block font-mono">(<?= $total_all_ot_hours; ?> Jam Riil Disetujui)</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Karyawan</th>
                                <th>Departemen</th>
                                <th>Shift</th>
                                <th>Gaji Pokok</th>
                                <th class="text-center">Hari Hadir</th>
                                <th class="text-center">Terlambat</th>
                                <th class="text-center">Jam Lembur</th>
                                <th class="text-center">Jam Setara PP35</th>
                                <th>Upah Lembur</th>
                                <th>Est. Take Home Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($payroll_summary)): ?>
                                <?php foreach ($payroll_summary as $p): ?>
                                    <tr>
                                        <td class="font-weight-bold font-mono"><?= $p->nip; ?></td>
                                        <td><strong><?= $p->full_name; ?></strong></td>
                                        <td><span class="badge badge-light border"><?= $p->dept_name; ?></span></td>
                                        <td><?= $p->shift_name; ?></td>
                                        <td class="font-mono">Rp <?= number_format($p->base_salary, 0, ',', '.'); ?></td>
                                        <td class="text-center font-weight-bold text-success">
                                            <span class="badge badge-success px-2 py-1"><?= $p->total_attendance; ?> Hari</span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($p->total_late_count > 0): ?>
                                                <span class="badge badge-warning font-weight-bold"><?= $p->total_late_count; ?>x (+<?= $p->total_late_mins; ?> mnt)</span>
                                            <?php else: ?>
                                                <span class="badge badge-light border text-success">0x</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center font-mono">
                                            <?= $p->total_ot_hours > 0 ? '<span class="badge badge-info">' . $p->total_ot_hours . ' Jam</span>' : '-'; ?>
                                        </td>
                                        <td class="text-center font-mono">
                                            <?= $p->equivalent_hours > 0 ? '<span class="badge badge-secondary">' . $p->equivalent_hours . ' Jam</span>' : '-'; ?>
                                        </td>
                                        <td class="font-mono font-weight-bold text-primary">
                                            <?= $p->total_ot_pay > 0 ? 'Rp ' . number_format($p->total_ot_pay, 0, ',', '.') : '-'; ?>
                                        </td>
                                        <td class="font-mono font-weight-bold text-success" style="font-size: 13px;">
                                            Rp <?= number_format($p->est_total_income, 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">Belum ada data pada periode cut-off ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: MASTER KARYAWAN LENGKAP DENGAN CRUD -->
        <div class="tab-pane fade" id="emp" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-users text-primary mr-1"></i> Data Master Personil Karyawan Pabrik</h6>
                        <small class="text-muted">Master database personil terintegrasi dengan penempatan shift, gaji pokok, dan hak akses sistem.</small>
                    </div>
                    <div class="d-flex align-items-center" style="gap: 8px;">
                        <a href="<?= site_url('dashboard/export_employees?format=excel'); ?>" class="btn btn-success btn-sm font-weight-bold px-3 shadow-sm" title="Unduh Spreadsheet Format Microsoft Excel (.xls)">
                            <i class="fas fa-file-excel mr-1"></i> Unduh Excel
                        </a>
                        <a href="<?= site_url('dashboard/export_employees'); ?>" class="btn btn-outline-success btn-sm font-weight-bold px-2 bg-white shadow-sm" title="Unduh Format CSV Baku">
                            <i class="fas fa-file-csv mr-1"></i> CSV
                        </a>
                        <button type="button" class="btn btn-primary btn-sm font-weight-bold px-3 shadow-sm" data-toggle="modal" data-target="#modalAddEmployee">
                            <i class="fas fa-user-plus mr-1"></i> Tambah Karyawan Baru
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Lengkap</th>
                                <th>Jabatan / Posisi</th>
                                <th>Gender</th>
                                <th>Departemen</th>
                                <th>Alokasi Shift</th>
                                <th>Wewenang Akun</th>
                                <th>Gaji Pokok</th>
                                <th>Email Login</th>
                                <th>Status</th>
                                <th class="text-center">Aksi Otoritas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $e): ?>
                                <tr>
                                    <td class="font-weight-bold font-mono"><?= $e->nip; ?></td>
                                    <td><strong><?= $e->full_name; ?></strong></td>
                                    <td><span class="badge badge-light border text-dark font-weight-bold"><?= htmlspecialchars(!empty($e->position) ? $e->position : '-'); ?></span></td>
                                    <td><?= $e->gender == 'L' ? '<span class="badge badge-light">L</span>' : '<span class="badge badge-light">P</span>'; ?></td>
                                    <td><span class="badge badge-light border"><?= $e->dept_name; ?></span></td>
                                    <td><?= $e->shift_name; ?></td>
                                    <td>
                                        <?php if ($e->role == 'HR_ADMIN'): ?>
                                            <span class="badge badge-warning text-dark font-weight-bold"><i class="fas fa-shield-alt mr-1"></i>ADMIN UTAMA</span>
                                        <?php elseif ($e->role == 'SUPERVISOR'): ?>
                                            <span class="badge badge-primary font-weight-bold"><i class="fas fa-user-check mr-1"></i>SUPERVISOR</span>
                                        <?php else: ?>
                                            <span class="badge badge-success font-weight-bold"><i class="fas fa-user mr-1"></i>STAF / PELAKSANA</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="font-mono">Rp <?= number_format($e->base_salary, 0, ',', '.'); ?></td>
                                    <td><code><?= $e->email; ?></code></td>
                                    <td>
                                        <?= $e->is_active ? '<span class="badge badge-success badge-status">Aktif</span>' : '<span class="badge badge-danger badge-status">Nonaktif</span>'; ?>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <button type="button" class="btn btn-outline-primary btn-sm px-2 py-1 btn-edit-emp" 
                                            data-id="<?= $e->id; ?>"
                                            data-nip="<?= $e->nip; ?>"
                                            data-name="<?= htmlspecialchars($e->full_name); ?>"
                                            data-position="<?= htmlspecialchars($e->position ?? ''); ?>"
                                            data-dept="<?= $e->department_id; ?>"
                                            data-shift="<?= $e->shift_id; ?>"
                                            data-role="<?= $e->role; ?>"
                                            data-salary="<?= $e->base_salary; ?>"
                                            title="Edit Data & Shift">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-sm px-2 py-1 ml-1 btn-reset-pass"
                                            data-id="<?= $e->id; ?>"
                                            data-nip="<?= $e->nip; ?>"
                                            data-name="<?= htmlspecialchars($e->full_name); ?>"
                                            title="Reset Kata Sandi Akun (Solusi Lupa Password)">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <a href="<?= site_url('dashboard/toggle_employee/' . $e->id); ?>" 
                                            class="btn btn-outline-<?= $e->is_active ? 'warning' : 'success'; ?> btn-sm px-2 py-1 ml-1"
                                            onclick="return confirm('Apakah Anda yakin ingin mengubah status keaktifan personil ini?');"
                                            title="<?= $e->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun'; ?>">
                                            <i class="fas fa-power-off"></i>
                                        </a>
                                        <?php if ($e->id != $this->session->userdata('user_id')): ?>
                                            <button type="button" class="btn btn-outline-danger btn-sm px-2 py-1 ml-1 btn-delete-emp"
                                                data-id="<?= $e->id; ?>"
                                                data-nip="<?= $e->nip; ?>"
                                                data-name="<?= htmlspecialchars($e->full_name); ?>"
                                                title="Hapus Data Karyawan (Cegah Duplikasi)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1 ml-1" disabled title="Akun Anda Sendiri (Sedang Digunakan)">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: DEPARTEMEN & SHIFT -->
        <div class="tab-pane fade" id="dept" role="tabpanel">
            <div class="row">
                <!-- Master Departemen -->
                <div class="col-lg-6 mb-4">
                    <div class="plant-card overflow-hidden">
                        <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-sitemap text-primary mr-1"></i> Master Departemen Pabrik (Office &amp; Non-Office)</h6>
                                <small class="text-muted">Master divisi kerja yang menaungi seluruh personil pabrik.</small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold px-3 shadow-sm" data-toggle="modal" data-target="#modalAddDepartment">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Departemen
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 small">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Departemen</th>
                                        <th class="text-center">Jumlah Personil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($departments as $d): ?>
                                        <tr>
                                            <td><span class="badge badge-dark font-mono"><?= $d->dept_code; ?></span></td>
                                            <td><strong><?= $d->dept_name; ?></strong></td>
                                            <td class="text-center"><span class="badge badge-primary px-2 py-1"><?= $d->total_member; ?> Orang</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Master Shift -->
                <div class="col-lg-6 mb-4">
                    <div class="plant-card overflow-hidden">
                        <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-business-time text-primary mr-1"></i> Master Pola Jam Kerja &amp; Shift (Office &amp; Pabrik)</h6>
                                <small class="text-muted">Pola jam kerja 24/7 lini pabrik &amp; jam normal kantor (Office Hours).</small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm font-weight-bold px-3 shadow-sm" data-toggle="modal" data-target="#modalAddShift">
                                <i class="fas fa-plus-circle mr-1"></i> Tambah Shift
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 small">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Pola Kerja</th>
                                        <th>Jam Kerja</th>
                                        <th>Kategori &amp; Tipe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($shifts as $s): ?>
                                        <tr>
                                            <td><span class="badge badge-dark font-mono"><?= $s->shift_code; ?></span></td>
                                            <td><strong><?= $s->shift_name; ?></strong></td>
                                            <td><span class="font-mono"><?= substr($s->start_time, 0, 5); ?> - <?= substr($s->end_time, 0, 5); ?> WIB</span></td>
                                            <td>
                                                <?php if ($s->shift_code === 'OFF' || stripos($s->shift_name, 'office') !== false): ?>
                                                    <span class="badge badge-info"><i class="fas fa-building mr-1"></i> Non-Shift (Office)</span>
                                                <?php elseif ($s->is_cross_day): ?>
                                                    <span class="badge badge-danger"><i class="fas fa-moon mr-1"></i> Pabrik: Lintas Hari (Malam)</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success"><i class="fas fa-industry mr-1"></i> Pabrik: Reguler Shift</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: ARSIP SPL PABRIK DENGAN KALKULASI PP 35/2021 -->
        <div class="tab-pane fade" id="spl" role="tabpanel">
            <div class="plant-card overflow-hidden">
                <div class="p-3 bg-white border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-file-invoice-dollar text-primary mr-1"></i> Arsip Surat Perintah Lembur &amp; Estimasi Upah (PP 35/2021)</h6>
                        <small class="text-muted">Monitoring komprehensif pengajuan, durasi lembur riil, konversi jam regulasi, dan estimasi beban upah pabrik.</small>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <div class="btn-group shadow-sm">
                            <a href="<?= site_url('dashboard/export_overtime?format=excel&filter_status=' . $filter_status . '&filter_dept=' . $filter_dept); ?>" class="btn btn-success btn-sm font-weight-bold px-3" title="Unduh Spreadsheet Format Microsoft Excel">
                                <i class="fas fa-file-excel mr-1"></i> Unduh Excel (.xls)
                            </a>
                            <a href="<?= site_url('dashboard/export_overtime?filter_status=' . $filter_status . '&filter_dept=' . $filter_dept); ?>" class="btn btn-outline-success btn-sm font-weight-bold px-2 bg-white" title="Unduh Format CSV Baku">
                                .CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 small">
                        <thead class="thead-light">
                            <tr>
                                <th>No. SPL</th>
                                <th>Karyawan</th>
                                <th>Divisi</th>
                                <th>Tanggal</th>
                                <th>Jam Lembur</th>
                                <th>Durasi Riil</th>
                                <th>Jam Setara PP35</th>
                                <th>Estimasi Upah</th>
                                <th>Target Tugas</th>
                                <th>Status</th>
                                <th class="text-center">Aksi Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($spl_archive)): ?>
                                <?php 
                                $total_ot_hours = 0;
                                $total_ot_cost = 0;
                                foreach ($spl_archive as $spl): 
                                    $calc = $this->Overtime_model->calculate_pp35_overtime($spl->total_hours, $spl->base_salary);
                                    if ($spl->status === 'APPROVED') {
                                        $total_ot_hours += $spl->total_hours;
                                        $total_ot_cost  += $calc['estimated_pay'];
                                    }
                                ?>
                                    <tr>
                                        <td><strong><?= $spl->spl_number; ?></strong></td>
                                        <td>
                                            <div class="font-weight-bold"><?= $spl->full_name; ?></div>
                                            <small class="text-muted font-mono"><?= $spl->nip; ?></small>
                                        </td>
                                        <td><span class="badge badge-light border"><?= $spl->dept_name; ?></span></td>
                                        <td><?= date('d/m/Y', strtotime($spl->overtime_date)); ?></td>
                                        <td><?= substr($spl->start_time, 0, 5); ?> - <?= substr($spl->end_time, 0, 5); ?></td>
                                        <td><span class="badge badge-info"><?= $spl->total_hours; ?> Jam</span></td>
                                        <td><span class="badge badge-secondary"><?= $calc['equivalent_hours']; ?> Jam</span></td>
                                        <td class="font-mono text-dark font-weight-bold">
                                            <?= $spl->status === 'APPROVED' ? 'Rp ' . number_format($calc['estimated_pay'], 0, ',', '.') : '-'; ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($spl->reason); ?>
                                            <?php if ($spl->status == 'REJECTED' && !empty($spl->rejection_note)): ?>
                                                <div class="text-danger font-italic small mt-1">"<?= htmlspecialchars($spl->rejection_note); ?>"</div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($spl->status == 'APPROVED'): ?>
                                                <span class="badge badge-success badge-status">APPROVED</span>
                                            <?php elseif ($spl->status == 'REJECTED'): ?>
                                                <span class="badge badge-danger badge-status">REJECTED</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning badge-status">PENDING</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="<?= site_url('dashboard/print_spl/' . $spl->id); ?>" target="_blank" class="btn btn-outline-dark btn-sm px-2 py-1 font-weight-bold" title="Cetak Dokumen Resmi SPL">
                                                <i class="fas fa-print"></i> Slip
                                            </a>
                                            <a href="<?= site_url('dashboard/delete_spl/' . $spl->id); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket lembur No. <?= $spl->spl_number; ?> (<?= htmlspecialchars($spl->full_name); ?>) secara permanen?');" class="btn btn-outline-danger btn-sm px-2 py-1 font-weight-bold ml-1" title="Hapus Arsip SPL Ini">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">Belum ada arsip tiket lembur.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Summary Footer Biaya Lembur -->
                <?php if (!empty($spl_archive)): ?>
                    <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                        <small class="text-muted font-weight-bold">
                            <i class="fas fa-calculator mr-1 text-primary"></i> 
                            Total Lembur Disetujui (Approved): <span class="text-primary font-weight-bold"><?= $total_ot_hours; ?> Jam</span>
                        </small>
                        <div class="font-weight-bold text-dark">
                            Total Beban Upah Lembur (Estimasi): 
                            <span class="text-success font-mono h6 mb-0 ml-1">Rp <?= number_format($total_ot_cost, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- TAB 5: PEMELIHARAAN DATABASE & OTOMASI LINUX/WSL -->
        <div class="tab-pane fade" id="backup" role="tabpanel">
            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="plant-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-database text-primary mr-2"></i> Pencadangan Basis Data Mandiri (Web Backup Tool)
                            </h5>
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> MySQL Engine Active</span>
                        </div>
                        <p class="text-muted small">
                            Lakukan pencadangan berkala data operasional, absensi harian, dan tiket lembur pabrik untuk mencegah kehilangan data akibat kegagalan perangkat keras atau kerusakan sistem.
                        </p>

                        <div class="alert alert-light border p-3 mb-4">
                            <div class="row text-center">
                                <div class="col-4 border-right">
                                    <small class="text-muted font-weight-bold d-block">DBMS ENGINE</small>
                                    <span class="font-weight-bold text-dark">MySQL / InnoDB</span>
                                </div>
                                <div class="col-4 border-right">
                                    <small class="text-muted font-weight-bold d-block">NAMA DATABASE</small>
                                    <span class="font-weight-bold text-dark">planthr_db</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted font-weight-bold d-block">FORMAT CADANGAN</small>
                                    <span class="font-weight-bold text-primary">.sql.gz (GZIP)</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            <a href="<?= site_url('maintenance/backup_db'); ?>" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm">
                                <i class="fas fa-download mr-1"></i> Unduh Cadangan Database (.sql.gz)
                            </a>
                            <a href="<?= base_url('database/planthr_db_dump.sql'); ?>" class="btn btn-outline-secondary font-weight-bold px-3 py-2" download>
                                <i class="fas fa-file-code mr-1"></i> Unduh File SQL Mentah (.sql)
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 mb-4">
                    <div class="plant-card p-4">
                        <h6 class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-terminal text-info mr-1"></i> Otomasi Linux / WSL (System Utility)
                        </h6>
                        <p class="text-muted small mb-3">
                            Skrip bash otomatis dengan kompresi gzip dan kebijakan rotasi file 7 hari tersedia di folder <code>scripts/auto_backup.sh</code>.
                        </p>
                        <div class="code-block mb-3">
                            # Eksekusi manual di terminal Linux/WSL:<br>
                            cd /mnt/c/xampp/htdocs/hr_plan/scripts<br>
                            chmod +x auto_backup.sh<br>
                            ./auto_backup.sh
                        </div>
                        <small class="text-muted d-block">
                            <i class="fas fa-shield-alt text-success mr-1"></i> File cadangan otomatis disimpan ke direktori aman dengan retensi 7 hari.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KARYAWAN -->
<div class="modal fade" id="modalAddEmployee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" action="<?= site_url('dashboard/add_employee'); ?>" class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i> Tambah Personil Karyawan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">NOMOR INDUK KARYAWAN (NIP) *</label>
                        <input type="text" name="nip" class="form-control font-mono" placeholder="Contoh: EMP007 / OPR015" required maxlength="20">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">NAMA LENGKAP *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Nama lengkap personil" required maxlength="120">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-dark"><i class="fas fa-briefcase text-primary mr-1"></i> JABATAN / POSISI KERJA (JOBDESK) *</label>
                        <select name="position_select" id="add_position_select" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Posisi / Jabatan Pabrik --</option>
                            <option value="Operator Mesin Produksi">Operator Mesin Produksi</option>
                            <option value="Teknisi Maintenance & Kelistrikan">Teknisi Maintenance & Kelistrikan</option>
                            <option value="Staff Quality Control (QC / QA)">Staff Quality Control (QC / QA)</option>
                            <option value="Staff Warehouse & Logistik">Staff Warehouse & Logistik</option>
                            <option value="Operator Forklift & Material Handler">Operator Forklift & Material Handler</option>
                            <option value="Staff PPIC & Production Planning">Staff PPIC & Production Planning</option>
                            <option value="Foreman / Line Leader Produksi">Foreman / Line Leader Produksi</option>
                            <option value="Supervisor Maintenance">Supervisor Maintenance</option>
                            <option value="Staff Human Resources & GA">Staff Human Resources & GA</option>
                            <option value="Staff Procurement & Purchasing">Staff Procurement & Purchasing</option>
                            <option value="Staff Finance & Accounting">Staff Finance & Accounting</option>
                            <option value="Petugas Keamanan / Security">Petugas Keamanan / Security</option>
                            <option value="Driver Logistik Operasional">Driver Logistik Operasional</option>
                            <option value="Helper & Cleaning Service">Helper & Cleaning Service</option>
                            <option value="OTHER" class="text-primary font-weight-bold bg-light">➕ Lainnya / Other (Tulis Manual Sendiri...)</option>
                        </select>
                        
                        <!-- Kolom input manual yang otomatis muncul jika memilih "OTHER" -->
                        <div id="add_custom_position_wrapper" class="mt-2" style="display: none;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white font-weight-bold"><i class="fas fa-pen mr-1"></i> Ketik Manual:</span>
                                </div>
                                <input type="text" name="custom_position" id="add_custom_position" class="form-control" placeholder="Contoh: Operator CNC / Drafter / Staf Pajak" maxlength="100">
                            </div>
                            <small class="text-success font-weight-bold mt-1 d-block"><i class="fas fa-check-circle mr-1"></i>Bebas tuliskan nama posisi / jobdesk apa pun sesuai kebutuhan pabrik Anda.</small>
                        </div>
                        <input type="hidden" name="position" id="add_final_position">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">JENIS KELAMIN *</label>
                        <select name="gender" class="form-control" required>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">DEPARTEMEN / DIVISI *</label>
                        <select name="department_id" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Departemen / Divisi Pabrik --</option>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?= $d->id; ?>"><?= $d->dept_name; ?> (<?= $d->dept_code; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">POLA SHIFT KERJA *</label>
                        <select name="shift_id" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Pola Shift Kerja --</option>
                            <?php foreach ($shifts as $s): ?>
                                <option value="<?= $s->id; ?>">
                                    <?= $s->shift_name; ?> (<?= substr($s->start_time, 0, 5); ?> - <?= substr($s->end_time, 0, 5); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="font-weight-bold small text-dark"><i class="fas fa-user-shield text-warning mr-1"></i> TINGKAT WEWENANG AKUN (ROLE) *</label>
                        <select name="role" class="form-control" required>
                            <option value="OPERATOR">1. STAF / KARYAWAN (Pelaksana: Finance, SCM, Produksi, dll)</option>
                            <option value="SUPERVISOR">2. SUPERVISOR / ATASAN (Penyetuju Lembur Departemen)</option>
                            <option value="HR_ADMIN">3. ADMINISTRATOR UTAMA (HR &amp; Pengelola Sistem Pabrik)</option>
                        </select>
                        <small class="text-info font-weight-bold d-block mt-1"><i class="fas fa-info-circle mr-1"></i>Tingkat hak akses: Staf biasa, Atasan penyetuju, atau Admin.</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold small text-muted">GAJI POKOK ACUAN (RP) *</label>
                        <input type="number" name="base_salary" class="form-control" value="4500000" min="1000000" step="50000" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold small text-muted">TANGGAL MASUK BEKERJA *</label>
                        <input type="date" name="join_date" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">EMAIL LOGIN *</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@plant.local" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">KATA SANDI DEFAULT *</label>
                        <input type="password" name="password" class="form-control" value="admin123" required>
                        <small class="text-muted">Default disarankan: admin123 (karyawan dapat mengubahnya kemudian).</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary font-weight-bold px-4">
                    <i class="fas fa-save mr-1"></i> Daftarkan Personil
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT KARYAWAN -->
<div class="modal fade" id="modalEditEmployee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" action="<?= site_url('dashboard/edit_employee'); ?>" class="modal-content border-0 shadow">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Perbarui Data Penempatan Karyawan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">NIP</label>
                        <input type="text" id="edit_nip" class="form-control bg-light font-mono font-weight-bold" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">NAMA LENGKAP *</label>
                        <input type="text" name="full_name" id="edit_name" class="form-control" required maxlength="120">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-dark"><i class="fas fa-briefcase text-primary mr-1"></i> JABATAN / POSISI KERJA (JOBDESK) *</label>
                        <select name="position_select" id="edit_position_select" class="form-control" required>
                            <option value="" disabled>-- Pilih Posisi / Jabatan Pabrik --</option>
                            <option value="Operator Mesin Produksi">Operator Mesin Produksi</option>
                            <option value="Teknisi Maintenance & Kelistrikan">Teknisi Maintenance & Kelistrikan</option>
                            <option value="Staff Quality Control (QC / QA)">Staff Quality Control (QC / QA)</option>
                            <option value="Staff Warehouse & Logistik">Staff Warehouse & Logistik</option>
                            <option value="Operator Forklift & Material Handler">Operator Forklift & Material Handler</option>
                            <option value="Staff PPIC & Production Planning">Staff PPIC & Production Planning</option>
                            <option value="Foreman / Line Leader Produksi">Foreman / Line Leader Produksi</option>
                            <option value="Supervisor Maintenance">Supervisor Maintenance</option>
                            <option value="Staff Human Resources & GA">Staff Human Resources & GA</option>
                            <option value="Staff Procurement & Purchasing">Staff Procurement & Purchasing</option>
                            <option value="Staff Finance & Accounting">Staff Finance & Accounting</option>
                            <option value="Petugas Keamanan / Security">Petugas Keamanan / Security</option>
                            <option value="Driver Logistik Operasional">Driver Logistik Operasional</option>
                            <option value="Helper & Cleaning Service">Helper & Cleaning Service</option>
                            <option value="OTHER" class="text-primary font-weight-bold bg-light">➕ Lainnya / Other (Tulis Manual Sendiri...)</option>
                        </select>
                        
                        <!-- Kolom input manual untuk Edit jika memilih "OTHER" -->
                        <div id="edit_custom_position_wrapper" class="mt-2" style="display: none;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary text-white font-weight-bold"><i class="fas fa-pen mr-1"></i> Ketik Manual:</span>
                                </div>
                                <input type="text" name="custom_position" id="edit_custom_position" class="form-control" placeholder="Contoh: Operator CNC / Drafter / Staf Pajak" maxlength="100">
                            </div>
                            <small class="text-success font-weight-bold mt-1 d-block"><i class="fas fa-check-circle mr-1"></i>Bebas tuliskan nama posisi / jobdesk apa pun sesuai kebutuhan pabrik Anda.</small>
                        </div>
                        <input type="hidden" name="position" id="edit_final_position">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold small text-muted">DEPARTEMEN / DIVISI *</label>
                        <select name="department_id" id="edit_dept" class="form-control" required>
                            <?php foreach ($departments as $d): ?>
                                <option value="<?= $d->id; ?>"><?= $d->dept_name; ?> (<?= $d->dept_code; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold small text-muted">ROTASI POLA SHIFT KERJA *</label>
                        <select name="shift_id" id="edit_shift" class="form-control" required>
                            <?php foreach ($shifts as $s): ?>
                                <option value="<?= $s->id; ?>">
                                    <?= $s->shift_name; ?> (<?= substr($s->start_time, 0, 5); ?> - <?= substr($s->end_time, 0, 5); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold small text-dark"><i class="fas fa-user-shield text-warning mr-1"></i> TINGKAT WEWENANG AKUN (ROLE) *</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="OPERATOR">STAF / KARYAWAN (Pelaksana)</option>
                            <option value="SUPERVISOR">SUPERVISOR / ATASAN (Penyetuju)</option>
                            <option value="HR_ADMIN">ADMINISTRATOR UTAMA</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold small text-muted">GAJI POKOK ACUAN (RP) *</label>
                        <input type="number" name="base_salary" id="edit_salary" class="form-control" min="1000000" step="50000" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="font-weight-bold small text-muted">GANTI KATA SANDI BARU (OPSIONAL)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah sandi">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary font-weight-bold px-4">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL RESET PASSWORD KARYAWAN -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="<?= site_url('dashboard/reset_password'); ?>" class="modal-content border-0 shadow">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="employee_id" id="reset_pass_id">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-key mr-2"></i> Ganti / Reset Kata Sandi Personil</h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-light border p-3 mb-3">
                    <small class="text-muted d-block font-weight-bold">PERSONIL YANG AKAN DI-UPDATE:</small>
                    <div class="h6 font-weight-bold text-dark mb-0 mt-1" id="reset_pass_name">Nama Karyawan</div>
                    <small class="text-muted font-mono" id="reset_pass_nip">NIP: EMP001</small>
                </div>
                <div class="form-group mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="font-weight-bold small text-muted mb-0">KATA SANDI BARU *</label>
                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 font-weight-bold" id="btn_fill_default_pwd" style="font-size: 11px;">
                            <i class="fas fa-magic mr-1"></i> Isi Standar (admin123)
                        </button>
                    </div>
                    <div class="input-group">
                        <input type="text" name="new_password" id="input_reset_new_password" class="form-control font-mono" placeholder="Ketik kata sandi baru (minimal 6 karakter)..." required minlength="6">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="btn_toggle_reset_pwd" title="Sembunyikan/Tampilkan Sandi">
                                <i class="fas fa-eye" id="icon_toggle_reset_pwd"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-shield-alt text-success mr-1"></i> Kata sandi akan langsung dienkripsi aman dengan standar <strong>Bcrypt</strong> begitu disimpan.
                    </small>
                </div>
                <div class="small text-muted bg-light p-2 rounded">
                    <i class="fas fa-info-circle text-primary mr-1"></i>
                    Demi keamanan enkripsi satu arah (Bcrypt Hash), kata sandi lama tidak ditampilkan. Setelah disimpan, beritahukan kata sandi baru ini kepada karyawan bersangkutan agar dapat segera login.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning font-weight-bold text-dark px-4 shadow-sm">
                    <i class="fas fa-check mr-1"></i> Simpan Kata Sandi Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL HAPUS DATA KARYAWAN (CEGAH DUPLIKAT) -->
<div class="modal fade" id="modalDeleteEmployee" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" action="<?= site_url('dashboard/delete_employee'); ?>" class="modal-content border-0 shadow">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="employee_id" id="delete_emp_id">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-trash-alt mr-2"></i> Konfirmasi Hapus Data Karyawan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning py-2 px-3 small border-0 mb-3 d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning mr-3 flex-shrink-0"></i>
                    <div>
                        <strong>Peringatan Penghapusan Permanen:</strong> Tindakan ini akan menghapus data karyawan dari database (termasuk riwayat absensi &amp; permohonan lembur terkait) guna mencegah terjadinya duplikasi data.
                    </div>
                </div>
                <div class="card bg-light border p-3 mb-3">
                    <small class="text-muted font-weight-bold d-block mb-1">DATA PERSONIL YANG AKAN DIHAPUS:</small>
                    <div class="h5 font-weight-bold text-danger mb-0" id="delete_emp_name">Nama Karyawan</div>
                    <small class="text-muted font-mono" id="delete_emp_nip">NIP: EMP000</small>
                </div>
                <p class="small text-muted mb-0">
                    Apakah Anda yakin 100% ingin menghapus data personil ini secara permanen dari sistem pabrik?
                </p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger font-weight-bold px-4 shadow-sm">
                    <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus Permanen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL TAMBAH DEPARTEMEN BARU -->
<div class="modal fade" id="modalAddDepartment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-sitemap mr-2"></i> Tambah Departemen Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="<?= site_url('dashboard/add_department'); ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">KODE DEPARTEMEN (UNIK) *</label>
                        <input type="text" name="dept_code" class="form-control font-mono text-uppercase" placeholder="Contoh: PRC, SCM, FIN, HSE" required maxlength="10">
                        <small class="text-muted">Singkatan unik 2-10 karakter (misal: PRC untuk Procurement).</small>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small text-muted">NAMA LENGKAP DEPARTEMEN *</label>
                        <input type="text" name="dept_name" class="form-control" placeholder="Contoh: Procurement & Purchasing" required maxlength="100">
                        <small class="text-muted">Dapat berupa divisi office maupun non-office.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Departemen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH POLA SHIFT / JAM KERJA BARU -->
<div class="modal fade" id="modalAddShift" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-business-time mr-2"></i> Tambah Pola Jam Kerja / Shift</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="<?= site_url('dashboard/add_shift'); ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">KODE POLA / SHIFT (UNIK) *</label>
                        <input type="text" name="shift_code" class="form-control font-mono text-uppercase" placeholder="Contoh: OFF, SH4, MID, SEC" required maxlength="10">
                        <small class="text-muted">Singkatan unik kode shift (misal: OFF untuk Office, MID untuk Middle shift).</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted">NAMA POLA SHIFT / JAM KERJA *</label>
                        <input type="text" name="shift_name" class="form-control" placeholder="Contoh: Non-Shift (Reguler Office) / Shift Middle" required maxlength="50">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="font-weight-bold small text-muted">JAM MASUK *</label>
                            <input type="time" name="start_time" class="form-control font-mono" value="08:00" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="font-weight-bold small text-muted">JAM PULANG *</label>
                            <input type="time" name="end_time" class="form-control font-mono" value="17:00" required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold small text-muted">TIPE SHIFT KERJA *</label>
                        <select name="is_cross_day" class="form-control" required>
                            <option value="0">Reguler Hari Sama (Contoh: 08:00 - 17:00 / 07:00 - 15:00)</option>
                            <option value="1">Lintas Hari / Malam Menembus Pagi (Contoh: 23:00 - 07:00)</option>
                        </select>
                        <small class="text-info font-weight-bold mt-1 d-block"><i class="fas fa-info-circle mr-1"></i>Pilih 'Lintas Hari' hanya jika jam shift malam selesai di hari berikutnya.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Pola Jam Kerja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Local Scripts -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/chart.min.js'); ?>"></script>

<script>
$(document).ready(function() {
    // Toggle input manual "OTHER" pada Modal Tambah Karyawan
    $('#add_position_select').on('change', function() {
        if ($(this).val() === 'OTHER') {
            $('#add_custom_position_wrapper').slideDown(150);
            $('#add_custom_position').prop('required', true).focus();
            $('#add_final_position').val($('#add_custom_position').val());
        } else {
            $('#add_custom_position_wrapper').slideUp(150);
            $('#add_custom_position').prop('required', false).val('');
            $('#add_final_position').val($(this).val());
        }
    });
    $('#add_custom_position').on('input', function() {
        $('#add_final_position').val($(this).val());
    });

    // Toggle input manual "OTHER" pada Modal Edit Karyawan
    $('#edit_position_select').on('change', function() {
        if ($(this).val() === 'OTHER') {
            $('#edit_custom_position_wrapper').slideDown(150);
            $('#edit_custom_position').prop('required', true).focus();
            $('#edit_final_position').val($('#edit_custom_position').val());
        } else {
            $('#edit_custom_position_wrapper').slideUp(150);
            $('#edit_custom_position').prop('required', false).val('');
            $('#edit_final_position').val($(this).val());
        }
    });
    $('#edit_custom_position').on('input', function() {
        $('#edit_final_position').val($(this).val());
    });

    // Event delegation untuk populate modal edit karyawan
    $('.btn-edit-emp').on('click', function() {
        var id = $(this).data('id');
        var nip = $(this).data('nip');
        var name = $(this).data('name');
        var position = $(this).data('position');
        var dept = $(this).data('dept');
        var shift = $(this).data('shift');
        var role = $(this).data('role');
        var salary = $(this).data('salary');

        $('#edit_id').val(id);
        $('#edit_nip').val(nip);
        $('#edit_name').val(name);
        $('#edit_dept').val(dept);
        $('#edit_shift').val(shift);
        $('#edit_role').val(role);
        $('#edit_salary').val(salary);

        // Periksa apakah posisi sudah ada di daftar preset dropdown
        var exists = false;
        $('#edit_position_select option').each(function() {
            if ($(this).val() === position && $(this).val() !== 'OTHER' && $(this).val() !== '') {
                exists = true;
            }
        });

        if (exists) {
            $('#edit_position_select').val(position);
            $('#edit_custom_position_wrapper').hide();
            $('#edit_custom_position').prop('required', false).val('');
            $('#edit_final_position').val(position);
        } else if (position && position !== '-') {
            $('#edit_position_select').val('OTHER');
            $('#edit_custom_position_wrapper').show();
            $('#edit_custom_position').prop('required', true).val(position);
            $('#edit_final_position').val(position);
        } else {
            $('#edit_position_select').val('');
            $('#edit_custom_position_wrapper').hide();
            $('#edit_custom_position').prop('required', false).val('');
            $('#edit_final_position').val('');
        }

        $('#modalEditEmployee').modal('show');
    });

    // Event delegation untuk modal reset password
    $('.btn-reset-pass').on('click', function() {
        var id = $(this).data('id');
        var nip = $(this).data('nip');
        var name = $(this).data('name');

        $('#reset_pass_id').val(id);
        $('#reset_pass_nip').text('NIP: ' + nip);
        $('#reset_pass_name').text(name);
        $('#input_reset_new_password').val('').attr('placeholder', 'Ketik kata sandi baru untuk ' + name + '...');

        $('#modalResetPassword').modal('show');
    });

    $('#btn_fill_default_pwd').on('click', function() {
        $('#input_reset_new_password').val('admin123').focus();
    });

    $('#btn_toggle_reset_pwd').on('click', function() {
        var input = $('#input_reset_new_password');
        var icon = $('#icon_toggle_reset_pwd');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    // Event delegation untuk modal hapus karyawan permanen
    $('.btn-delete-emp').on('click', function() {
        var id = $(this).data('id');
        var nip = $(this).data('nip');
        var name = $(this).data('name');

        $('#delete_emp_id').val(id);
        $('#delete_emp_nip').text('NIP: ' + nip);
        $('#delete_emp_name').text(name);

        $('#modalDeleteEmployee').modal('show');
    });

    var chartTrend = null;
    var chartOt = null;
    var chartShift = null;
    var chartOtCost = null;

    // Inisialisasi Chart 1: Tren Kehadiran 7 Hari Terakhir
    var trendCtx = document.getElementById('attendanceTrendChart');
    if (trendCtx) {
        chartTrend = new Chart(trendCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($trend_7days['labels']); ?>,
                datasets: [
                    {
                        label: 'Tepat Waktu',
                        data: <?= json_encode($trend_7days['on_time']); ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 4
                    },
                    {
                        label: 'Terlambat',
                        data: <?= json_encode($trend_7days['late']); ?>,
                        backgroundColor: '#f59e0b',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: false,
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#e2e8f0' }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { family: 'Inter', size: 12 } }
                    }
                }
            }
        });
    }

    // Inisialisasi Chart 2: Distribusi Lembur per Departemen
    var otCtx = document.getElementById('otDistributionChart');
    if (otCtx) {
        chartOt = new Chart(otCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($ot_dist['labels']); ?>,
                datasets: [{
                    data: <?= json_encode($ot_dist['data']); ?>,
                    backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Inter', size: 11 }, boxWidth: 12 }
                    }
                }
            }
        });
    }

    // Inisialisasi Chart 3: Komposisi Personil per Shift Kerja
    var shiftCtx = document.getElementById('shiftDistributionChart');
    if (shiftCtx) {
        chartShift = new Chart(shiftCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($shift_dist['labels']); ?>,
                datasets: [{
                    data: <?= json_encode($shift_dist['data']); ?>,
                    backgroundColor: ['#2563eb', '#f59e0b', '#6366f1', '#10b981', '#ec4899', '#06b6d4'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Inter', size: 11 }, boxWidth: 12 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed + ' Karyawan';
                            }
                        }
                    }
                }
            }
        });
    }

    // Inisialisasi Chart 4: Estimasi Biaya Upah Lembur (Rp) per Departemen
    var otCostCtx = document.getElementById('otCostDistributionChart');
    if (otCostCtx) {
        chartOtCost = new Chart(otCostCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($ot_cost_dist['labels']); ?>,
                datasets: [{
                    label: 'Biaya Upah Lembur (Rp)',
                    data: <?= json_encode($ot_cost_dist['data']); ?>,
                    backgroundColor: '#059669',
                    hoverBackgroundColor: '#047857',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 10 },
                            maxRotation: 35,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e2e8f0' },
                        ticks: {
                            font: { family: 'JetBrains Mono', size: 10 },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Upah Lembur: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Live-Sync Real-Time Polling (100% Data Riil Database MySQL via Ajax)
    function syncLiveAnalytics() {
        $.ajax({
            url: '<?= site_url("dashboard/api_live_analytics"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res && res.status === 'success') {
                    // 1. Update Metrik Ringkasan Atas
                    if (res.summary) {
                        $('#metric_total_employees').text(res.summary.total_employees);
                        $('#metric_total_dept').text(res.summary.total_dept);
                        $('#metric_today_present').text(res.summary.today_present);
                        $('#metric_pending_spl').text(res.summary.pending_spl);
                        $('#metric_on_time_rate').text(res.summary.on_time_rate + '%');
                        
                        var lateText = res.summary.today_late > 0 
                            ? res.summary.today_late + ' Terlambat Masuk' 
                            : '100% Tepat Waktu';
                        $('#metric_today_late_text').text(lateText)
                            .removeClass('text-danger text-success')
                            .addClass(res.summary.today_late > 0 ? 'text-danger' : 'text-success');
                    }

                    // 2. Update Chart 1: Tren Kehadiran
                    if (chartTrend && res.trend_7days) {
                        chartTrend.data.labels = res.trend_7days.labels;
                        chartTrend.data.datasets[0].data = res.trend_7days.on_time;
                        chartTrend.data.datasets[1].data = res.trend_7days.late;
                        chartTrend.update();
                    }

                    // 3. Update Chart 2: Distribusi Jam Lembur
                    if (chartOt && res.ot_dist) {
                        chartOt.data.labels = res.ot_dist.labels;
                        chartOt.data.datasets[0].data = res.ot_dist.data;
                        chartOt.update();
                    }

                    // 4. Update Chart 3: Komposisi Shift
                    if (chartShift && res.shift_dist) {
                        chartShift.data.labels = res.shift_dist.labels;
                        chartShift.data.datasets[0].data = res.shift_dist.data;
                        chartShift.update();
                    }

                    // 5. Update Chart 4: Biaya Upah Lembur PP 35/2021
                    if (chartOtCost && res.ot_cost_dist) {
                        chartOtCost.data.labels = res.ot_cost_dist.labels;
                        chartOtCost.data.datasets[0].data = res.ot_cost_dist.data;
                        chartOtCost.update();
                    }

                    // Update timestamp badge
                    if (res.timestamp) {
                        $('#liveSyncTime').text('Sync: ' + res.timestamp);
                    }
                }
            },
            error: function() {
                $('#liveSyncTime').text('Offline');
            }
        });
    }

    // Interval sinkronisasi otomatis tiap 25 detik (tanpa refresh halaman)
    setInterval(syncLiveAnalytics, 25000);

    // Hash navigation retention (auto switch to tab if anchor in url)
    var hash = window.location.hash;
    if (hash) {
        $('#hrTabs a[href="' + hash + '"]').tab('show');
    }
    $('#hrTabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    // Solusi Masalah #5: Cegah Double-Submit Form di tingkat antarmuka klien (Anti-Spam Klik)
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
