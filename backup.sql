-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: STOCKEASE_CUSTOMERS
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `customers_1`
--

DROP TABLE IF EXISTS `customers_1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `vat` varchar(50) NOT NULL,
  `xero_account` varchar(50) NOT NULL,
  `invoice_due_date` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers_1`
--

LOCK TABLES `customers_1` WRITE;
/*!40000 ALTER TABLE `customers_1` DISABLE KEYS */;
INSERT INTO `customers_1` VALUES (1,'Sharma Enterprises','Rajesh Sharma','9876543211','rajesh@sharma.com','India','Mumbai','MH','400001','VAT12345','XA001',30,'2025-03-23 10:12:36',NULL),(2,'Verma Solutions','Pooja Verma','8765432109','pooja@verma.com','India','Delhi','DL','110001','VAT23456','XA002',45,'2025-03-23 10:12:36',NULL),(3,'Patel Traders','Amit Patel','7654321098','amit@patel.com','India','Ahmedabad','GJ','380001','VAT34567','XA003',60,'2025-03-23 10:12:36',NULL),(4,'Nair Tech','Priya Nair','6543210987','priya@nair.com','India','Bangalore','KA','560001','VAT45678','XA004',15,'2025-03-23 10:12:36',NULL),(5,'Reddy Industries','Suresh Reddy','5432109876','suresh@reddy.com','India','Hyderabad','TS','500001','VAT56789','XA005',30,'2025-03-23 10:12:36',NULL),(6,'Mehta Solutions','Neha Mehta','4321098765','neha@mehta.com','India','Pune','MH','411001','VAT67890','XA006',45,'2025-03-23 10:12:36',NULL),(7,'Das Corp','Arun Das','3210987654','arun@das.com','India','Kolkata','WB','700001','VAT78901','XA007',60,'2025-03-23 10:12:36',NULL),(8,'Bose Technologies','Ananya Bose','2109876543','ananya@bose.com','India','Chennai','TN','600001','VAT89012','XA008',15,'2025-03-23 10:12:36',NULL),(9,'Gupta Exports','Vikas Gupta','1098765432','vikas@gupta.com','India','Jaipur','RJ','302001','VAT90123','XA009',30,'2025-03-23 10:12:36',NULL),(10,'Iyer Enterprises','Sneha Iyer','9988776655','sneha@iyer.com','India','Kochi','KL','682001','VAT01234','XA010',45,'2025-03-23 10:12:36',NULL),(11,'Rastogi Ventures','Manish Rastogi','8877665544','manish@rastogi.com','India','Lucknow','UP','226001','VAT12340','XA011',60,'2025-03-23 10:12:36',NULL),(12,'Joshi Automotives','Kiran Joshi','7766554433','kiran@joshi.com','India','Nagpur','MH','440001','VAT23450','XA012',15,'2025-03-23 10:12:36',NULL),(13,'Menon Exports','Deepika Menon','6655443322','deepika@menon.com','India','Thiruvananthapuram','KL','695001','VAT34560','XA013',30,'2025-03-23 10:12:36',NULL),(14,'Singh Enterprises','Harpreet Singh','5544332211','harpreet@singh.com','India','Amritsar','PB','143001','VAT45670','XA014',45,'2025-03-23 10:12:36',NULL),(15,'Agarwal Technologies','Rohit Agarwal','4433221100','rohit@agarwal.com','India','Indore','MP','452001','VAT56780','XA015',60,'2025-03-23 10:12:36',NULL),(16,'Malhotra Group','Radhika Malhotra','3322110099','radhika@malhotra.com','India','Chandigarh','CH','160001','VAT67890','XA016',15,'2025-03-23 10:12:36',NULL),(17,'Krishnan IT Solutions','Vivek Krishnan','2211009988','vivek@krishnan.com','India','Coimbatore','TN','641001','VAT78900','XA017',30,'2025-03-23 10:12:36',NULL),(18,'Desai Innovations','Simran Desai','1100998877','simran@desai.com','India','Surat','GJ','395001','VAT89010','XA018',45,'2025-03-23 10:12:36',NULL),(19,'Kumar Logistics','Anil Kumar','9998887776','anil@kumar.com','India','Patna','BR','800001','VAT90120','XA019',60,'2025-03-23 10:12:36',NULL),(20,'Choudhary Traders','Meera Choudhary','8887776665','meera@choudhary.com','India','Bhopal','MP','462001','VAT01230','XA020',15,'2025-03-23 10:12:36',NULL),(21,'Banerjee Consultants','Subhajit Banerjee','7776665554','subhajit@banerjee.com','India','Kolkata','WB','700002','VAT12346','XA021',30,'2025-03-23 10:12:36',NULL),(22,'Saxena Builders','Arjun Saxena','6665554443','arjun@saxena.com','India','Gwalior','MP','474001','VAT23457','XA022',45,'2025-03-23 10:12:36',NULL),(23,'Kapoor Jewellers','Tanya Kapoor','5554443332','tanya@kapoor.com','India','Delhi','DL','110002','VAT34568','XA023',60,'2025-03-23 10:12:36',NULL),(24,'Dutta Pharmaceuticals','Rajiv Dutta','4443332221','rajiv@dutta.com','India','Ranchi','JH','834001','VAT45679','XA024',15,'2025-03-23 10:12:36',NULL),(25,'Pillai Constructions','Asha Pillai','3332221110','asha@pillai.com','India','Mangalore','KA','575001','VAT56780','XA025',30,'2025-03-23 10:12:36',NULL),(26,'Sreeja Mynampati','Sreeja Mynampati','08639967987','smynampati04@gmail.com','India','Hyderabad','Telangana','500091','0','0',1,'2025-03-25 12:17:10',NULL),(27,'Ram','Kiran','9867854321','kiranram@gmail.com','India','Hyderabad','Telangana','500091','0','X001',1,'2025-03-26 06:53:21',NULL),(29,'Srja Mynampati','Sre','08639967987','smyi04@gmail.com','India','Hyderabad','Telangana','500091','0','XA006',1,'2025-04-12 05:37:38',NULL);
/*!40000 ALTER TABLE `customers_1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_logs`
--

DROP TABLE IF EXISTS `email_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_at` datetime NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_logs`
--

LOCK TABLES `email_logs` WRITE;
/*!40000 ALTER TABLE `email_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) NOT NULL DEFAULT 5,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'SKU001','Product A',10,5,99.99),(2,'SKU002','Product B',3,5,149.99),(3,'SKU003','Product C',0,5,199.99),(4,'LAP-001','Laptop',100,5,45000.00),(5,'MOU-002','Mouse',200,5,500.00),(6,'KEY-003','Keyboard',152,5,1200.00),(7,'MON-004','Monitor',80,5,15000.00);
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'LAP-001','Laptop',45000.00,100),(2,'MOU-002','Mouse',500.00,200),(3,'KEY-003','Keyboard',1200.00,150),(4,'MON-004','Monitor',15000.00,75);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `po_number` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin'),(2,'Manager'),(3,'Staff'),(4,'Viewer');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'INV-1001',1,5000.00,'2025-04-13 12:19:25'),(2,'INV-1002',2,7500.00,'2025-04-13 12:19:25'),(3,'INV-1003',1,3000.00,'2025-03-13 12:19:25');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `date_format` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(255) DEFAULT 'user',
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$12$9gdyxVzmVx2IWWrlPf.ad.3UoIugUTdr6F8hqODnSCFb4bDCfWHCK','2025-03-26 05:38:19','3','eja3myna@gmail.com');
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

-- Dump completed on 2025-04-27 11:43:06
