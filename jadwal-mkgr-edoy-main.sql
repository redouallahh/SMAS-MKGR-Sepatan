-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: db_jadwal_mkgr
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
SET UNIQUE_CHECKS = 0;
SET FOREIGN_KEY_CHECKS = 0;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- ========================================================
-- DROP TABLES (Child tables dropped first to prevent dependency issues)
-- ========================================================
DROP TABLE IF EXISTS `jadwal`;
DROP TABLE IF EXISTS `guru`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `mapel`;
DROP TABLE IF EXISTS `jam_pelajaran`;
DROP TABLE IF EXISTS `ruang_kelas`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `kepalasekolah`;

-- ========================================================
-- CREATE TABLES & INSERT DATA
-- ========================================================

--
-- Table structure for table `guru`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `nip` char(18) DEFAULT NULL,
  `nama_guru` varchar(60) NOT NULL,
  `kontak` varchar(15) DEFAULT NULL,
  `status_tugas` varchar(30) DEFAULT 'Aktif Mengajar',
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru`
--
LOCK TABLES `guru` WRITE;
/*!40000 ALTER TABLE `guru` DISABLE KEYS */;
INSERT INTO `guru` VALUES (2,NULL,'13214234','edo','02938492038','Cuti','');
/*!40000 ALTER TABLE `guru` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `kelas`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kepalasekolah` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama_kepsek` varchar(100) NOT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `status_tugas` varchar(30) DEFAULT 'Aktif Mengajar',
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kepalasekolah`
--
LOCK TABLES `kepalasekolah` WRITE;
/*!40000 ALTER TABLE `kepalasekolah` DISABLE KEYS */;
INSERT INTO `kepalasekolah` VALUES (1, 3, '198001012005011001', 'Drs. Ahmad Subarjo, M.Pd.', '081234567890', 'Aktif Mengajar', NULL);
/*!40000 ALTER TABLE `kepalasekolah` ENABLE KEYS */;
UNLOCK TABLES;

CREATE TABLE `kelas` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) NOT NULL,
  `tingkat` varchar(5) DEFAULT NULL,
  `wali_kelas` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_kelas` (`nama_kelas`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--
LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (3,'MIPA 2',NULL,NULL);
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `mapel`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mapel` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(15) DEFAULT NULL,
  `nama_mapel` varchar(50) NOT NULL,
  `kelompok` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mapel`
--
LOCK TABLES `mapel` WRITE;
/*!40000 ALTER TABLE `mapel` DISABLE KEYS */;
INSERT INTO `mapel` VALUES (3,'MTK-1','Matematika','Wajib');
/*!40000 ALTER TABLE `mapel` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jadwal`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `jam_ke` tinyint NOT NULL,
  `id_guru` smallint DEFAULT NULL,
  `id_kelas` smallint DEFAULT NULL,
  `id_mapel` smallint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_guru` (`id_guru`),
  KEY `id_kelas` (`id_kelas`),
  KEY `id_mapel` (`id_mapel`),
  CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`id_mapel`) REFERENCES `mapel` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--
LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (6,'Senin',1,2,3,3),(7,'Senin',2,2,3,3);
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jam_pelajaran`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jam_pelajaran` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `jam_ke` tinyint NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jam_pelajaran`
--
LOCK TABLES `jam_pelajaran` WRITE;
/*!40000 ALTER TABLE `jam_pelajaran` DISABLE KEYS */;
INSERT INTO `jam_pelajaran` VALUES (1,1,'07:15:00','08:00:00'),(2,2,'08:00:00','08:45:00'),(3,3,'08:45:00','09:30:00');
/*!40000 ALTER TABLE `jam_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `ruang_kelas`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruang_kelas` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `nama_ruangan` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_ruangan` (`nama_ruangan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruang_kelas`
--
LOCK TABLES `ruang_kelas` WRITE;
/*!40000 ALTER TABLE `ruang_kelas` DISABLE KEYS */;
INSERT INTO `ruang_kelas` VALUES (3,'Lab Komputer'),(1,'Ruang 01'),(2,'Ruang 02');
/*!40000 ALTER TABLE `ruang_kelas` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `user`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` char(32) NOT NULL,
  `pertanyaan_keamanan` varchar(150) DEFAULT NULL,
  `jawaban_keamanan` varchar(100) DEFAULT NULL,
  `role` enum('admin','guru','kepala_sekolah') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--
LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','0192023a7bbd73250516f069df18b500','Siapa nama hewan peliharaan pertama Anda?','kucing','admin'),(2,'guru1','77e69623d386c91e1c34a2e5d79633c7','Siapa nama hewan peliharaan pertama Anda?','kucing','guru'),(3,'kepsek1','f1839074d2719277d3f8bc9447472096','Siapa nama hewan peliharaan pertama Anda?','kucing','kepala_sekolah');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `users`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` tinyint NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` char(32) NOT NULL,
  `nama_lengkap` varchar(60) NOT NULL,
  `role` enum('admin','guru','kepala_sekolah') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-21 13:17:48
