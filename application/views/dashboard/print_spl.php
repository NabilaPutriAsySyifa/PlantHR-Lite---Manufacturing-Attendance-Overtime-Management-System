<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <!-- Bootstrap 4.6.2 & Local CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #0f172a;
            padding: 30px 0;
        }
        .page-container {
            max-width: 840px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 50px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            position: relative;
        }
        .header-logo {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .header-logo span {
            color: #2563eb;
        }
        .doc-title {
            text-align: center;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 8px 0;
            margin: 20px 0 24px 0;
        }
        .doc-title h4 {
            margin: 0;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .doc-title small {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            color: #2563eb;
        }
        .table-spl th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            font-size: 13px;
            width: 25%;
            vertical-align: middle;
        }
        .table-spl td {
            font-size: 13.5px;
            vertical-align: middle;
        }
        .signature-box {
            text-align: center;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            background-color: #f8fafc;
        }
        .stamp-approved {
            border: 3px solid #16a34a;
            color: #16a34a;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 2px;
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
            transform: rotate(-5deg);
        }
        .stamp-rejected {
            border: 3px solid #dc2626;
            color: #dc2626;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 2px;
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
            transform: rotate(-5deg);
        }
        .stamp-pending {
            border: 3px solid #ea580c;
            color: #ea580c;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 2px;
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
            transform: rotate(-5deg);
        }
        .barcode-sim {
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 4px;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .page-container {
                box-shadow: none;
                border: none;
                padding: 20px 30px;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Action Bar (Hidden when printed) -->
<div class="container mb-4 no-print" style="max-width: 840px;">
    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm border">
        <div>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <span class="ml-2 font-weight-bold text-dark">Pratinjau Cetak Surat Perintah Lembur (SPL)</span>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary font-weight-bold px-4 shadow-sm">
                <i class="fas fa-print mr-2"></i> Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>
</div>

<!-- Printable Paper Container -->
<div class="page-container">
    <!-- Header Perusahaan -->
    <div class="row align-items-center pb-3 border-bottom">
        <div class="col-8">
            <div class="header-logo"><i class="fas fa-industry text-primary mr-2"></i>PT INDO MANUFACTURING SEJAHTERA</div>
            <div class="small text-muted font-weight-bold">PLANT &amp; OPERATION DIVISION &bull; CILEGON INDUSTRIAL ZONE</div>
            <div class="small text-muted">Kawasan Industri Cilegon Kav. 12-14, Banten | Telp: (0254) 398-100</div>
        </div>
        <div class="col-4 text-right">
            <div class="small text-muted font-weight-bold">FORMULIR MUTU HRD</div>
            <div class="small font-mono text-dark font-weight-bold">FRM/HR-PLANT/021-R3</div>
            <div class="barcode-sim mt-1">|||||| |||| |||||||</div>
        </div>
    </div>

    <!-- Title & No. SPL -->
    <div class="doc-title d-flex justify-content-between align-items-center">
        <div class="text-left">
            <h4>SURAT PERINTAH LEMBUR (SPL)</h4>
            <div class="small text-muted">OVERTIME WORK ORDER SLIP</div>
        </div>
        <div class="text-right">
            <small class="d-block">NO: <?= $spl->spl_number; ?></small>
            <span class="badge badge-light border text-muted">Tgl Terbit: <?= date('d/m/Y', strtotime($spl->created_at)); ?></span>
        </div>
    </div>

    <!-- Watermark / Status Stamp -->
    <div class="text-right mb-3">
        <?php if ($spl->status === 'APPROVED'): ?>
            <div class="stamp-approved"><i class="fas fa-check-circle mr-1"></i> RESMI DISETUJUI</div>
        <?php elseif ($spl->status === 'REJECTED'): ?>
            <div class="stamp-rejected"><i class="fas fa-times-circle mr-1"></i> DITOLAK</div>
        <?php else: ?>
            <div class="stamp-pending"><i class="fas fa-clock mr-1"></i> DALAM PROSES</div>
        <?php endif; ?>
    </div>

    <!-- Data Personil Karyawan -->
    <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2">I. DATA PERSONIL PELAKSANA</h6>
    <table class="table table-bordered table-spl mb-4">
        <tbody>
            <tr>
                <th>NIP Karyawan</th>
                <td class="font-weight-bold font-mono"><?= $spl->nip; ?></td>
                <th>Departemen / Divisi</th>
                <td class="font-weight-bold"><?= $spl->dept_name; ?> (<?= $spl->dept_code; ?>)</td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td class="font-weight-bold text-primary"><?= $spl->full_name; ?></td>
                <th>Pola Shift Reguler</th>
                <td><?= $spl->shift_name; ?> (<?= substr($spl->shift_start, 0, 5); ?> - <?= substr($spl->shift_end, 0, 5); ?> WIB)</td>
            </tr>
        </tbody>
    </table>

    <!-- Rincian Penugasan Lembur -->
    <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2">II. RINCIAN JADWAL &amp; TARGET TUGAS OPERASIONAL</h6>
    <table class="table table-bordered table-spl mb-4">
        <tbody>
            <tr>
                <th>Tanggal Pelaksanaan</th>
                <td class="font-weight-bold"><?= date('l, d F Y', strtotime($spl->overtime_date)); ?></td>
                <th>Alokasi Jam Kerja</th>
                <td class="font-weight-bold text-success">
                    <?= substr($spl->start_time, 0, 5); ?> s/d <?= substr($spl->end_time, 0, 5); ?> WIB
                </td>
            </tr>
            <tr>
                <th>Total Durasi Riil</th>
                <td class="font-weight-bold text-primary font-mono" style="font-size: 15px;">
                    <?= $spl->total_hours; ?> Jam Kerja
                </td>
                <th>Status Otorisasi</th>
                <td>
                    <span class="badge badge-<?= $spl->status === 'APPROVED' ? 'success' : ($spl->status === 'REJECTED' ? 'danger' : 'warning'); ?> font-weight-bold px-2 py-1">
                        <?= $spl->status; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th>Uraian Rencana Kerja / Justifikasi Lembur</th>
                <td colspan="3" class="p-3" style="background-color: #fbfcfe; line-height: 1.6;">
                    <strong>Deskripsi Tugas:</strong> <?= nl2br(htmlspecialchars($spl->reason)); ?>
                    <?php if ($spl->status === 'REJECTED' && !empty($spl->rejection_note)): ?>
                        <div class="mt-2 p-2 bg-light border border-danger text-danger rounded small">
                            <strong>Catatan Penolakan Atasan:</strong> <?= htmlspecialchars($spl->rejection_note); ?>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Simulasi Perhitungan Kompensasi Lembur Sesuai PP No. 35/2021 -->
    <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2">III. ESTIMASI KOMPENSASI UPAH LEMBUR (REGULASI PP NO. 35 TAHUN 2021)</h6>
    <div class="card bg-light border mb-4">
        <div class="card-body p-3">
            <div class="row text-center mb-2">
                <div class="col-3 border-right">
                    <small class="text-muted font-weight-bold d-block">GAJI POKOK ACUAN</small>
                    <span class="font-weight-bold font-mono">Rp <?= number_format($spl->base_salary, 0, ',', '.'); ?></span>
                </div>
                <div class="col-3 border-right">
                    <small class="text-muted font-weight-bold d-block">UPAH PER JAM (1/173)</small>
                    <span class="font-weight-bold font-mono">Rp <?= number_format($calc['hourly_rate'], 0, ',', '.'); ?></span>
                </div>
                <div class="col-3 border-right">
                    <small class="text-muted font-weight-bold d-block">JAM SETARA PP 35</small>
                    <span class="font-weight-bold font-mono text-primary"><?= $calc['equivalent_hours']; ?> Jam</span>
                </div>
                <div class="col-3">
                    <small class="text-muted font-weight-bold d-block">ESTIMASI UPAH LEMBUR</small>
                    <span class="font-weight-bold font-mono text-success" style="font-size: 15px;">
                        Rp <?= number_format($calc['estimated_pay'], 0, ',', '.'); ?>
                    </span>
                </div>
            </div>
            <div class="border-top pt-2 mt-2 small text-muted text-center">
                <i class="fas fa-info-circle mr-1 text-primary"></i> 
                Formula Depnaker: Jam Pertama dikalikan 1.5x upah per jam, Jam Kedua dan seterusnya dikalikan 2.0x upah per jam (Rincian: <?= $calc['breakdown']; ?>).
            </div>
        </div>
    </div>

    <!-- Lembar Tanda Tangan Resmi 3 Pihak -->
    <h6 class="font-weight-bold text-dark border-bottom pb-1 mb-2">IV. OTORISASI &amp; PENGESAHAN DOKUMEN</h6>
    <div class="row mt-3">
        <div class="col-4">
            <div class="signature-box">
                <small class="text-muted font-weight-bold">Karyawan Pelaksana</small>
                <div>
                    <div class="font-weight-bold text-dark"><?= $spl->full_name; ?></div>
                    <small class="text-muted font-mono"><?= $spl->nip; ?></small>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="signature-box">
                <small class="text-muted font-weight-bold">Atasan / Supervisor Menyetujui</small>
                <div>
                    <?php if (!empty($spl->approver_name)): ?>
                        <div class="font-weight-bold text-success"><i class="fas fa-check-circle mr-1"></i><?= $spl->approver_name; ?></div>
                        <small class="text-muted font-mono"><?= $spl->approver_nip ?: 'SPV VERIFIED'; ?></small>
                    <?php else: ?>
                        <div class="text-muted font-italic">(Menunggu Verifikasi)</div>
                        <small class="text-muted">-</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="signature-box">
                <small class="text-muted font-weight-bold">HR &amp; GA Division</small>
                <div>
                    <div class="font-weight-bold text-dark">Budi Santoso, S.Psi.</div>
                    <small class="text-muted font-mono">HR PLANT MANAGER</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Catatan Mutu -->
    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center small text-muted">
        <div>
            <i class="fas fa-lock mr-1 text-secondary"></i> Dokumen resmi ini dicetak dari sistem PlantHR Lite Cloud Server.
        </div>
        <div class="font-mono">
            Waktu Cetak: <?= date('d/m/Y H:i:s'); ?> WIB
        </div>
    </div>
</div>

</body>
</html>
