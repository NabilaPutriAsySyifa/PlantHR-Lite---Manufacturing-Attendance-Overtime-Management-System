-- =====================================================================
-- PlantHR Lite - Database Schema & Initial Seeders
-- DBMS Engine: MySQL 5.7+ / MariaDB 10.3+ (InnoDB)
-- Collation  : utf8mb4_unicode_ci
-- =====================================================================

CREATE DATABASE IF NOT EXISTS planthr_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE planthr_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS overtime_requests;
DROP TABLE IF EXISTS attendances;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS shifts;
DROP TABLE IF EXISTS departments;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Master Departemen
CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dept_code VARCHAR(10) NOT NULL UNIQUE,
  dept_name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Master Pola Shift Kerja
CREATE TABLE shifts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  shift_code VARCHAR(10) NOT NULL UNIQUE,
  shift_name VARCHAR(50) NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  is_cross_day TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Master Data Karyawan
CREATE TABLE employees (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nip VARCHAR(20) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  gender ENUM('L', 'P') NOT NULL,
  department_id INT UNSIGNED NOT NULL,
  shift_id INT UNSIGNED NOT NULL,
  role ENUM('OPERATOR', 'SUPERVISOR', 'HR_ADMIN') NOT NULL DEFAULT 'OPERATOR',
  base_salary DECIMAL(12, 2) NOT NULL DEFAULT 4500000.00,
  join_date DATE NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_emp_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON UPDATE CASCADE,
  CONSTRAINT fk_emp_shift FOREIGN KEY (shift_id) REFERENCES shifts(id) ON UPDATE CASCADE,
  INDEX idx_emp_dept (department_id),
  INDEX idx_emp_role (role, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Log Absensi Harian
CREATE TABLE attendances (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,
  attendance_date DATE NOT NULL,
  clock_in DATETIME NULL,
  clock_out DATETIME NULL,
  status ENUM('PRESENT', 'LATE', 'ABSENT', 'LEAVE') NOT NULL DEFAULT 'PRESENT',
  late_minutes INT UNSIGNED NOT NULL DEFAULT 0,
  notes VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_att_emp FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  UNIQUE KEY uq_emp_date (employee_id, attendance_date),
  INDEX idx_att_date (attendance_date),
  INDEX idx_att_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Pengajuan Surat Perintah Lembur (SPL)
CREATE TABLE overtime_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  spl_number VARCHAR(30) NOT NULL UNIQUE,
  employee_id INT UNSIGNED NOT NULL,
  overtime_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  total_hours DECIMAL(4, 2) NOT NULL DEFAULT 0.00,
  reason TEXT NOT NULL,
  status ENUM('PENDING', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'PENDING',
  approved_by INT UNSIGNED NULL,
  rejection_note VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_ot_emp FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  CONSTRAINT fk_ot_approver FOREIGN KEY (approved_by) REFERENCES employees(id) ON DELETE SET NULL,
  INDEX idx_ot_date (overtime_date),
  INDEX idx_ot_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data Sampel Awal (Password default semua akun: admin123)
-- Hash Bcrypt untuk admin123: $2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G
INSERT INTO departments (dept_code, dept_name) VALUES
('PROD', 'Production & Converting'),
('ENG', 'Maintenance & Engineering'),
('QC', 'Quality Control'),
('LOG', 'Warehouse & Logistics'),
('HRD', 'Human Resources & GA'),
('PRC', 'Procurement & Purchasing'),
('SCM', 'Supply Chain Management'),
('FIN', 'Finance & Accounting');

INSERT INTO shifts (shift_code, shift_name, start_time, end_time, is_cross_day) VALUES
('SH1', 'Shift 1 (Pagi)', '07:00:00', '15:00:00', 0),
('SH2', 'Shift 2 (Sore)', '15:00:00', '23:00:00', 0),
('SH3', 'Shift 3 (Malam)', '23:00:00', '07:00:00', 1);

INSERT INTO employees (nip, full_name, gender, department_id, shift_id, role, base_salary, join_date, email, password) VALUES
('EMP001', 'Budi Santoso', 'L', 5, 1, 'HR_ADMIN', 7500000.00, '2023-01-10', 'hr@plant.local', '$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G'),
('EMP002', 'Asep Suhendar', 'L', 1, 1, 'SUPERVISOR', 6000000.00, '2023-03-15', 'spv@plant.local', '$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G'),
('EMP003', 'Rian Hidayat', 'L', 1, 1, 'OPERATOR', 4500000.00, '2024-02-01', 'rian@plant.local', '$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G'),
('EMP004', 'Dewi Lestari', 'P', 3, 2, 'OPERATOR', 4600000.00, '2024-03-01', 'dewi@plant.local', '$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G'),
('EMP005', 'Fajar Pratama', 'L', 2, 3, 'OPERATOR', 4800000.00, '2023-11-15', 'fajar@plant.local', '$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G'),
('EMP006', 'Siti Rahmawati', 'P', 4, 1, 'OPERATOR', 4500000.00, '2024-01-10', 'siti@plant.local', '$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G');
