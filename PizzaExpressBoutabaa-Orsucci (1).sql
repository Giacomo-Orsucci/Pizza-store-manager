-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: localhost    Database: pizzaexpress
-- ------------------------------------------------------
-- Server version	8.0.21

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
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `cf` varchar(16) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `cellulare` varchar(10) NOT NULL,
  PRIMARY KEY (`cf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contiene`
--

DROP TABLE IF EXISTS `contiene`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contiene` (
  `idProdotto` int NOT NULL,
  `nomeIngrediente` varchar(35) NOT NULL,
  PRIMARY KEY (`idProdotto`,`nomeIngrediente`),
  KEY `nomeIngrediente` (`nomeIngrediente`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contiene`
--

LOCK TABLES `contiene` WRITE;
/*!40000 ALTER TABLE `contiene` DISABLE KEYS */;
INSERT INTO `contiene` VALUES (1,'Acciughe'),(1,'Basilico '),(1,'Giacomo'),(1,'Mozzarella di bufala'),(1,'O core mio'),(1,'Pomodoro'),(15,'Basilico '),(15,'Farina 0'),(15,'Mozzarella di bufala'),(16,'Acciughe'),(16,'Farina 0'),(16,'Gorgonzola'),(16,'Mozzarella di bufala'),(16,'Parmigiano Reggiano'),(18,'Basilico '),(18,'Farina 0'),(18,'gaicomino il formaggino'),(18,'Giacomo'),(18,'Mozzarella di bufala'),(18,'Nduja'),(18,'Ti puzza il cazzo a 8 euro'),(19,'Burrata'),(19,'Carciofi '),(19,'Farina 0'),(19,'Pomodoro'),(23,'Aglio'),(23,'Basilico '),(23,'Boutabaa'),(23,'Farina 0'),(23,'Pomodoro'),(23,'Prova 1'),(28,'Basilico '),(28,'Farina di mais'),(28,'Mozzarella di bufala'),(28,'Pomodoro'),(29,'Carciofi sotto olio'),(29,'Farina 0'),(29,'Funghi'),(29,'Mozzarella di bufala'),(29,'Olive in salamoia'),(29,'Pomodoro'),(29,'Prosciutto cotto'),(30,'Carciofi sotto olio'),(30,'Mozzarella di bufala'),(30,'Olive in salamoia'),(30,'Pomodoro'),(30,'Prosciutto cotto'),(31,'Aglio'),(31,'Basilico '),(31,'Farina di mais'),(31,'Pomodoro');
/*!40000 ALTER TABLE `contiene` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `include`
--

DROP TABLE IF EXISTS `include`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `include` (
  `idOrdine` int NOT NULL,
  `idProdotto` int NOT NULL,
  `quantita` int NOT NULL,
  PRIMARY KEY (`idOrdine`,`idProdotto`),
  KEY `idProdotto` (`idProdotto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `include`
--

LOCK TABLES `include` WRITE;
/*!40000 ALTER TABLE `include` DISABLE KEYS */;
/*!40000 ALTER TABLE `include` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingrediente`
--

DROP TABLE IF EXISTS `ingrediente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingrediente` (
  `nome` varchar(35) NOT NULL,
  `descrizione` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nome`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingrediente`
--

LOCK TABLES `ingrediente` WRITE;
/*!40000 ALTER TABLE `ingrediente` DISABLE KEYS */;
INSERT INTO `ingrediente` VALUES ('Pomodoro','Pomodori I.G.P della Toscana'),('Mozzarella di bufala','Direttamente dalla Campania'),('Carciofi sotto olio','Carciofi in olio extra vergine di oliva'),('Aglio','Per scacciare i vampiri'),('Olive in salamoia','Olive toscane in salamoia'),('Nduja','Dalla Calabria piu profonda'),('Basilico ','Basilico fresco del nostro orto'),('Acciughe','Le migliori acciughe salate del mediterraneo'),('Farina 0','Grano antico macinato a pietra'),('Farina integrale','Grano antico macinato a pietra'),('Gorgonzola','Gorgonzola D.O.P dei migliori caseifici locali'),('Parmigiano Reggiano','l illustrissimo'),('Carciofi ','Raccolti a km 0'),('Prosciutto cotto','Prosciutto cotto di Parma'),('Funghi','Funghi profumatissimi'),('Farina di mais','Senza glutine'),('Burrata','Soffice soffice');
/*!40000 ALTER TABLE `ingrediente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordine`
--

DROP TABLE IF EXISTS `ordine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cf` varchar(16) DEFAULT NULL,
  `dettagliconsegna` varchar(200) DEFAULT NULL,
  `data` date NOT NULL,
  `via` varchar(38) NOT NULL,
  `civico` int NOT NULL,
  `città` varchar(35) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cf` (`cf`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordine`
--

LOCK TABLES `ordine` WRITE;
/*!40000 ALTER TABLE `ordine` DISABLE KEYS */;
/*!40000 ALTER TABLE `ordine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prodotto`
--

DROP TABLE IF EXISTS `prodotto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prodotto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(35) NOT NULL,
  `prezzo` float NOT NULL,
  `descrizione` varchar(100) DEFAULT NULL,
  `vegetariano` tinyint(1) NOT NULL,
  `celiaco` tinyint(1) NOT NULL,
  `vegano` tinyint(1) NOT NULL,
  `immagine` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodotto`
--

LOCK TABLES `prodotto` WRITE;
/*!40000 ALTER TABLE `prodotto` DISABLE KEYS */;
INSERT INTO `prodotto` VALUES (23,'Marinara',7,'Un mare di rosso aglioso sovrasta questa soffice terra ed è delizioso',1,0,1,'Immagini/Marinara.png'),(18,'Diavola',7,'Lasciati infiammare il palato',0,0,0,'Immagini/Diavola.png'),(15,'Margherita',5,'La classica ed insormontabile',1,0,0,'Immagini/Margherita.png'),(16,'4 Formaggi',7,'Immergiti in un piacere formaggioso',1,0,0,'Immagini/4 Formaggi.png'),(1,'Napoli',7,'A volte pochi ingredienti fanno la differenza              -Forza Napoli',1,0,0,'Immagini\\Napoli.png'),(19,'Carciofi e burrata',7,'Il morbido incontra lo spinoso in una accoppiata eccezionale',1,0,0,'Immagini/Carciofi e Burrata.png'),(28,'Margherita senza glutine',7,'La classica adatta a tutti',1,1,0,'Immagini/senza glutine Margherita.jpg'),(30,'4 Stagioni',8,'Quattro opposti uniti in un unica terra',0,0,0,'Immagini/4Stagioni.jpg'),(31,'Marinara senza glutine',8,'La Regina dell aglio adatta a tutti',1,1,1,'Immagini/Marinara senza glutine.png');
/*!40000 ALTER TABLE `prodotto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utenti` (
  `username` varchar(30) NOT NULL,
  `password` char(32) NOT NULL,
  `loggato` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES ('Orsu','c616b0008febf5b9461997ef8cfe06fa',0),('Bota','c30b6dbba96275a015a03fa101674f05',0);
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-10 16:48:06
