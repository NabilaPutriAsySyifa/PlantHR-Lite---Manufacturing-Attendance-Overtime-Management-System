# Kamus Data (Data Dictionary) - PlantHR Lite

Dokumen ini memuat spesifikasi teknis skema basis data relasional untuk sistem manajemen presensi dan lembur lini manufaktur (**PlantHR Lite**).

* **DBMS Engine:** MySQL / MariaDB (InnoDB)
* **Default Collation:** `utf8mb4_unicode_ci`
* **Desain Skema:** Normalisasi Bentuk Ketiga (3NF) dengan Integritas Referensial (*Foreign Key*) dan Optimasi Indeks.

---

## 1. Tabel `departments`
Menyimpan master data divisi dan unit kerja operasional pabrik.

| Nama Kolom | Tipe Data | Nullable | Nilai Default | Keterangan & Batasan |
|---|---|---|---|---|
| `id` | INT UNSIGNED | NO | *AUTO_INCREMENT* | Kunci utama (Primary Key). |
| `dept_code` | VARCHAR(10) | NO | - | Kode unik divisi (cth: `PROD`, `QC`, `ENG`). |
| `dept_name` | VARCHAR(100) | NO | - | Nama lengkap departemen pabrik. |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu pencatatan data. |
| `updated_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu modifikasi data terakhir. |

---

## 2. Tabel `shifts`
Menyimpan konfigurasi parameter jam kerja 3-shift pabrik (24/7).

| Nama Kolom | Tipe Data | Nullable | Nilai Default | Keterangan & Batasan |
|---|---|---|---|---|
| `id` | INT UNSIGNED | NO | *AUTO_INCREMENT* | Kunci utama (Primary Key). |
| `shift_code` | VARCHAR(10) | NO | - | Kode pola shift (`SH1`, `SH2`, `SH3`). |
| `shift_name` | VARCHAR(50) | NO | - | Label nama shift (Pagi, Sore, Malam). |
| `start_time` | TIME | NO | - | Jam mulai jam kerja operasional. |
| `end_time` | TIME | NO | - | Jam selesai jam kerja operasional. |
| `is_cross_day` | TINYINT(1) | NO | `0` | `1` jika shift melewati tengah malam (Shift 3). |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu pembuatan pola shift. |

---

## 3. Tabel `employees`
Menyimpan master data profil karyawan, penempatan divisi, shift, serta hak akses autentikasi.

| Nama Kolom | Tipe Data | Nullable | Nilai Default | Keterangan & Batasan |
|---|---|---|---|---|
| `id` | INT UNSIGNED | NO | *AUTO_INCREMENT* | Kunci utama (Primary Key). |
| `nip` | VARCHAR(20) | NO | - | Nomor Induk Pegawai unik. |
| `full_name` | VARCHAR(120) | NO | - | Nama lengkap karyawan. |
| `gender` | ENUM('L', 'P') | NO | - | Jenis kelamin (`L` = Laki-laki, `P` = Perempuan). |
| `department_id` | INT UNSIGNED | NO | - | Relasi ke `departments(id)`. |
| `shift_id` | INT UNSIGNED | NO | - | Relasi ke `shifts(id)`. |
| `role` | ENUM | NO | `'OPERATOR'` | Otoritas hak akses: `OPERATOR`, `SUPERVISOR`, `HR_ADMIN`. |
| `base_salary` | DECIMAL(12, 2) | NO | `4500000.00` | Gaji pokok acuan dasar kalkulasi upah lembur PP No. 35/2021. |
| `join_date` | DATE | NO | - | Tanggal resmi mulai bekerja. |
| `email` | VARCHAR(100) | NO | - | Email unik untuk login ke portal web. |
| `password` | VARCHAR(255) | NO | - | Hash kata sandi menggunakan Bcrypt. |
| `is_active` | TINYINT(1) | NO | `1` | Status akun (`1` = Aktif, `0` = Nonaktif). |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu pendaftaran akun. |
| `updated_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu pembaruan data karyawan. |

* **Foreign Key Constraints:**
  * `fk_emp_dept`: `FOREIGN KEY (department_id) REFERENCES departments(id) ON UPDATE CASCADE`
  * `fk_emp_shift`: `FOREIGN KEY (shift_id) REFERENCES shifts(id) ON UPDATE CASCADE`
* **Indeks:**
  * `idx_emp_dept` (`department_id`)
  * `idx_emp_role` (`role`, `is_active`)

---

## 4. Tabel `attendances`
Mencatat riwayat log presensi harian, jam masuk/keluar, dan kalkulasi keterlambatan.

| Nama Kolom | Tipe Data | Nullable | Nilai Default | Keterangan & Batasan |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | *AUTO_INCREMENT* | Kunci utama (Primary Key). |
| `employee_id` | INT UNSIGNED | NO | - | Relasi ke `employees(id)`. |
| `attendance_date` | DATE | NO | - | Tanggal absensi berjalan. |
| `clock_in` | DATETIME | YES | `NULL` | Stempel waktu presensi masuk (*Clock In*). |
| `clock_out` | DATETIME | YES | `NULL` | Stempel waktu presensi pulang (*Clock Out*). |
| `status` | ENUM | NO | `'PRESENT'` | Status: `PRESENT`, `LATE`, `ABSENT`, `LEAVE`. |
| `late_minutes` | INT UNSIGNED | NO | `0` | Durasi keterlambatan dalam satuan menit. |
| `notes` | VARCHAR(255) | YES | `NULL` | Catatan tambahan presensi. |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu rekaman dibuat di sistem. |

* **Foreign Key Constraint:**
  * `fk_att_emp`: `FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE`
* **Unique Constraint:**
  * `uq_emp_date` (`employee_id`, `attendance_date`): Mencegah duplikasi data presensi pada tanggal yang sama.
* **Indeks:**
  * `idx_att_date` (`attendance_date`)
  * `idx_att_status` (`status`)

---

## 5. Tabel `overtime_requests`
Menyimpan transaksi permohonan lembur (SPL) dan proses verifikasi oleh atasan.

| Nama Kolom | Tipe Data | Nullable | Nilai Default | Keterangan & Batasan |
|---|---|---|---|---|
| `id` | BIGINT UNSIGNED | NO | *AUTO_INCREMENT* | Kunci utama (Primary Key). |
| `spl_number` | VARCHAR(30) | NO | - | Nomor urut SPL unik (`SPL/YYYYMM/XXX`). |
| `employee_id` | INT UNSIGNED | NO | - | Relasi pemohon ke `employees(id)`. |
| `overtime_date` | DATE | NO | - | Tanggal pelaksanaan lembur. |
| `start_time` | TIME | NO | - | Jam mulai lembur. |
| `end_time` | TIME | NO | - | Jam selesai lembur. |
| `total_hours` | DECIMAL(4,2) | NO | `0.00` | Durasi bersih jam lembur desimal. |
| `reason` | TEXT | NO | - | Uraian target tugas / justifikasi lembur. |
| `status` | ENUM | NO | `'PENDING'` | Status persetujuan: `PENDING`, `APPROVED`, `REJECTED`. |
| `approved_by` | INT UNSIGNED | YES | `NULL` | Relasi approver ke `employees(id)`. |
| `rejection_note` | VARCHAR(255) | YES | `NULL` | Catatan justifikasi penolakan dari atasan. |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu permohonan disubmit. |
| `updated_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu keputusan verifikasi dieksekusi. |

* **Foreign Key Constraints:**
  * `fk_ot_emp`: `FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE`
  * `fk_ot_approver`: `FOREIGN KEY (approved_by) REFERENCES employees(id) ON DELETE SET NULL`
* **Indeks:**
  * `idx_ot_date` (`overtime_date`)
  * `idx_ot_status` (`status`)
