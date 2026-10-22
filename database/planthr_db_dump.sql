-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: planthr_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `planthr_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `planthr_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `planthr_db`;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) unsigned NOT NULL,
  `attendance_date` date NOT NULL,
  `clock_in` datetime DEFAULT NULL,
  `clock_out` datetime DEFAULT NULL,
  `status` enum('PRESENT','LATE','ABSENT','LEAVE') NOT NULL DEFAULT 'PRESENT',
  `late_minutes` int(10) unsigned NOT NULL DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_emp_date` (`employee_id`,`attendance_date`),
  KEY `idx_att_date` (`attendance_date`),
  KEY `idx_att_status` (`status`),
  CONSTRAINT `fk_att_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,3,'2026-10-20','2026-10-20 11:48:58','2026-10-20 11:50:59','LATE',289,NULL,'2026-10-20 09:48:58'),(2,4,'2026-10-21','2026-10-21 14:55:00',NULL,'PRESENT',0,NULL,'2026-10-21 09:42:06'),(3,5,'2026-10-21','2026-10-21 22:58:00',NULL,'PRESENT',0,NULL,'2026-10-21 09:42:06'),(4,6,'2026-10-21','2026-10-21 07:15:00',NULL,'LATE',15,NULL,'2026-10-21 09:42:06'),(5,1,'2026-10-21','2026-10-21 06:50:00','2026-10-22 11:01:44','PRESENT',0,NULL,'2026-10-21 09:42:06'),(6,2,'2026-10-21','2026-10-21 06:45:00','2026-10-22 11:03:26','PRESENT',0,NULL,'2026-10-21 09:42:06'),(7,4,'2026-10-20','2026-10-20 14:50:00','2026-10-20 23:05:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(8,5,'2026-10-20','2026-10-20 23:12:00','2026-10-21 07:01:00','LATE',12,NULL,'2026-10-21 09:42:36'),(9,6,'2026-10-20','2026-10-20 06:55:00','2026-10-20 15:05:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(10,3,'2026-10-19','2026-10-19 07:05:00','2026-10-19 15:00:00','LATE',5,NULL,'2026-10-21 09:42:36'),(11,4,'2026-10-19','2026-10-19 14:52:00','2026-10-19 23:02:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(12,5,'2026-10-19','2026-10-19 22:50:00','2026-10-20 07:00:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(13,6,'2026-10-19','2026-10-19 06:50:00','2026-10-19 15:01:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(14,3,'2026-10-18','2026-10-18 06:45:00','2026-10-18 15:00:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(15,4,'2026-10-18','2026-10-18 14:55:00','2026-10-18 23:00:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(16,5,'2026-10-18','2026-10-18 22:55:00','2026-10-19 07:05:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(17,6,'2026-10-18','2026-10-18 06:48:00','2026-10-18 15:03:00','PRESENT',0,NULL,'2026-10-21 09:42:36'),(19,3,'2026-10-21','2026-10-21 12:27:27','2026-10-21 12:27:50','LATE',328,NULL,'2026-10-21 10:27:27'),(21,3,'2026-10-22','2026-10-22 08:33:31','2026-10-22 11:08:00','LATE',94,NULL,'2026-10-22 01:33:31'),(22,1,'2026-10-22','2026-10-22 11:02:07','2026-10-22 11:02:20','LATE',243,NULL,'2026-10-22 04:02:07'),(23,2,'2026-10-22','2026-10-22 11:03:31',NULL,'LATE',244,NULL,'2026-10-22 04:03:31');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dept_code` varchar(10) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `dept_code` (`dept_code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'PROD','Production & Converting','2026-10-20 08:56:12','2026-10-20 08:56:12'),(2,'ENG','Maintenance & Engineering','2026-10-20 08:56:12','2026-10-20 08:56:12'),(3,'QC','Quality Control','2026-10-20 08:56:12','2026-10-20 08:56:12'),(4,'LOG','Warehouse & Logistics','2026-10-20 08:56:12','2026-10-20 08:56:12'),(5,'HRD','Human Resources & GA','2026-10-20 08:56:12','2026-10-20 08:56:12'),(6,'PRC','Procurement & Purchasing','2026-10-22 03:41:31','2026-10-22 03:41:31'),(7,'SCM','Supply Chain Management','2026-10-22 03:41:31','2026-10-22 03:41:31'),(8,'FIN','Finance & Accounting','2026-10-22 03:41:31','2026-10-22 03:41:31');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(20) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `department_id` int(10) unsigned NOT NULL,
  `shift_id` int(10) unsigned NOT NULL,
  `role` enum('OPERATOR','SUPERVISOR','HR_ADMIN') NOT NULL DEFAULT 'OPERATOR',
  `base_salary` decimal(12,2) NOT NULL DEFAULT 4500000.00,
  `join_date` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_emp_shift` (`shift_id`),
  KEY `idx_emp_dept` (`department_id`),
  KEY `idx_emp_role` (`role`,`is_active`),
  CONSTRAINT `fk_emp_dept` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_emp_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'EMP001','Budi Santoso','L',5,1,'HR_ADMIN',7500000.00,'2023-01-10','hr@plant.local','$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G',1,'2026-10-20 08:56:12','2026-10-21 09:41:33'),(2,'EMP002','Asep Suhendar','L',1,1,'SUPERVISOR',6000000.00,'2023-03-15','spv@plant.local','$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G',1,'2026-10-20 08:56:12','2026-10-21 09:41:33'),(3,'EMP003','Rian Hidayat','L',1,1,'OPERATOR',4500000.00,'2024-02-01','rian@plant.local','$2y$10$Y8SY5vH.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G',1,'2026-10-20 08:56:12','2026-10-20 08:56:12'),(4,'EMP004','Dewi Lestari','P',3,2,'OPERATOR',4600000.00,'2024-03-01','dewi@plant.local','\\.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G',1,'2026-10-21 09:41:48','2026-10-21 09:41:48'),(5,'EMP005','Fajar Pratama','L',2,3,'OPERATOR',4800000.00,'2023-11-15','fajar@plant.local','\\.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G',1,'2026-10-21 09:41:48','2026-10-21 09:41:48'),(6,'EMP006','Siti Rahmawati','P',4,1,'OPERATOR',4500000.00,'2024-01-10','siti@plant.local','\\.0P.moy7rNT2jR.8CEFwz1loI.yAdmdDSWLMxBECH.gZ6G',1,'2026-10-21 09:41:48','2026-10-21 09:41:48');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `overtime_requests`
--

DROP TABLE IF EXISTS `overtime_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `overtime_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `spl_number` varchar(30) NOT NULL,
  `employee_id` int(10) unsigned NOT NULL,
  `overtime_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL DEFAULT 0.00,
  `reason` text NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
  `approved_by` int(10) unsigned DEFAULT NULL,
  `rejection_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `spl_number` (`spl_number`),
  KEY `fk_ot_emp` (`employee_id`),
  KEY `fk_ot_approver` (`approved_by`),
  KEY `idx_ot_date` (`overtime_date`),
  KEY `idx_ot_status` (`status`),
  CONSTRAINT `fk_ot_approver` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_ot_emp` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `overtime_requests`
--

LOCK TABLES `overtime_requests` WRITE;
/*!40000 ALTER TABLE `overtime_requests` DISABLE KEYS */;
INSERT INTO `overtime_requests` VALUES (1,'SPL/202610/001',3,'2026-10-20','15:00:00','18:00:00',3.00,'Perbaikan mesin converting lini 2','APPROVED',2,NULL,'2026-10-20 09:49:41','2026-10-20 04:53:15'),(2,'SPL/202610/002',3,'2026-10-21','23:00:00','04:00:00',5.00,'Overhaul mesin boiler malam hari','REJECTED',2,'Kapasitas operator shift malam sudah mencukupi','2026-10-20 09:54:07','2026-10-20 04:54:38'),(3,'SPL/202610/003',3,'2026-10-22','16:00:00','19:00:00',3.00,'Pembersihan filter dust collector lini 1','APPROVED',2,NULL,'2026-10-20 09:55:12','2026-10-20 07:42:41'),(4,'SPL/202610/004',3,'2026-10-20','20:10:00','21:12:00',1.03,'ganti pak ramli qc','APPROVED',2,NULL,'2026-10-20 12:10:02','2026-10-20 07:42:38'),(5,'SPL/202610/005',4,'2026-10-21','23:00:00','02:00:00',3.00,'Sampling batch produk ekspor lot #889','APPROVED',2,NULL,'2026-10-21 09:42:53','2026-10-21 09:42:53'),(6,'SPL/202610/006',5,'2026-10-21','07:00:00','10:00:00',3.00,'Preventive maintenance conveyor line 4','APPROVED',2,NULL,'2026-10-21 09:42:53','2026-10-21 09:42:53'),(7,'SPL/202610/007',6,'2026-10-21','15:00:00','18:00:00',3.00,'Loading kontainer stuffing gudang timur','APPROVED',2,NULL,'2026-10-21 09:42:53','2026-10-21 09:42:53'),(8,'SPL/202610/008',3,'2026-10-21','17:00:00','20:00:00',3.00,'Pembersihan hopper dan penggantian bearing motor lini 1','APPROVED',2,NULL,'2026-10-21 10:28:05','2026-10-21 05:28:21'),(9,'SPL/202610/009',3,'2026-10-23','16:00:00','18:00:00',2.00,'mengganti oli silinder converting lini 2','APPROVED',2,NULL,'2026-10-22 04:20:09','2026-10-22 04:21:44');
/*!40000 ALTER TABLE `overtime_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_code` varchar(10) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_cross_day` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_code` (`shift_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES (1,'SH1','Shift 1 (Pagi)','07:00:00','15:00:00',0,'2026-10-20 08:56:12'),(2,'SH2','Shift 2 (Sore)','15:00:00','23:00:00',0,'2026-10-20 08:56:12'),(3,'SH3','Shift 3 (Malam)','23:00:00','07:00:00',1,'2026-10-20 08:56:12');
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-22 12:39:06
