-- MySQL dump 10.13  Distrib 8.0.25, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: penjualan
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `penjualan_detail`
--

DROP TABLE IF EXISTS `penjualan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penjualan_detail` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `penjualan_id` int NOT NULL,
  `nama_barang` varchar(45) NOT NULL,
  `qty` float NOT NULL,
  `harga` double NOT NULL,
  PRIMARY KEY (`id_detail`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penjualan_detail`
--

LOCK TABLES `penjualan_detail` WRITE;
/*!40000 ALTER TABLE `penjualan_detail` DISABLE KEYS */;
INSERT INTO `penjualan_detail` VALUES (93,76,'NITRO',2,20000),(94,76,'PERTALITE',10,5000),(98,77,'NENAS',2,100000),(99,77,'SEPATU',1,1500000),(107,78,'KEJU',9,10000),(108,78,'KELAPA PARUT',2,8000),(109,79,'LAPTOP',1,5000000),(110,79,'MOUSE',1,150000),(111,79,'SSD',2,500000),(112,79,'KAMERA EXTERNAL',1,120000),(113,79,'FLASHDISK',1,80000),(114,79,'KEYBOARD EXTERNAL',1,650000),(118,75,'SEPEDA LISTRIK',1,2000000),(119,75,'RAM 8GB',5,600000),(120,81,'DHARMA',100,1000),(121,84,'PULAU MEDAN',12,90000),(125,85,'KEMEJA',6,50000),(129,88,'kaldnaio',0,500),(130,89,'sjadh',0,20),(133,93,'ASDAS',0.3,100000),(134,93,'OASK',1.87,100000),(135,86,'BERAS',0.5,12000),(136,90,'KECAP ASIN',5.78,100000),(137,90,'KETAPEL',12.99,455000),(138,94,'TES BARANG',2.2,20000),(141,95,'LALI',7,200000),(142,95,'RUMAH SAKIT',30,120000),(143,96,'ASDA',10,20000),(148,91,'TELINGA',0.02,2000.02),(151,74,'TRADO',10,1000000.02),(152,87,'UNTA',2,800000);
/*!40000 ALTER TABLE `penjualan_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_pelanggan`
--

DROP TABLE IF EXISTS `tbl_pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_pelanggan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pelanggan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_pelanggan`
--

LOCK TABLES `tbl_pelanggan` WRITE;
/*!40000 ALTER TABLE `tbl_pelanggan` DISABLE KEYS */;
INSERT INTO `tbl_pelanggan` VALUES (1,'Dharma'),(2,'Bakti'),(3,'Situmorang'),(4,'Dono'),(5,'Kasino'),(6,'Indro'),(7,'Warno'),(8,'Susiono'),(9,'Wak Karno'),(10,'Lolo');
/*!40000 ALTER TABLE `tbl_pelanggan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_penjualan`
--

DROP TABLE IF EXISTS `tbl_penjualan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_penjualan` (
  `id_penjualan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_bukti` varchar(255) DEFAULT NULL,
  `tgl_bukti` date DEFAULT NULL,
  `pelanggan_id` int DEFAULT NULL,
  UNIQUE KEY `id` (`id_penjualan`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_penjualan`
--

LOCK TABLES `tbl_penjualan` WRITE;
/*!40000 ALTER TABLE `tbl_penjualan` DISABLE KEYS */;
INSERT INTO `tbl_penjualan` VALUES (74,'ASD12','2025-06-05',1),(75,'AAD12','2025-06-16',6),(76,'IUO12','2025-06-11',2),(77,'SAD23','2025-06-19',3),(78,'SAD89','2026-03-04',4),(79,'BAI80','2025-06-10',7),(81,'IUI12','2025-06-05',8),(84,'BFH21','2025-06-22',1),(85,'ASD21','2025-06-02',3),(86,'QWQ12','2025-06-07',1),(87,'AAB80','2025-06-07',2),(88,'HSD12','2025-06-07',1),(89,'SDA21','2025-06-07',1),(90,'ABC73','2025-06-07',3),(91,'ABC80','2025-06-07',5),(93,'KSA12','2025-06-07',9),(94,'SPE12','2025-06-07',1),(95,'ASH28','2025-06-10',3),(96,'HAS12','2025-06-09',1);
/*!40000 ALTER TABLE `tbl_penjualan` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-12 13:46:15
