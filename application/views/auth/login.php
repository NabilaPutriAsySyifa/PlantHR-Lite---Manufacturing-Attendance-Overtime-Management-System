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

<!-- Modal Panduan Lupa Kata Sandi (SOP Pabrik) -->
<div class="modal fade" id="modalForgotPassword" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-key mr-2"></i> Bantuan Lupa Kata Sandi Akun Pabrik</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <span class="rounded-circle bg-light p-3 d-inline-block text-primary">
                        <i class="fas fa-user-shield fa-2x"></i>
                    </span>
                </div>
                <h6 class="font-weight-bold text-dark mb-2">Prosedur Reset Kata Sandi (SOP Pabrik)</h6>
                <p class="text-muted small text-left mb-3">
                    Untuk menjaga keamanan data operasional pabrik dan integritas data log absensi karyawan, reset kata sandi dilakukan secara terpusat oleh <strong>Divisi HR / Administrator Sistem Pabrik</strong>:
                </p>
                <div class="alert alert-light border text-left small mb-3">
                    <ol class="pl-3 mb-0" style="line-height: 1.8;">
                        <li>Hubungi <strong>Divisi HRD</strong> atau <strong>Supervisor Lini</strong> Anda dengan menyebutkan <strong>NIP</strong> dan <strong>Nama Lengkap</strong>.</li>
                        <li>HR Administrator akan membuka panel <strong>Master Karyawan</strong> dan mereset kata sandi akun Anda (misal ke sandi standar <code>admin123</code> atau sandi baru yang disepakati).</li>
                        <li>Setelah direset, Anda dapat langsung login kembali ke portal ini.</li>
                    </ol>
                </div>
                <button type="button" class="btn btn-primary btn-block btn-sm font-weight-bold" data-dismiss="modal">
                    Saya Mengerti
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
