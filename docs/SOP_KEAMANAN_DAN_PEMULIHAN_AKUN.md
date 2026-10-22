# STANDAR OPERASIONAL PROSEDUR (SOP)
## TATA KELOLA AKUN, KEAMANAN KREDENSIAL, DAN PEMULIHAN SISTEM (DISASTER RECOVERY)
**PlantHR Lite — Manufacturing Attendance & Overtime Management System**

*Nomor Dokumen:* `SOP-SEC-IT-HR-002`  
*Klasifikasi Dokumen:* **CAMPURAN (PUBLIK, TERBATAS, DAN RAHASIA)**  
*Terakhir Diperbarui:* `22 Oktober 2026`  

---

## 1. TUJUAN & RUANG LINGKUP
SOP ini disusun guna menjamin integritas data operasional pabrik (kehadiran, shift kerja, dan perhitungan upah lembur resmi) serta mencegah celah keamanan peniruan identitas (*identity theft*) dan kebocoran kredensial administratif.

SOP ini mengatur pemisahan informasi secara berjenjang (*Need-To-Know Basis*) menjadi tiga tingkat klasifikasi:
1. **Bagian I (PUBLIK / UMUM):** Prosedur bantuan yang boleh diketahui oleh **seluruh karyawan, staf, dan supervisor**.
2. **Bagian II (TERBATAS / RESTRICTED):** Prosedur operasional yang **hanya boleh diketahui dan dijalankan oleh HR Administrator**.
3. **Bagian III (RAHASIA / CONFIDENTIAL):** Prosedur darurat teknis yang **hanya boleh diketahui dan dijalankan oleh Tim IT / Sysadmin / Database Administrator**.

---

## 2. MATRIKS KLASIFIKASI AKSES INFORMASI

| Komponen Prosedur | Karyawan / Staf / SPV | HR Administrator | Tim IT / Sysadmin |
|---|:---:|:---:|:---:|
| Tata Cara Pengajuan Reset Sandi | **Boleh Tahu** (Publik) | Boleh Tahu | Boleh Tahu |
| Kebijakan Keamanan Akun Karyawan | **Boleh Tahu** (Publik) | Boleh Tahu | Boleh Tahu |
| Penggunaan Menu Master Karyawan | *Dilarang Tahu* | **Akses Penuh** | Akses Penuh |
| Tombol & Log Reset Sandi Web | *Dilarang Tahu* | **Akses Penuh** | Akses Penuh |
| Nilai Sandi Default Awal (`admin123`) | *Dilarang Tahu (Rahasia)* | **Akses Internal** | Akses Internal |
| Skrip Terminal CLI (`reset_admin.php`) | *Dilarang Tahu (Rahasia)* | *Dilarang Akses* | **Akses Khusus IT** |
| Akses Konsol Server & Database MySQL | *Dilarang Tahu (Rahasia)* | *Dilarang Akses* | **Akses Khusus IT** |
| Skrip Backup Otomatis (`auto_backup.sh`) | *Dilarang Tahu (Rahasia)* | *Dilarang Akses* | **Akses Khusus IT** |

---

## BAGIAN I: SOP LAYANAN BANTUAN AKUN KARYAWAN
*(Klasifikasi: UMUM / Terbuka untuk Seluruh Karyawan & Tampil di Halaman Login)*

### A. Kebijakan Keamanan Akun
1. Akun PlantHR Lite bersifat personal, unik, dan terikat pada Nomor Induk Karyawan (NIP) resmi.
2. Setiap personil bertanggung jawab penuh atas seluruh aktivitas presensi dan permohonan lembur yang dilakukan menggunakan akun miliknya.
3. Dilarang keras membagikan kata sandi pribadi kepada rekan kerja sesama operator, staf, maupun pihak luar.

### B. Prosedur Permohonan Bantuan Lupa Kata Sandi
1. **Langkah 1 (Pelaporan):** Karyawan yang lupa kata sandi atau akunnya terkendala dapat melapor langsung ke kantor **HRD / Personalia** atau menginformasikan kepada **Supervisor** yang sedang bertugas di shift kerjanya.
2. **Langkah 2 (Verifikasi Identitas Resmi):** Karyawan wajib menyebutkan **NIP** dan menunjukkan kartu tanda pengenal resmi (**ID Badge Pabrik**) kepada petugas HRD untuk membuktikan keabsahan kepemilikan akun.
3. **Langkah 3 (Penerbitan Kredensial Baru):** Petugas HRD yang berwenang akan memproses pemulihan dan memberikan kata sandi baru yang aman secara langsung kepada karyawan bersangkutan.
4. **Langkah 4 (Penggantian Sandi Mandiri):** Karyawan wajib segera login ke portal dan memperbarui kata sandi baru demi menjaga privasi dan keamanan akun pribadi.

> **Pemberitahuan Keamanan Penting Bagi Karyawan:**
> * Sistem **tidak menggunakan pemulihan otomatis via email publik** guna mencegah manipulasi kehadiran atau pencurian akun kerja.
> * Petugas HRD maupun Tim IT **tidak pernah** meminta kata sandi pribadi karyawan melalui telepon, WhatsApp, SMS, atau media lainnya.

---

## BAGIAN II: SOP TATA KELOLA KREDENSIAL DIVISI HRD
*(Klasifikasi: TERBATAS / RESTRICTED — Hanya untuk HR Administrator)*

### A. Kewenangan & Tanggung Jawab HR Administrator
1. HR Administrator memiliki kewenangan penuh mengelola data induk personil di menu **Data Master Karyawan**.
2. Reset kata sandi personil **hanya boleh dilakukan** apabila personil bersangkutan hadir secara fisik atau telah diverifikasi secara sah melalui atasan langsung (Supervisor departemen).

### B. Prosedur Reset Kata Sandi Personil di Portal
1. Buka portal PlantHR Lite dan login sebagai **Administrator Utama**.
2. Masuk ke tab **Data Master Karyawan**.
3. Cari nama atau NIP karyawan yang mengajukan permohonan pemulihan.
4. Klik tombol kuning berikon kunci (**Ganti / Reset Kata Sandi Personil**).
5. Pada jendela popup modal yang terbuka:
   - Sistem akan menampilkan status kata sandi karyawan saat ini beserta riwayat pembaruan terakhir.
   - Ketikkan kata sandi baru (minimal 6 karakter) atau gunakan opsi inisialisasi sementara.
   - Klik **Simpan Kata Sandi Baru**.
6. Sistem seketika mengacak sandi menggunakan algoritma **Bcrypt Hash satu arah**, memperbarui kolom `password_changed_at`, dan menampilkan label **Sandi Baru Aktif**.
7. Beritahukan kata sandi baru tersebut secara rahasia kepada karyawan dan tegaskan untuk segera login.

### C. Prosedur Pemutusan Akses (Karyawan Resign / Nonaktif)
1. Apabila ada karyawan yang berhenti kerja, mutasi keluar, atau diberhentikan, HR Admin **wajib seketika menonaktifkan akun** melalui tombol daya (*Toggle Active*) di tabel Master Karyawan.
2. Akun nonaktif seketika terblokir dari sistem login portal.

---

## BAGIAN III: SOP DISASTER RECOVERY & PEMULIHAN SISTEM TIM IT
*(Klasifikasi: RAHASIA / CONFIDENTIAL — Khusus Tim IT / System Administrator)*

### A. Skenario Pemulihan Darurat Akun HR Administrator Terkunci
Apabila akun Administrator Utama (`hr@plant.local`) terkunci, lupa kata sandi, atau terjadi anomali kredensial administratif sehingga portal web tidak dapat diakses:

1. **Akses Konsol Server:**
   Petugas IT / Sysadmin yang memiliki hak akses administratif masuk ke konsol terminal server lokal/VPS via SSH atau Command Line lokal.
2. **Navigasi ke Direktori Root:**
   ```bash
   cd c:\xampp\htdocs\hr_plan
   # atau pada server Linux:
   cd /var/www/html/hr_plan
   ```
3. **Eksekusi Alat Pemulihan CLI Darurat:**
   Jalankan skrip darurat PHP:
   ```bash
   php scripts/reset_admin.php hr@plant.local [password_baru]
   ```
   *Contoh eksekusi:*
   ```bash
   php scripts/reset_admin.php hr@plant.local admin123
   ```
4. **Mekanisme Skrip:**
   - Skrip `scripts/reset_admin.php` melakukan koneksi langsung ke socket database MariaDB/MySQL (`planthr_db`).
   - Melakukan enkripsi `password_hash($password, PASSWORD_BCRYPT)`.
   - Melakukan pembaruan kolom `password`, `password_changed_at`, dan `updated_at` pada tabel `employees` secara langsung dalam waktu 1 detik tanpa bergantung pada web server Apache.
5. **Verifikasi Login:**
   HR Administrator diminta mencoba login kembali ke portal dan segera mengganti kata sandi administratif.

### B. Prosedur Pencadangan (Backup) & Pemulihan Database
1. **Pencadangan Terjadwal (Cron Job Linux):**
   Skrip `scripts/auto_backup.sh` berjalan otomatis setiap pukul 02:00 WIB untuk mengekspor database `planthr_db` ke dalam format `.sql.gz`.
2. **Kebijakan Retensi:**
   Sistem otomatis merotasi dan menghapus file cadangan yang berumur lebih dari 7 hari guna menjaga efisiensi ruang disk server.
3. **Pencadangan Manual (Windows / XAMPP):**
   HR Admin dapat memanfaatkan fitur *Backup Database (.sql.gz)* pada tab Pengaturan Sistem di portal web.

---

## 4. KEPATUHAN & SANKSI
1. Setiap staf atau personil yang terbukti menyalahgunakan kredensial orang lain atau memanipulasi absensi akan dikenakan sanksi indisipliner sesuai Peraturan Perusahaan.
2. Pembocoran skrip administratif terminal (`scripts/reset_admin.php`) atau akses konsol database kepada pihak yang tidak berhak dikategorikan sebagai pelanggaran keamanan siber berat (*Critical Security Incident*).
