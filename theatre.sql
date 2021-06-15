-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: theatre
-- ------------------------------------------------------
-- Server version	8.0.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `genres` (
  `idGenres` int(11) NOT NULL AUTO_INCREMENT,
  `Category` varchar(45) NOT NULL,
  PRIMARY KEY (`idGenres`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genres`
--

LOCK TABLES `genres` WRITE;
/*!40000 ALTER TABLE `genres` DISABLE KEYS */;
INSERT INTO `genres` VALUES (1,'Комедія'),(2,'Драма'),(3,'Трагедія'),(4,'Тріллер'),(5,'Меланхолія'),(6,'М\'юзікл'),(7,'Документальне'),(8,'Романтика');
/*!40000 ALTER TABLE `genres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_has_spectacle`
--

DROP TABLE IF EXISTS `order_has_spectacle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `order_has_spectacle` (
  `idOrder_has_spectacle` int(11) NOT NULL AUTO_INCREMENT,
  `idOrder` int(11) NOT NULL,
  `idSpectacles` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  PRIMARY KEY (`idOrder_has_spectacle`,`idSpectacles`,`idOrder`),
  KEY `idOrder_idx` (`idOrder`),
  KEY `idDishes_idx` (`idSpectacles`),
  CONSTRAINT `idOrder` FOREIGN KEY (`idOrder`) REFERENCES `orders` (`idOrders`) ON DELETE CASCADE,
  CONSTRAINT `idSpectacles` FOREIGN KEY (`idSpectacles`) REFERENCES `spectacles` (`idSpectacles`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_has_spectacle`
--

LOCK TABLES `order_has_spectacle` WRITE;
/*!40000 ALTER TABLE `order_has_spectacle` DISABLE KEYS */;
INSERT INTO `order_has_spectacle` VALUES (16,21,2,5),(16,21,6,1),(16,21,7,9),(29,34,2,1),(29,34,3,1),(29,34,5,1),(30,34,1,1),(30,34,2,1),(30,34,3,1),(30,34,5,1),(30,34,6,4),(30,34,9,1),(31,34,1,1),(31,34,6,1),(32,34,6,1),(33,38,4,1),(54,59,4,4),(55,60,5,2),(56,61,3,2),(57,62,3,3);
/*!40000 ALTER TABLE `order_has_spectacle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `orders` (
  `idOrders` int(11) NOT NULL AUTO_INCREMENT,
  `idStatus` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`idOrders`,`idStatus`,`idUser`),
  KEY `idStatus_idx` (`idStatus`),
  KEY `idUser_idx` (`idUser`),
  CONSTRAINT `idStatus` FOREIGN KEY (`idStatus`) REFERENCES `status` (`idStatus`),
  CONSTRAINT `idUser` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (34,1,33),(38,1,34),(59,1,35),(60,1,36),(61,1,37),(21,2,27),(62,2,38);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `roles` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`idRole`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'user'),(2,'admin'),(3,'TestRole');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spectacles`
--

DROP TABLE IF EXISTS `spectacles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `spectacles` (
  `idSpectacles` int(11) NOT NULL AUTO_INCREMENT,
  `idGenres` int(11) NOT NULL,
  `idStages` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Code` varchar(45) NOT NULL,
  `Image` text NOT NULL,
  `Price` double(10,2) NOT NULL,
  `Date` date NOT NULL,
  `Bought` int(11) NOT NULL,
  PRIMARY KEY (`idSpectacles`,`idGenres`,`idStages`),
  KEY `idCategory_idx` (`idGenres`),
  KEY `idStages_idx` (`idStages`),
  CONSTRAINT `idCategory` FOREIGN KEY (`idGenres`) REFERENCES `genres` (`idGenres`),
  CONSTRAINT `idStages` FOREIGN KEY (`idStages`) REFERENCES `stages` (`idStages`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spectacles`
--

LOCK TABLES `spectacles` WRITE;
/*!40000 ALTER TABLE `spectacles` DISABLE KEYS */;
INSERT INTO `spectacles` VALUES (1,1,1,'Два пінгвіни','dva_pingviny','objects/dishes_images/afysha_dva_pingvina_ua.jpg',73.00,'2021-08-08',15),(2,1,2,'Чоловік на час','cholovik_na_chas','objects/dishes_images/cholovick_na_chas.jpg',170.00,'2021-09-07',50),(3,1,1,'Чоловіки на мережі нервового зриву','cholovik_nervy','objects/dishes_images/muzhchyny-na-grany-nervnogo-sryva.jpg',180.00,'2021-07-24',5),(4,1,2,'Дівичник','divichnyk','objects/dishes_images/poster_a4_devichnik_1.jpg',90.00,'2021-08-13',60),(5,3,1,'Спокуса','spokusa','objects/dishes_images/poster_a4_spokusa_4.jpg',260.00,'2021-09-18',32),(6,1,1,'Три + кіт','try_kit','objects/dishes_images/try_plyu_kot_ua.jpg',200.00,'2021-08-12',18),(7,1,2,'Приборкання С\'юзен','pryborkanna_syuzen','objects/dishes_images/ukroshhenye-syuzen.jpg',110.00,'2021-08-18',6),(8,1,2,'Вечеря на трьох','vecherya_na_tryoh','objects/dishes_images/uzhyn-na-troyh.jpg',70.00,'2021-07-29',12),(9,1,2,'Дура любов','dura_lubov','objects/dishes_images/poster_a4_dura-725x1024.jpg',75.00,'2021-09-26',15),(10,4,1,'Метод Х','method_x','objects/dishes_images/metod_afisha_site-2-725x1024.jpg',110.00,'2021-09-21',2);
/*!40000 ALTER TABLE `spectacles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stages`
--

DROP TABLE IF EXISTS `stages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `stages` (
  `idStages` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  `Capacity` int(11) NOT NULL,
  PRIMARY KEY (`idStages`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stages`
--

LOCK TABLES `stages` WRITE;
/*!40000 ALTER TABLE `stages` DISABLE KEYS */;
INSERT INTO `stages` VALUES (1,'Main stage',150),(2,'Secondary stage',80);
/*!40000 ALTER TABLE `stages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `status` (
  `idStatus` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`idStatus`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'заброньовано'),(2,'сплачено'),(3,'скасовано'),(4,'відмінено'),(5,'відмінено\\сплачено');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `Login` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `Name` varchar(45) DEFAULT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `Phone` varchar(45) DEFAULT NULL,
  `idRole` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idUser`,`idRole`),
  UNIQUE KEY `Login_UNIQUE` (`Login`),
  KEY `idRole_idx` (`idRole`),
  CONSTRAINT `idRole` FOREIGN KEY (`idRole`) REFERENCES `roles` (`idRole`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test','test','Test','test@test.su','0661758644',1),(14,'','secret',NULL,NULL,NULL,1),(20,'admin','admin',NULL,NULL,NULL,2),(25,'Vasiliy','1276123',NULL,NULL,'+7637271723',1),(26,'Oleg','1720431',NULL,NULL,'+967261273213',1),(27,'Olga','12931623713',NULL,NULL,'+92737126371',1),(28,'Alex','Alex',NULL,NULL,'+38012376123712367',1),(29,'Nikitos','SGHANKDK',NULL,NULL,'7236123',1),(30,'Nikitka','asdasd',NULL,NULL,'asdasd',1),(31,'banana','123ewdaefd',NULL,NULL,'',1),(32,'s','s',NULL,NULL,'435325245',1),(33,'ama','ama',NULL,NULL,'',1),(34,'qwe','qwe',NULL,NULL,'',1),(35,'temp','temp',NULL,NULL,'12255223',1),(36,'mama','mama',NULL,NULL,'666666',1),(37,'anton123','123',NULL,NULL,'+380608675849',1),(38,'artem123','123',NULL,NULL,'+38076587467',1);
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

-- Dump completed on 2021-06-15 10:56:31
