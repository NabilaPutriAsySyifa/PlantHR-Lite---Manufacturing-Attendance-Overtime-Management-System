<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Masuk ke Portal | PlantHR Lite</title>
    <!-- Local Assets for 100% Offline Stability -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --surface: #ffffff;
            --bg: #0f172a;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: radial-gradient(circle at 50% 20%, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            color: #1e293b;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .brand-icon-box {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }
        .form-control {
            height: 46px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-login {
            height: 46px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 8px;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.2px;
            transition: all 0.2s ease;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -2px rgba(37, 99, 235, 0.35);
        }
    </style>
</head>
<body class="d-flex align-items-center py-4">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card p-4 p-sm-5 login-card">
                <!-- Brand Header -->
                <div class="text-center mb-4">
                    <div class="brand-icon-box mb-3 text-white">
                        <i class="fas fa-industry fa-2x"></i>
                    </div>
                    <h4 class="font-weight-bold text-slate-900 mb-1" style="color: #0f172a;">PlantHR <span class="text-primary">Lite</span></h4>
                    <p class="text-muted small mb-0">Manufacturing Attendance &amp; Overtime Management</p>
                </div>

                <!-- Alert Messages -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger py-2 px-3 small border-0 shadow-sm d-flex align-items-center mb-3">
                        <i class="fas fa-exclamation-circle mr-2 flex-shrink-0"></i>
                        <div><?= $this->session->flashdata('error'); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success py-2 px-3 small border-0 shadow-sm d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle mr-2 flex-shrink-0"></i>
                        <div><?= $this->session->flashdata('success'); ?></div>
                    </div>
                <?php endif; ?>

                <!-- Form Login -->
                <form method="POST" action="<?= site_url('auth/login'); ?>" id="loginForm">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-secondary mb-1">
                            <i class="far fa-envelope mr-1 text-primary"></i> Email Karyawan
                        </label>
                        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="nama@plant.local" value="<?= set_value('email'); ?>" required autofocus>
                    </div>
                    <div class="form-group mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="small font-weight-bold text-secondary mb-0">
                                <i class="fas fa-lock mr-1 text-primary"></i> Kata Sandi
                            </label>
                            <a href="#" class="small text-primary font-weight-bold" data-toggle="modal" data-target="#modalForgotPassword" style="font-size: 12px;">
                                Lupa Kata Sandi?
                            </a>
                        </div>
                        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-login btn-block shadow-sm">
                        Masuk ke Portal <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4 pt-2 text-muted border-top" style="font-size: 11px;">
                    &copy; <?= date('Y'); ?> PlantHR Lite &bull; 3-Shift Manufacturing HRMS
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Panduan Lupa Kata Sandi (SOP Layanan Karyawan) -->
<div class="modal fade" id="modalForgotPassword" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-shield-alt mr-2"></i> Bantuan Akun &amp; Pemulihan Kata Sandi</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <span class="rounded-circle bg-light p-3 d-inline-block text-primary shadow-sm mb-2">
                        <i class="fas fa-user-shield fa-2x"></i>
                    </span>
                    <h5 class="font-weight-bold text-dark mb-1">Tata Cara Bantuan &amp; Pemulihan Akun Karyawan</h5>
                    <p class="text-muted small mb-0">
                        Demi menjaga kerahasiaan catatan kehadiran serta keamanan data perhitungan upah lembur resmi, ikuti prosedur resmi berikut:
                    </p>
                </div>

                <div class="row">
                    <!-- Langkah 1 & 2 -->
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-primary px-2 py-1 mr-2"><i class="fas fa-building mr-1"></i> Langkah 1</span>
                                <strong class="small text-dark">Lapor ke Bagian Personalia (HRD)</strong>
                            </div>
                            <p class="small text-muted mb-0">
                                Karyawan yang lupa kata sandi atau mengalami kendala login dapat melapor langsung ke ruang kantor <strong>HRD / Personalia</strong> atau melalui <strong>Supervisor</strong> yang bertugas pada jam kerja shift Anda.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-primary px-2 py-1 mr-2"><i class="fas fa-id-card mr-1"></i> Langkah 2</span>
                                <strong class="small text-dark">Verifikasi Identitas Resmi</strong>
                            </div>
                            <p class="small text-muted mb-0">
                                Sebutkan <strong>Nomor Induk Karyawan (NIP)</strong> dan tunjukkan <strong>ID Badge Pabrik</strong> Anda kepada petugas HRD untuk memastikan keabsahan kepemilikan akun personil.
                            </p>
                        </div>
                    </div>

                    <!-- Langkah 3 & 4 -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-success px-2 py-1 mr-2"><i class="fas fa-key mr-1"></i> Langkah 3</span>
                                <strong class="small text-dark">Penerbitan Kredensial Baru</strong>
                            </div>
                            <p class="small text-muted mb-0">
                                Petugas HRD yang berwenang akan memverifikasi data Anda dan menerbitkan kata sandi baru yang aman secara langsung kepada Anda.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-warning text-dark px-2 py-1 mr-2"><i class="fas fa-lock mr-1"></i> Langkah 4</span>
                                <strong class="small text-dark">Login &amp; Ganti Sandi Mandiri</strong>
                            </div>
                            <p class="small text-muted mb-0">
                                Setelah berhasil login ke portal, Anda disarankan untuk segera memperbarui kata sandi baru demi menjaga privasi dan keamanan akun Anda sendiri.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-secondary border-0 mt-3 mb-0 small">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-primary mt-1 mr-2 flex-shrink-0"></i>
                        <div>
                            <strong>Kebijakan Privasi &amp; Keamanan Akun:</strong> Sistem PlantHR Lite terhubung langsung dengan data absensi dan kalkulasi upah lembur resmi. Sistem <strong>tidak menggunakan reset otomatis via email publik</strong> guna mencegah risiko manipulasi kehadiran atau pencurian identitas kerja. Petugas HRD maupun Tim IT <strong>tidak akan pernah</strong> meminta kata sandi pribadi Anda melalui telepon atau pesan singkat.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-primary btn-sm font-weight-bold px-4" data-dismiss="modal">
                    Saya Paham &amp; Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Local Scripts for Modal & Interactions -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
