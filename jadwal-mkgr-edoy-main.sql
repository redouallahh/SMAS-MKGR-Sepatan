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
  `kode_guru` varchar(10) DEFAULT NULL,
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
INSERT INTO `guru` VALUES 
(1,NULL,'01',NULL,'SITI KHUSNUL K , S.Pd.I',NULL,'Aktif Mengajar',NULL),
(2,NULL,'02',NULL,'ROHMAT, SH.I',NULL,'Aktif Mengajar',NULL),
(3,NULL,'03',NULL,'KUROTUL ARDIYAH, S.Sos',NULL,'Aktif Mengajar',NULL),
(4,NULL,'04',NULL,'MARLINA, M.Pd',NULL,'Aktif Mengajar',NULL),
(5,NULL,'05',NULL,'FENTI POVIANTI L, S.Pd',NULL,'Aktif Mengajar',NULL),
(6,NULL,'06',NULL,'SYAEFUL BAHRI, S.Pd.I',NULL,'Aktif Mengajar',NULL),
(7,NULL,'07',NULL,'Ir. HERKY PURBAYA',NULL,'Aktif Mengajar',NULL),
(8,NULL,'08',NULL,'SUPARMAN, S.Pd',NULL,'Aktif Mengajar',NULL),
(9,NULL,'09',NULL,'SITI JUMRIAH, S.Pd',NULL,'Aktif Mengajar',NULL),
(10,NULL,'10',NULL,'WINDRA KURNIAWATI, S.Pd',NULL,'Aktif Mengajar',NULL),
(11,NULL,'11',NULL,'MUHAMAD SOLEH, S.Pd',NULL,'Aktif Mengajar',NULL),
(12,NULL,'12',NULL,'SITI TOYIBAH, S.Kom',NULL,'Aktif Mengajar',NULL),
(13,NULL,'13',NULL,'Dra. SAINEM',NULL,'Aktif Mengajar',NULL),
(14,NULL,'14',NULL,'PONIDI, S.Pd',NULL,'Aktif Mengajar',NULL),
(15,NULL,'15',NULL,'MUHAMAD IDZ AWAY FASHLY, S.Kom',NULL,'Aktif Mengajar',NULL),
(16,NULL,'16',NULL,'PUPUT PUSPITASARI, S.Pd',NULL,'Aktif Mengajar',NULL),
(17,NULL,'17',NULL,'DIDIET ADITIA IBRAHIM, S.Pd',NULL,'Aktif Mengajar',NULL),
(18,NULL,'18',NULL,'NUR NENGSIH, S.Pd',NULL,'Aktif Mengajar',NULL),
(19,NULL,'19',NULL,'YANTI SATYA, M.Psi',NULL,'Aktif Mengajar',NULL),
(20,NULL,'20',NULL,'SYAMSUL ANWAR, S.Pd',NULL,'Aktif Mengajar',NULL),
(21,NULL,'21',NULL,'DHENISYAH FRISANDINI',NULL,'Aktif Mengajar',NULL),
(22,NULL,'22',NULL,'ELISA',NULL,'Aktif Mengajar',NULL),
(23,NULL,'23',NULL,'DELYA PUSPITA DEWI',NULL,'Aktif Mengajar',NULL),
(24,NULL,'24',NULL,'SYAHRU ROMDONI, S.Pd',NULL,'Aktif Mengajar',NULL),
(25,NULL,'25',NULL,'NURHASANUDIN, S.Pd',NULL,'Aktif Mengajar',NULL);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kepalasekolah`
--
LOCK TABLES `kepalasekolah` WRITE;
/*!40000 ALTER TABLE `kepalasekolah` DISABLE KEYS */;
INSERT INTO `kepalasekolah` VALUES (1, 3, '198001012005011001', 'Drs. Ahmad Subarjo, M.Pd.', '081234567890');
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
INSERT INTO `kelas` VALUES 
(1,'10.1',NULL,NULL),
(2,'10.2',NULL,NULL),
(3,'10.3',NULL,NULL),
(4,'10.4',NULL,NULL),
(5,'10.5',NULL,NULL),
(6,'10.6',NULL,NULL),
(7,'11.F1',NULL,NULL),
(8,'11.F2',NULL,NULL),
(9,'11.F3',NULL,NULL),
(10,'11.F4',NULL,NULL),
(11,'11.F5',NULL,NULL),
(12,'12.A1',NULL,NULL),
(13,'12.A2',NULL,NULL),
(14,'12.S1',NULL,NULL),
(15,'12.S2',NULL,NULL),
(16,'12.S3',NULL,NULL);
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mapel`
--
LOCK TABLES `mapel` WRITE;
/*!40000 ALTER TABLE `mapel` DISABLE KEYS */;
INSERT INTO `mapel` VALUES 
(1,'A','Pendidikan Agama Islam dan Budi Pekerti'),
(2,'B','Pendidikan Pancasila'),
(3,'C','Bahasa Indonesia'),
(4,'D','Matematika (Umum)'),
(5,'E','Bahasa Inggris'),
(6,'F','Pendidikan Jasmani, Olahraga, dan Kesehatan'),
(7,'G','Sejarah'),
(8,'H','Sejarah Indo'),
(9,'I','Senibudaya'),
(10,'J','Seni Rupa/Seni Musik/Seni Tari'),
(11,'K','Biologi'),
(12,'L','Fisika'),
(13,'M','Matematika Lanjutan'),
(14,'N','Kimia'),
(15,'O','Informatika'),
(16,'P','Sosiologi'),
(17,'Q','Prakarya'),
(18,'R','Ekonomi'),
(19,'S','Geografi'),
(20,'T','Sejarah Lanjutan'),
(21,'U','mulok/ Tari/Musik'),
(22,'V','Bimbingan Konseling');
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
INSERT INTO `jadwal` VALUES 
(1,'Kamis','1',24,1,6),
(2,'Kamis','1',11,2,4),
(3,'Kamis','1',2,3,2),
(4,'Kamis','1',16,4,3),
(5,'Kamis','1',12,5,15),
(6,'Kamis','1',14,6,19),
(7,'Kamis','1',4,7,3),
(8,'Kamis','1',18,8,4),
(9,'Kamis','1',22,9,11),
(10,'Kamis','1',20,10,22),
(11,'Kamis','1',13,11,7),
(12,'Kamis','1',25,12,4),
(13,'Kamis','1',8,13,14),
(14,'Kamis','1',1,14,1),
(15,'Kamis','1',3,15,16),
(16,'Kamis','1',10,16,18),
(17,'Kamis','2',24,1,6),
(18,'Kamis','2',11,2,4),
(19,'Kamis','2',2,3,2),
(20,'Kamis','2',16,4,3),
(21,'Kamis','2',12,5,15),
(22,'Kamis','2',14,6,19),
(23,'Kamis','2',18,7,4),
(24,'Kamis','2',4,8,3),
(25,'Kamis','2',22,9,11),
(26,'Kamis','2',20,10,22),
(27,'Kamis','2',13,11,7),
(28,'Kamis','2',8,12,14),
(29,'Kamis','2',25,13,4),
(30,'Kamis','2',10,14,18),
(31,'Kamis','2',1,15,1),
(32,'Kamis','2',3,16,16),
(33,'Kamis','3',3,1,16),
(34,'Kamis','3',16,2,3),
(35,'Kamis','3',9,3,18),
(36,'Kamis','3',4,4,5),
(37,'Kamis','3',8,5,14),
(38,'Kamis','3',12,6,15),
(39,'Kamis','3',10,7,18),
(40,'Kamis','3',2,8,2),
(41,'Kamis','3',13,9,7),
(42,'Kamis','3',22,10,11),
(43,'Kamis','3',17,11,6),
(44,'Kamis','3',18,12,13),
(45,'Kamis','3',7,13,12),
(46,'Kamis','3',24,14,6),
(47,'Kamis','3',14,15,19),
(48,'Kamis','3',11,16,4),
(49,'Kamis','4',3,1,16),
(50,'Kamis','4',16,2,3),
(51,'Kamis','4',9,3,18),
(52,'Kamis','4',4,4,5),
(53,'Kamis','4',8,5,14),
(54,'Kamis','4',12,6,15),
(55,'Kamis','4',10,7,18),
(56,'Kamis','4',2,8,2),
(57,'Kamis','4',13,9,7),
(58,'Kamis','4',22,10,11),
(59,'Kamis','4',17,11,6),
(60,'Kamis','4',1,12,1),
(61,'Kamis','4',7,13,12),
(62,'Kamis','4',24,14,6),
(63,'Kamis','4',23,15,20),
(64,'Kamis','4',11,16,4),
(65,'Kamis','5',12,1,15),
(66,'Kamis','5',7,2,12),
(67,'Kamis','5',8,3,14),
(68,'Kamis','5',2,4,2),
(69,'Kamis','5',11,5,4),
(70,'Kamis','5',16,6,3),
(71,'Kamis','5',5,7,10),
(72,'Kamis','5',13,8,7),
(73,'Kamis','5',10,9,18),
(74,'Kamis','5',18,10,4),
(75,'Kamis','5',9,11,3),
(76,'Kamis','5',22,12,11),
(77,'Kamis','5',1,13,1),
(78,'Kamis','5',4,14,5),
(79,'Kamis','5',24,15,6),
(80,'Kamis','5',23,16,20),
(81,'Kamis','6',12,1,15),
(82,'Kamis','6',7,2,12),
(83,'Kamis','6',8,3,14),
(84,'Kamis','6',2,4,2),
(85,'Kamis','6',11,5,4),
(86,'Kamis','6',16,6,3),
(87,'Kamis','6',5,7,10),
(88,'Kamis','6',13,8,7),
(89,'Kamis','6',10,9,18),
(90,'Kamis','6',9,10,3),
(91,'Kamis','6',18,11,4),
(92,'Kamis','6',22,12,11),
(93,'Kamis','6',1,13,1),
(94,'Kamis','6',4,14,5),
(95,'Kamis','6',24,15,6),
(96,'Kamis','6',23,16,20),
(97,'Kamis','7',19,1,22),
(98,'Kamis','7',8,2,14),
(99,'Kamis','7',17,3,21),
(100,'Kamis','7',11,4,4),
(101,'Kamis','7',16,5,3),
(102,'Kamis','7',9,6,18),
(103,'Kamis','7',7,7,12),
(104,'Kamis','7',20,8,22),
(105,'Kamis','7',2,9,2),
(106,'Kamis','7',1,10,1),
(107,'Kamis','7',18,11,4),
(108,'Kamis','7',24,12,6),
(109,'Kamis','7',22,13,11),
(110,'Kamis','7',23,14,20),
(111,'Kamis','7',5,15,9),
(112,'Kamis','7',12,16,17),
(113,'Kamis','8',19,1,22),
(114,'Kamis','8',8,2,14),
(115,'Kamis','8',17,3,21),
(116,'Kamis','8',11,4,4),
(117,'Kamis','8',16,5,3),
(118,'Kamis','8',9,6,18),
(119,'Kamis','8',7,7,12),
(120,'Kamis','8',20,8,22),
(121,'Kamis','8',2,9,2),
(122,'Kamis','8',1,10,1),
(123,'Kamis','8',18,11,4),
(124,'Kamis','8',4,12,3),
(125,'Kamis','8',24,13,6),
(126,'Kamis','8',23,14,20),
(127,'Kamis','8',5,15,9),
(128,'Kamis','8',12,16,17),
(129,'Jumat','2',11,1,4),
(130,'Jumat','2',4,2,5),
(131,'Jumat','2',16,3,3),
(132,'Jumat','2',9,4,18),
(133,'Jumat','2',24,5,6),
(134,'Jumat','2',13,6,7),
(135,'Jumat','2',17,7,5),
(136,'Jumat','2',21,8,11),
(137,'Jumat','2',22,9,11),
(138,'Jumat','2',18,10,4),
(139,'Jumat','2',15,11,15),
(140,'Jumat','2',8,12,14),
(141,'Jumat','2',2,13,2),
(142,'Jumat','2',3,14,16),
(143,'Jumat','2',23,15,20),
(144,'Jumat','2',14,16,19),
(145,'Jumat','3',11,1,4),
(146,'Jumat','3',4,2,5),
(147,'Jumat','3',16,3,3),
(148,'Jumat','3',9,4,18),
(149,'Jumat','3',24,5,6),
(150,'Jumat','3',13,6,7),
(151,'Jumat','3',17,7,5),
(152,'Jumat','3',21,8,11),
(153,'Jumat','3',22,9,11),
(154,'Jumat','3',18,10,4),
(155,'Jumat','3',15,11,15),
(156,'Jumat','3',8,12,14),
(157,'Jumat','3',2,13,2),
(158,'Jumat','3',14,14,19),
(159,'Jumat','3',23,15,20),
(160,'Jumat','3',1,16,1),
(161,'Jumat','4',2,1,2),
(162,'Jumat','4',21,2,11),
(163,'Jumat','4',3,3,16),
(164,'Jumat','4',24,4,6),
(165,'Jumat','4',7,5,12),
(166,'Jumat','4',6,6,1),
(167,'Jumat','4',20,7,22),
(168,'Jumat','4',17,8,5),
(169,'Jumat','4',14,9,19),
(170,'Jumat','4',15,10,15),
(171,'Jumat','4',10,11,18),
(172,'Jumat','4',13,12,8),
(173,'Jumat','4',4,13,3),
(174,'Jumat','4',23,14,20),
(175,'Jumat','4',1,15,1),
(176,'Jumat','4',11,16,4),
(177,'Jumat','5',2,1,2),
(178,'Jumat','5',21,2,11),
(179,'Jumat','5',3,3,16),
(180,'Jumat','5',24,4,6),
(181,'Jumat','5',7,5,12),
(182,'Jumat','5',6,6,1),
(183,'Jumat','5',20,7,22),
(184,'Jumat','5',17,8,5),
(185,'Jumat','5',14,9,19),
(186,'Jumat','5',15,10,15),
(187,'Jumat','5',10,11,18),
(188,'Jumat','5',13,12,8),
(189,'Jumat','5',18,13,13),
(190,'Jumat','5',11,14,4),
(191,'Jumat','5',1,15,1),
(192,'Jumat','5',23,16,20),
(193,'Jumat','6',4,1,5),
(194,'Jumat','6',2,2,2),
(195,'Jumat','6',13,3,7),
(196,'Jumat','6',14,4,19),
(197,'Jumat','6',17,5,21),
(198,'Jumat','6',24,6,6),
(199,'Jumat','6',21,7,11),
(200,'Jumat','6',7,8,12),
(201,'Jumat','6',3,9,16),
(202,'Jumat','6',5,10,10),
(203,'Jumat','6',22,11,11),
(204,'Jumat','6',19,12,22),
(205,'Jumat','6',18,13,13),
(206,'Jumat','6',15,14,15),
(207,'Jumat','6',11,15,4),
(208,'Jumat','6',20,16,22),
(209,'Jumat','7',4,1,5),
(210,'Jumat','7',2,2,2),
(211,'Jumat','7',13,3,7),
(212,'Jumat','7',14,4,19),
(213,'Jumat','7',17,5,21),
(214,'Jumat','7',24,6,6),
(215,'Jumat','7',21,7,11),
(216,'Jumat','7',7,8,12),
(217,'Jumat','7',3,9,16),
(218,'Jumat','7',5,10,10),
(219,'Jumat','7',22,11,11),
(220,'Jumat','7',19,12,22),
(221,'Jumat','7',18,13,13),
(222,'Jumat','7',15,14,15),
(223,'Jumat','7',10,15,18),
(224,'Jumat','7',20,16,22),
(225,'Senin','1',11,1,2),
(226,'Senin','1',6,2,10),
(227,'Senin','1',10,3,6),
(228,'Senin','1',16,4,16),
(229,'Senin','1',7,5,22),
(230,'Senin','1',7,6,18),
(231,'Senin','1',20,7,7),
(232,'Senin','1',8,8,4),
(233,'Senin','1',1,9,21),
(234,'Senin','1',13,10,10),
(235,'Senin','1',5,11,9),
(236,'Senin','1',5,12,21),
(237,'Senin','1',15,13,7),
(238,'Senin','1',1,14,21),
(239,'Senin','1',3,15,3),
(240,'Senin','1',18,16,2),
(241,'Senin','2',16,1,13),
(242,'Senin','2',12,2,22),
(243,'Senin','2',14,3,6),
(244,'Senin','2',17,4,6),
(245,'Senin','2',17,5,12),
(246,'Senin','2',4,6,14),
(247,'Senin','2',15,7,3),
(248,'Senin','2',9,8,12),
(249,'Senin','2',18,9,18),
(250,'Senin','2',9,10,20),
(251,'Senin','2',21,11,9),
(252,'Senin','2',8,12,2),
(253,'Senin','2',1,13,17),
(254,'Senin','2',2,14,3),
(255,'Senin','2',25,15,21),
(256,'Senin','2',10,16,3),
(257,'Senin','3',1,1,18),
(258,'Senin','3',23,2,3),
(259,'Senin','3',21,3,6),
(260,'Senin','3',11,4,6),
(261,'Senin','3',22,5,5),
(262,'Senin','3',15,6,16),
(263,'Senin','3',19,7,21),
(264,'Senin','3',18,8,19),
(265,'Senin','3',19,9,5),
(266,'Senin','3',18,10,21),
(267,'Senin','3',12,11,6),
(268,'Senin','3',22,12,22),
(269,'Senin','3',12,13,3),
(270,'Senin','3',8,14,22),
(271,'Senin','3',17,15,8),
(272,'Senin','3',23,16,1),
(273,'Senin','4',4,1,13),
(274,'Senin','4',20,2,22),
(275,'Senin','4',25,3,10),
(276,'Senin','4',8,4,6),
(277,'Senin','4',12,5,1),
(278,'Senin','4',12,6,2),
(279,'Senin','4',20,7,4),
(280,'Senin','4',12,8,3),
(281,'Senin','4',21,9,15),
(282,'Senin','4',13,10,4),
(283,'Senin','4',4,11,4),
(284,'Senin','4',4,12,19),
(285,'Senin','4',7,13,7),
(286,'Senin','4',15,14,13),
(287,'Senin','4',18,15,9),
(288,'Senin','4',2,16,5),
(289,'Senin','5',7,1,10),
(290,'Senin','5',19,2,22),
(291,'Senin','5',18,3,19),
(292,'Senin','5',19,4,15),
(293,'Senin','5',25,5,6),
(294,'Senin','5',24,6,17),
(295,'Senin','5',14,7,7),
(296,'Senin','5',3,8,14),
(297,'Senin','5',20,9,16),
(298,'Senin','5',18,10,5),
(299,'Senin','5',2,11,12),
(300,'Senin','5',19,12,14),
(301,'Senin','5',4,13,16),
(302,'Senin','5',15,14,12),
(303,'Senin','5',23,15,11),
(304,'Senin','5',11,16,18),
(305,'Senin','6',5,1,4),
(306,'Senin','6',2,2,3),
(307,'Senin','6',18,3,19),
(308,'Senin','6',19,4,8),
(309,'Senin','6',20,5,12),
(310,'Senin','6',2,6,17),
(311,'Senin','6',17,7,12),
(312,'Senin','6',25,8,10),
(313,'Senin','6',15,9,15),
(314,'Senin','6',24,10,14),
(315,'Senin','6',1,11,9),
(316,'Senin','6',6,12,8),
(317,'Senin','6',21,13,20),
(318,'Senin','6',14,14,9),
(319,'Senin','6',16,15,19),
(320,'Senin','6',8,16,15),
(321,'Senin','7',19,1,20),
(322,'Senin','7',14,2,14),
(323,'Senin','7',14,3,19),
(324,'Senin','7',8,4,9),
(325,'Senin','7',17,5,17),
(326,'Senin','7',11,6,18),
(327,'Senin','7',23,7,22),
(328,'Senin','7',9,8,11),
(329,'Senin','7',18,9,16),
(330,'Senin','7',7,10,19),
(331,'Senin','7',24,11,2),
(332,'Senin','7',1,12,11),
(333,'Senin','7',11,13,22),
(334,'Senin','7',4,14,9),
(335,'Senin','7',14,15,21),
(336,'Senin','7',9,16,4),
(337,'Senin','8',6,1,7),
(338,'Senin','8',5,2,20),
(339,'Senin','8',1,3,8),
(340,'Senin','8',3,4,8),
(341,'Senin','8',2,5,2),
(342,'Senin','8',19,6,17),
(343,'Senin','8',5,7,7),
(344,'Senin','8',2,8,11),
(345,'Senin','8',6,9,2),
(346,'Senin','8',13,10,8),
(347,'Senin','8',19,11,1),
(348,'Senin','8',19,12,13),
(349,'Senin','8',14,13,13),
(350,'Senin','8',2,14,17),
(351,'Senin','8',8,15,10),
(352,'Senin','8',17,16,9),
(353,'Selasa','1',17,1,18),
(354,'Selasa','1',5,2,2),
(355,'Selasa','1',16,3,1),
(356,'Selasa','1',8,4,19),
(357,'Selasa','1',23,5,11),
(358,'Selasa','1',14,6,12),
(359,'Selasa','1',8,7,12),
(360,'Selasa','1',9,8,8),
(361,'Selasa','1',20,9,3),
(362,'Selasa','1',4,10,17),
(363,'Selasa','1',3,11,7),
(364,'Selasa','1',3,12,3),
(365,'Selasa','1',11,13,20),
(366,'Selasa','1',5,14,5),
(367,'Selasa','1',2,15,4),
(368,'Selasa','1',5,16,2),
(369,'Selasa','2',20,1,17),
(370,'Selasa','2',2,2,5),
(371,'Selasa','2',20,3,4),
(372,'Selasa','2',21,4,4),
(373,'Selasa','2',8,5,12),
(374,'Selasa','2',7,6,15),
(375,'Selasa','2',11,7,21),
(376,'Selasa','2',16,8,19),
(377,'Selasa','2',10,9,1),
(378,'Selasa','2',4,10,11),
(379,'Selasa','2',14,11,13),
(380,'Selasa','2',19,12,22),
(381,'Selasa','2',7,13,8),
(382,'Selasa','2',3,14,10),
(383,'Selasa','2',6,15,15),
(384,'Selasa','2',18,16,15),
(385,'Selasa','3',17,1,22),
(386,'Selasa','3',17,2,3),
(387,'Selasa','3',2,3,7),
(388,'Selasa','3',15,4,9),
(389,'Selasa','3',11,5,14),
(390,'Selasa','3',21,6,11),
(391,'Selasa','3',19,7,19),
(392,'Selasa','3',22,8,20),
(393,'Selasa','3',18,9,15),
(394,'Selasa','3',3,10,21),
(395,'Selasa','3',12,11,22),
(396,'Selasa','3',6,12,1),
(397,'Selasa','3',17,13,11),
(398,'Selasa','3',2,14,6),
(399,'Selasa','3',16,15,6),
(400,'Selasa','3',22,16,18),
(401,'Selasa','4',20,1,19),
(402,'Selasa','4',17,2,18),
(403,'Selasa','4',1,3,5),
(404,'Selasa','4',15,4,11),
(405,'Selasa','4',18,5,18),
(406,'Selasa','4',16,6,15),
(407,'Selasa','4',2,7,14),
(408,'Selasa','4',3,8,5),
(409,'Selasa','4',16,9,9),
(410,'Selasa','4',23,10,13),
(411,'Selasa','4',5,11,16),
(412,'Selasa','4',15,12,22),
(413,'Selasa','4',25,13,22),
(414,'Selasa','4',4,14,22),
(415,'Selasa','4',22,15,15),
(416,'Selasa','4',16,16,22),
(417,'Selasa','5',14,1,5),
(418,'Selasa','5',21,2,6),
(419,'Selasa','5',19,3,7),
(420,'Selasa','5',2,4,14),
(421,'Selasa','5',25,5,5),
(422,'Selasa','5',15,6,7),
(423,'Selasa','5',21,7,3),
(424,'Selasa','5',20,8,10),
(425,'Selasa','5',21,9,8),
(426,'Selasa','5',17,10,10),
(427,'Selasa','5',1,11,18),
(428,'Selasa','5',13,12,15),
(429,'Selasa','5',24,13,13),
(430,'Selasa','5',2,14,1),
(431,'Selasa','5',11,15,20),
(432,'Selasa','5',12,16,22),
(433,'Selasa','6',3,1,3),
(434,'Selasa','6',12,2,18),
(435,'Selasa','6',22,3,16),
(436,'Selasa','6',18,4,15),
(437,'Selasa','6',23,5,12),
(438,'Selasa','6',16,6,9),
(439,'Selasa','6',17,7,6),
(440,'Selasa','6',15,8,19),
(441,'Selasa','6',11,9,10),
(442,'Selasa','6',2,10,12),
(443,'Selasa','6',16,11,5),
(444,'Selasa','6',1,12,20),
(445,'Selasa','6',21,13,13),
(446,'Selasa','6',5,14,17),
(447,'Selasa','6',19,15,22),
(448,'Selasa','6',21,16,20),
(449,'Selasa','7',23,1,10),
(450,'Selasa','7',4,2,15),
(451,'Selasa','7',7,3,5),
(452,'Selasa','7',6,4,6),
(453,'Selasa','7',16,5,1),
(454,'Selasa','7',24,6,13),
(455,'Selasa','7',17,7,19),
(456,'Selasa','7',10,8,7),
(457,'Selasa','7',3,9,8),
(458,'Selasa','7',6,10,19),
(459,'Selasa','7',13,11,5),
(460,'Selasa','7',22,12,6),
(461,'Selasa','7',12,13,14),
(462,'Selasa','7',11,14,20),
(463,'Selasa','7',5,15,5),
(464,'Selasa','7',24,16,8),
(465,'Selasa','8',14,1,17),
(466,'Selasa','8',25,2,4),
(467,'Selasa','8',14,3,1),
(468,'Selasa','8',21,4,9),
(469,'Selasa','8',5,5,13),
(470,'Selasa','8',19,6,7),
(471,'Selasa','8',7,7,20),
(472,'Selasa','8',18,8,2),
(473,'Selasa','8',9,9,16),
(474,'Selasa','8',10,10,16),
(475,'Selasa','8',1,11,13),
(476,'Selasa','8',25,12,11),
(477,'Selasa','8',17,13,16),
(478,'Selasa','8',25,14,16),
(479,'Selasa','8',9,15,2),
(480,'Selasa','8',7,16,22),
(481,'Rabu','1',19,1,13),
(482,'Rabu','1',25,2,21),
(483,'Rabu','1',18,3,8),
(484,'Rabu','1',10,4,13),
(485,'Rabu','1',13,5,22),
(486,'Rabu','1',3,6,5),
(487,'Rabu','1',18,7,19),
(488,'Rabu','1',10,8,16),
(489,'Rabu','1',18,9,1),
(490,'Rabu','1',4,10,21),
(491,'Rabu','1',9,11,1),
(492,'Rabu','1',7,12,19),
(493,'Rabu','1',21,13,21),
(494,'Rabu','1',7,14,4),
(495,'Rabu','1',21,15,20),
(496,'Rabu','1',14,16,8),
(497,'Rabu','2',19,1,3),
(498,'Rabu','2',25,2,3),
(499,'Rabu','2',8,3,12),
(500,'Rabu','2',9,4,13),
(501,'Rabu','2',17,5,6),
(502,'Rabu','2',7,6,3),
(503,'Rabu','2',4,7,9),
(504,'Rabu','2',24,8,14),
(505,'Rabu','2',1,9,13),
(506,'Rabu','2',21,10,10),
(507,'Rabu','2',19,11,10),
(508,'Rabu','2',4,12,22),
(509,'Rabu','2',15,13,5),
(510,'Rabu','2',20,14,16),
(511,'Rabu','2',16,15,9),
(512,'Rabu','2',3,16,15),
(513,'Rabu','3',18,1,3),
(514,'Rabu','3',23,2,2),
(515,'Rabu','3',2,3,8),
(516,'Rabu','3',2,4,9),
(517,'Rabu','3',4,5,22),
(518,'Rabu','3',21,6,16),
(519,'Rabu','3',22,7,9),
(520,'Rabu','3',5,8,21),
(521,'Rabu','3',15,9,22),
(522,'Rabu','3',4,10,16),
(523,'Rabu','3',13,11,15),
(524,'Rabu','3',14,12,21),
(525,'Rabu','3',23,13,3),
(526,'Rabu','3',12,14,13),
(527,'Rabu','3',9,15,4),
(528,'Rabu','3',5,16,4),
(529,'Rabu','4',19,1,2),
(530,'Rabu','4',23,2,17),
(531,'Rabu','4',7,3,8),
(532,'Rabu','4',8,4,14),
(533,'Rabu','4',14,5,21),
(534,'Rabu','4',22,6,8),
(535,'Rabu','4',6,7,21),
(536,'Rabu','4',7,8,14),
(537,'Rabu','4',13,9,20),
(538,'Rabu','4',23,10,4),
(539,'Rabu','4',18,11,5),
(540,'Rabu','4',3,12,6),
(541,'Rabu','4',20,13,20),
(542,'Rabu','4',9,14,6),
(543,'Rabu','4',8,15,20),
(544,'Rabu','4',10,16,1),
(545,'Rabu','5',11,1,2),
(546,'Rabu','5',21,2,15),
(547,'Rabu','5',22,3,18),
(548,'Rabu','5',6,4,18),
(549,'Rabu','5',10,5,8),
(550,'Rabu','5',20,6,7),
(551,'Rabu','5',8,7,4),
(552,'Rabu','5',25,8,7),
(553,'Rabu','5',1,9,2),
(554,'Rabu','5',23,10,9),
(555,'Rabu','5',12,11,18),
(556,'Rabu','5',23,12,16),
(557,'Rabu','5',1,13,16),
(558,'Rabu','5',12,14,17),
(559,'Rabu','5',11,15,3),
(560,'Rabu','5',13,16,6),
(561,'Rabu','6',16,1,8),
(562,'Rabu','6',22,2,19),
(563,'Rabu','6',6,3,9),
(564,'Rabu','6',13,4,3),
(565,'Rabu','6',3,5,12),
(566,'Rabu','6',14,6,6),
(567,'Rabu','6',20,7,17),
(568,'Rabu','6',6,8,1),
(569,'Rabu','6',19,9,21),
(570,'Rabu','6',11,10,20),
(571,'Rabu','6',2,11,13),
(572,'Rabu','6',14,12,12),
(573,'Rabu','6',13,13,11),
(574,'Rabu','6',1,14,14),
(575,'Rabu','6',19,15,11),
(576,'Rabu','6',4,16,19),
(577,'Rabu','7',12,1,18),
(578,'Rabu','7',6,2,13),
(579,'Rabu','7',25,3,15),
(580,'Rabu','7',23,4,2),
(581,'Rabu','7',19,5,1),
(582,'Rabu','7',21,6,14),
(583,'Rabu','7',25,7,12),
(584,'Rabu','7',9,8,13),
(585,'Rabu','7',18,9,13),
(586,'Rabu','7',20,10,13),
(587,'Rabu','7',8,11,13),
(588,'Rabu','7',12,12,2),
(589,'Rabu','7',6,13,15),
(590,'Rabu','7',2,14,18),
(591,'Rabu','7',24,15,6),
(592,'Rabu','7',5,16,20),
(593,'Rabu','8',9,1,11),
(594,'Rabu','8',19,2,6),
(595,'Rabu','8',13,3,20),
(596,'Rabu','8',25,4,10),
(597,'Rabu','8',21,5,2),
(598,'Rabu','8',19,6,2),
(599,'Rabu','8',11,7,19),
(600,'Rabu','8',16,8,3),
(601,'Rabu','8',16,9,8),
(602,'Rabu','8',18,10,6),
(603,'Rabu','8',12,11,20),
(604,'Rabu','8',19,12,13),
(605,'Rabu','8',21,13,12),
(606,'Rabu','8',22,14,11),
(607,'Rabu','8',2,15,14),
(608,'Rabu','8',8,16,9);

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
