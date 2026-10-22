# PlantHR Lite - Manufacturing Attendance & Overtime Management System

![PHP Version](https://img.shields.io/badge/PHP-7.4%20%7C%208.x-777BB4?logo=php)
![Framework](https://img.shields.io/badge/Framework-CodeIgniter%203.1.13-EF4223?logo=codeigniter)
![Database](https://img.shields.io/badge/Database-MySQL%20%2F%20InnoDB-4479A1?logo=mysql)
![Frontend](https://img.shields.io/badge/UI-Bootstrap%204%20%26%20Chart.js-7952B3?logo=bootstrap)
![Docker](https://img.shields.io/badge/Container-Docker%20Ready-2496ED?logo=docker)
![Timezone](https://img.shields.io/badge/Timezone-WIB%20%28Asia%2FJakarta%29-009688)
![Platform](https://img.shields.io/badge/Platform-Windows%20XAMPP%20%7C%20Linux%20Cloud-FCC624)

**PlantHR Lite** adalah aplikasi web Human Resources Management System (HRMS) operasional pabrik yang dirancang khusus untuk memecahkan alur kerja manufaktur nyata: pengelolaan roster 3-shift bergilir 24/7 (termasuk shift malam lintas hari), presensi mandiri seluruh peran (*Employee Self-Service / ESS*), validasi presensi harian otomatis berbasis zona waktu Indonesia Barat (**WIB / Asia/Jakarta**), pengajuan tiket Surat Perintah Lembur (SPL) berjenjang dengan audit trail penolakan Supervisor, rekapitulasi periode cut-off payroll dengan ekspor ganda (**Excel .xls & CSV**), 4 pilar analitik eksekutif dengan **Real-Time Live Sync (100% data riil database MySQL)**, formulir cetak SPL resmi siap audit ISO, manajemen departemen dinamis, serta utilitas pencadangan database mandiri.

> 🌐 **Live Demo & Akses Publik:**  
> Uji coba langsung seluruh fitur sistem operasional pabrik ini secara online tanpa perlu instalasi lokal melalui portal resmi:  
> 🚀 **[Masuk ke Portal | PlantHR Lite](https://epson-thumbzilla-subjects-beach.trycloudflare.com/planthr-lite/auth/login)**  
> *(Silakan gunakan kredensial login yang tertera pada tabel [Akun Uji Coba Demo](#akun-uji-coba-demo) di bawah).*

---

## Pemetaan Masalah Nyata Manufaktur vs Solusi Teknis

| Masalah Nyata Lapangan Manufaktur | Solusi Teknis di PlantHR Lite | Kualifikasi Kompetensi Terjawab |
| :--- | :--- | :--- |
| **1. Shift malam lintas hari (*cross-day*)** | Kolom `is_cross_day` pada tabel `shifts` & penyesuaian logika tanggal aktif 24-jam di `Attendance_model`. | Pemahaman proses bisnis HRMS manufaktur |
| **2. Keterlambatan subjektif / manual** | Komparasi otomatis waktu Clock In terhadap jadwal shift dengan kalkulasi menit terlambat (*Late In minutes*) berbasis WIB. | Logika bisnis backend & otomasi perhitungan |
| **3. Birokrasi kertas SPL lambat & tercecer** | Modul tiket digital berjenjang (*Multi-Role RBAC*) dilengkapi formulir cetak resmi standar ISO/Depnaker. | PHP Fullstack Framework CI3 (MVC) |
| **4. Alasan penolakan lembur tidak transparan** | Modal penolakan dengan catatan wajib (*mandatory rejection note*) tersimpan permanen sebagai jejak audit (*audit trail*). | Analisa proses bisnis, akuntabilitas, & ketelitian |
| **5. Risiko duplikasi data & anomali (*double submission*)** | *Unique composite constraint* (`uq_emp_date`) pada InnoDB MySQL, validasi backend anti-duplicate SPL, dan proteksi frontend (*spinner disable button*). | Perancangan basis data relasional & keamanan data |
| **6. Rekap payroll memakan waktu berhari-hari** | Modul Rekapitulasi Periode Cut-Off Payroll otomatis (misal tanggal 21-20), kalkulasi upah lembur regulasi PP No. 35/2021, dan ekspor instan ke format Excel (`.xls`) dan CSV. | Analisa dokumentasi aplikasi, DB, & regulasi ketenagakerjaan |
| **7. Pantauan analitik statis & data bias** | 4 pilar analitik visual (Chart.js) dengan sinkronisasi otomatis (*Real-Time Live-Sync*) 100% data riil database MySQL setiap 25 detik tanpa perlu me-refresh halaman. | Business Intelligence, Data Integrity, & Frontend Motion |
| **8. Risiko kehilangan data presensi pabrik** | *Native Web Backup* terkompresi (`.sql.gz`) via PHP `CI_DB_utility` (siap pakai di Windows) + skrip pendamping otomasi Linux Bash (`mysqldump` & rotasi retensi 7 hari untuk server VPS/Cloud). | Maintenance database & Administrasi Sistem (Windows / Linux) |

---

## Fitur Unggulan Sistem

### 1. Multi-Role Authentication & Access Control (RBAC)
* **Portal Karyawan (Employee Self-Service / ESS):**
  * Berlaku untuk seluruh personil operasional pabrik: **Produksi**, **Maintenance & Engineering (Teknisi)**, **Quality Control (QC)**, **Gudang & Logistik**, **Procurement**, **SCM**, dll.
  * Presensi mandiri (*Clock In / Clock Out*), monitoring log absensi shift harian, pengajuan tiket lembur mandiri (SPL) dengan pendeteksi duplikasi otomatis, dan unduh/cetak lembar kerja SPL resmi.
* **Dashboard Supervisor (Pimpinan Shift / Kepala Regu):**
  * Presensi mandiri terintegrasi pimpinan shift.
  * Verifikasi permohonan lembur bawahan secara *real-time* (*Approve* langsung atau *Reject* dengan modal alasan/catatan resmi wajib).
  * Pemantauan status kehadiran seluruh anggota tim departemen hari ini, serta rekam jejak keputusan otorisasi lembur.
* **Portal HR Administrator:**
  * Presensi mandiri staf HRD.
  * Monitoring metrik kehadiran pabrik, 4 pilar visual analytics (Chart.js) dengan live sync database, rekap presensi terfilter, modul rekap cut-off payroll, master data personil (CRUD & rotasi shift), manajemen departemen dinamis, utilitas reset kata sandi instan, serta pencadangan basis data.
* **Keamanan Kredensial:** Seluruh kata sandi dienkripsi menggunakan standar industri **Bcrypt** (`PASSWORD_BCRYPT`), dilengkapi panduan SOP Lupa Password bagi karyawan.

### 2. Manufacturing 3-Shift & Attendance Engine
* Konfigurasi 3 shift operasional 24/7:
  * **Shift 1 Pagi:** 07:00 – 15:00 WIB
  * **Shift 2 Sore:** 15:00 – 23:00 WIB
  * **Shift 3 Malam:** 23:00 – 07:00 WIB (Lintas hari / *Cross-day*)
* Sinkronisasi zona waktu baku **WIB (Asia/Jakarta - GMT+7)** mencakup kawasan industri manufaktur utama (Cilegon, Bekasi, Karawang, Cikarang, Surabaya, dll.).
* Presensi Mandiri Universal (*Universal Self-Service*): Operator, Supervisor, dan HR Admin dapat melakukan Clock In & Clock Out mandiri dengan pencatatan detik riil server.
* Kalkulasi otomatis status keterlambatan (*Late In*) dan akumulasi menit terlambat.
* Integritas data terjamin via *Unique Composite Key* di tabel database guna mencegah presensi ganda di hari yang sama.

### 3. Regulasi Lembur PP No. 35 Tahun 2021 & Cetak Dokumen SPL
* Otomasi perhitungan upah lembur sesuai hukum ketenagakerjaan Indonesia:
  * **Jam pertama:** 1.5x upah per jam
  * **Jam kedua & seterusnya:** 2.0x upah per jam (upah/jam = Gaji Pokok / 173)
* **Cetak Lembar Resmi SPL (Printable Work Order Slip):** Format A4 dengan Kop Perusahaan, No. Formulir Mutu (`FRM/HR-PLANT/021-R3`), barcode simulasi, rincian jadwal, kalkulasi kompensasi, dan 3 kolom tanda tangan resmi (Pemohon/Operator, Pimpinan Shift/SPV, HRD).
* **Manajemen Arsip SPL:** HR Admin dapat memonitor seluruh rekam jejak lembur pabrik dan menghapus tiket anomali secara aman melalui tombol aksi *Hapus* terproteksi.

### 4. 4 Pilar Analitik Eksekutif & Real-Time Live Sync
Dashboard HR Admin dilengkapi 4 grafik analitik bisnis terpadu:
1. **Tren Kedisiplinan Presensi 7 Hari (*Bar Chart*):** Membandingkan personil hadir tepat waktu versus terlambat masuk shift setiap hari.
2. **Distribusi Jam Lembur per Divisi (*Doughnut Chart*):** Memperlihatkan departemen dengan total durasi jam lembur disetujui tertinggi.
3. **Komposisi Personil per Shift Kerja (*Doughnut Chart*):** Menjaga keseimbangan kapasitas regu kerja 24/7 (Shift 1, Shift 2, dan Shift 3).
4. **Estimasi Biaya Upah Lembur PP 35/2021 (*Monetary Bar Chart*):** Menghitung pengeluaran riil upah lembur dalam nominal Rupiah (`Rp`) per departemen untuk pengendalian anggaran (*budget control*).
* **Fitur Real-Time Live Sync (100% Data Asli Database):**
  * Browser secara otomatis menyinkronkan data grafik dan metrik setiap 25 detik via AJAX murni ke database MySQL.
  * Menggunakan animasi transisi Chart.js yang mulus tanpa kedip (*no page reload*).
  * Dilengkapi badge status waktu sinkronisasi riil (`Live Sync: HH:mm:ss WIB`).

### 5. Modul Rekapitulasi Periode Cut-Off Payroll Terpadu & Ekspor
* Filter rentang tanggal cut-off payroll yang fleksibel (misal tanggal 21 bulan lalu s/d 20 bulan ini).
* Otomatis mengagregasi total hari hadir, frekuensi & menit terlambat, total jam lembur riil, jam setara PP 35/2021, total upah lembur, dan estimasi total *take home pay* seluruh karyawan.
* **Dukungan Dual Export Terpadu:**
  * **Unduh Excel (`.xls`):** Dokumen tabel berformat profesional lengkap dengan styling korporat warna Navy, garis grid rapi, dan format mata uang akuntansi (`Rp #,##0`). Tersedia pada:
    * Rekap Presensi Harian Pabrik
    * Rekap Cut-Off Payroll Terpadu
    * Arsip Surat Perintah Lembur (SPL)
    * Data Master Personil Karyawan
  * **Unduh CSV (`.csv`):** Dilengkapi *UTF-8 Byte Order Mark (BOM)* agar kompatibel dibuka langsung di Microsoft Excel tanpa masalah karakter.

### 6. Manajemen Master Karyawan & Departemen Dinamis
* **Master Karyawan:** Tambah karyawan baru, edit informasi personil, alokasi rotasi shift kerja, status aktif/nonaktif, reset kata sandi akun karyawan, serta ekspor data ke Excel & CSV.
* **Departemen Dinamis:** HR Admin dapat menambahkan departemen baru secara interaktif (baik divisi lini pabrik maupun kantor pendukung seperti Procurement, SCM, Finance) lengkap dengan kode unik dan nama divisi.

### 7. Database Maintenance & Backup Otomatis
* **Web Backup Tool (Windows / XAMPP):** Pemanfaatan pustaka native PHP `CI_DB_utility` untuk mengekstraksi cadangan data terkompresi (`.sql.gz`) langsung dari panel HR Admin hanya dengan sekali klik di browser.
* **Linux Shell Script (`scripts/auto_backup.sh`):** Skrip shell mandiri berbasis `mysqldump` dengan kompresi gzip dan kebijakan rotasi pembersihan berkas berumur lebih dari 7 hari, siap dieksekusi terjadwal via Cron jika aplikasi di-deploy pada VPS/Cloud Server Linux.

---

## Arsitektur & Tech Stack
* **Backend:** PHP 7.4 / 8.x (CodeIgniter 3.1.13 - Arsitektur MVC)
* **Basis Data:** MySQL 5.7+ / MariaDB 10.3+ (Engine InnoDB, Foreign Key Cascade, Normalisasi 3NF, Charset UTF-8)
* **Frontend:** HTML5, CSS3, Bootstrap 4.6.2, FontAwesome 5.15.4, Chart.js, jQuery (100% Local Assets, bebas kendala offline/intranet)
* **Web Server:** XAMPP (Apache HTTP Server) / Docker Container (Cloud-Ready)
* **Keamanan:** CSRF Protection, Prepared Statements (Anti SQL Injection), XSS Filtering, Bcrypt Password Hashing.

---

## Struktur Repositori
```text
planthr-lite/
├── .htaccess                     # URL Rewrite (menghilangkan index.php)
├── .gitignore                    # Pengecualian berkas cache, sesi, dan log
├── Dockerfile                    # Konfigurasi container Docker (PHP 8.1 Apache)
├── README.md                     # Dokumentasi komprehensif sistem
├── index.php                     # Titik masuk utama aplikasi CI3
├── assets/                       # Aset lokal (CSS, JS, Fonts, Chart.js)
├── application/
│   ├── config/                   # Konfigurasi database, autoload, routes, dan base_url dinamis
│   ├── controllers/              # Auth, Dashboard multi-role, dan Maintenance
│   ├── core/MY_Controller.php    # Middleware role-guard pengaman sesi
│   ├── models/                   # Kueri database (Active Record / Query Builder)
│   └── views/                    # Tampilan UI antarmuka Bootstrap 4 & Chart.js
├── database/
│   ├── planthr_schema.sql        # Skema DDL tabel relasional dan data seeder
│   └── planthr_db_dump.sql       # MySQL dump siap pakai (UTF-8)
└── scripts/
    └── auto_backup.sh            # Skrip bash cadangan MySQL untuk server Linux / Cron
```

---

## Panduan Instalasi Lokal (XAMPP)

1. **Clone Repositori:**
   Pindahkan atau clone repositori ini ke dalam direktori `htdocs` XAMPP Anda:
   ```bash
   git clone https://github.com/NabilaPutriAsySyifa/PlantHR-Lite---Manufacturing-Attendance-Overtime-Management-System.git c:/xampp/htdocs/hr_plan
   ```

2. **Impor Database:**
   * Buka browser dan akses **`http://localhost/phpmyadmin`**.
   * Buat basis data baru bernama **`planthr_db`**.
   * Pilih tab **Import**, lalu unggah file **`database/planthr_db_dump.sql`** *(atau gunakan `database/planthr_schema.sql`)*.

3. **Konfigurasi Lingkungan (Opsional):**
   * Konfigurasi koneksi basis data berada di file [`application/config/database.php`](application/config/database.php).
   * Secara default telah disetel untuk XAMPP standar (`hostname: localhost`, `username: root`, `password: ''`).

4. **Jalankan Aplikasi:**
   Buka peramban web dan akses:
   ```text
   http://localhost/hr_plan
   ```

---

## Akun Uji Coba Demo

Tautan Akses Cepat: 🚀 **[Masuk ke Portal | PlantHR Lite](https://epson-thumbzilla-subjects-beach.trycloudflare.com/planthr-lite/auth/login)**  
Kata sandi bawaan untuk seluruh akun uji coba adalah: **`admin123`**

| Peran (Role) | Divisi & Penugasan Shift / Wewenang | Alamat Email |
|---|---|---|
| **HR Administrator** | Hak Penuh: Metrik, 4 Analitik Chart.js, Rekap Cut-off Payroll, CRUD Karyawan, Tambah Dept, Backup DB | `hr@plant.local` |
| **Supervisor** | Pimpinan Regu: Verifikasi Otoritas SPL (Approve/Reject + Catatan Resmi), Monitoring Kehadiran Tim | `spv@plant.local` |
| **Operator Produksi** | Production & Converting — **Shift 1 Pagi** (07:00 – 15:00 WIB) - Presensi, Ajukan SPL, Riwayat | `rian@plant.local` |
| **Operator QC** | Quality Control — **Shift 2 Sore** (15:00 – 23:00 WIB) - Presensi, Ajukan SPL, Riwayat | `dewi@plant.local` |
| **Teknisi Maintenance** | Maintenance & Engineering — **Shift 3 Malam** (23:00 – 07:00 WIB Cross-day) - Presensi Lintas Hari | `fajar@plant.local` |
| **Operator Gudang** | Warehouse & Logistics — **Shift 1 Pagi** (07:00 – 15:00 WIB) - Presensi, Logistik | `siti@plant.local` |

---

## Standar Mutu & Kepatuhan Legal
* **Kepatuhan Ketenagakerjaan:** Perhitungan lembur mengacu pada **Peraturan Pemerintah (PP) No. 35 Tahun 2021** Pasal 31.
* **Standar Mutu Dokumen:** Formulir cetak Surat Perintah Lembur (SPL) mengadopsi standar sistem manajemen mutu industri (**ISO 9001 / Formulir Mutu HRD No. FRM/HR-PLANT/021-R3**).
* **Integritas Waktu:** Seluruh pencatatan waktu terkunci (*server-time locked*) pada Waktu Indonesia Barat (**WIB / Asia/Jakarta**).
