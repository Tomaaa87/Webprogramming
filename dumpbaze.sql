-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: shop
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `cart_status` enum('active','checked_out','abandoned') DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (1,8,2,1,290.00,'2025-10-30 10:28:08','active'),(2,9,5,1,300.00,'2025-10-30 10:28:08','active'),(3,10,7,2,220.00,'2025-10-30 10:28:08','active'),(4,10,9,1,180.00,'2025-10-30 10:28:08','active');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` text,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Tires','High-performance tires for all vehicles.','2025-10-30 09:46:57'),(2,'Engine Parts','Durable engine components for reliable performance.','2025-10-30 09:46:57'),(3,'Body Parts','Exterior and body parts to restore or upgrade your car.','2025-10-30 09:46:57');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_orders`
--

DROP TABLE IF EXISTS `custom_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `details` text,
  `estimated_price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `custom_orders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_orders`
--

LOCK TABLES `custom_orders` WRITE;
/*!40000 ALTER TABLE `custom_orders` DISABLE KEYS */;
INSERT INTO `custom_orders` VALUES (13,9,'2.5 TFSI DAZA Engine Retrofit (Golf 7R)','Requesting a 2.5 TFSI DAZA engine retrofit into Golf 7R. Includes wiring harness, ECU adaptation, custom mounts, and full mechanical integration.',9800.00,'Performance Upgrades','2025-10-30 10:20:20'),(14,10,'Full Air Suspension Install (Tank + Compressor)','Full air suspension install with air management system, compressor, and tank integration. Includes installation and calibration for comfort and stance modes.',5200.00,'Suspension','2025-10-30 10:20:20'),(15,11,'Stage 2 Turbo Upgrade + Intercooler Kit','Complete Stage 2 turbo upgrade with intercooler kit. Includes forged internals, upgraded injectors, remap, and dyno tuning.',7400.00,'Performance Upgrades','2025-10-30 10:20:20'),(16,12,'Widebody Kit Install + Paint Match','Widebody kit installation with professional paint matching. Includes bumper extensions, fenders, side skirts, and paint blending for OEM finish.',8700.00,'Exterior','2025-10-30 10:20:20');
/*!40000 ALTER TABLE `custom_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,13,1,1,330.00),(2,13,2,1,290.00),(3,14,4,1,750.00),(4,14,5,2,300.00),(5,15,6,1,450.00),(6,15,7,1,220.00),(7,16,1,1,330.00),(8,16,9,3,180.00),(9,17,8,1,200.00),(10,17,3,1,250.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) DEFAULT '0.00',
  `status` enum('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
  `is_custom` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (9,7,9800.00,'Pending',1,'2025-10-30 10:18:32'),(10,8,5200.00,'Pending',1,'2025-10-30 10:18:32'),(11,9,7400.00,'Pending',1,'2025-10-30 10:18:32'),(12,10,8700.00,'Pending',1,'2025-10-30 10:18:32'),(13,6,630.00,'Completed',0,'2025-10-30 10:26:56'),(14,7,1350.00,'Processing',0,'2025-10-30 10:26:56'),(15,8,580.00,'Pending',0,'2025-10-30 10:26:56'),(16,9,870.00,'Completed',0,'2025-10-30 10:26:56'),(17,10,400.00,'Processing',0,'2025-10-30 10:26:56');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Pirelli P-Zero',330.00,'The best summer tire you can get.',1,'./assets/images/pirelli.webp','2025-10-30 09:48:53'),(2,'Continental SportContact 6',200.00,'Perfect balance of grip and comfort.',1,'./assets/images/continental.webp','2025-10-30 09:48:53'),(3,'KUMHO Ecsta PS71',150.00,'Affordable high-performance summer tire.',1,'./assets/images/kumho.webp','2025-10-30 09:48:53'),(4,'Turbocharger',1890.00,'Add power and performance.',2,'./assets/images/turbo.webp','2025-10-30 09:48:53'),(5,'Turbo Intake',985.00,'Increases air flow and engine response.',2,'./assets/images/intake.webp','2025-10-30 09:48:53'),(6,'Exhaust System',2750.00,'Deep tone and improved flow.',2,'./assets/images/exhaust.webp','2025-10-30 09:48:53'),(7,'Spoiler',750.00,'Carbon-fiber design for aerodynamic stability.',3,'./assets/images/spoiler.webp','2025-10-30 09:48:53'),(8,'Front Lip',450.00,'Enhances sporty front look.',3,'./assets/images/frontlip.webp','2025-10-30 09:48:53'),(9,'Rear Lip',750.00,'Stylish rear upgrade.',3,'./assets/images/rearlip.webp','2025-10-30 09:48:53');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (6,'John Doe','john.doe@example.com','password123','Sarajevo','+38761234567','admin','2025-10-30 10:16:05'),(7,'Mia Petrović','mia.p@example.com','password123','Mostar','+38765432100','user','2025-10-30 10:16:05'),(8,'Amar Hadžić','amar.h@example.com','password123','Tuzla','+38762111222','user','2025-10-30 10:16:05'),(9,'Lejla Kovačević','lejla.k@example.com','password123','Zenica','+38763888888','user','2025-10-30 10:16:05'),(10,'Luka Matić','luka.m@example.com','password123','Banja Luka','+38762333444','user','2025-10-30 10:16:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'shop'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-30 10:40:40
