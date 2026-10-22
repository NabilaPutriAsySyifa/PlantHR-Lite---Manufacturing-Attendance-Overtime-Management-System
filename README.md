# PlantHR Lite - Manufacturing Attendance & Overtime Management System

![PHP Version](https://img.shields.io/badge/PHP-7.4%20%7C%208.x-777BB4?logo=php)
![Framework](https://img.shields.io/badge/Framework-CodeIgniter%203.1.13-EF4223?logo=codeigniter)
![Database](https://img.shields.io/badge/Database-MySQL%20%2F%20InnoDB-4479A1?logo=mysql)
![Frontend](https://img.shields.io/badge/UI-Bootstrap%204%20%26%20Chart.js-7952B3?logo=bootstrap)
![OS Compatibility](https://img.shields.io/badge/Platform-Linux%20%7C%20Windows%20Server-FCC624?logo=linux)

**PlantHR Lite** adalah aplikasi web Human Resources Management System (HRMS) operasional pabrik yang dirancang khusus untuk memecahkan alur kerja manufaktur nyata: pengelolaan roster 3-shift bergilir (termasuk shift malam lintas hari), validasi presensi harian otomatis dengan kalkulasi keterlambatan, pengajuan tiket Surat Perintah Lembur (SPL) berjenjang dengan audit trail penolakan, rekapitulasi periode cut-off payroll, formulir cetak SPL siap audit, serta pemeliharaan (*maintenance*) database mandiri.

---

## Pemetaan Masalah Nyata Manufaktur vs Solusi Teknis

| Masalah Nyata Lapangan Manufaktur | Solusi Teknis di PlantHR Lite | Kualifikasi Kompetensi Terjawab |
| :--- | :--- | :--- |
| **1. Shift malam lintas hari (*cross-day*)** | Kolom `is_cross_day` pada tabel `shifts` & penyesuaian logika tanggal aktif 24-jam di `Attendance_model`. | Pemahaman proses bisnis HRMS manufaktur |
| **2. Keterlambatan subjektif / manual** | Komparasi otomatis waktu Clock In terhadap jadwal shift dengan kalkulasi menit terlambat (*Late In minutes*). | Logika bisnis backend & otomasi perhitungan |
| **3. Birokrasi kertas SPL lambat & tercecer** | Modul tiket digital berjenjang (*Multi-Role RBAC*) dilengkapi formulir cetak resmi standar ISO/Depnaker. | PHP Fullstack Framework CI3 (MVC) |
| **4. Alasan penolakan lembur tidak transparan** | Modal penolakan dengan catatan wajib (*mandatory rejection note*) tersimpan permanen sebagai jejak audit (*audit trail*). | Analisa proses bisnis, akuntabilitas, & ketelitian |
| **5. Risiko duplikasi data & anomali (*double submission*)** | *Unique composite constraint* (`uq_emp_date`) pada InnoDB MySQL, validasi backend anti-duplicate SPL, dan proteksi frontend (*spinner disable button*). | Perancangan basis data relasional & keamanan data |
| **6. Rekap payroll memakan waktu berhari-hari** | Modul Rekapitulasi Periode Cut-Off Payroll otomatis (misal tanggal 21-20), kalkulasi upah lembur regulasi PP No. 35/2021, dan ekspor instan ke CSV/Excel. | Analisa dokumentasi aplikasi, DB, & regulasi ketenagakerjaan |
| **7. Risiko kehilangan data presensi pabrik** | Web-based backup database terkompresi (`.sql.gz`) + skrip otomasi Bash Linux (`mysqldump` & rotasi retensi 7 hari). | Maintenance database & Administrasi OS Linux / WSL |

---

## Fitur Unggulan Sistem

### 1. Multi-Role Authentication & Access Control (RBAC)
* **Operator:** Presensi mandiri (*Clock In / Clock Out*), monitoring log absensi shift, pengajuan SPL dengan live calculator durasi & deteksi lintas hari, serta cetak slip SPL resmi.
* **Supervisor:** Verifikasi permohonan lembur tim secara *real-time* (*Approve* langsung atau *Reject* dengan catatan wajib), monitoring kehadiran staf departemen hari ini, serta riwayat keputusan verifikasi.
* **HR Admin:** Monitoring metrik kehadiran pabrik, visual analytics (Chart.js), rekap presensi terfilter, modul rekapitulasi periode cut-off payroll, master data personil (CRUD & rotasi shift), serta utilitas backup basis data.

### 2. Manufacturing 3-Shift & Attendance Engine
* Konfigurasi 3 shift operasional 24/7: Shift 1 Pagi (07:00–15:00), Shift 2 Sore (15:00–23:00), dan Shift 3 Malam (23:00–07:00).
* Kalkulasi otomatis status keterlambatan (*Late In*) dan akumulasi menit terlambat.
* Integritas data terjamin via *Unique Composite Key* di tabel database guna mencegah presensi ganda di hari yang sama.

### 3. Regulasi Lembur PP No. 35 Tahun 2021 & Cetak Dokumen SPL
* Otomasi perhitungan upah lembur sesuai hukum ketenagakerjaan:
  * Jam pertama: 1.5x upah per jam
  * Jam kedua & seterusnya: 2.0x upah per jam (upah/jam = Gaji Pokok / 173)
* **Cetak Lembar Resmi SPL (Printable Work Order Slip):** Format A4 dengan Kop Perusahaan, No. Formulir Mutu (`FRM/HR-PLANT/021-R3`), barcode simulasi, rincian jadwal, kalkulasi kompensasi, dan 3 kolom tanda tangan resmi (Operator, SPV, HR).

### 4. Modul Rekapitulasi Periode Cut-Off Payroll Terpadu
* Filter rentang tanggal cut-off payroll yang fleksibel (misal tanggal 21 bulan lalu s/d 20 bulan ini).
* Otomatis mengagregasi total hari hadir, frekuensi & menit terlambat, total jam lembur riil, jam setara PP 35/2021, total upah lembur, dan estimasi total take home pay seluruh karyawan.
* Unduh langsung laporan siap bayar payroll dalam format `.CSV` (dilengkapi UTF-8 BOM untuk Microsoft Excel).

### 5. Database Maintenance & Linux Shell Automation
* **Web Backup Tool:** Pemanfaatan pustaka `CI_DB_utility` untuk mengekstraksi cadangan data terkompresi (`.sql.gz`) langsung dari panel HR Admin.
* **Linux Shell Script (`scripts/auto_backup.sh`):** Skrip shell mandiri berbasis `mysqldump` dengan kompresi gzip dan kebijakan rotasi pembersihan berkas berumur lebih dari 7 hari untuk eksekusi terjadwal via Cron.

---

## Arsitektur & Tech Stack
* **Backend:** PHP 7.4 / 8.x (CodeIgniter 3.1.13 - Arsitektur MVC)
* **Basis Data:** MySQL 5.7+ / MariaDB 10.3+ (Engine InnoDB, Foreign Key Cascade, Normalisasi 3NF)
* **Frontend:** HTML5, CSS3, Bootstrap 4.6.2, FontAwesome 5.15.4, Chart.js, jQuery (100% Local Assets, bebas kendala offline/intranet)
* **Web Server:** XAMPP (Apache HTTP Server)
* **Otomatisasi:** Bash Shell Script (Linux / WSL)

---

## Struktur Repositori
```text
planthr-lite/
├── .htaccess                     # URL Rewrite (menghilangkan index.php)
├── .gitignore                    # Pengecualian berkas cache dan log
├── README.md                     # Dokumentasi komprehensif portofolio
├── index.php                     # Titik masuk utama aplikasi CI3
├── assets/                       # Aset lokal (CSS, JS, Fonts, Chart.js)
├── application/
│   ├── config/                   # Konfigurasi database, autoload, routes, dan base_url
│   ├── controllers/              # Auth, Dashboard multi-role, dan Maintenance
│   ├── core/MY_Controller.php    # Middleware role-guard pengaman sesi
│   ├── models/                   # Kueri database (Active Record / Query Builder)
│   └── views/                    # Tampilan UI antarmuka Bootstrap 4 & Chart.js
├── database/
│   ├── planthr_schema.sql        # Skema DDL tabel relasional dan data seeder
│   └── planthr_db_dump.sql       # MySQL dump siap pakai
└── scripts/
    └── auto_backup.sh            # Skrip bash pencadangan MySQL otomatis untuk Linux
```

---

## Akun Uji Coba Demo

Kata sandi bawaan untuk seluruh akun uji coba adalah: **`admin123`**

| Peran (Role) | Alamat Email | Akses & Wewenang |
|---|---|---|
| **HR Administrator** | `hr@plant.local` | Metrik kehadiran, grafik analitik, rekap presensi terfilter, rekap cut-off payroll, CRUD karyawan, ekspor CSV, backup DB. |
| **Supervisor** | `spv@plant.local` | Verifikasi SPL masuk (Setujui / Tolak dengan catatan wajib), pantau kehadiran tim shift, cetak slip SPL. |
| **Operator Produksi** | `rian@plant.local` | Presensi harian (*Clock In/Out*), pengajuan lembur SPL, rekap presensi, cetak slip SPL disetujui. |
