-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: akademi_langgas_sinau
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'Jadwal Ujian Praktik Tengah Semester Genap 2026','Diberitahukan kepada seluruh siswa Akademi Langgas Sinau bahwa ujian praktik akan dilaksanakan pada tanggal 20 s/d 25 bulan ini. Harap seluruh siswa mempersiapkan portofolio mini project masing-masing dan memastikan tingkat kehadiran di atas 80%.\n\nJika ada kendala teknis atau perizinan, segera koordinasikan dengan bagian akademik.',NULL,'2026-09-06 04:42:29','published','2026-09-07 21:42:29','2026-09-07 21:42:29'),(2,'Workshop Eksklusif: Membangun Karir di Industri Digital & Freelance','Akademi Langgas Sinau menghadirkan praktisi industri digital dalam sesi mentoring \'Berdikari Mengenal Diri: Menembus Pasar Kerja Global\'. Workshop ini gratis untuk seluruh siswa aktif angkatan 2026 pada hari Sabtu mendatang pukul 09.00 WIB.',NULL,'2026-09-07 04:42:29','published','2026-09-07 21:42:29','2026-09-07 21:42:29'),(3,'Informasi Pemeliharaan Server Lab Komputer','Pemeliharaan rutin jaringan dan server lab akan dilaksanakan pada akhir pekan ini. Akses internet lab lokal sementara dinonaktifkan mulai Sabtu malam pukul 22.00 WIB.',NULL,'2026-09-08 04:42:29','published','2026-09-07 21:42:29','2026-09-07 21:42:29');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance_locations`
--

DROP TABLE IF EXISTS `attendance_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `plus_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `radius_meters` int NOT NULL DEFAULT '150',
  `strict_radius` tinyint(1) NOT NULL DEFAULT '1',
  `in_start` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '08:00',
  `in_on_time_end` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '09:30',
  `in_late_end` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '13:50',
  `out_start` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '14:00',
  `out_end` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '17:00',
  `auto_alpa_time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '17:00',
  `working_days` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_locations`
--

LOCK TABLES `attendance_locations` WRITE;
/*!40000 ALTER TABLE `attendance_locations` DISABLE KEYS */;
INSERT INTO `attendance_locations` VALUES (1,'LKP Langgas Sinau (Kampus Utama Banjar Rejo)','Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung','V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung',-5.1241746,105.3321494,30,1,'08:00','09:30','13:55','14:00','17:00','17:01','[1, 2, 3, 4, 5, 6, 0]',1,'Titik lokasi resmi presensi LKP Langgas Sinau berdasarkan Google Plus Code V8GJ+8W Banjar Rejo.','2026-09-09 02:35:46','2026-09-09 03:02:23');
/*!40000 ALTER TABLE `attendance_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned DEFAULT NULL,
  `attendance_location_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `check_in_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_lat` decimal(10,7) DEFAULT NULL,
  `check_in_lng` decimal(10,7) DEFAULT NULL,
  `check_in_distance` int DEFAULT NULL,
  `check_out_lat` decimal(10,7) DEFAULT NULL,
  `check_out_lng` decimal(10,7) DEFAULT NULL,
  `check_out_distance` int DEFAULT NULL,
  `status` enum('hadir','terlambat','izin','sakit','alpa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_student_id_foreign` (`student_id`),
  KEY `attendances_schedule_id_foreign` (`schedule_id`),
  KEY `attendances_attendance_location_id_foreign` (`attendance_location_id`),
  CONSTRAINT `attendances_attendance_location_id_foreign` FOREIGN KEY (`attendance_location_id`) REFERENCES `attendance_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendances_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mentor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leader_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issued_date` date NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `score_discipline` decimal(5,2) DEFAULT NULL,
  `score_initiative` decimal(5,2) DEFAULT NULL,
  `score_teamwork` decimal(5,2) DEFAULT NULL,
  `score_responsibility` decimal(5,2) DEFAULT NULL,
  `score_attitude` decimal(5,2) DEFAULT NULL,
  `score_attendance` decimal(5,2) DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `grade_predicate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assessment_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificates_certificate_number_unique` (`certificate_number`),
  KEY `certificates_student_id_foreign` (`student_id`),
  KEY `certificates_is_published_index` (`is_published`),
  CONSTRAINT `certificates_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'Web Development Fullstack','Teknologi Informasi','Kelas intensif pembuatan aplikasi web modern menggunakan Laravel, Tailwind, dan MySQL.','aktif','2026-09-07 21:42:24','2026-09-07 21:42:24'),(2,'Desain Grafis & UI/UX','Desain Komunikasi Visual','Kelas perancangan visual, user experience, branding, dan prototyping antarmuka.','aktif','2026-09-07 21:42:24','2026-09-07 21:42:24'),(3,'Administrasi Perkantoran & Digital Office','Bisnis & Manajemen','Kelas keahlian tata kelola dokumen modern, spreadsheet tingkat lanjut, dan pembukuan.','aktif','2026-09-07 21:42:24','2026-09-07 21:42:24'),(4,'Prakerin Web Developer','Rekayasa Perangkat Lunak',NULL,'aktif','2026-09-08 05:47:36','2026-09-08 05:47:36'),(5,'Prakerin - Coding dan Pemrograman Web','Rekayasa Perangkat Lunak',NULL,'aktif','2026-09-09 03:11:26','2026-09-09 03:11:26');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grades_student_id_foreign` (`student_id`),
  KEY `grades_class_id_foreign` (`class_id`),
  CONSTRAINT `grades_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grades`
--

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;
/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_09_08_020421_create_classes_table',1),(5,'2026_09_08_020423_create_students_table',1),(6,'2026_09_08_020424_create_schedules_table',1),(7,'2026_09_08_020425_create_attendances_table',1),(8,'2026_09_08_020426_create_permissions_table',1),(9,'2026_09_08_020427_create_grades_table',1),(10,'2026_09_08_020429_create_certificates_table',1),(11,'2026_09_08_020430_create_announcements_table',1),(12,'2026_09_08_043658_add_selfie_and_coords_to_attendances_table',1),(13,'2026_09_08_044656_add_terlambat_to_attendances_status_enum',2),(14,'2026_09_08_120702_add_school_origin_to_students_table',3),(15,'2026_09_08_121711_add_grades_and_published_to_certificates_table',4),(16,'2026_09_08_125725_add_mentor_name_to_certificates_table',5),(17,'2026_09_08_130620_add_leader_name_to_certificates_table',6),(18,'2026_09_09_030000_create_attendance_locations_table',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_student_id_foreign` (`student_id`),
  CONSTRAINT `permissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `room` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_class_id_foreign` (`class_id`),
  CONSTRAINT `schedules_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES (1,1,'Pengenalan HTML5 & CSS3 Modern','2026-08-25','09:00:00','12:00:00','Lab Komputer 1','Dasar sintaks HTML5 semantik dan styling CSS modern.','2026-09-07 21:42:29','2026-09-07 21:42:29'),(2,1,'Dasar PHP Modern & OOP','2026-09-01','09:00:00','12:00:00','Lab Komputer 1','Pemrograman berbasis objek dan modularitas PHP.','2026-09-07 21:42:29','2026-09-07 21:42:29'),(3,1,'Framework Laravel 12 & MVC Architecture','2026-09-08','09:00:00','12:00:00','Lab Komputer 1','Routing, Controller, Model Eloquent, dan Blade Templating.','2026-09-07 21:42:29','2026-09-07 21:42:29'),(4,1,'Integrasi Tailwind CSS & Alpine.js','2026-09-15','09:00:00','12:00:00','Lab Komputer 1','Desain interaktif responsif tanpa framework berat.','2026-09-07 21:42:29','2026-09-07 21:42:29'),(5,2,'Fundamental Typography & Color Harmony','2026-09-03','13:00:00','16:00:00','Studio Desain A','Memahami komposisi visual, psikologi warna, dan hierarki tipografi.','2026-09-07 21:42:29','2026-09-07 21:42:29'),(6,2,'Figma Prototyping & Design System','2026-09-08','13:00:00','16:00:00','Studio Desain A','Membuat wireframe, komponen UI interaktif dan prototype.','2026-09-07 21:42:29','2026-09-07 21:42:29'),(7,3,'Automasi Spreadsheet & Formula Finansial','2026-09-08','08:30:00','11:30:00','Ruang Teori B','Kombinasi VLOOKUP, XLOOKUP, Pivot Table, dan Dashboard ringkas.','2026-09-07 21:42:29','2026-09-07 21:42:29');
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('WabHVx6oKM8bBgntYM9otcmwPYPWrg7dl4nrdoSm',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJUZzlseW9hUkQwVllCb3FZZzhGakhsWjlPMFpDbmhYTlFaTHA2cnZsIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1788924122),('Yqk3gIMKHh9DDE4xRlFjMCP12OLrHfwQckw5u23G',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.136.1 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36','eyJfdG9rZW4iOiJSN05zU2F0S3ZlWDNrOEt0OU9vaTA5cVBNUEFmNkVrWWJ0cFJwTWZJIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1788923143);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned DEFAULT NULL,
  `student_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_number_unique` (`student_number`),
  KEY `students_user_id_foreign` (`user_id`),
  KEY `students_class_id_foreign` (`class_id`),
  CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (12,13,5,'LS-2026-001',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:11:27','2026-09-09 03:11:27'),(13,14,5,'LS-2026-002',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:12:31','2026-09-09 03:12:31'),(14,15,5,'LS-2026-003',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:13:23','2026-09-09 03:13:23'),(15,16,5,'LS-2026-004',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:14:27','2026-09-09 03:14:27'),(16,17,5,'LS-2026-005',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:15:46','2026-09-09 03:15:46'),(17,18,5,'LS-2026-006',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:16:41','2026-09-09 03:16:41'),(18,19,5,'LS-2026-007',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:17:38','2026-09-09 03:17:38'),(19,20,5,'LS-2026-008',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:18:38','2026-09-09 03:18:38'),(20,21,5,'LS-2026-009',NULL,NULL,NULL,'Rekayasa Perangkat Lunak','SMK MA\'ARIF NU 6 SEKAMPUNG','2026-07-20','aktif','2026-09-09 03:19:38','2026-09-09 03:19:38');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator Langgas','admin@langgas-sinau.com',NULL,'$2y$12$BUvIkY0MLgczOJUYEcqAzOlhY0zGBYzX8l6fm8ja3ln7fPg758KuO','admin',NULL,'2026-09-07 21:42:24','2026-09-07 21:42:24'),(13,'Alfi Rihadatul Aisya','Alfi@langgas.com',NULL,'$2y$12$tGkep6Tz6r1LbNrN3BnYRueegd8CLoIt.4SSbp45pZXm9Ef1bKTSK','siswa',NULL,'2026-09-09 03:11:27','2026-09-09 03:11:27'),(14,'David Rivaldo','David@langgas.com',NULL,'$2y$12$pJ6mUV1P.3ssfoixyK.Rs.R7YeBUyP3T7PAPBv6fpcdfnTTWeo4Q2','siswa',NULL,'2026-09-09 03:12:31','2026-09-09 03:12:31'),(15,'Dino Candra Reskya','Dino@langgas.com',NULL,'$2y$12$GkAG5sB5moe1MNwEkueNselU/KPvyVd3.xtt0XIO/S1qu7osHoDWG','siswa',NULL,'2026-09-09 03:13:23','2026-09-09 03:13:42'),(16,'Dhiaz Novian Ivana','Dhiaz@langgas.com',NULL,'$2y$12$s1a0U2aE6pW.cK8H26CEWO3y.9cXA46110fqKYsJl6sUJOaNS1Qby','siswa',NULL,'2026-09-09 03:14:27','2026-09-09 03:14:27'),(17,'Kharisma Warassantika','Kharisma@langgas.com',NULL,'$2y$12$IFoxNT69RpeZK37l5Tfp8ON9bBg8ZQcTj.WRB/aiLfuzabCtH48kC','siswa',NULL,'2026-09-09 03:15:46','2026-09-09 03:15:46'),(18,'Lusy Ana Sari','Lusy@langgas.com',NULL,'$2y$12$PaDRFf9veExrkZI9BMPKzOWlaFJdwbCRjm1ox0YZ4i0IhIyTOWQtK','siswa',NULL,'2026-09-09 03:16:41','2026-09-09 03:16:41'),(19,'Mibakhul Munir','Munir@langgas.com',NULL,'$2y$12$S.Vr5/4J6rw0ZlBYnsZCn.5G72kRxBSUikdymaCwLRGSrKdr8.Xvi','siswa',NULL,'2026-09-09 03:17:38','2026-09-09 03:17:38'),(20,'Novia Ardianti','Novia@langgas.com',NULL,'$2y$12$ynqaEBRZX1XwZ0Ux97dIaONzHUsyIwyLzELQkj7D64wvxpDnhNNpO','siswa',NULL,'2026-09-09 03:18:37','2026-09-09 03:18:37'),(21,'Syifa Zahratussita','Syifa@langgas.com',NULL,'$2y$12$9QpuqSznZEL8IO4QwHea4u2iYQKo2/pxYDLOctM4Es6GJyO8md3jO','siswa',NULL,'2026-09-09 03:19:38','2026-09-09 03:19:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-09 10:25:10
