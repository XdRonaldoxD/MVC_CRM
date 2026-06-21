-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: farxcfyq_boticarosa
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
-- Table structure for table `atributo`
--

DROP TABLE IF EXISTS `atributo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `atributo` (
  `id_atributo` int NOT NULL AUTO_INCREMENT,
  `glosa_atributo` varchar(255) DEFAULT NULL,
  `id_padre_atributo` int DEFAULT NULL,
  `descripcion_atributo` varchar(5000) DEFAULT NULL,
  `orden_atributo` int DEFAULT NULL,
  `path_atributo` varchar(5000) DEFAULT NULL,
  `vigente_atributo` int DEFAULT NULL,
  `visibleimagenonline_atributo` int NOT NULL DEFAULT '1',
  `visiblemultiseleccion_atributo` int DEFAULT '1',
  PRIMARY KEY (`id_atributo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atributo`
--

LOCK TABLES `atributo` WRITE;
/*!40000 ALTER TABLE `atributo` DISABLE KEYS */;
/*!40000 ALTER TABLE `atributo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `atributo_producto`
--

DROP TABLE IF EXISTS `atributo_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `atributo_producto` (
  `id_atributo_producto` int NOT NULL AUTO_INCREMENT,
  `id_atributo` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `stock_atributo` int DEFAULT NULL,
  PRIMARY KEY (`id_atributo_producto`) USING BTREE,
  KEY `fk_relationship_501` (`id_atributo`) USING BTREE,
  KEY `fk_relationship_502` (`id_producto`) USING BTREE,
  CONSTRAINT `id_atributo` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id_atributo`),
  CONSTRAINT `id_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atributo_producto`
--

LOCK TABLES `atributo_producto` WRITE;
/*!40000 ALTER TABLE `atributo_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `atributo_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bodega`
--

DROP TABLE IF EXISTS `bodega`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bodega` (
  `id_bodega` int NOT NULL AUTO_INCREMENT,
  `codigo_bodega` varchar(255) DEFAULT NULL,
  `glosa_bodega` varchar(255) DEFAULT NULL,
  `vigente_bodega` int DEFAULT '1',
  PRIMARY KEY (`id_bodega`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bodega`
--

LOCK TABLES `bodega` WRITE;
/*!40000 ALTER TABLE `bodega` DISABLE KEYS */;
INSERT INTO `bodega` VALUES (18,NULL,'PUQUIO CANO',1);
/*!40000 ALTER TABLE `bodega` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bodega_sucursal`
--

DROP TABLE IF EXISTS `bodega_sucursal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bodega_sucursal` (
  `id_bodega_sucursal` int NOT NULL AUTO_INCREMENT,
  `id_sucursal` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  PRIMARY KEY (`id_bodega_sucursal`) USING BTREE,
  KEY `fk_relationship_25` (`id_bodega`) USING BTREE,
  KEY `fk_relationship_26` (`id_sucursal`) USING BTREE,
  CONSTRAINT `fk_relationship_25` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_relationship_26` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bodega_sucursal`
--

LOCK TABLES `bodega_sucursal` WRITE;
/*!40000 ALTER TABLE `bodega_sucursal` DISABLE KEYS */;
INSERT INTO `bodega_sucursal` VALUES (12,17,18);
/*!40000 ALTER TABLE `bodega_sucursal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boleta`
--

DROP TABLE IF EXISTS `boleta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boleta` (
  `id_boleta` int NOT NULL AUTO_INCREMENT,
  `id_negocio` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `numero_boleta` int DEFAULT NULL,
  `serie_boleta` varchar(255) DEFAULT NULL,
  `valor_boleta` float DEFAULT NULL,
  `fechacreacion_boleta` datetime DEFAULT NULL,
  `fechavencimiento_boleta` date DEFAULT NULL,
  `iva_boleta` float DEFAULT NULL,
  `total_boleta` float DEFAULT NULL,
  `urlpdf_boleta` varchar(255) DEFAULT NULL,
  `xmlpdf_boleta` varchar(255) DEFAULT NULL,
  `path_boleta` varchar(255) DEFAULT NULL,
  `estado_boleta` int DEFAULT NULL,
  `saldo_boleta` float(255,0) DEFAULT NULL,
  `path_ticket_pos` varchar(255) DEFAULT NULL,
  `xml_boleta` text,
  `cdrzip_boleta` text,
  `comentario_boleta` varchar(255) DEFAULT NULL,
  `path_ticket_boleta` varchar(255) DEFAULT NULL,
  `respuesta_sunat_boleta` longtext,
  PRIMARY KEY (`id_boleta`) USING BTREE,
  KEY `fk_relationship_77` (`id_folio`) USING BTREE,
  KEY `fk_relationship_198` (`id_negocio`) USING BTREE,
  KEY `fk_relationship_199` (`id_cliente`) USING BTREE,
  KEY `fk_relationship_205` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_relationship_198` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_relationship_199` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_relationship_205` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `fk_relationship_77` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boleta`
--

LOCK TABLES `boleta` WRITE;
/*!40000 ALTER TABLE `boleta` DISABLE KEYS */;
INSERT INTO `boleta` VALUES (42,125,1,23242,6,4,'BL01',11.02,'2026-06-13 12:57:42',NULL,1.98,13,NULL,NULL,'Documento_BOLETA202606131781373462.pdf',1,NULL,NULL,NULL,NULL,'Pendiente de envío manual a SUNAT','Ticket_202606131781373462.pdf','{\"ruta_xml\":null,\"ruta_zip\":null,\"Descripcion\":\"Pendiente de env\\u00edo manual a SUNAT\"}'),(43,127,1,23242,6,5,'BL01',10.17,'2026-06-13 13:02:24',NULL,1.83,12,NULL,NULL,'Documento_BOLETA202606131781373744.pdf',1,NULL,NULL,NULL,NULL,'Pendiente de envío manual a SUNAT','Ticket_202606131781373744.pdf','{\"ruta_xml\":null,\"ruta_zip\":null,\"Descripcion\":\"Pendiente de env\\u00edo manual a SUNAT\"}'),(44,128,1,23242,6,6,'BL01',22.03,'2026-06-13 18:38:19',NULL,3.97,26,NULL,NULL,'Documento_BOLETA202606131781393899.pdf',1,NULL,NULL,NULL,NULL,'Pendiente de envío manual a SUNAT','Ticket_202606131781393899.pdf','{\"ruta_xml\":null,\"ruta_zip\":null,\"Descripcion\":\"Pendiente de env\\u00edo manual a SUNAT\"}');
/*!40000 ALTER TABLE `boleta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja`
--

DROP TABLE IF EXISTS `caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caja` (
  `id_caja` int NOT NULL AUTO_INCREMENT,
  `id_sucursal_staff` int DEFAULT NULL,
  `id_staff` int DEFAULT NULL,
  `fechacreacion_caja` datetime DEFAULT NULL,
  `fechacierre_caja` datetime DEFAULT NULL,
  `estado_caja` int DEFAULT '1',
  `montoinicial_caja` float(11,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_caja`) USING BTREE,
  KEY `fk_relationship_204` (`id_sucursal_staff`) USING BTREE,
  CONSTRAINT `fk_relationship_204` FOREIGN KEY (`id_sucursal_staff`) REFERENCES `sucursal_staff` (`id_sucursal_staff`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja`
--

LOCK TABLES `caja` WRITE;
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
INSERT INTO `caja` VALUES (30,NULL,108,'2024-01-07 10:51:12','2026-06-15 11:55:08',0,0),(31,NULL,122,'2024-01-09 21:36:21','2026-06-12 01:54:31',0,0),(33,NULL,122,'2026-06-14 00:07:35','2026-06-14 00:29:41',0,0),(34,NULL,122,'2026-06-14 01:01:42','2026-06-15 11:47:27',0,0),(35,NULL,122,'2026-06-15 12:42:23',NULL,1,0);
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `canil`
--

DROP TABLE IF EXISTS `canil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `canil` (
  `id_canil` int NOT NULL AUTO_INCREMENT,
  `id_sucursal` int DEFAULT NULL,
  `glosa_canil` varchar(255) DEFAULT NULL,
  `orden_canil` int DEFAULT NULL,
  `vigente_canil` int DEFAULT '1',
  PRIMARY KEY (`id_canil`) USING BTREE,
  KEY `fk_relationship_125` (`id_sucursal`) USING BTREE,
  CONSTRAINT `fk_relationship_125` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canil`
--

LOCK TABLES `canil` WRITE;
/*!40000 ALTER TABLE `canil` DISABLE KEYS */;
/*!40000 ALTER TABLE `canil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `id_tipo_inventario` int DEFAULT NULL,
  `glosa_categoria` varchar(255) DEFAULT NULL,
  `codigo_categoria` varchar(255) DEFAULT NULL,
  `id_categoria_padre` int DEFAULT NULL,
  `descripcion_categoria` text,
  `orden_categoria` int DEFAULT NULL,
  `vigente_categoria` int DEFAULT '1',
  `pathimagen_categoria` varchar(255) DEFAULT NULL,
  `pathimagen_id_categoria` longtext,
  `pathimagenpopular_categoria` longtext,
  `pathimagenpopular_id_categoria` longtext,
  `visibleonline_categoria` int DEFAULT '1',
  `urlamigable_categoria` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`) USING BTREE,
  KEY `fk_relationship_43` (`id_tipo_inventario`) USING BTREE,
  CONSTRAINT `fk_relationship_43` FOREIGN KEY (`id_tipo_inventario`) REFERENCES `tipo_inventario` (`id_tipo_inventario`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,3,'Farmacia','CAT-1',0,'',NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708635817/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708635816.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708635816',1,'Farmacia-CAT-1'),(2,3,'Salud','CAT-2',0,'',NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708635861/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708635859.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708635859',1,'Salud-CAT-2'),(3,3,'Mamá y Bebé','CAT-3',0,'',NULL,1,NULL,NULL,NULL,NULL,1,'Mamá-y-Bebé'),(4,3,'Cuidado Personal','CAT-4',0,'',NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708635782/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708635781.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708635781',1,'Cuidado-Personal-CAT-4'),(5,3,'Malestar y Fiebre','CAT-5',1,'',NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708559434/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559433.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559433',1,'Malestar-y-Fiebre-CAT-5'),(6,3,'Resfriado Común','CAT-6',1,'',NULL,1,NULL,NULL,NULL,NULL,1,'Resfriado-Común'),(7,3,'Malestar tos con flema','CAT-7',1,'',NULL,0,NULL,NULL,NULL,NULL,1,'Malestar-tos-con-flema'),(8,3,'Malestar General y Fiebre','CAT-8',1,'',NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708559455/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559453.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559453',1,'Malestar-General-y-Fiebre-CAT-8'),(9,3,'Baño','CAT-9',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Preparaciones-para-tos-y-Resfrio'),(10,3,'Jabones','CAT-10',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Jabones-CAT-10'),(11,3,'Cuidado del Cabello','CAT-11',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Congestión-Nasal'),(12,3,'Cuidado Bucal','CAT-12',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Cólicos-Menstruales'),(13,3,'Cuidado de Manos y pies','CAT-13',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Malestar-Estomacal'),(14,3,'Afeitado','CAT-14',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Cuidado-Muscular-y-Articular'),(15,3,'Desodorantes','CAT-15',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Dolor-Generalizado'),(16,3,'Depilación','CAT-16',3,'',NULL,1,NULL,NULL,NULL,NULL,1,'Antinflamatorios-y-Antireumáticos'),(17,3,'Malestar Estomacales','CAT-17',1,'',NULL,1,'202402081707369022.png',NULL,NULL,NULL,1,'Malestar-Estomacales-CAT-17'),(18,3,'Dolor Generalizados','CAT-18',1,'',NULL,1,NULL,NULL,NULL,NULL,1,'Acidez'),(19,3,'Colicos Menstruales','CAT-19',2,'',NULL,0,NULL,NULL,NULL,NULL,1,'Agentes-Antidiarreicos'),(20,3,'Sistema Urinario','CAT-20',2,'',NULL,1,NULL,NULL,NULL,NULL,1,'Dolor-Muscular'),(21,3,'Sistema Cardiovascular','CAT-21',2,'',NULL,0,NULL,NULL,NULL,NULL,1,'Sistema-Cardiovascular'),(22,3,'Sistema Sanguineo','CAT-22',2,'',NULL,0,NULL,NULL,NULL,NULL,1,'Antinflamatorios-y-Antireumáticos'),(23,3,'Parches/compresas y bolsas','CAT-23',68,'',NULL,1,NULL,NULL,NULL,NULL,1,'Parches-compresas-y-bolsas'),(25,3,'Sistema Digestivo','CAT-25',2,'',NULL,1,NULL,NULL,NULL,NULL,1,'Sistema-Digestivo'),(27,3,'Cuidado Dermatológico','CAT-26',2,'',NULL,0,NULL,NULL,NULL,NULL,1,'Cuidado-Dermatológico'),(28,3,'Dispositivos Quirúrgicos','CAT-27',2,'',NULL,0,NULL,NULL,NULL,NULL,1,'Dispositivos-Quirúrgicos'),(29,3,'Oftalmológicos','CAT-28',2,'',NULL,1,NULL,NULL,NULL,NULL,1,'Oftalmológicos'),(30,3,'Sistema Respiratorio','CAT-29',2,'',NULL,1,NULL,NULL,NULL,NULL,1,'Sistema-Respiratorio'),(31,3,'Sistema Cardiovascular','CAT-30',0,'',NULL,0,NULL,NULL,NULL,NULL,1,'Sistema-Cardiovascular'),(32,3,'Problemas Generales','CAT-31',2,'',NULL,1,NULL,NULL,NULL,NULL,1,'Problemas-Generales'),(33,3,'Vitaminas y Complementos ','CAT-32',2,'',NULL,1,NULL,NULL,NULL,NULL,1,'Vitamina-D'),(34,3,'Vitamina C','CAT-33',33,'',NULL,1,NULL,NULL,NULL,NULL,1,'Vitamina-C'),(35,3,'Vitamina B','CAT-34',33,'',NULL,1,NULL,NULL,NULL,NULL,1,'Vitamina-B'),(36,3,'Vitamina A','CAT-35',33,'',NULL,1,NULL,NULL,NULL,NULL,1,'Vitamina-A'),(37,3,'Multivitaminicos','CAT-36',33,'',NULL,1,NULL,NULL,NULL,NULL,1,'Multivitaminicos'),(38,3,'Vitamina D','CAT-37',33,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Vitaminas-y-Complementos'),(39,3,'Cuidado del Cabello','CAT-38',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cuidado-del-Cabello'),(40,3,'Cuidado Masculino','CAT-39',4,NULL,NULL,0,NULL,NULL,NULL,NULL,1,'Cuidado-Masculino'),(42,3,'Baño','CAT-40',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Baño'),(43,3,'Jabones','CAT-41',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Jabones'),(44,3,'Cuidado Bucal','CAT-42',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cuidado-Bucal'),(45,3,'Cuidado Mano y pies','CAT-43',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cuidado-Mano-y-pies'),(46,3,'Cuidado Intimo','CAT-44',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cuidado-Intimo'),(47,3,'Cepillo De Ducha','CAT-45',42,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cepillo-De-Ducha'),(48,3,'Esponja','CAT-46',42,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Esponja'),(49,3,'Acondicionador','CAT-47',42,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Acondicionador'),(50,3,'Jabones','CAT-48',42,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'JabonesCAT-48'),(51,3,'Toallas','CAT-49',42,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Toallas'),(52,3,'Shampoo','CAT-50',42,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Shampoo'),(53,3,'Jabón en Barra','CAT-51',43,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Jabón-en-Barra'),(54,3,'Jabon Líquido','CAT-52',43,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Jabon-Líquido'),(55,3,'Gel de Ducha','CAT-53',43,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Gel-de-Ducha'),(56,3,'Crema de Peinar','CAT-54',39,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Crema-de-Peinar'),(57,3,'Cera/Gel Cabello','CAT-55',39,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cera-Gel-Cabello'),(58,3,'Shampoo y Acondicionadores','CAT-56',39,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Shampoo-y-Acondicionadores'),(59,3,'Crema Dental','CAT-57',44,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Crema-Dental'),(60,3,'Cepillo Dental','CAT-58',44,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Cepillo-Dental'),(61,3,'Desodorantes','CAT-59',4,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Desodorantes'),(62,3,'Hombre','CAT-60',61,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Hombre'),(63,3,'Mujer','CAT-61',61,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Mujer'),(64,3,'Analgésicos','CAT-62',18,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Analgésicos'),(65,3,'Dolor Muscular','CAT-63',18,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Dolor-Muscular'),(66,3,'Antiflamatorios y Antireumáticos','CAT-64',18,NULL,NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708559475/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559474.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559474',1,'Antiflamatorios-y-Antireumáticos-CAT-64'),(67,2,'Accesorio Celular','CAT-65',0,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Accesorio-Celular'),(68,2,'Cargador','CAT-66',67,NULL,NULL,1,'202401061704553576.jpeg',NULL,NULL,NULL,1,'Cargador-CAT-66'),(69,2,'Cable USB','CAT-67',67,NULL,NULL,1,'202401061704553551.png',NULL,NULL,NULL,1,'Cable-USB-CAT-67'),(70,3,'Analgésico','CAT-68',5,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Analgésico-CAT-68'),(71,3,'Congestión Nasal','CAT-69',6,NULL,NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708559571/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559569.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559569',1,'Congestión-Nasal-CAT-69'),(72,3,'Analgésico','CAT-70',6,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Analgésico-CAT-70'),(73,3,'Preparación para Tos y Resfrió','CAT-71',6,NULL,NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708559593/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559592.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559592',1,'Preparación-para-Tos-y-Resfrió-CAT-71'),(74,3,'Analgésicos','CAT-72',8,NULL,NULL,1,NULL,NULL,'https://res.cloudinary.com/do7dzakiw/image/upload/v1708559552/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559551.jpg','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_categoria/_1708559551',1,'Analgésicos-CAT-72'),(75,3,'Acidez','CAT-73',17,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Acidez-CAT-73'),(76,3,'Problemas Estomacales','CAT-74',17,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Problemas-Estomacales-CAT-74'),(80,3,'El mejor cuidado para tus manos','CAT-74',45,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'El-mejor-cuidado-para-tus-manos-CAT-74'),(81,3,'Crema para pies y manos','CAT-75',45,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Crema-para-pies-y-manos-CAT-75'),(82,3,'Acidosis Metabólicos','CAT-76',32,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Acidosis-Metabólicos-CAT-76'),(83,3,'Analgésicos y Antinflamatorios','CAT-77',32,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Analgésicos-y-Antinflamatorios-CAT-77'),(84,3,'Antimigrañosos','CAT-78',32,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antimigrañosos-CAT-78'),(85,3,'Sedantes','CAT-79',32,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Sedantes-CAT-79'),(86,3,'Corticoides','CAT-80',32,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Corticoides-CAT-80'),(87,3,'Relajante Musculares','CAT-81',32,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Relajante-Musculares-CAT-81'),(88,3,'Antiasmáticos','CAT-82',30,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antiasmáticos-CAT-82'),(89,3,'Dolor De Garganta','CAT-83',30,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Dolor-De-Garganta-CAT-83'),(90,3,'Antigripales','CAT-84',30,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antigripales-CAT-84'),(91,3,'Tos y Resfrio','CAT-85',30,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Tos-y-Resfrio-CAT-85'),(92,3,'Antibióticos','CAT-86',30,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antibióticos-CAT-86'),(93,3,'Lubricantes','CAT-87',29,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Lubricantes-CAT-87'),(94,3,'Antibióticos Oftálmicos','CAT-88',29,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antibióticos-Oftálmicos-CAT-88'),(95,3,'Antiinfecciosos','CAT-89',29,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antiinfecciosos-CAT-89'),(96,3,'Reposición Electrolitos','CAT-90',25,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Reposición-Electrolitos-CAT-90'),(97,3,'Antiácidos','CAT-91',25,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antiácidos-CAT-91'),(98,3,'Anti flatulentos','CAT-92',25,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Anti-flatulentos-CAT-92'),(99,3,'Otros','CAT-93',25,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Otros-CAT-93'),(100,3,'Laxantes','CAT-94',25,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Laxantes-CAT-94'),(101,3,'Flora Intestinal','CAT-95',25,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Flora-Intestinal-CAT-95'),(102,3,'Antibióticos','CAT-96',20,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Antibióticos-CAT-96'),(103,3,'Anti prostáticos','CAT-97',20,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Anti-prostáticos-CAT-97'),(104,3,'Difusión Eréctil','CAT-98',20,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Difusión-Eréctil-CAT-98'),(105,3,'Toallas Higiénicas','CAT-99',46,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Toallas-Higiénicas-CAT-99'),(106,3,'Higiene íntimo','CAT-100',46,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Higiene-íntimo-CAT-100'),(107,3,'Protectores Diarios','CAT-101',46,NULL,NULL,1,NULL,NULL,NULL,NULL,1,'Protectores-Diarios-CAT-101'),(108,NULL,'CAT TEST','C99',NULL,'prueba',NULL,1,NULL,NULL,NULL,NULL,1,NULL),(109,2,'CAT FK TEST',NULL,67,NULL,NULL,1,'https://x/y.jpg',NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria_producto`
--

DROP TABLE IF EXISTS `categoria_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_producto` (
  `id_categoria_producto` int NOT NULL AUTO_INCREMENT,
  `id_categoria` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  PRIMARY KEY (`id_categoria_producto`) USING BTREE,
  KEY `fk_relationship_55` (`id_categoria`) USING BTREE,
  KEY `fk_relationship_56` (`id_producto`) USING BTREE,
  CONSTRAINT `fk_relationship_55` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `fk_relationship_56` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_producto`
--

LOCK TABLES `categoria_producto` WRITE;
/*!40000 ALTER TABLE `categoria_producto` DISABLE KEYS */;
INSERT INTO `categoria_producto` VALUES (104,92,176),(108,5,192),(109,6,192),(110,8,192),(111,5,191),(112,6,191),(113,8,191),(116,17,189),(117,18,188),(118,64,188),(119,65,188),(120,66,188),(121,35,184),(122,37,184),(123,8,183),(124,17,182),(125,75,189),(126,76,189),(127,5,181),(128,70,181),(129,92,181),(130,83,181),(131,66,180),(132,71,179),(133,73,179),(134,90,179),(135,91,179),(136,58,177),(137,66,176),(138,102,175),(139,92,175),(140,50,152),(141,53,152),(142,70,192),(143,71,192),(144,72,192),(145,73,192),(146,74,192),(147,90,192),(148,83,192),(149,70,191),(150,71,191),(151,72,191),(152,73,191),(153,74,191),(154,90,191),(155,91,191),(156,83,191),(157,76,190),(158,100,190),(159,60,169),(160,59,168),(161,62,90),(162,62,193);
/*!40000 ALTER TABLE `categoria_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificado_digital_empresa`
--

DROP TABLE IF EXISTS `certificado_digital_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificado_digital_empresa` (
  `id_certificado_digital` int NOT NULL AUTO_INCREMENT,
  `id_empresa_venta_online` int DEFAULT NULL,
  `usuariosol_certificado_digital` varchar(255) DEFAULT NULL,
  `clavesol_certificado_digital` longtext,
  `clavearchivo_certificado_digital` longtext,
  `path_certificado_digital` varchar(255) DEFAULT NULL,
  `nombre_certificado_digital` varchar(255) DEFAULT NULL,
  `fechainicio_certificado_digital` datetime DEFAULT NULL,
  `fechafin_certificado_digital` datetime DEFAULT NULL,
  `fechacreacion_certificado_digital` datetime DEFAULT NULL,
  `uso_certificado_digital` int DEFAULT '0',
  PRIMARY KEY (`id_certificado_digital`) USING BTREE,
  KEY `fk_empresa_venta_online` (`id_empresa_venta_online`) USING BTREE,
  CONSTRAINT `fk_empresa_venta_online` FOREIGN KEY (`id_empresa_venta_online`) REFERENCES `empresa_venta_online` (`id_empresa_venta_online`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificado_digital_empresa`
--

LOCK TABLES `certificado_digital_empresa` WRITE;
/*!40000 ALTER TABLE `certificado_digital_empresa` DISABLE KEYS */;
INSERT INTO `certificado_digital_empresa` VALUES (7,34,'MODDATOS','cvxUija0pOdv9BqwWGHO/Do6jzZ0r2A9yNMS2x+DvNtK2Q==','2p/cuMrUVT/n3yZ8RMEaTTo6q4GtAGk0q7qLAiUALtpGlQ==','certificadopruebasunat1720130233.pfx','NOMBRE','2023-08-11 19:37:36','2025-08-10 19:37:36','2024-07-04 16:57:13',1),(9,34,'RONALDOS','qyiSffe507SlirdpUWHNtDo6Hl81UbmvQXLatL3pZ+oONA==','yxmc27/q3y9jjxymVj0czTo6v6gWziscO7kQqAcTLNkQxA==','certificadoRealLili1720301534.p12','||USO','2022-11-14 14:09:40','2025-11-13 14:09:40','2024-07-06 16:32:14',0);
/*!40000 ALTER TABLE `certificado_digital_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cirugias`
--

DROP TABLE IF EXISTS `cirugias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cirugias` (
  `id_cirugia` int NOT NULL AUTO_INCREMENT,
  `glosa_cirugia` varchar(255) DEFAULT NULL,
  `detalle_cirugia` text,
  `dias_cirugia` int DEFAULT NULL,
  `orden_cirugia` int DEFAULT NULL,
  `vigente_cirugia` int DEFAULT '1',
  PRIMARY KEY (`id_cirugia`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cirugias`
--

LOCK TABLES `cirugias` WRITE;
/*!40000 ALTER TABLE `cirugias` DISABLE KEYS */;
/*!40000 ALTER TABLE `cirugias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciudad`
--

DROP TABLE IF EXISTS `ciudad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciudad` (
  `id_ciudad` int NOT NULL AUTO_INCREMENT,
  `id_region` int DEFAULT NULL,
  `glosa_ciudad` varchar(255) DEFAULT NULL,
  `orden_ciudad` int DEFAULT NULL,
  `vigente_ciudad` int DEFAULT NULL,
  PRIMARY KEY (`id_ciudad`) USING BTREE,
  KEY `fk_relationship_34` (`id_region`) USING BTREE,
  CONSTRAINT `fk_relationship_34` FOREIGN KEY (`id_region`) REFERENCES `region` (`id_region`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudad`
--

LOCK TABLES `ciudad` WRITE;
/*!40000 ALTER TABLE `ciudad` DISABLE KEYS */;
/*!40000 ALTER TABLE `ciudad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `id_institucion_financiera` int DEFAULT NULL,
  `id_tipo_cuenta` int DEFAULT NULL,
  `id_comuna` int DEFAULT NULL,
  `idDistrito` int DEFAULT NULL,
  `dni_cliente` varchar(255) DEFAULT NULL,
  `dv_cliente` varchar(50) DEFAULT NULL,
  `tipodocumento_cliente` varchar(255) DEFAULT NULL,
  `ruc_cliente` varchar(255) DEFAULT NULL,
  `nombre_cliente` varchar(255) DEFAULT NULL,
  `apellidopaterno_cliente` varchar(255) DEFAULT NULL,
  `apellidomaterno_cliente` varchar(255) DEFAULT NULL,
  `e_mail_cliente` varchar(255) DEFAULT NULL,
  `telefono_cliente` varchar(255) DEFAULT NULL,
  `celular_cliente` varchar(255) DEFAULT NULL,
  `direccion_cliente` varchar(255) DEFAULT NULL,
  `numerocuenta_cliente` varchar(255) DEFAULT NULL,
  `comentario_cliente` text,
  `rutcuenta_cliente` varchar(255) DEFAULT NULL,
  `nombrecuenta_cliente` varchar(255) DEFAULT NULL,
  `emailcuenta_cliente` varchar(255) DEFAULT NULL,
  `ubigeo_cliente` varchar(255) DEFAULT NULL,
  `fechacreacion_cliente` date DEFAULT NULL,
  `fechanacimiento_cliente` date DEFAULT NULL,
  `mediollegada_cliente` varchar(255) DEFAULT NULL,
  `vigente_cliente` int DEFAULT '1',
  `esmigrado_cliente` int DEFAULT '0',
  `contraseniaactualizada_cliente` int DEFAULT NULL,
  PRIMARY KEY (`id_cliente`) USING BTREE,
  KEY `fk_relationship_44` (`id_comuna`) USING BTREE,
  KEY `fk_relationship_48` (`id_institucion_financiera`) USING BTREE,
  KEY `fk_relationship_49` (`id_tipo_cuenta`) USING BTREE,
  KEY `fk_relationshio_50` (`idDistrito`) USING BTREE,
  CONSTRAINT `fk_relationshio_50` FOREIGN KEY (`idDistrito`) REFERENCES `distrito` (`idDistrito`),
  CONSTRAINT `fk_relationship_44` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`),
  CONSTRAINT `fk_relationship_48` FOREIGN KEY (`id_institucion_financiera`) REFERENCES `institucion_financiera` (`id_institucion_financiera`),
  CONSTRAINT `fk_relationship_49` FOREIGN KEY (`id_tipo_cuenta`) REFERENCES `tipo_cuenta` (`id_tipo_cuenta`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,NULL,NULL,NULL,NULL,'00000000',NULL,'GENERICO','','CLIENTE GENERICO',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-07',NULL,NULL,1,0,NULL),(73,NULL,NULL,NULL,1330,'75144370',NULL,'DNI',NULL,'Ronaldo','Durand','Luna','smithxd118@gmail.com','','980535377','Cruz de Cano 101',NULL,NULL,NULL,NULL,NULL,NULL,'2024-04-08',NULL,'RESERVA_ONLINE',1,0,NULL),(74,NULL,NULL,NULL,NULL,'12345678',NULL,NULL,NULL,'Juan','Perez',NULL,'j@x.com','999',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL),(75,NULL,NULL,NULL,250,NULL,NULL,NULL,NULL,'C FK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,NULL),(76,NULL,NULL,NULL,NULL,'75144370','1','DNI/PASAPORTE',NULL,'RONALDO SMIT','DURAND','LUNA','',NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-12',NULL,NULL,1,0,NULL);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comprobante_ingreso`
--

DROP TABLE IF EXISTS `comprobante_ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comprobante_ingreso` (
  `id_comprobante_ingreso` int NOT NULL AUTO_INCREMENT,
  `id_folio` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `valor_comprobante_ingreso` float(11,0) DEFAULT NULL,
  `fechacreacion_comprobante_ingreso` datetime DEFAULT NULL,
  `path_comprobante_ingreso` varchar(255) DEFAULT NULL,
  `numero_comprobante_ingreso` int DEFAULT NULL,
  `estado_comprobante_ingreso` int DEFAULT NULL,
  PRIMARY KEY (`id_comprobante_ingreso`) USING BTREE,
  KEY `fk_relationship_75` (`id_folio`) USING BTREE,
  KEY `fk_relationship_79` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_relationship_75` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_relationship_79` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comprobante_ingreso`
--

LOCK TABLES `comprobante_ingreso` WRITE;
/*!40000 ALTER TABLE `comprobante_ingreso` DISABLE KEYS */;
/*!40000 ALTER TABLE `comprobante_ingreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comuna`
--

DROP TABLE IF EXISTS `comuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comuna` (
  `id_comuna` int NOT NULL AUTO_INCREMENT,
  `id_ciudad` int DEFAULT NULL,
  `glosa_comuna` varchar(255) DEFAULT NULL,
  `codigoobuma_comuna` varchar(255) DEFAULT NULL,
  `orden_comuna` int DEFAULT NULL,
  `codigochilexpress_comuna` varchar(255) DEFAULT NULL,
  `vigente_comuna` int DEFAULT '1',
  PRIMARY KEY (`id_comuna`) USING BTREE,
  KEY `fk_relationship_39` (`id_ciudad`) USING BTREE,
  CONSTRAINT `fk_relationship_39` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudad` (`id_ciudad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comuna`
--

LOCK TABLES `comuna` WRITE;
/*!40000 ALTER TABLE `comuna` DISABLE KEYS */;
/*!40000 ALTER TABLE `comuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion_pago`
--

DROP TABLE IF EXISTS `condicion_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condicion_pago` (
  `id_condicion_pago` int NOT NULL AUTO_INCREMENT,
  `glosa_condicion_pago` varchar(255) DEFAULT NULL,
  `cantidaddias_condicion_pago` int DEFAULT NULL,
  `orden_condicion_pago` int DEFAULT NULL,
  `vigente_condicion_pago` int DEFAULT NULL,
  PRIMARY KEY (`id_condicion_pago`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion_pago`
--

LOCK TABLES `condicion_pago` WRITE;
/*!40000 ALTER TABLE `condicion_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `condicion_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conector`
--

DROP TABLE IF EXISTS `conector`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conector` (
  `id_conector` int NOT NULL AUTO_INCREMENT,
  `glosa_conector` varchar(255) DEFAULT NULL,
  `campotabla_conector` varchar(255) DEFAULT NULL,
  `tabla_conector` varchar(255) DEFAULT NULL,
  `descripcion_conector` text,
  `orden_conector` int DEFAULT NULL,
  `vigente_conector` int DEFAULT '1',
  PRIMARY KEY (`id_conector`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conector`
--

LOCK TABLES `conector` WRITE;
/*!40000 ALTER TABLE `conector` DISABLE KEYS */;
/*!40000 ALTER TABLE `conector` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `convenio`
--

DROP TABLE IF EXISTS `convenio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `convenio` (
  `id_convenio` int NOT NULL AUTO_INCREMENT,
  `glosa_convenio` varchar(255) DEFAULT NULL,
  `id_listado_servicio` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_sucursal` int DEFAULT NULL,
  `fechacreacion_convenio` datetime DEFAULT NULL,
  `vigente_convenio` int DEFAULT NULL,
  PRIMARY KEY (`id_convenio`) USING BTREE,
  KEY `fk_usuario_idconvenio` (`id_usuario`) USING BTREE,
  KEY `fk_listadoservicio_convenio` (`id_listado_servicio`) USING BTREE,
  CONSTRAINT `fk_listadoservicio_convenio` FOREIGN KEY (`id_listado_servicio`) REFERENCES `listado_servicio` (`id_listado_servicio`),
  CONSTRAINT `fk_usuario_idconvenio` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `convenio`
--

LOCK TABLES `convenio` WRITE;
/*!40000 ALTER TABLE `convenio` DISABLE KEYS */;
/*!40000 ALTER TABLE `convenio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credito_cliente`
--

DROP TABLE IF EXISTS `credito_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `credito_cliente` (
  `id_credito_cliente` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `cupomaximo_credito_cliente` float(11,0) DEFAULT NULL,
  `saldo_credito_cliente` float(11,0) DEFAULT NULL,
  `deuda_credito_cliente` float(11,0) DEFAULT NULL,
  `fechacreacion_credito_cliente` datetime DEFAULT NULL,
  `vigente_credito_cliente` int DEFAULT NULL,
  PRIMARY KEY (`id_credito_cliente`) USING BTREE,
  KEY `fk_cliente_creditocliente` (`id_cliente`) USING BTREE,
  KEY `fk_idusuariocreditocliente_usuario_` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_cliente_creditocliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_idusuariocreditocliente_usuario_` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credito_cliente`
--

LOCK TABLES `credito_cliente` WRITE;
/*!40000 ALTER TABLE `credito_cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `credito_cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dato_extra`
--

DROP TABLE IF EXISTS `dato_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dato_extra` (
  `id_dato_extra` int NOT NULL AUTO_INCREMENT,
  `id_tipo_dato_extra` int DEFAULT NULL,
  `glosa_dato_extra` varchar(255) DEFAULT NULL,
  `orden_dato_extra` int DEFAULT NULL,
  `vigente_dato_extra` int DEFAULT '1',
  PRIMARY KEY (`id_dato_extra`) USING BTREE,
  KEY `fk_relationship_63` (`id_tipo_dato_extra`) USING BTREE,
  CONSTRAINT `fk_relationship_63` FOREIGN KEY (`id_tipo_dato_extra`) REFERENCES `tipo_dato_extra` (`id_tipo_dato_extra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dato_extra`
--

LOCK TABLES `dato_extra` WRITE;
/*!40000 ALTER TABLE `dato_extra` DISABLE KEYS */;
/*!40000 ALTER TABLE `dato_extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `idDepartamento` int NOT NULL AUTO_INCREMENT COMMENT 'id unico',
  `departamento` varchar(50) NOT NULL COMMENT 'nombre del departamento',
  `idPais` int NOT NULL COMMENT 'llave foranea',
  PRIMARY KEY (`idDepartamento`) USING BTREE,
  KEY `idPais1` (`idPais`) USING BTREE,
  CONSTRAINT `Pais.Departamento` FOREIGN KEY (`idPais`) REFERENCES `pais` (`idPais`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'AMAZONAS',1),(2,'ANCASH',1),(3,'APURIMAC',1),(4,'AREQUIPA',1),(5,'AYACUCHO',1),(6,'CAJAMARCA',1),(7,'CUSCO',1),(8,'HUANCAVELICA',1),(9,'HUANUCO',1),(10,'ICA',1),(11,'JUNIN',1),(12,'LA LIBERTAD',1),(13,'LAMBAYEQUE',1),(14,'LIMA',1),(15,'LORETO',1),(16,'MADRE DE DIOS',1),(17,'MOQUEGUA',1),(18,'PASCO',1),(19,'PIURA',1),(20,'PUNO',1),(21,'SAN MARTIN',1),(22,'TACNA',1),(23,'TUMBES',1),(24,'UCAYALI',1);
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `descuento`
--

DROP TABLE IF EXISTS `descuento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `descuento` (
  `id_descuento` int NOT NULL AUTO_INCREMENT,
  `glosa_descuento` varchar(255) DEFAULT NULL,
  `valor_descuento` float(11,0) DEFAULT NULL,
  `fechacreacion_descuento` datetime DEFAULT NULL,
  `orden_descuento` int DEFAULT NULL,
  `vigente_descuento` int DEFAULT '1',
  PRIMARY KEY (`id_descuento`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descuento`
--

LOCK TABLES `descuento` WRITE;
/*!40000 ALTER TABLE `descuento` DISABLE KEYS */;
/*!40000 ALTER TABLE `descuento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `descuento_global`
--

DROP TABLE IF EXISTS `descuento_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `descuento_global` (
  `id_descuento_global` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_tipo_descuento` int DEFAULT NULL,
  `glosa_descuento_global` varchar(255) DEFAULT NULL,
  `fechadesde_descuento_global` datetime DEFAULT NULL,
  `fechahasta_descuento_global` datetime DEFAULT NULL,
  `fechacreacion_descuento_global` datetime DEFAULT NULL,
  `porcentaje_descuento_global` float DEFAULT NULL,
  `monto_descuento_global` float DEFAULT NULL,
  `prioridad_descuento_global` int DEFAULT NULL,
  `vigente_descuento_global` int DEFAULT NULL,
  PRIMARY KEY (`id_descuento_global`) USING BTREE,
  KEY `fk_idusuario_descuento_global` (`id_usuario`) USING BTREE,
  KEY `fk_idtipodescuento_descuento_global` (`id_tipo_descuento`) USING BTREE,
  CONSTRAINT `fk_idtipodescuento_descuento_global` FOREIGN KEY (`id_tipo_descuento`) REFERENCES `tipo_descuento` (`id_tipo_descuento`),
  CONSTRAINT `fk_idusuario_descuento_global` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descuento_global`
--

LOCK TABLES `descuento_global` WRITE;
/*!40000 ALTER TABLE `descuento_global` DISABLE KEYS */;
/*!40000 ALTER TABLE `descuento_global` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `despacho`
--

DROP TABLE IF EXISTS `despacho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `despacho` (
  `id_despacho` int NOT NULL AUTO_INCREMENT,
  `id_negocio` int DEFAULT NULL,
  `id_estado_preparacion` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `fechahoracreacion_despacho` datetime DEFAULT NULL,
  `fechahoraentrega_despacho` datetime DEFAULT NULL,
  `valortotal_despacho` float DEFAULT NULL,
  `comentario_despacho` text,
  PRIMARY KEY (`id_despacho`) USING BTREE,
  KEY `fk_negocio_despacho` (`id_negocio`) USING BTREE,
  KEY `fk_estado_preparacion_despacho` (`id_estado_preparacion`) USING BTREE,
  KEY `fk_cliente_despacho` (`id_cliente`) USING BTREE,
  KEY `fk_bodega_despacho` (`id_bodega`) USING BTREE,
  KEY `fk_usuario_despacho` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_bodega_despacho` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_cliente_despacho` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_estado_preparacion_despacho` FOREIGN KEY (`id_estado_preparacion`) REFERENCES `estado_preparacion` (`id_estado_preparacion`),
  CONSTRAINT `fk_negocio_despacho` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_usuario_despacho` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `despacho`
--

LOCK TABLES `despacho` WRITE;
/*!40000 ALTER TABLE `despacho` DISABLE KEYS */;
/*!40000 ALTER TABLE `despacho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_zona_oferta`
--

DROP TABLE IF EXISTS `detalle_zona_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_zona_oferta` (
  `id_detalle_zona_oferta` int NOT NULL AUTO_INCREMENT,
  `id_zona_oferta` int NOT NULL,
  `id_producto` int NOT NULL,
  `id_lista_precio` int DEFAULT NULL,
  `preciolista_detalle_zona_oferta` float DEFAULT NULL,
  `porcentajedescuento_detalle_zona_oferta` float DEFAULT NULL,
  `preciototalventa_detalle_zona_oferta` float DEFAULT NULL,
  `orden_detalle_zona_oferta` int DEFAULT NULL,
  PRIMARY KEY (`id_detalle_zona_oferta`) USING BTREE,
  KEY `fk_zona_oferta` (`id_zona_oferta`) USING BTREE,
  KEY `fk_producto_zona_oferta` (`id_producto`) USING BTREE,
  CONSTRAINT `fk_producto_zona_oferta` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_zona_oferta` FOREIGN KEY (`id_zona_oferta`) REFERENCES `zona_oferta` (`id_zona_oferta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_zona_oferta`
--

LOCK TABLES `detalle_zona_oferta` WRITE;
/*!40000 ALTER TABLE `detalle_zona_oferta` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_zona_oferta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direccion_cliente`
--

DROP TABLE IF EXISTS `direccion_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direccion_cliente` (
  `id_direccion_cliente` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `calle_direccion_cliente` varchar(5000) DEFAULT NULL,
  `numero_direccion_cliente` varchar(255) DEFAULT NULL,
  `id_comuna` int DEFAULT NULL,
  `villaproblacionsector_direccion_cliente` varchar(1000) DEFAULT NULL,
  `numerocasadepartamento_direccion_cliente` varchar(255) DEFAULT NULL,
  `referencia_direccion_cliente` text,
  `nombrerecibe_direccion_cliente` varchar(1000) DEFAULT NULL,
  `apellidosrecibe_direccion_cliente` varchar(1000) DEFAULT NULL,
  `rutrecibe_direccion_cliente` varchar(255) DEFAULT NULL,
  `telefonorecibe_direccion_cliente` varchar(255) DEFAULT NULL,
  `email_direccion_cliente` varchar(255) DEFAULT NULL,
  `vigente_direccion_cliente` int DEFAULT NULL,
  PRIMARY KEY (`id_direccion_cliente`) USING BTREE,
  KEY `fk_cliente_direccion_cliente` (`id_cliente`) USING BTREE,
  KEY `fk_comuna_direccion_cliente` (`id_comuna`) USING BTREE,
  CONSTRAINT `fk_cliente_direccion_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_comuna_direccion_cliente` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direccion_cliente`
--

LOCK TABLES `direccion_cliente` WRITE;
/*!40000 ALTER TABLE `direccion_cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `direccion_cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distrito`
--

DROP TABLE IF EXISTS `distrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distrito` (
  `idDistrito` int NOT NULL COMMENT 'id unico',
  `distrito` varchar(50) NOT NULL COMMENT 'nombre de distrito',
  `idProvincia` int NOT NULL COMMENT 'llave foranea',
  PRIMARY KEY (`idDistrito`) USING BTREE,
  KEY `idProvincia1` (`idProvincia`) USING BTREE,
  CONSTRAINT `Provincia.Distrito` FOREIGN KEY (`idProvincia`) REFERENCES `provincia` (`idProvincia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distrito`
--

LOCK TABLES `distrito` WRITE;
/*!40000 ALTER TABLE `distrito` DISABLE KEYS */;
INSERT INTO `distrito` VALUES (1,'ARAMANGO',1),(2,'COPALLIN',1),(3,'EL PARCO',1),(4,'IMAZA',1),(5,'LA PECA',1),(6,'CHISQUILLA',2),(7,'CHURUJA',2),(8,'COROSHA',2),(9,'CUISPES',2),(10,'FLORIDA',2),(11,'JAZAN',2),(12,'JUMBILLA',2),(13,'RECTA',2),(14,'SAN CARLOS',2),(15,'SHIPASBAMBA',2),(16,'VALERA',2),(17,'YAMBRASBAMBA',2),(18,'ASUNCION',3),(19,'BALSAS',3),(20,'CHACHAPOYAS',3),(21,'CHETO',3),(22,'CHILIQUIN',3),(23,'CHUQUIBAMBA',3),(24,'GRANADA',3),(25,'HUANCAS',3),(26,'LA JALCA',3),(27,'LEIMEBAMBA',3),(28,'LEVANTO',3),(29,'MAGDALENA',3),(30,'MARISCAL CASTILLA',3),(31,'MOLINOPAMPA',3),(32,'MONTEVIDEO',3),(33,'OLLEROS',3),(34,'QUINJALCA',3),(35,'SAN FRANCISCO DE DAGUAS',3),(36,'SAN ISIDRO DE MAINO',3),(37,'SOLOCO',3),(38,'SONCHE',3),(39,'EL CENEPA',4),(40,'NIEVA',4),(41,'RIO SANTIAGO',4),(42,'CAMPORREDONDO',5),(43,'COCABAMBA',5),(44,'COLCAMAR',5),(45,'CONILA',5),(46,'INGUILPATA',5),(47,'LAMUD',5),(48,'LONGUITA',5),(49,'LONYA CHICO',5),(50,'LUYA',5),(51,'LUYA VIEJO',5),(52,'MARIA',5),(53,'OCALLI',5),(54,'OCUMAL',5),(55,'PISUQUIA',5),(56,'PROVIDENCIA',5),(57,'SAN CRISTOBAL',5),(58,'SAN FRANCISCO DEL YESO',5),(59,'SAN JERONIMO',5),(60,'SAN JUAN DE LOPECANCHA',5),(61,'SANTA CATALINA',5),(62,'SANTO TOMAS',5),(63,'TINGO',5),(64,'TRITA',5),(65,'CHIRIMOTO',6),(66,'COCHAMAL',6),(67,'HUAMBO',6),(68,'LIMABAMBA',6),(69,'LONGAR',6),(70,'MARISCAL BENAVIDES',6),(71,'MILPUC',6),(72,'OMIA',6),(73,'SAN NICOLAS',6),(74,'SANTA ROSA',6),(75,'TOTORA',6),(76,'VISTA ALEGRE',6),(77,'BAGUA GRANDE',7),(78,'CAJARURO',7),(79,'CUMBA',7),(80,'EL MILAGRO',7),(81,'JAMALCA',7),(82,'LONYA GRANDE',7),(83,'YAMON',7),(84,'AIJA',8),(85,'CORIS',8),(86,'HUACLLAN',8),(87,'LA MERCED',8),(88,'SUCCHA',8),(89,'ACZO',9),(90,'CHACCHO',9),(91,'CHINGAS',9),(92,'LLAMELLIN',9),(93,'MIRGAS',9),(94,'SAN JUAN DE RONTOY',9),(95,'ACOCHACA',10),(96,'CHACAS',10),(97,'ABELARDO PARDO LEZAMETA',11),(98,'ANTONIO RAYMONDI',11),(99,'AQUIA',11),(100,'CAJACAY',11),(101,'CANIS',11),(102,'CHIQUIAN',11),(103,'COLQUIOC',11),(104,'HUALLANCA',11),(105,'HUASTA',11),(106,'HUAYLLACAYAN',11),(107,'LA PRIMAVERA',11),(108,'MANGAS',11),(109,'PACLLON',11),(110,'SAN MIGUEL DE CORPANQUI',11),(111,'TICLLOS',11),(112,'ACOPAMPA',12),(113,'AMASHCA',12),(114,'ANTA',12),(115,'ATAQUERO',12),(116,'CARHUAZ',12),(117,'MARCARA',12),(118,'PARIAHUANCA',12),(119,'SAN MIGUEL DE ACO',12),(120,'SHILLA',12),(121,'TINCO',12),(122,'YUNGAR',12),(123,'SAN LUIS',13),(124,'SAN NICOLAS',13),(125,'YAUYA',13),(126,'BUENA VISTA ALTA',14),(127,'CASMA',14),(128,'COMANDANTE NOEL',14),(129,'YAUTAN',14),(130,'ACO',15),(131,'BAMBAS',15),(132,'CORONGO',15),(133,'CUSCA',15),(134,'LA PAMPA',15),(135,'YANAC',15),(136,'YUPAN',15),(137,'COCHABAMBA',16),(138,'COLCABAMBA',16),(139,'HUANCHAY',16),(140,'HUARAZ',16),(141,'INDEPENDENCIA',16),(142,'JANGAS',16),(143,'LA LIBERTAD',16),(144,'OLLEROS',16),(145,'PAMPAS',16),(146,'PARIACOTO',16),(147,'PIRA',16),(148,'TARICA',16),(149,'ANRA',17),(150,'CAJAY',17),(151,'CHAVIN DE HUANTAR',17),(152,'HUACACHI',17),(153,'HUACCHIS',17),(154,'HUACHIS',17),(155,'HUANTAR',17),(156,'HUARI',17),(157,'MASIN',17),(158,'PAUCAS',17),(159,'PONTO',17),(160,'RAHUAPAMPA',17),(161,'RAPAYAN',17),(162,'SAN MARCOS',17),(163,'SAN PEDRO DE CHANA',17),(164,'UCO',17),(165,'COCHAPETI',18),(166,'CULEBRAS',18),(167,'HUARMEY',18),(168,'HUAYAN',18),(169,'MALVAS',18),(170,'CARAZ',19),(171,'HUALLANCA',19),(172,'HUATA',19),(173,'HUAYLAS',19),(174,'MATO',19),(175,'PAMPAROMAS',19),(176,'PUEBLO LIBRE',19),(177,'SANTA CRUZ',19),(178,'SANTO TORIBIO',19),(179,'YURACMARCA',19),(180,'CASCA',20),(181,'ELEAZAR GUZMAN BARRON',20),(182,'FIDEL OLIVAS ESCUDERO',20),(183,'LLAMA',20),(184,'LLUMPA',20),(185,'LUCMA',20),(186,'MUSGA',20),(187,'PISCOBAMBA',20),(188,'ACAS',21),(189,'CAJAMARQUILLA',21),(190,'CARHUAPAMPA',21),(191,'COCHAS',21),(192,'CONGAS',21),(193,'LLIPA',21),(194,'OCROS',21),(195,'SAN CRISTOBAL DE RAJAN',21),(196,'SAN PEDRO',21),(197,'SANTIAGO DE CHILCAS',21),(198,'BOLOGNESI',22),(199,'CABANA',22),(200,'CONCHUCOS',22),(201,'HUACASCHUQUE',22),(202,'HUANDOVAL',22),(203,'LACABAMBA',22),(204,'LLAPO',22),(205,'PALLASCA',22),(206,'PAMPAS',22),(207,'SANTA ROSA',22),(208,'TAUCA',22),(209,'HUAYLLAN',23),(210,'PAROBAMBA',23),(211,'POMABAMBA',23),(212,'QUINUABAMBA',23),(213,'CATAC',24),(214,'COTAPARACO',24),(215,'HUAYLLAPAMPA',24),(216,'LLACLLIN',24),(217,'MARCA',24),(218,'PAMPAS CHICO',24),(219,'PARARIN',24),(220,'RECUAY',24),(221,'TAPACOCHA',24),(222,'TICAPAMPA',24),(223,'CACERES DEL PERU',25),(224,'CHIMBOTE',25),(225,'COISHCO',25),(226,'MACATE',25),(227,'MORO',25),(228,'NEPEÑA',25),(229,'NUEVO CHIMBOTE',25),(230,'SAMANCO',25),(231,'SANTA',25),(232,'ACOBAMBA',26),(233,'ALFONSO UGARTE',26),(234,'CASHAPAMPA',26),(235,'CHINGALPO',26),(236,'HUAYLLABAMBA',26),(237,'QUICHES',26),(238,'RAGASH',26),(239,'SAN JUAN',26),(240,'SICSIBAMBA',26),(241,'SIHUAS',26),(242,'CASCAPARA',27),(243,'MANCOS',27),(244,'MATACOTO',27),(245,'QUILLO',27),(246,'RANRAHIRCA',27),(247,'SHUPLUY',27),(248,'YANAMA',27),(249,'YUNGAY',27),(250,'ABANCAY',28),(251,'CHACOCHE',28),(252,'CIRCA',28),(253,'CURAHUASI',28),(254,'HUANIPACA',28),(255,'LAMBRAMA',28),(256,'PICHIRHUA',28),(257,'SAN PEDRO DE CACHORA',28),(258,'TAMBURCO',28),(259,'ANDAHUAYLAS',29),(260,'ANDARAPA',29),(261,'CHIARA',29),(262,'HUANCARAMA',29),(263,'HUANCARAY',29),(264,'HUAYANA',29),(265,'KAQUIABAMBA',29),(266,'KISHUARA',29),(267,'PACOBAMBA',29),(268,'PACUCHA',29),(269,'PAMPACHIRI',29),(270,'POMACOCHA',29),(271,'SAN ANTONIO DE CACHI',29),(272,'SAN JERONIMO',29),(273,'SAN MIGUEL DE CHACCRAMPA',29),(274,'SANTA MARIA DE CHICMO',29),(275,'TALAVERA',29),(276,'TUMAY HUARACA',29),(277,'TURPO',29),(278,'ANTABAMBA',30),(279,'EL ORO',30),(280,'HUAQUIRCA',30),(281,'JUAN ESPINOZA MEDRANO',30),(282,'OROPESA',30),(283,'PACHACONAS',30),(284,'SABAINO',30),(285,'CAPAYA',31),(286,'CARAYBAMBA',31),(287,'CHALHUANCA',31),(288,'CHAPIMARCA',31),(289,'COLCABAMBA',31),(290,'COTARUSE',31),(291,'HUAYLLO',31),(292,'JUSTO APU SAHUARAURA',31),(293,'LUCRE',31),(294,'POCOHUANCA',31),(295,'SAN JUAN DE CHACÑA',31),(296,'SAÑAYCA',31),(297,'SORAYA',31),(298,'TAPAIRIHUA',31),(299,'TINTAY',31),(300,'TORAYA',31),(301,'YANACA',31),(302,'ANCO-HUALLO',32),(303,'CHINCHEROS',32),(304,'COCHARCAS',32),(305,'HUACCANA',32),(306,'OCOBAMBA',32),(307,'ONGOY',32),(308,'RANRACANCHA',32),(309,'URANMARCA',32),(310,'CHALLHUAHUACHO',33),(311,'COTABAMBAS',33),(312,'COYLLURQUI',33),(313,'HAQUIRA',33),(314,'MARA',33),(315,'TAMBOBAMBA',33),(316,'CHUQUIBAMBILLA',34),(317,'CURASCO',34),(318,'CURPAHUASI',34),(319,'GAMARRA',34),(320,'HUAYLLATI',34),(321,'MAMARA',34),(322,'MICAELA BASTIDAS',34),(323,'PATAYPAMPA',34),(324,'PROGRESO',34),(325,'SAN ANTONIO',34),(326,'SANTA ROSA',34),(327,'TURPAY',34),(328,'VILCABAMBA',34),(329,'VIRUNDO',34),(330,'ALTO SELVA ALEGRE',35),(331,'AREQUIPA',35),(332,'CAYMA',35),(333,'CERRO COLORADO',35),(334,'CHARACATO',35),(335,'CHIGUATA',35),(336,'JACOBO HUNTER',35),(337,'JOSE LUIS BUSTAMANTE Y RIVERO',35),(338,'LA JOYA',35),(339,'MARIANO MELGAR',35),(340,'MIRAFLORES',35),(341,'MOLLEBAYA',35),(342,'PAUCARPATA',35),(343,'POCSI',35),(344,'POLOBAYA',35),(345,'QUEQUEÑA',35),(346,'SABANDIA',35),(347,'SACHACA',35),(348,'SAN JUAN DE SIGUAS',35),(349,'SAN JUAN DE TARUCANI',35),(350,'SANTA ISABEL DE SIGUAS',35),(351,'SANTA RITA DE SIGUAS',35),(352,'SOCABAYA',35),(353,'TIABAYA',35),(354,'UCHUMAYO',35),(355,'VITOR  1/',35),(356,'YANAHUARA',35),(357,'YARABAMBA',35),(358,'YURA',35),(359,'CAMANA',36),(360,'JOSE MARIA QUIMPER',36),(361,'MARIANO NICOLAS VALCARCEL',36),(362,'MARISCAL CACERES',36),(363,'NICOLAS DE PIEROLA',36),(364,'OCOÑA',36),(365,'QUILCA',36),(366,'SAMUEL PASTOR',36),(367,'ACARI',37),(368,'ATICO',37),(369,'ATIQUIPA',37),(370,'BELLA UNION',37),(371,'CAHUACHO',37),(372,'CARAVELI',37),(373,'CHALA',37),(374,'CHAPARRA',37),(375,'HUANUHUANU',37),(376,'JAQUI',37),(377,'LOMAS',37),(378,'QUICACHA',37),(379,'YAUCA',37),(380,'ANDAGUA',38),(381,'APLAO',38),(382,'AYO',38),(383,'CHACHAS',38),(384,'CHILCAYMARCA',38),(385,'CHOCO',38),(386,'HUANCARQUI',38),(387,'MACHAGUAY',38),(388,'ORCOPAMPA',38),(389,'PAMPACOLCA',38),(390,'TIPAN',38),(391,'UÑON',38),(392,'URACA',38),(393,'VIRACO',38),(394,'ACHOMA',39),(395,'CABANACONDE',39),(396,'CALLALLI',39),(397,'CAYLLOMA',39),(398,'CHIVAY',39),(399,'COPORAQUE',39),(400,'HUAMBO',39),(401,'HUANCA',39),(402,'ICHUPAMPA',39),(403,'LARI',39),(404,'LLUTA',39),(405,'MACA',39),(406,'MADRIGAL',39),(407,'MAJES',39),(408,'SAN ANTONIO DE CHUCA',39),(409,'SIBAYO',39),(410,'TAPAY',39),(411,'TISCO',39),(412,'TUTI',39),(413,'YANQUE',39),(414,'ANDARAY',40),(415,'CAYARANI',40),(416,'CHICHAS',40),(417,'CHUQUIBAMBA',40),(418,'IRAY',40),(419,'RIO GRANDE',40),(420,'SALAMANCA',40),(421,'YANAQUIHUA',40),(422,'COCACHACRA',41),(423,'DEAN VALDIVIA',41),(424,'ISLAY',41),(425,'MEJIA',41),(426,'MOLLENDO',41),(427,'PUNTA DE BOMBON',41),(428,'ALCA',42),(429,'CHARCANA',42),(430,'COTAHUASI',42),(431,'HUAYNACOTAS',42),(432,'PAMPAMARCA',42),(433,'PUYCA',42),(434,'QUECHUALLA',42),(435,'SAYLA',42),(436,'TAURIA',42),(437,'TOMEPAMPA',42),(438,'TORO',42),(439,'CANGALLO',43),(440,'CHUSCHI',43),(441,'LOS MOROCHUCOS',43),(442,'MARIA PARADO DE BELLIDO',43),(443,'PARAS',43),(444,'TOTOS',43),(445,'ACOCRO',44),(446,'ACOS VINCHOS',44),(447,'AYACUCHO',44),(448,'CARMEN ALTO',44),(449,'CHIARA',44),(450,'JESUS NAZARENO',44),(451,'OCROS',44),(452,'PACAYCASA',44),(453,'QUINUA',44),(454,'SAN JOSE DE TICLLAS',44),(455,'SAN JUAN BAUTISTA',44),(456,'SANTIAGO DE PISCHA',44),(457,'SOCOS',44),(458,'TAMBILLO',44),(459,'VINCHOS',44),(460,'CARAPO',45),(461,'SACSAMARCA',45),(462,'SANCOS',45),(463,'SANTIAGO DE LUCANAMARCA',45),(464,'AYAHUANCO',46),(465,'HUAMANGUILLA',46),(466,'HUANTA',46),(467,'IGUAIN',46),(468,'LLOCHEGUA',46),(469,'LURICOCHA',46),(470,'SANTILLANA',46),(471,'SIVIA',46),(472,'ANCO',47),(473,'AYNA',47),(474,'CHILCAS',47),(475,'CHUNGUI',47),(476,'LUIS CARRANZA',47),(477,'SAN MIGUEL',47),(478,'SANTA ROSA',47),(479,'TAMBO',47),(480,'AUCARA',48),(481,'CABANA',48),(482,'CARMEN SALCEDO',48),(483,'CHAVIÑA',48),(484,'CHIPAO',48),(485,'HUAC-HUAS',48),(486,'LARAMATE',48),(487,'LEONCIO PRADO',48),(488,'LLAUTA',48),(489,'LUCANAS',48),(490,'OCAÑA',48),(491,'OTOCA',48),(492,'PUQUIO',48),(493,'SAISA',48),(494,'SAN CRISTOBAL',48),(495,'SAN JUAN',48),(496,'SAN PEDRO',48),(497,'SAN PEDRO DE PALCO',48),(498,'SANCOS',48),(499,'SANTA ANA DE HUAYCAHUACHO',48),(500,'SANTA LUCIA',48),(501,'CHUMPI',49),(502,'CORACORA',49),(503,'CORONEL CASTAÑEDA',49),(504,'PACAPAUSA',49),(505,'PULLO',49),(506,'PUYUSCA',49),(507,'SAN FRANCISCO DE RAVACAYCO',49),(508,'UPAHUACHO',49),(509,'COLTA',50),(510,'CORCULLA',50),(511,'LAMPA',50),(512,'MARCABAMBA',50),(513,'OYOLO',50),(514,'PARARCA',50),(515,'PAUSA',50),(516,'SAN JAVIER DE ALPABAMBA',50),(517,'SAN JOSE DE USHUA',50),(518,'SARA SARA',50),(519,'BELEN',51),(520,'CHALCOS',51),(521,'CHILCAYOC',51),(522,'HUACAÑA',51),(523,'MORCOLLA',51),(524,'PAICO',51),(525,'QUEROBAMBA',51),(526,'SAN PEDRO DE LARCAY',51),(527,'SAN SALVADOR DE QUIJE',51),(528,'SANTIAGO DE PAUCARAY',51),(529,'SORAS',51),(530,'ALCAMENCA',52),(531,'APONGO',52),(532,'ASQUIPATA',52),(533,'CANARIA',52),(534,'CAYARA',52),(535,'COLCA',52),(536,'HUAMANQUIQUIA',52),(537,'HUANCAPI',52),(538,'HUANCARAYLLA',52),(539,'HUAYA',52),(540,'SARHUA',52),(541,'VILCANCHOS',52),(542,'ACCOMARCA',53),(543,'CARHUANCA',53),(544,'CONCEPCION',53),(545,'HUAMBALPA',53),(546,'INDEPENDENCIA',53),(547,'SAURAMA',53),(548,'VILCAS HUAMAN',53),(549,'VISCHONGO',53),(550,'CACHACHI',54),(551,'CAJABAMBA',54),(552,'CONDEBAMBA',54),(553,'SITACOCHA',54),(554,'ASUNCION',55),(555,'CAJAMARCA',55),(556,'CHETILLA',55),(557,'COSPAN',55),(558,'ENCAÑADA',55),(559,'JESUS',55),(560,'LLACANORA',55),(561,'LOS BAÑOS DEL INCA',55),(562,'MAGDALENA',55),(563,'MATARA',55),(564,'NAMORA',55),(565,'SAN JUAN',55),(566,'CELENDIN',56),(567,'CHUMUCH',56),(568,'CORTEGANA',56),(569,'HUASMIN',56),(570,'JORGE CHAVEZ',56),(571,'JOSE GALVEZ',56),(572,'LA LIBERTAD DE PALLAN',56),(573,'MIGUEL IGLESIAS',56),(574,'OXAMARCA',56),(575,'SOROCHUCO',56),(576,'SUCRE',56),(577,'UTCO',56),(578,'ANGUIA',57),(579,'CHADIN',57),(580,'CHALAMARCA',57),(581,'CHIGUIRIP',57),(582,'CHIMBAN',57),(583,'CHOROPAMPA',57),(584,'CHOTA',57),(585,'COCHABAMBA',57),(586,'CONCHAN',57),(587,'HUAMBOS',57),(588,'LAJAS',57),(589,'LLAMA',57),(590,'MIRACOSTA',57),(591,'PACCHA',57),(592,'PION',57),(593,'QUEROCOTO',57),(594,'SAN JUAN DE LICUPIS',57),(595,'TACABAMBA',57),(596,'TOCMOCHE',57),(597,'CHILETE',58),(598,'CONTUMAZA',58),(599,'CUPISNIQUE',58),(600,'GUZMANGO',58),(601,'SAN BENITO',58),(602,'SANTA CRUZ DE TOLEDO',58),(603,'TANTARICA',58),(604,'YONAN',58),(605,'CALLAYUC',59),(606,'CHOROS',59),(607,'CUJILLO',59),(608,'CUTERVO',59),(609,'LA RAMADA',59),(610,'PIMPINGOS',59),(611,'QUEROCOTILLO',59),(612,'SAN ANDRES DE CUTERVO',59),(613,'SAN JUAN DE CUTERVO',59),(614,'SAN LUIS DE LUCMA',59),(615,'SANTA CRUZ',59),(616,'SANTO TOMAS',59),(617,'SOCOTA',59),(618,'STO. DOMINGO DE LA CAPILLA',59),(619,'TORIBIO CASANOVA',59),(620,'BAMBAMARCA',60),(621,'CHUGUR',60),(622,'HUALGAYOC',60),(623,'BELLAVISTA',61),(624,'CHONTALI',61),(625,'COLASAY',61),(626,'HUABAL',61),(627,'JAEN',61),(628,'LAS PIRIAS',61),(629,'POMAHUACA',61),(630,'PUCARA',61),(631,'SALLIQUE',61),(632,'SAN FELIPE',61),(633,'SAN JOSE DEL ALTO',61),(634,'SANTA ROSA',61),(635,'CHIRINOS',62),(636,'HUARANGO',62),(637,'LA COIPA',62),(638,'NAMBALLE',62),(639,'SAN IGNACIO',62),(640,'SAN JOSE DE LOURDES',62),(641,'TABACONAS',62),(642,'CHANCAY',63),(643,'EDUARDO VILLANUEVA',63),(644,'GREGORIO PITA',63),(645,'ICHOCAN',63),(646,'JOSE MANUEL QUIROZ',63),(647,'JOSE SABOGAL',63),(648,'PEDRO GALVEZ',63),(649,'BOLIVAR',64),(650,'CALQUIS',64),(651,'CATILLUC',64),(652,'EL PRADO',64),(653,'LA FLORIDA',64),(654,'LLAPA',64),(655,'NANCHOC',64),(656,'NIEPOS',64),(657,'SAN GREGORIO',64),(658,'SAN MIGUEL',64),(659,'SAN SILVESTRE DE COCHAN',64),(660,'TONGOD',64),(661,'UNION AGUA BLANCA',64),(662,'SAN BERNARDINO',65),(663,'SAN LUIS',65),(664,'SAN PABLO',65),(665,'TUMBADEN',65),(666,'ANDABAMBA',66),(667,'CATACHE',66),(668,'CHANCAYBAÑOS',66),(669,'LA ESPERANZA',66),(670,'NINABAMBA',66),(671,'PULAN',66),(672,'SANTA CRUZ',66),(673,'SAUCEPAMPA',66),(674,'SEXI',66),(675,'UTICYACU',66),(676,'YAUYUCAN',66),(677,'ACOMAYO',67),(678,'ACOPIA',67),(679,'ACOS',67),(680,'MOSOC LLACTA',67),(681,'POMACANCHI',67),(682,'RONDOCAN',67),(683,'SANGARARA',67),(684,'ANCAHUASI',68),(685,'ANTA',68),(686,'CACHIMAYO',68),(687,'CHINCHAYPUJIO',68),(688,'HUAROCONDO',68),(689,'LIMATAMBO',68),(690,'MOLLEPATA',68),(691,'PUCYURA',68),(692,'ZURITE',68),(693,'CALCA',69),(694,'COYA',69),(695,'LAMAY',69),(696,'LARES',69),(697,'PISAC',69),(698,'SAN SALVADOR',69),(699,'TARAY',69),(700,'YANATILE',69),(701,'CHECCA',70),(702,'KUNTURKANKI',70),(703,'LANGUI',70),(704,'LAYO',70),(705,'PAMPAMARCA',70),(706,'QUEHUE',70),(707,'TUPAC AMARU',70),(708,'YANAOCA',70),(709,'CHECACUPE',71),(710,'COMBAPATA',71),(711,'MARANGANI',71),(712,'PITUMARCA',71),(713,'SAN PABLO',71),(714,'SAN PEDRO',71),(715,'SICUANI',71),(716,'TINTA',71),(717,'CAPACMARCA',72),(718,'CHAMACA',72),(719,'COLQUEMARCA',72),(720,'LIVITACA',72),(721,'LLUSCO',72),(722,'QUIÑOTA',72),(723,'SANTO TOMAS',72),(724,'VELILLE',72),(725,'CCORCA',73),(726,'CUSCO',73),(727,'POROY',73),(728,'SAN JERONIMO',73),(729,'SAN SEBASTIAN',73),(730,'SANTIAGO',73),(731,'SAYLLA',73),(732,'WANCHAQ',73),(733,'ALTO PICHIGUA',74),(734,'CONDOROMA',74),(735,'COPORAQUE',74),(736,'ESPINAR',74),(737,'OCORURO',74),(738,'PALLPATA',74),(739,'PICHIGUA',74),(740,'SUYCKUTAMBO',74),(741,'ECHARATE',75),(742,'HUAYOPATA',75),(743,'MARANURA',75),(744,'OCOBAMBA',75),(745,'PICHARI',75),(746,'QUELLOUNO',75),(747,'QUIMBIRI',75),(748,'SANTA ANA',75),(749,'SANTA TERESA',75),(750,'VILCABAMBA',75),(751,'ACCHA',76),(752,'CCAPI',76),(753,'COLCHA',76),(754,'HUANOQUITE',76),(755,'OMACHA',76),(756,'PACCARITAMBO',76),(757,'PARURO',76),(758,'PILLPINTO',76),(759,'YAURISQUE',76),(760,'CAICAY',77),(761,'CHALLABAMBA',77),(762,'COLQUEPATA',77),(763,'HUANCARANI',77),(764,'KOSÑIPATA',77),(765,'PAUCARTAMBO',77),(766,'ANDAHUAYLILLAS',78),(767,'CAMANTI',78),(768,'CCARHUAYO',78),(769,'CCATCA',78),(770,'CUSIPATA',78),(771,'HUARO',78),(772,'LUCRE',78),(773,'MARCAPATA',78),(774,'OCONGATE',78),(775,'OROPESA',78),(776,'QUIQUIJANA',78),(777,'URCOS',78),(778,'CHINCHERO',79),(779,'HUAYLLABAMBA',79),(780,'MACHUPICCHU',79),(781,'MARAS',79),(782,'OLLANTAYTAMBO',79),(783,'URUBAMBA',79),(784,'YUCAY',79),(785,'ACOBAMBA',80),(786,'ANDABAMBA',80),(787,'ANTA',80),(788,'CAJA',80),(789,'MARCAS',80),(790,'PAUCARA',80),(791,'POMACOCHA',80),(792,'ROSARIO',80),(793,'ANCHONGA',81),(794,'CALLANMARCA',81),(795,'CCOCHACCASA',81),(796,'CHINCHO',81),(797,'CONGALLA',81),(798,'HUANCA-HUANCA',81),(799,'HUAYLLAY GRANDE',81),(800,'JULCAMARCA',81),(801,'LIRCAY',81),(802,'SAN ANTONIO DE ANTAPARCO',81),(803,'SANTO TOMAS DE PATA',81),(804,'SECCLLA',81),(805,'ARMA',82),(806,'AURAHUA',82),(807,'CAPILLAS',82),(808,'CASTROVIRREYNA',82),(809,'CHUPAMARCA',82),(810,'COCAS',82),(811,'HUACHOS',82),(812,'HUAMATAMBO',82),(813,'MOLLEPAMPA',82),(814,'SAN JUAN',82),(815,'SANTA ANA',82),(816,'TANTARA',82),(817,'TICRAPO',82),(818,'ANCO',83),(819,'CHINCHIHUASI',83),(820,'CHURCAMPA',83),(821,'EL CARMEN',83),(822,'LA MERCED',83),(823,'LOCROJA',83),(824,'PACHAMARCA',83),(825,'PAUCARBAMBA',83),(826,'SAN MIGUEL DE MAYOCC',83),(827,'SAN PEDRO DE CORIS',83),(828,'ACOBAMBILLA',84),(829,'ACORIA',84),(830,'ASCENCION',84),(831,'CONAYCA',84),(832,'CUENCA',84),(833,'HUACHOCOLPA',84),(834,'HUANCAVELICA',84),(835,'HUANDO',84),(836,'HUANDO',84),(837,'HUAYLLAHUARA',84),(838,'IZCUCHACA',84),(839,'LARIA',84),(840,'MANTA',84),(841,'MARISCAL CACERES',84),(842,'MOYA',84),(843,'NUEVO OCCORO',84),(844,'PALCA',84),(845,'PILCHACA',84),(846,'VILCA',84),(847,'YAULI',84),(848,'AYAVI',85),(849,'CORDOVA',85),(850,'HUAYACUNDO ARMA',85),(851,'HUAYTARA',85),(852,'LARAMARCA',85),(853,'OCOYO',85),(854,'PILPICHACA',85),(855,'QUERCO',85),(856,'QUITO-ARMA',85),(857,'SAN ANTONIO DE CUSICANCHA',85),(858,'SAN FSCO. DE SANGAYAICO',85),(859,'SAN ISIDRO',85),(860,'SANTIAGO DE CHOCORVOS',85),(861,'SANTIAGO DE QUIRAHUARA',85),(862,'SANTO DOMINGO DE CAPILLAS',85),(863,'TAMBO',85),(864,'ACOSTAMBO',86),(865,'ACRAQUIA',86),(866,'AHUAYCHA',86),(867,'COLCABAMBA',86),(868,'DANIEL HERNANDEZ',86),(869,'HUACHOCOLPA',86),(870,'HUARIBAMBA',86),(871,'PAMPAS',86),(872,'PAZOS',86),(873,'QUISHUAR',86),(874,'SALCABAMBA',86),(875,'SALCAHUASI',86),(876,'SAN MARCOS DE ROCCHAC',86),(877,'SURCUBAMBA',86),(878,'TINTAY PUNCU',86),(879,'YAHUIMPUQUIO',86),(880,'AMBO',87),(881,'CAYNA',87),(882,'COLPAS',87),(883,'CONCHAMARCA',87),(884,'HUACAR',87),(885,'SAN FRANCISCO',87),(886,'SAN RAFAEL',87),(887,'TOMAYQUICHUA',87),(888,'CHUQUIS',88),(889,'LA UNION',88),(890,'MARIAS',88),(891,'PACHAS',88),(892,'QUIVILLA',88),(893,'RIPAN',88),(894,'SHUNQUI',88),(895,'SILLAPATA',88),(896,'YANAS',88),(897,'CANCHABAMBA',89),(898,'COCHABAMBA',89),(899,'HUACAYBAMBA',89),(900,'PINRA',89),(901,'ARANCAY',90),(902,'CHAVIN DE PARIARCA',90),(903,'JACAS GRANDE',90),(904,'JIRCAN',90),(905,'LLATA',90),(906,'MIRAFLORES',90),(907,'MONZON',90),(908,'PUNCHAO',90),(909,'PUÑOS',90),(910,'SINGA',90),(911,'TANTAMAYO',90),(912,'AMARILIS',91),(913,'CHINCHAO',91),(914,'CHURUBAMBA',91),(915,'HUANUCO',91),(916,'MARGOS',91),(917,'PILCOMARCA',91),(918,'QUISQUI',91),(919,'SAN FRANCISCO DE CAYRAN',91),(920,'SAN PEDRO DE CHAULAN',91),(921,'SANTA MARIA DEL VALLE',91),(922,'YARUMAYO',91),(923,'BAÑOS',92),(924,'JESUS',92),(925,'JIVIA',92),(926,'QUEROPALCA',92),(927,'RONDOS',92),(928,'SAN FRANCISCO DE ASIS',92),(929,'SAN MIGUEL DE CAURI',92),(930,'DANIEL ALOMIA  ROBLES',93),(931,'HERMILIO VALDIZAN',93),(932,'JOSE CRESPO Y CASTILLO',93),(933,'LUYANDO',93),(934,'MARIANO DAMASO BERAUN',93),(935,'RUPA-RUPA',93),(936,'CHOLON',94),(937,'HUACRACHUCO',94),(938,'SAN BUENAVENTURA',94),(939,'CHAGLLA',95),(940,'MOLINO',95),(941,'PANAO',95),(942,'UMARI',95),(943,'CODO DEL POZUZO',96),(944,'HONORIA',96),(945,'PUERTO INCA',96),(946,'TOURNAVISTA',96),(947,'YUYAPICHIS',96),(948,'APARICIO POMARES',97),(949,'CAHUAC',97),(950,'CHACABAMBA',97),(951,'CHAVINILLO',97),(952,'CHORAS',97),(953,'JACAS CHICO',97),(954,'OBAS',97),(955,'PAMPAMARCA',97),(956,'ALTO LARAN',98),(957,'CHAVIN',98),(958,'CHINCHA ALTA',98),(959,'CHINCHA BAJA',98),(960,'EL CARMEN',98),(961,'GROCIO PRADO',98),(962,'PUEBLO NUEVO',98),(963,'SAN JUAN DE YANAC',98),(964,'SAN PEDRO DE HUACARPANA',98),(965,'SUNAMPE',98),(966,'TAMBO DE MORA',98),(967,'ICA',99),(968,'LA TINGUIÑA',99),(969,'LOS AQUIJES',99),(970,'OCUCAJE',99),(971,'PACHACUTEC',99),(972,'PARCONA',99),(973,'PUEBLO NUEVO',99),(974,'SALAS',99),(975,'SAN JOSE DE LOS MOLINOS',99),(976,'SAN JUAN BAUTISTA',99),(977,'SANTIAGO',99),(978,'SUBTANJALLA',99),(979,'TATE',99),(980,'YAUCA DEL ROSARIO',99),(981,'CHANGUILLO',100),(982,'EL INGENIO',100),(983,'MARCONA',100),(984,'NAZCA',100),(985,'VISTA ALEGRE',100),(986,'LLIPATA',101),(987,'PALPA',101),(988,'RIO GRANDE',101),(989,'SANTA CRUZ',101),(990,'TIBILLO',101),(991,'HUANCANO',102),(992,'HUMAY',102),(993,'INDEPENDENCIA',102),(994,'PARACAS',102),(995,'PISCO',102),(996,'SAN ANDRES',102),(997,'SAN CLEMENTE',102),(998,'TUPAC AMARU INCA',102),(999,'CHANCHAMAYO',103),(1000,'PERENE',103),(1001,'PICHANAQUI',103),(1002,'SAN LUIS DE SHUARO',103),(1003,'SAN RAMON',103),(1004,'VITOC',103),(1005,'AHUAC',104),(1006,'CHONGOS BAJO',104),(1007,'CHUPACA',104),(1008,'HUACHAC',104),(1009,'HUAMANCACA CHICO',104),(1010,'SAN JUAN DE ISCOS',104),(1011,'SAN JUAN DE JARPA',104),(1012,'TRES DE DICIEMBRE',104),(1013,'YANACANCHA',104),(1014,'ACO',105),(1015,'ANDAMARCA',105),(1016,'CHAMBARA',105),(1017,'COCHAS',105),(1018,'COMAS',105),(1019,'CONCEPCION',105),(1020,'HEROINAS TOLEDO',105),(1021,'MANZANARES',105),(1022,'MARISCAL CASTILLA',105),(1023,'MATAHUASI',105),(1024,'MITO',105),(1025,'NUEVE DE JULIO',105),(1026,'ORCOTUNA',105),(1027,'SAN JOSE DE QUERO',105),(1028,'SANTA ROSA DE OCOPA',105),(1029,'CARHUACALLANGA',106),(1030,'CHACAPAMPA',106),(1031,'CHICCHE',106),(1032,'CHILCA',106),(1033,'CHONGOS ALTO',106),(1034,'CHUPURO',106),(1035,'COLCA',106),(1036,'CULLHUAS',106),(1037,'EL TAMBO',106),(1038,'HUACRAPUQUIO',106),(1039,'HUALHUAS',106),(1040,'HUANCAN',106),(1041,'HUANCAYO',106),(1042,'HUASICANCHA',106),(1043,'HUAYUCACHI',106),(1044,'INGENIO',106),(1045,'PARIAHUANCA',106),(1046,'PILCOMAYO',106),(1047,'PUCARA',106),(1048,'QUICHUAY',106),(1049,'QUILCAS',106),(1050,'SAN AGUSTIN',106),(1051,'SAN JERONIMO DE TUNAN',106),(1052,'SANTO DOMINGO DE ACOBAMBA',106),(1053,'SAÑO',106),(1054,'SAPALLANGA',106),(1055,'SICAYA',106),(1056,'VIQUES',106),(1057,'ACOLLA',107),(1058,'APATA',107),(1059,'ATAURA',107),(1060,'CANCHAYLLO',107),(1061,'CURICACA',107),(1062,'EL MANTARO',107),(1063,'HUAMALI',107),(1064,'HUARIPAMPA',107),(1065,'HUERTAS',107),(1066,'JANJAILLO',107),(1067,'JAUJA',107),(1068,'JULCAN',107),(1069,'LEONOR ORDOÑEZ',107),(1070,'LLOCLLAPAMPA',107),(1071,'MARCO',107),(1072,'MASMA',107),(1073,'MASMA CHICCHE',107),(1074,'MOLINOS',107),(1075,'MONOBAMBA',107),(1076,'MUQUI',107),(1077,'MUQUIYAUYO',107),(1078,'PACA',107),(1079,'PACCHA',107),(1080,'PANCAN',107),(1081,'PARCO',107),(1082,'POMACANCHA',107),(1083,'RICRAN',107),(1084,'SAN LORENZO',107),(1085,'SAN PEDRO DE CHUNAN',107),(1086,'SAUSA',107),(1087,'SINCOS',107),(1088,'TUNAN MARCA',107),(1089,'YAULI',107),(1090,'YAUYOS',107),(1091,'CARHUAMAYO',108),(1092,'JUNIN',108),(1093,'ONDORES',108),(1094,'ULCUMAYO',108),(1095,'COVIRIALI',109),(1096,'LLAYLLA',109),(1097,'MAZAMARI',109),(1098,'PAMPA HERMOSA',109),(1099,'PANGOA',109),(1100,'RIO NEGRO',109),(1101,'RIO TAMBO',109),(1102,'SATIPO',109),(1103,'ACOBAMBA',110),(1104,'HUARICOLCA',110),(1105,'HUASAHUASI',110),(1106,'LA UNION',110),(1107,'PALCA',110),(1108,'PALCAMAYO',110),(1109,'SAN PEDRO DE CAJAS',110),(1110,'TAPO',110),(1111,'TARMA',110),(1112,'CHACAPALPA',111),(1113,'HUAY-HUAY',111),(1114,'LA OROYA',111),(1115,'MARCAPOMACOCHA',111),(1116,'MOROCOCHA',111),(1117,'PACCHA',111),(1118,'SANTA ROSA DE SACCO',111),(1119,'STA. BARBARA DE CARHUACAYAN',111),(1120,'SUITUCANCHA',111),(1121,'YAULI',111),(1122,'ASCOPE',112),(1123,'CASA GRANDE',112),(1124,'CHICAMA',112),(1125,'CHOCOPE',112),(1126,'MAGDALENA DE CAO',112),(1127,'PAIJAN',112),(1128,'RAZURI',112),(1129,'SANTIAGO DE CAO',112),(1130,'BAMBAMARCA',113),(1131,'BOLIVAR',113),(1132,'CONDORMARCA',113),(1133,'LONGOTEA',113),(1134,'UCHUMARCA',113),(1135,'UCUNCHA',113),(1136,'CHEPEN',114),(1137,'PACANGA',114),(1138,'PUEBLO NUEVO',114),(1139,'CASCAS',115),(1140,'LUCMA',115),(1141,'MARMOT',115),(1142,'SAYAPULLO',115),(1143,'CALAMARCA',116),(1144,'CARABAMBA',116),(1145,'HUASO',116),(1146,'JULCAN',116),(1147,'AGALLPAMPA',117),(1148,'CHARAT',117),(1149,'HUARANCHAL',117),(1150,'LA CUESTA',117),(1151,'MACHE',117),(1152,'OTUZCO',117),(1153,'PARANDAY',117),(1154,'SALPO',117),(1155,'SINSICAP',117),(1156,'USQUIL',117),(1157,'GUADALUPE',118),(1158,'JEQUETEPEQUE',118),(1159,'PACASMAYO',118),(1160,'SAN JOSE',118),(1161,'SAN PEDRO DE LLOC',118),(1162,'BULDIBUYO',119),(1163,'CHILLIA',119),(1164,'HUANCASPATA',119),(1165,'HUAYLILLAS',119),(1166,'HUAYO',119),(1167,'ONGON',119),(1168,'PARCOY',119),(1169,'PATAZ',119),(1170,'PIAS',119),(1171,'SANTIAGO DE CHALLAS',119),(1172,'TAURIJA',119),(1173,'TAYABAMBA',119),(1174,'URPAY',119),(1175,'CHUGAY',120),(1176,'COCHORCO',120),(1177,'CURGOS',120),(1178,'HUAMACHUCO',120),(1179,'MARCABAL',120),(1180,'SANAGORAN',120),(1181,'SARIN',120),(1182,'SARTIMBAMBA',120),(1183,'ANGASMARCA',121),(1184,'CACHICADAN',121),(1185,'MOLLEBAMBA',121),(1186,'MOLLEPATA',121),(1187,'QUIRUVILCA',121),(1188,'SANTA CRUZ DE CHUCA',121),(1189,'SANTIAGO DE CHUCO',121),(1190,'SITABAMBA',121),(1191,'EL PORVENIR',122),(1192,'FLORENCIA DE MORA',122),(1193,'HUANCHACO',122),(1194,'LA ESPERANZA',122),(1195,'LAREDO',122),(1196,'MOCHE',122),(1197,'POROTO',122),(1198,'SALAVERRY',122),(1199,'SIMBAL',122),(1200,'TRUJILLO',122),(1201,'VICTOR LARCO HERRERA',122),(1202,'CHAO',123),(1203,'GUADALUPITO',123),(1204,'VIRU',123),(1205,'CAYALTI',124),(1206,'CHICLAYO',124),(1207,'CHONGOYAPE',124),(1208,'ETEN',124),(1209,'ETEN PUERTO',124),(1210,'JOSE LEONARDO ORTIZ',124),(1211,'LA VICTORIA',124),(1212,'LAGUNAS',124),(1213,'MONSEFU',124),(1214,'NUEVA ARICA',124),(1215,'OYOTUN',124),(1216,'PATAPO',124),(1217,'PICSI',124),(1218,'PIMENTEL',124),(1219,'POMALCA',124),(1220,'PUCALA',124),(1221,'REQUE',124),(1222,'SANTA ROSA',124),(1223,'SAÑA',124),(1224,'TUMAN',124),(1225,'CANARIS',125),(1226,'FERRENAFE',125),(1227,'INCAHUASI',125),(1228,'MANUEL A. MESONES MURO',125),(1229,'PITIPO',125),(1230,'PUEBLO NUEVO',125),(1231,'CHOCHOPE',126),(1232,'ILLIMO',126),(1233,'JAYANCA',126),(1234,'LAMBAYEQUE',126),(1235,'MOCHUMI',126),(1236,'MORROPE',126),(1237,'MOTUPE',126),(1238,'OLMOS',126),(1239,'PACORA',126),(1240,'SALAS',126),(1241,'SAN JOSE',126),(1242,'TUCUME',126),(1243,'BARRANCA',127),(1244,'PARAMONGA',127),(1245,'PATIVILCA',127),(1246,'SUPE',127),(1247,'SUPE PUERTO',127),(1248,'CAJATAMBO',128),(1249,'COPA',128),(1250,'GORGOR',128),(1251,'HUANCAPON',128),(1252,'MANAS',128),(1253,'BELLAVISTA',129),(1254,'CALLAO',129),(1255,'CARMEN DE LA LEGUA  REYNOSO',129),(1256,'LA PERLA',129),(1257,'LA PUNTA',129),(1258,'VENTANILLA',129),(1259,'ARAHUAY',130),(1260,'CANTA',130),(1261,'HUAMANTANGA',130),(1262,'HUAROS',130),(1263,'LACHAQUI',130),(1264,'SAN BUENAVENTURA',130),(1265,'SANTA ROSA DE QUIVES',130),(1266,'ASIA',131),(1267,'CALANGO',131),(1268,'CERRO AZUL',131),(1269,'CHILCA',131),(1270,'COAYLLO',131),(1271,'IMPERIAL',131),(1272,'LUNAHUANA',131),(1273,'MALA',131),(1274,'NUEVO IMPERIAL',131),(1275,'PACARAN',131),(1276,'QUILMANA',131),(1277,'SAN ANTONIO',131),(1278,'SAN LUIS',131),(1279,'SAN VICENTE DE CAÑETE',131),(1280,'SANTA CRUZ DE FLORES',131),(1281,'ZUÑIGA',131),(1282,'ATAVILLOS ALTO',132),(1283,'ATAVILLOS BAJO',132),(1284,'AUCALLAMA',132),(1285,'CHANCAY',132),(1286,'HUARAL',132),(1287,'IHUARI',132),(1288,'LAMPIAN',132),(1289,'PACARAOS',132),(1290,'SAN MIGUEL DE ACOS',132),(1291,'SANTA CRUZ DE ANDAMARCA',132),(1292,'SUMBILCA',132),(1293,'VEINTISIETE DE NOVIEMBRE',132),(1294,'ANTIOQUIA',133),(1295,'CALLAHUANCA',133),(1296,'CARAMPOMA',133),(1297,'CHICLA',133),(1298,'CUENCA',133),(1299,'HUACHUPAMPA',133),(1300,'HUANZA',133),(1301,'HUAROCHIRI',133),(1302,'LAHUAYTAMBO',133),(1303,'LANGA',133),(1304,'LARAOS',133),(1305,'MARIATANA',133),(1306,'MATUCANA',133),(1307,'RICARDO PALMA',133),(1308,'SAN ANDRES DE TUPICOCHA',133),(1309,'SAN ANTONIO',133),(1310,'SAN BARTOLOME',133),(1311,'SAN DAMIAN',133),(1312,'SAN JUAN DE IRIS',133),(1313,'SAN JUAN DE TANTARANCHE',133),(1314,'SAN LORENZO DE QUINTI',133),(1315,'SAN MATEO',133),(1316,'SAN MATEO DE OTAO',133),(1317,'SAN PEDRO DE CASTA',133),(1318,'SAN PEDRO DE HUANCAYRE',133),(1319,'SANGALLAYA',133),(1320,'SANTA CRUZ DE COCACHACRA',133),(1321,'SANTA EULALIA',133),(1322,'SANTIAGO DE ANCHUCAYA',133),(1323,'SANTIAGO DE TUNA',133),(1324,'STO. DMGO. DE LOS OLLEROS',133),(1325,'SURCO',133),(1326,'AMBAR',134),(1327,'CALETA DE CARQUIN',134),(1328,'CHECRAS',134),(1329,'HUACHO',134),(1330,'HUALMAY',134),(1331,'HUAURA',134),(1332,'LEONCIO PRADO',134),(1333,'PACCHO',134),(1334,'SANTA LEONOR',134),(1335,'SANTA MARIA',134),(1336,'SAYAN',134),(1337,'VEGUETA',134),(1338,'ANCON',135),(1339,'ATE',135),(1340,'BARRANCO',135),(1341,'BREÑA',135),(1342,'CARABAYLLO',135),(1343,'CHACLACAYO',135),(1344,'CHORRILLOS',135),(1345,'CIENEGUILLA',135),(1346,'COMAS',135),(1347,'EL AGUSTINO',135),(1348,'INDEPENDENCIA',135),(1349,'JESUS MARIA',135),(1350,'LA MOLINA',135),(1351,'LA VICTORIA',135),(1352,'LIMA',135),(1353,'LINCE',135),(1354,'LOS OLIVOS',135),(1355,'LURIGANCHO',135),(1356,'LURIN',135),(1357,'MAGDALENA DEL MAR',135),(1358,'MAGDALENA VIEJA',135),(1359,'MIRAFLORES',135),(1360,'PACHACAMAC',135),(1361,'PUCUSANA',135),(1362,'PUENTE PIEDRA',135),(1363,'PUNTA HERMOSA',135),(1364,'PUNTA NEGRA',135),(1365,'RIMAC',135),(1366,'SAN BARTOLO',135),(1367,'SAN BORJA',135),(1368,'SAN ISIDRO',135),(1369,'SAN JUAN DE LURIGANCHO',135),(1370,'SAN JUAN DE MIRAFLORES',135),(1371,'SAN LUIS',135),(1372,'SAN MARTIN DE PORRES',135),(1373,'SAN MIGUEL',135),(1374,'SANTA ANITA',135),(1375,'SANTA MARIA DEL MAR',135),(1376,'SANTA ROSA',135),(1377,'SANTIAGO DE SURCO',135),(1378,'SURQUILLO',135),(1379,'VILLA EL SALVADOR',135),(1380,'VILLA MARIA DEL TRIUNFO',135),(1381,'ANDAJES',136),(1382,'CAUJUL',136),(1383,'COCHAMARCA',136),(1384,'NAVAN',136),(1385,'OYON',136),(1386,'PACHANGARA',136),(1387,'ALIS',137),(1388,'AYAUCA',137),(1389,'AYAVIRI',137),(1390,'AZANGARO',137),(1391,'CACRA',137),(1392,'CARANIA',137),(1393,'CATAHUASI',137),(1394,'CHOCOS',137),(1395,'COCHAS',137),(1396,'COLONIA',137),(1397,'HONGOS',137),(1398,'HUAMPARA',137),(1399,'HUANCAYA',137),(1400,'HUANGASCAR',137),(1401,'HUANTAN',137),(1402,'HUAYEC',137),(1403,'LARAOS',137),(1404,'LINCHA',137),(1405,'MADEAN',137),(1406,'MIRAFLORES',137),(1407,'OMAS',137),(1408,'PUTINZA',137),(1409,'QUINCHES',137),(1410,'QUINOCAY',137),(1411,'SAN JOAQUIN',137),(1412,'SAN PEDRO DE PILAS',137),(1413,'TANTA',137),(1414,'TAURIPAMPA',137),(1415,'TOMAS',137),(1416,'TUPE',137),(1417,'VIÑAC',137),(1418,'VITIS',137),(1419,'YAUYOS',137),(1420,'BALSAPUERTO',138),(1421,'BARRANCA',138),(1422,'CAHUAPANAS',138),(1423,'JEBEROS',138),(1424,'LAGUNAS',138),(1425,'MANSERICHE',138),(1426,'MORONA',138),(1427,'PASTAZA',138),(1428,'SANTA CRUZ',138),(1429,'TENIENTE CESAR LOPEZ ROJAS',138),(1430,'YURIMAGUAS',138),(1431,'NAUTA',139),(1432,'PARINARI',139),(1433,'TIGRE',139),(1434,'TROMPETEROS',139),(1435,'URARINAS',139),(1436,'PEBAS',140),(1437,'RAMON CASTILLA',140),(1438,'SAN PABLO',140),(1439,'YAVARI',140),(1440,'ALTO NANAY',141),(1441,'BELEN',141),(1442,'FERNANDO LORES',141),(1443,'INDIANA',141),(1444,'IQUITOS',141),(1445,'LAS AMAZONAS',141),(1446,'MAZAN',141),(1447,'NAPO',141),(1448,'PUNCHANA',141),(1449,'PUTUMAYO',141),(1450,'SAN JUAN BAUTISTA',141),(1451,'TORRES CAUSANA',141),(1452,'ALTO TAPICHE',142),(1453,'CAPELO',142),(1454,'EMILIO SAN MARTIN',142),(1455,'JENARO HERRERA',142),(1456,'MAQUIA',142),(1457,'PUINAHUA',142),(1458,'REQUENA',142),(1459,'SAQUENA',142),(1460,'SOPLIN',142),(1461,'TAPICHE',142),(1462,'YAQUERANA',142),(1463,'YAQUERANA',142),(1464,'CONTAMANA',143),(1465,'INAHUAYA',143),(1466,'PADRE MARQUEZ',143),(1467,'PAMPA HERMOSA',143),(1468,'SARAYACU',143),(1469,'VARGAS GUERRA',143),(1470,'FITZCARRALD',144),(1471,'HUEPETUCHE',144),(1472,'MADRE DE DIOS',144),(1473,'MANU',144),(1474,'IBERIA',145),(1475,'IÑAPARI',145),(1476,'TAHUAMANU',145),(1477,'INAMBARI',146),(1478,'LABERINTO',146),(1479,'LAS PIEDRAS',146),(1480,'TAMBOPATA',146),(1481,'CHOJATA',147),(1482,'COALAQUE',147),(1483,'ICHUYA',147),(1484,'LA CAPILLA',147),(1485,'LLOQUE',147),(1486,'MATALAQUE',147),(1487,'OMATE',147),(1488,'PUQUINA',147),(1489,'QUINISTAQUILLAS',147),(1490,'UBINAS',147),(1491,'YUNGA',147),(1492,'EL ALGARROBAL',148),(1493,'ILO',148),(1494,'PACOCHA',148),(1495,'CARUMAS',149),(1496,'CUCHUMBAYA',149),(1497,'MOQUEGUA',149),(1498,'SAMEGUA',149),(1499,'SAN CRISTOBAL',149),(1500,'TORATA',149),(1501,'CHACAYAN',150),(1502,'GOYLLARISQUIZGA',150),(1503,'PAUCAR',150),(1504,'SAN PEDRO DE PILLAO',150),(1505,'SANTA ANA DE TUSI',150),(1506,'TAPUC',150),(1507,'VILCABAMBA',150),(1508,'YANAHUANCA',150),(1509,'CHONTABAMBA',151),(1510,'HUANCABAMBA',151),(1511,'OXAPAMPA',151),(1512,'PALCAZU',151),(1513,'POZUZO',151),(1514,'PUERTO BERMUDEZ',151),(1515,'VILLA RICA',151),(1516,'CHAUPIMARCA',152),(1517,'HUACHON',152),(1518,'HUARIACA',152),(1519,'HUAYLLAY',152),(1520,'NINACACA',152),(1521,'PALLANCHACRA',152),(1522,'PAUCARTAMBO',152),(1523,'SAN FCO.DE ASIS DE YARUSYACAN',152),(1524,'SIMON BOLIVAR',152),(1525,'TICLACAYAN',152),(1526,'TINYAHUARCO',152),(1527,'VICCO',152),(1528,'YANACANCHA',152),(1529,'AYABACA',153),(1530,'FRIAS',153),(1531,'JILILI',153),(1532,'LAGUNAS',153),(1533,'MONTERO',153),(1534,'PACAIPAMPA',153),(1535,'PAIMAS',153),(1536,'SAPILLICA',153),(1537,'SICCHEZ',153),(1538,'SUYO',153),(1539,'CANCHAQUE',154),(1540,'EL CARMEN DE LA FRONTERA',154),(1541,'HUANCABAMBA',154),(1542,'HUARMACA',154),(1543,'LALAQUIZ',154),(1544,'SAN MIGUEL DE EL FAIQUE',154),(1545,'SONDOR',154),(1546,'SONDORILLO',154),(1547,'BUENOS AIRES',155),(1548,'CHALACO',155),(1549,'CHULUCANAS',155),(1550,'LA MATANZA',155),(1551,'MORROPON',155),(1552,'SALITRAL',155),(1553,'SAN JUAN DE BIGOTE',155),(1554,'SANTA CATALINA DE MOSSA',155),(1555,'SANTO DOMINGO',155),(1556,'YAMANGO',155),(1557,'AMOTAPE',156),(1558,'ARENAL',156),(1559,'COLAN',156),(1560,'LA HUACA',156),(1561,'PAITA',156),(1562,'TAMARINDO',156),(1563,'VICHAYAL',156),(1564,'CASTILLA',157),(1565,'CATACAOS',157),(1566,'CURA MORI',157),(1567,'EL TALLAN',157),(1568,'LA ARENA',157),(1569,'LA UNION',157),(1570,'LAS LOMAS',157),(1571,'PIURA',157),(1572,'TAMBO GRANDE',157),(1573,'BELLAVISTA DE LA UNION',158),(1574,'BERNAL',158),(1575,'CRISTO NOS VALGA',158),(1576,'RINCONADA LLICUAR',158),(1577,'SECHURA',158),(1578,'VICE',158),(1579,'BELLAVISTA',159),(1580,'IGNACIO ESCUDERO',159),(1581,'LANCONES',159),(1582,'MARCAVELICA',159),(1583,'MIGUEL CHECA',159),(1584,'QUERECOTILLO',159),(1585,'SALITRAL',159),(1586,'SULLANA',159),(1587,'EL ALTO',160),(1588,'LA BREA',160),(1589,'LOBITOS',160),(1590,'LOS ORGANOS',160),(1591,'MANCORA',160),(1592,'PARIÑAS',160),(1593,'ACHAYA',161),(1594,'ARAPA',161),(1595,'ASILLO',161),(1596,'AZANGARO',161),(1597,'CAMINACA',161),(1598,'CHUPA',161),(1599,'JOSE D. CHOQUEHUANCA',161),(1600,'MUYANI',161),(1601,'POTONI',161),(1602,'SAMAN',161),(1603,'SAN ANTON',161),(1604,'SAN JOSE',161),(1605,'SAN JUAN DE SALINAS',161),(1606,'SANTIAGO DE PUPUJA',161),(1607,'TIRAPATA',161),(1608,'AJOYANI',162),(1609,'AYAPATA',162),(1610,'COASA',162),(1611,'CORANI',162),(1612,'CRUCERO',162),(1613,'ITUATA',162),(1614,'MACUSANI',162),(1615,'OLLACHEA',162),(1616,'SAN GABAN',162),(1617,'USICAYOS',162),(1618,'DESAGUADERO',163),(1619,'HUACULLANI',163),(1620,'JULI',163),(1621,'KELLUYO',163),(1622,'PISACOMA',163),(1623,'POMATA',163),(1624,'ZEPITA',163),(1625,'CAPAZO',164),(1626,'CONDURIRI',164),(1627,'ILAVE',164),(1628,'PILCUYO',164),(1629,'SANTA ROSA',164),(1630,'COJATA',165),(1631,'HUANCANE',165),(1632,'HUATASANI',165),(1633,'INCHUPALLA',165),(1634,'PUSI',165),(1635,'ROSASPATA',165),(1636,'TARACO',165),(1637,'VILQUE CHICO',165),(1638,'CABANILLA',166),(1639,'CALAPUJA',166),(1640,'LAMPA',166),(1641,'NICASIO',166),(1642,'OCUVIRI',166),(1643,'PALCA',166),(1644,'PARATIA',166),(1645,'PUCARA',166),(1646,'SANTA LUCIA',166),(1647,'VILAVILA',166),(1648,'ANTAUTA',167),(1649,'AYAVIRI',167),(1650,'CUPI',167),(1651,'LLALLI',167),(1652,'MACARI',167),(1653,'NUYOA',167),(1654,'ORURILLO',167),(1655,'SANTA ROSA',167),(1656,'UMACHIRI',167),(1657,'CONIMA',168),(1658,'HUAYRAPATA',168),(1659,'MOHO',168),(1660,'TILALI',168),(1661,'ACORA',169),(1662,'AMANTANI',169),(1663,'ATUNCOLLA',169),(1664,'CAPACHICA',169),(1665,'CHUCUITO',169),(1666,'COATA',169),(1667,'HUATA',169),(1668,'MAYAZO',169),(1669,'PAUCARCOLLA',169),(1670,'PICHACANI',169),(1671,'PLATERIA',169),(1672,'PUNO',169),(1673,'SAN ANTONIO',169),(1674,'TIQUILLACA',169),(1675,'VILQUE',169),(1676,'ANANEA',170),(1677,'PEDRO VILCA APAZA',170),(1678,'PUTINA',170),(1679,'QUILCAPUNCU',170),(1680,'SINA',170),(1681,'CABANA',171),(1682,'CABANILLAS',171),(1683,'CARACOTO',171),(1684,'JULIACA',171),(1685,'ALTO INAMBARI',172),(1686,'CUYOCUYO',172),(1687,'LIMBANI',172),(1688,'PATAMBUCO',172),(1689,'PHARA',172),(1690,'QUIACA',172),(1691,'SAN JUAN DEL ORO',172),(1692,'SANDIA',172),(1693,'YANAHUAYA',172),(1694,'ANAPIA',173),(1695,'COPANI',173),(1696,'CUTURAPI',173),(1697,'OLLARAYA',173),(1698,'TINICACHI',173),(1699,'UNICACHI',173),(1700,'YUNGUYO',173),(1701,'ALTO BIAVO',174),(1702,'BAJO BIAVO',174),(1703,'BELLAVISTA',174),(1704,'HUALLAGA',174),(1705,'SAN PABLO',174),(1706,'SAN RAFAEL',174),(1707,'AGUA BLANCA',175),(1708,'SAN JOSE DE SISA',175),(1709,'SAN MARTIN',175),(1710,'SANTA ROSA',175),(1711,'SHATOJA',175),(1712,'ALTO SAPOSOA',176),(1713,'EL ESLABON',176),(1714,'PISCOYACU',176),(1715,'SACANCHE',176),(1716,'SAPOSOA',176),(1717,'TINGO DE SAPOSOA',176),(1718,'ALONSO DE ALVARADO',177),(1719,'BARRANQUITA',177),(1720,'CAYNARACHI',177),(1721,'CUÑUMBUQUI',177),(1722,'LAMAS',177),(1723,'PINTO RECODO',177),(1724,'RUMISAPA',177),(1725,'SAN ROQUE DE CUMBAZA',177),(1726,'SHANAO',177),(1727,'TABALOSOS',177),(1728,'ZAPATERO',177),(1729,'CAMPANILLA',178),(1730,'HUICUNGO',178),(1731,'JUANJUI',178),(1732,'PACHIZA',178),(1733,'PAJARILLO',178),(1734,'CALZADA',179),(1735,'HABANA',179),(1736,'JEPELACIO',179),(1737,'MOYOBAMBA',179),(1738,'SORITOR',179),(1739,'YANTALO',179),(1740,'BUENOS AIRES',180),(1741,'CASPISAPA',180),(1742,'PICOTA',180),(1743,'PILLUANA',180),(1744,'PUCACACA',180),(1745,'SAN CRISTOBAL',180),(1746,'SAN HILARION',180),(1747,'SHAMBOYACU',180),(1748,'TINGO DE PONASA',180),(1749,'TRES UNIDOS',180),(1750,'AWAJUN',181),(1751,'ELIAS SOPLIN VARGAS',181),(1752,'NUEVA CAJAMARCA',181),(1753,'PARDO MIGUEL',181),(1754,'POSIC',181),(1755,'RIOJA',181),(1756,'SAN FERNANDO',181),(1757,'YORONGOS',181),(1758,'YURACYACU',181),(1759,'ALBERTO LEVEAU',182),(1760,'CACATACHI',182),(1761,'CHAZUTA',182),(1762,'CHIPURANA',182),(1763,'EL PORVENIR',182),(1764,'HUIMBAYOC',182),(1765,'JUAN GUERRA',182),(1766,'LA BANDA DE SHILCAYO',182),(1767,'MORALES',182),(1768,'PAPAPLAYA',182),(1769,'SAN ANTONIO',182),(1770,'SAUCE',182),(1771,'SHAPAJA',182),(1772,'TARAPOTO',182),(1773,'NUEVO PROGRESO',183),(1774,'POLVORA',183),(1775,'SHUNTE',183),(1776,'TOCACHE',183),(1777,'UCHIZA',183),(1778,'CAIRANI',184),(1779,'CAMILACA',184),(1780,'CANDARAVE',184),(1781,'CURIBAYA',184),(1782,'HUANUARA',184),(1783,'QUILAHUANI',184),(1784,'ILABAYA',185),(1785,'ITE',185),(1786,'LOCUMBA',185),(1787,'ALTO DE LA ALIANZA',186),(1788,'CALANA',186),(1789,'CIUDAD NUEVA',186),(1790,'GREGORIO ALBARRACIN LANCHIPA',186),(1791,'INCLAN',186),(1792,'PACHIA',186),(1793,'PALCA',186),(1794,'POCOLLAY',186),(1795,'SAMA',186),(1796,'TACNA',186),(1797,'ESTIQUE',187),(1798,'ESTIQUE-PAMPA',187),(1799,'HEROES ALBARRACIN',187),(1800,'SITAJARA',187),(1801,'SUSAPAYA',187),(1802,'TARATA',187),(1803,'TARUCACHI',187),(1804,'TICACO',187),(1805,'CASITAS',188),(1806,'ZORRITOS',188),(1807,'CORRALES',189),(1808,'LA CRUZ',189),(1809,'PAMPAS DE HOSPITAL',189),(1810,'SAN JACINTO',189),(1811,'SAN JUAN DE LA VIRGEN',189),(1812,'TUMBES',189),(1813,'AGUAS VERDES',190),(1814,'MATAPALO',190),(1815,'PAPAYAL',190),(1816,'ZARUMILLA',190),(1817,'RAYMONDI',191),(1818,'SEPAHUA',191),(1819,'TAHUANIA',191),(1820,'YURUA',191),(1821,'CALLERIA',192),(1822,'CAMPOVERDE',192),(1823,'IPARIA',192),(1824,'MASISEA',192),(1825,'NUEVA REQUENA',192),(1826,'YARINACOCHA',192),(1827,'CURIMANA',193),(1828,'IRAZOLA',193),(1829,'PADRE ABAD',193),(1830,'PURUS',194);
/*!40000 ALTER TABLE `distrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `egreso`
--

DROP TABLE IF EXISTS `egreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `egreso` (
  `id_egreso` int NOT NULL AUTO_INCREMENT,
  `id_caja` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_negocio` int DEFAULT NULL,
  `id_tipo_egreso` int DEFAULT NULL,
  `numero_egreso` int DEFAULT NULL,
  `fechacreacion_egreso` datetime DEFAULT NULL,
  `valor_egreso` float(11,2) DEFAULT NULL,
  `comentario_egreso` text,
  `responsable_egreso` varchar(255) DEFAULT NULL,
  `estado_egreso` int DEFAULT '1',
  `path_egreso` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_egreso`) USING BTREE,
  KEY `fk_caja_egreso` (`id_caja`) USING BTREE,
  KEY `fk_tipoegreso_egreso` (`id_tipo_egreso`) USING BTREE,
  CONSTRAINT `fk_caja_egreso` FOREIGN KEY (`id_caja`) REFERENCES `caja` (`id_caja`),
  CONSTRAINT `fk_tipoegreso_egreso` FOREIGN KEY (`id_tipo_egreso`) REFERENCES `tipo_egreso` (`id_tipo_egreso`)
) ENGINE=InnoDB AUTO_INCREMENT=7908 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `egreso`
--

LOCK TABLES `egreso` WRITE;
/*!40000 ALTER TABLE `egreso` DISABLE KEYS */;
/*!40000 ALTER TABLE `egreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa_externa`
--

DROP TABLE IF EXISTS `empresa_externa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa_externa` (
  `id_empresa_externa` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int DEFAULT NULL,
  `rut_empresa_externa` int DEFAULT NULL,
  `dv_empresa_externa` varchar(255) DEFAULT NULL,
  `razonsocial_empresa_externa` varchar(255) DEFAULT NULL,
  `vigente_empresa_externa` int DEFAULT NULL,
  `nombre_empresa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_empresa_externa`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa_externa`
--

LOCK TABLES `empresa_externa` WRITE;
/*!40000 ALTER TABLE `empresa_externa` DISABLE KEYS */;
/*!40000 ALTER TABLE `empresa_externa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa_venta_online`
--

DROP TABLE IF EXISTS `empresa_venta_online`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa_venta_online` (
  `id_empresa_venta_online` int NOT NULL AUTO_INCREMENT,
  `id_lista_precio` int DEFAULT NULL,
  `id_sucursal` int DEFAULT NULL,
  `idDistrito` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `ruc_empresa_venta_online` varchar(255) DEFAULT NULL,
  `nombre_empresa_venta_online` varchar(255) DEFAULT NULL,
  `razon_social_empresa_venta_online` varchar(255) DEFAULT NULL,
  `telefono_empresa_venta_online` varchar(255) DEFAULT NULL,
  `celular_empresa_venta_online` varchar(255) DEFAULT NULL,
  `direccion_empresa_venta_online` varchar(255) DEFAULT NULL,
  `email_empresa_venta_online` varchar(255) DEFAULT NULL,
  `giro_empresa_venta_online` varchar(255) DEFAULT NULL,
  `tokenaccesoapi_empresa_venta_online` varchar(255) DEFAULT NULL,
  `mostrarstockdisponibledesde_empresa_venta_online` int DEFAULT NULL,
  `dominio_empresa_venta_online` varchar(255) DEFAULT NULL,
  `pathfoto_empresa_venta_online` varchar(255) DEFAULT NULL,
  `pixelgoogle_empresa_venta_online` longtext,
  `pixelfacebook_empresa_venta_online` longtext,
  `urlicono_empresa_venta_online` longtext,
  `public_idicono_empresa_venta_online` longtext,
  `urllogohorizontal_empresa_venta_online` longtext,
  `public_idlogohorizontal_empresa_venta_online` longtext,
  `urllogovertical_empresa_venta_online` longtext,
  `public_idlogovertical_empresa_venta_online` longtext,
  `comprobante_defecto_venta_online` varchar(255) DEFAULT NULL,
  `serie_boleta_empresa_venta_online` char(4) DEFAULT NULL,
  `serie_factura_empresa_venta_online` char(4) DEFAULT NULL,
  `serie_nc_boleta_empresa_venta_online` char(4) DEFAULT NULL,
  `serie_nc_factura_empresa_venta_online` char(4) DEFAULT NULL,
  `serie_nd_boleta_empresa_venta_online` char(4) DEFAULT NULL,
  `serie_nd_factura_empresa_venta_online` char(4) DEFAULT NULL,
  `serie_nota_venta_empresa_venta_online` char(4) DEFAULT NULL,
  PRIMARY KEY (`id_empresa_venta_online`) USING BTREE,
  KEY `fk_id_sucursal_empresa_venta_online` (`id_sucursal`) USING BTREE,
  KEY `fk_id_bodega_empresa_venta_online` (`id_bodega`) USING BTREE,
  KEY `fk_idDistrito` (`idDistrito`) USING BTREE,
  CONSTRAINT `fk_id_bodega_empresa_venta_online` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_id_sucursal_empresa_venta_online` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  CONSTRAINT `fk_idDistrito` FOREIGN KEY (`idDistrito`) REFERENCES `distrito` (`idDistrito`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa_venta_online`
--

LOCK TABLES `empresa_venta_online` WRITE;
/*!40000 ALTER TABLE `empresa_venta_online` DISABLE KEYS */;
INSERT INTO `empresa_venta_online` VALUES (34,NULL,17,1330,18,'10157622680','SALUD VIDA ROSA','DURAND IBAÑEZ JHONNY CHARLES','','','Cruz de cano 101','yovanna@gmail.com','VENTAS DE MEDICAMENTOS Y ACCESORIOS UTILES',NULL,NULL,'localhost',NULL,'','<script>\n!function(f,b,e,v,n,t,s)\n{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\nn.callMethod.apply(n,arguments):n.queue.push(arguments)};\nif(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';\nn.queue=[];t=b.createElement(e);t.async=!0;\nt.src=v;s=b.getElementsByTagName(e)[0];\ns.parentNode.insertBefore(t,s)}(window, document,\'script\',\n\'https://connect.facebook.net/en_US/fbevents.js\');\nfbq(\'init\', \'2050426555337758\');\nfbq(\'track\', \'PageView\');\n</script>\n<noscript><img height=\"1\" width=\"1\" style=\"display:none\"\nsrc=\"https://www.facebook.com/tr?id=2050426555337758&ev=PageView&noscript=1\"\n/></noscript>','https://res.cloudinary.com/do7dzakiw/image/upload/v1706107803/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagenes_empresa/wakymm3nfkxya0kn6q8j.png','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagenes_empresa/wakymm3nfkxya0kn6q8j','https://res.cloudinary.com/do7dzakiw/image/upload/v1709074078/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagenes_empresa/jgb9n2llymgyununtbpm.png','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagenes_empresa/jgb9n2llymgyununtbpm','https://res.cloudinary.com/do7dzakiw/image/upload/v1704836642/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagenes_empresa/k7ssg5mgrn1fmqgjiq5w.png','api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagenes_empresa/k7ssg5mgrn1fmqgjiq5w',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'N001');
/*!40000 ALTER TABLE `empresa_venta_online` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `envio_resumen`
--

DROP TABLE IF EXISTS `envio_resumen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `envio_resumen` (
  `id_envio_resumen` int NOT NULL AUTO_INCREMENT,
  `fecha_envio_resumen` date DEFAULT NULL,
  `numeroenvio_envio_resumen` int DEFAULT NULL,
  `cantidadexentas_envio_resumen` int DEFAULT NULL,
  `cantidadafectas_envio_resumen` int DEFAULT NULL,
  `cantidadanuladas_envio_resumen` int DEFAULT NULL,
  `jsonsalida_envio_resumen` longtext,
  `respuesta_envio_resumen` longtext,
  `estado_envio_resumen` int DEFAULT NULL,
  PRIMARY KEY (`id_envio_resumen`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `envio_resumen`
--

LOCK TABLES `envio_resumen` WRITE;
/*!40000 ALTER TABLE `envio_resumen` DISABLE KEYS */;
/*!40000 ALTER TABLE `envio_resumen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especificaciones_producto`
--

DROP TABLE IF EXISTS `especificaciones_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especificaciones_producto` (
  `id_especificaciones_producto` int NOT NULL AUTO_INCREMENT,
  `glosa_especificaciones_producto` varchar(1000) DEFAULT NULL,
  `respuesta_especificaciones_producto` varchar(100) DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `vigente_especificaciones_producto` int DEFAULT NULL,
  PRIMARY KEY (`id_especificaciones_producto`) USING BTREE,
  KEY `fk_especificaciones_producto_1` (`id_producto`) USING BTREE,
  CONSTRAINT `fk_especificaciones_producto_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especificaciones_producto`
--

LOCK TABLES `especificaciones_producto` WRITE;
/*!40000 ALTER TABLE `especificaciones_producto` DISABLE KEYS */;
INSERT INTO `especificaciones_producto` VALUES (1,'Paracetamol','Contiene 400 mg',192,1),(2,'Noscapina Clorhidrato','Contiene 10 mg',192,1),(3,'Vitamina C','Contiene 50 mg',192,1),(4,'Paracetamol','Contiene 400 mg',191,1),(5,'Noscapina Clorhidrato','Contiene 10 mg',191,1),(6,'Vitamina C ',' 50 mg',191,1),(7,'Indicaciones','Para el tratamiento sintomático de la gripe y el resfrío',191,1),(8,'Dosis usual','3 sobres al día o bien 2 sobres al día más 1 de noche antes de acostarse',191,1),(9,'ATC','HA06AX',190,1),(11,'Dimensiones','6 x 9 x 9 cm x 0 . kg',190,1),(12,'Principio Activo','CASSIA ANGUSTIFOLIA 125 mg',190,1),(13,'Tipo Producto','PRODUCTO NATURAL EXTRANJERO',190,1),(14,'Via Administración','ORAL',190,1),(15,'Modo Uso','2 comprimidos recubiertos al día, de preferencia antes de acostarse. Este producto hace efecto de 8 ',190,1),(16,'Extracto seco de hojas Cassia angustifolia Vahl','Contiene 20% p/p…125,00 mg ',190,1),(17,'Descripción','Contiene Cada sobre de 5gr ',189,1),(18,'Bicarbonato de sodio','Contiene 2.18gr',189,1),(19,'Sulfato de magnesio','Contiene 0.88gr',189,1),(20,'Sacarina sódica','Contiene 2.5mg',189,1),(21,'Dosis','Adultos y niños mayores de 12 años',189,1),(22,'Preparación','Disuelva un sobre en medio vaso de agua. Repita si es necesario.',189,1),(23,'Vitamina B1 (tiamina)','Es indispensable para el crecimiento, desarrollo y funcionamiento de las células del organismo',184,1),(24,'Vitamina B2 (riboflavina)','Contribuye a la generación de energía y desarrollo celular',184,1),(25,'Vitamina B3 (niacina)','Se recomienda para nivelar el colesterol en la sangre.',184,1),(26,'Vitamina B5 (ácido pantoténico)','Mejora el funcionamiento del sistema inmune',184,1),(27,'Vitamina B6 (piridoxina)','Evita la anemia, el riesgo de depresión y erupciones en la piel.',184,1),(28,'Vitamina B7 (biotina)','Auxilia para el correcto funcionamiento del metabolismo',184,1),(29,'Vitamina B9 (ácido fólico)','Previene la anemia megaloblástica y perniciosa',184,1),(30,'Vitamina B12 (cobalamina)','Ayuda a producir glóbulos rojos, ADN, ARN, energía y tejidos',184,1),(31,'Naproxeno Sódico','Contiene 275 mg',180,1),(32,'Vía de Administración','Oral',180,1),(33,'Adultos','1 tableta cada 12 horas',180,1),(34,'Dosis Maxima',' No debe exceder 1.100 mg (4 tabletas)',180,1),(35,'Material','No aplica',177,1),(36,'Beneficios De Uso','Elimina los piojos.+',177,1),(37,'Advertencias De Almacenamiento','Conservar en un lugar fresco y seco.',177,1),(38,'Advertencias De Uso	','Mantener afuera del alcance de los niños.',177,1),(39,'Paracetamol ','Contienes 450mg',173,1),(40,'Orfenadrina','Contienes 35mg',173,1),(41,'Beneficio','Remueve más placa que un cepillo manual.',169,1),(42,'Característica destacada','Mango ergonómico de goma suave.',169,1),(43,'Ingrediente','Fluoruro de sodio; agentes limpiadores y ingredientes refrescantes',168,1),(46,'Beneficio','Protección contra las caries; eliminación de la placa bacteriana y adecuado para toda la familia',168,1),(49,'Instrucciones de Uso','Indicaciones sobre la cantidad de pasta dental a usar en cada cepillado y la frecuencia recomendada ',168,1),(50,'Tipo de Producto','Desodorantes',193,1),(51,'Beneficios','Limpieza y Exfoliación',193,1),(52,'Contenido','150ml',193,1),(53,'Género','Masculino',193,1),(54,'Zona Aplicación','Cuerpo y Rostro',193,1),(55,'Protección Solar SPF','Sin SPF',193,1);
/*!40000 ALTER TABLE `especificaciones_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_pago`
--

DROP TABLE IF EXISTS `estado_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_pago` (
  `id_estado_pago` int NOT NULL AUTO_INCREMENT,
  `glosa_estado_pago` varchar(255) DEFAULT NULL,
  `color_estado_pago` varchar(255) DEFAULT NULL,
  `orden_estado_pago` int DEFAULT NULL,
  `vigente_estado_pago` int DEFAULT NULL,
  PRIMARY KEY (`id_estado_pago`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_pago`
--

LOCK TABLES `estado_pago` WRITE;
/*!40000 ALTER TABLE `estado_pago` DISABLE KEYS */;
INSERT INTO `estado_pago` VALUES (1,'Sin Pagar',NULL,1,1),(2,'Resembolsado Parcialmente',NULL,2,1),(3,'Pendiente',NULL,3,1),(4,'Autorizado',NULL,4,1),(5,'Pagado',NULL,5,1),(6,'Pagado Parcialmente',NULL,6,1),(7,'Rembolsado',NULL,7,1),(8,'Anulado',NULL,8,1),(9,'Vencido',NULL,9,1);
/*!40000 ALTER TABLE `estado_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_pedido`
--

DROP TABLE IF EXISTS `estado_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_pedido` (
  `id_estado_pedido` int NOT NULL AUTO_INCREMENT,
  `glosa_estado_pedido` varchar(255) DEFAULT NULL,
  `color_estado_pedido` varchar(255) DEFAULT NULL,
  `orden_estado_pedido` int DEFAULT NULL,
  `vigente_estado_pedido` int DEFAULT NULL,
  PRIMARY KEY (`id_estado_pedido`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_pedido`
--

LOCK TABLES `estado_pedido` WRITE;
/*!40000 ALTER TABLE `estado_pedido` DISABLE KEYS */;
INSERT INTO `estado_pedido` VALUES (1,'Abierto',NULL,1,1),(2,'Archivado',NULL,2,1),(3,'Cancelado',NULL,3,1);
/*!40000 ALTER TABLE `estado_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_preparacion`
--

DROP TABLE IF EXISTS `estado_preparacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_preparacion` (
  `id_estado_preparacion` int NOT NULL AUTO_INCREMENT,
  `glosa_estado_preparacion` varchar(255) DEFAULT NULL,
  `color_estado_preparacion` varchar(255) DEFAULT NULL,
  `orden_estado_preparacion` int DEFAULT NULL,
  `vigente_estado_preparacion` int DEFAULT NULL,
  PRIMARY KEY (`id_estado_preparacion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_preparacion`
--

LOCK TABLES `estado_preparacion` WRITE;
/*!40000 ALTER TABLE `estado_preparacion` DISABLE KEYS */;
INSERT INTO `estado_preparacion` VALUES (1,'Preparado',NULL,1,1),(2,'Por Preparar',NULL,2,1),(3,'Preparado Parcialmente',NULL,3,1),(4,'Programado',NULL,4,1),(5,'Espera',NULL,5,1),(6,'Preparando-Picking',NULL,6,1),(7,'Entregado',NULL,7,1),(8,'Anulado',NULL,8,1);
/*!40000 ALTER TABLE `estado_preparacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examenes`
--

DROP TABLE IF EXISTS `examenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examenes` (
  `id_examen` int NOT NULL AUTO_INCREMENT,
  `glosa_examen` varchar(255) DEFAULT NULL,
  `detalle_examen` text,
  `precio_examen` float DEFAULT NULL,
  `aplicaiva_examen` int DEFAULT NULL,
  `orden_examen` int DEFAULT NULL,
  `vigente_examen` int DEFAULT '1',
  PRIMARY KEY (`id_examen`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examenes`
--

LOCK TABLES `examenes` WRITE;
/*!40000 ALTER TABLE `examenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `examenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura`
--

DROP TABLE IF EXISTS `factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factura` (
  `id_factura` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_negocio` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `numero_factura` varchar(255) DEFAULT NULL,
  `serie_factura` varchar(255) DEFAULT NULL,
  `fechacreacion_factura` datetime DEFAULT NULL,
  `valorafecto_factura` float DEFAULT NULL,
  `valorexento_factura` float DEFAULT NULL,
  `iva_factura` float DEFAULT NULL,
  `total_factura` float DEFAULT NULL,
  `fechavencimiento_factura` date DEFAULT NULL,
  `estado_factura` int DEFAULT NULL,
  `urlpdf_factura` varchar(255) DEFAULT NULL,
  `urlxml_factura` varchar(255) DEFAULT NULL,
  `path_documento` varchar(255) DEFAULT NULL,
  `saldo_factura` float(255,0) DEFAULT NULL,
  `path_ticket_factura` varchar(255) DEFAULT NULL,
  `xml_factura` text,
  `cdrzip_factura` text,
  `comentario_factura` varchar(255) DEFAULT NULL,
  `respuesta_sunat_factura` longtext,
  PRIMARY KEY (`id_factura`) USING BTREE,
  KEY `fk_relationship_201` (`id_cliente`) USING BTREE,
  KEY `fk_relationship_202` (`id_negocio`) USING BTREE,
  KEY `fk_relationship_210` (`id_usuario`) USING BTREE,
  KEY `fk_relationship_239` (`id_folio`) USING BTREE,
  CONSTRAINT `fk_relationship_201` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_relationship_202` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_relationship_210` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `fk_relationship_239` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura`
--

LOCK TABLES `factura` WRITE;
/*!40000 ALTER TABLE `factura` DISABLE KEYS */;
/*!40000 ALTER TABLE `factura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folio`
--

DROP TABLE IF EXISTS `folio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `folio` (
  `id_folio` int NOT NULL AUTO_INCREMENT,
  `glosa_folio` varchar(255) DEFAULT NULL,
  `numero_folio` int DEFAULT NULL,
  `serie_folio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_folio`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folio`
--

LOCK TABLES `folio` WRITE;
/*!40000 ALTER TABLE `folio` DISABLE KEYS */;
INSERT INTO `folio` VALUES (1,'Orden de Compra',1,NULL),(2,'Negocio',364,NULL),(3,'Ficha',1,NULL),(4,'Ingreso',361,NULL),(5,'Comprobante de Ingreso',1,NULL),(6,'Boleta Afecta',7,'BL01'),(7,'Boleta Exenta',1,NULL),(8,'Nota de Credito',2,'BNL1'),(9,'Factura Afecta',46,'F111'),(10,'Comprobante Interno',1,NULL),(11,'Pedido',64,NULL),(12,'Nota de Credito',9,'FN01'),(13,'Factura Exenta',1,NULL),(14,'Nota Debito Boleta',1,NULL),(15,'Guia Despacho',1,NULL),(16,'Resumen Anulacion',1,'20210225'),(17,'Nota de Venta',193,'N001'),(18,'Egreso',18,NULL),(19,'Nota Debito Factura',1,NULL);
/*!40000 ALTER TABLE `folio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folio_empresa_externa`
--

DROP TABLE IF EXISTS `folio_empresa_externa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `folio_empresa_externa` (
  `id_folio_empresa_externa` int NOT NULL AUTO_INCREMENT,
  `id_empresa_externa` int DEFAULT NULL,
  `nombre_folio_empresa_externa` varchar(255) DEFAULT NULL,
  `valor_folio_empresa_externa` varchar(11) DEFAULT NULL,
  `sii_folio_empresa_externa` int DEFAULT NULL,
  PRIMARY KEY (`id_folio_empresa_externa`) USING BTREE,
  KEY `fk_folio_empresa_externa_empresaexterna` (`id_empresa_externa`) USING BTREE,
  CONSTRAINT `fk_folio_empresa_externa_empresaexterna` FOREIGN KEY (`id_empresa_externa`) REFERENCES `empresa_externa` (`id_empresa_externa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folio_empresa_externa`
--

LOCK TABLES `folio_empresa_externa` WRITE;
/*!40000 ALTER TABLE `folio_empresa_externa` DISABLE KEYS */;
/*!40000 ALTER TABLE `folio_empresa_externa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guia_despacho`
--

DROP TABLE IF EXISTS `guia_despacho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guia_despacho` (
  `id_guia_despacho` int NOT NULL AUTO_INCREMENT,
  `id_folio` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_proveedor` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_motivo_despacho` int DEFAULT NULL,
  `id_comuna` int DEFAULT NULL,
  `numero_guia_despacho` varchar(255) DEFAULT NULL,
  `fechacreacion_guia_despacho` datetime DEFAULT NULL,
  `fecha_guia_despacho` date DEFAULT NULL,
  `valorneto_guia_despacho` float DEFAULT NULL,
  `iva_guia_despacho` float DEFAULT NULL,
  `descuento_guia_despacho` float DEFAULT NULL,
  `total_guia_despacho` float DEFAULT NULL,
  `estado_guia_despacho` int DEFAULT '1',
  `pathcarta_guia_despacho` varchar(255) DEFAULT NULL,
  `pathticket_guia_despacho` varchar(255) DEFAULT NULL,
  `urlcarta_guia_despacho` varchar(500) DEFAULT NULL,
  `urlticket_guia_despacho` varchar(500) DEFAULT NULL,
  `direccionentrega_guia_despacho` varchar(255) DEFAULT NULL,
  `email_guia_despacho` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_guia_despacho`) USING BTREE,
  KEY `fk_idfolio_guia_despacho` (`id_folio`) USING BTREE,
  KEY `fk_idcliente_guia_despacho` (`id_cliente`) USING BTREE,
  KEY `fk_idproveedor_guia_despacho` (`id_proveedor`) USING BTREE,
  KEY `fk_idusuario_guia_despacho` (`id_usuario`) USING BTREE,
  KEY `fk_idmotivodespacho_motivo_despacho` (`id_motivo_despacho`) USING BTREE,
  KEY `fk_comunaguiadespacho_comuna` (`id_comuna`) USING BTREE,
  CONSTRAINT `fk_comunaguiadespacho_comuna` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`),
  CONSTRAINT `fk_idcliente_guia_despacho` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_idfolio_guia_despacho` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_idmotivodespacho_motivo_despacho` FOREIGN KEY (`id_motivo_despacho`) REFERENCES `motivo_despacho` (`id_motivo_despacho`),
  CONSTRAINT `fk_idproveedor_guia_despacho` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  CONSTRAINT `fk_idusuario_guia_despacho` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guia_despacho`
--

LOCK TABLES `guia_despacho` WRITE;
/*!40000 ALTER TABLE `guia_despacho` DISABLE KEYS */;
/*!40000 ALTER TABLE `guia_despacho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingreso`
--

DROP TABLE IF EXISTS `ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingreso` (
  `id_ingreso` int NOT NULL AUTO_INCREMENT,
  `id_negocio` int DEFAULT NULL,
  `id_institucion_financiera` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_comprobante_ingreso` int DEFAULT NULL,
  `id_medio_pago` int DEFAULT NULL,
  `id_caja` int DEFAULT NULL,
  `id_tipo_ingreso` int DEFAULT NULL,
  `valor_ingreso` float(11,2) DEFAULT NULL,
  `numero_ingreso` varchar(255) DEFAULT NULL,
  `comentario_ingreso` text,
  `fechavencimiento_ingreso` date DEFAULT NULL,
  `estado_ingreso` int DEFAULT NULL,
  `documento` varchar(255) DEFAULT NULL,
  `fechacreacion_ingreso` datetime DEFAULT NULL,
  `fechapago` date DEFAULT NULL,
  `mediopagoonline_ingreso` varchar(255) DEFAULT NULL,
  `fechatransferencia_ingreso` date DEFAULT NULL,
  `ruttransferencia_ingreso` varchar(255) DEFAULT NULL,
  `numero_transbank` int DEFAULT NULL,
  PRIMARY KEY (`id_ingreso`) USING BTREE,
  KEY `fk_relationship_71` (`id_comprobante_ingreso`) USING BTREE,
  KEY `fk_relationship_72` (`id_medio_pago`) USING BTREE,
  KEY `fk_relationship_76` (`id_folio`) USING BTREE,
  KEY `fk_relationship_197` (`id_negocio`) USING BTREE,
  KEY `fk_relationship_200` (`id_institucion_financiera`) USING BTREE,
  KEY `fk_relationship_203` (`id_caja`) USING BTREE,
  KEY `fk_tipo_ingreso_ingreso` (`id_tipo_ingreso`) USING BTREE,
  CONSTRAINT `fk_relationship_197` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_relationship_200` FOREIGN KEY (`id_institucion_financiera`) REFERENCES `institucion_financiera` (`id_institucion_financiera`),
  CONSTRAINT `fk_relationship_203` FOREIGN KEY (`id_caja`) REFERENCES `caja` (`id_caja`),
  CONSTRAINT `fk_relationship_71` FOREIGN KEY (`id_comprobante_ingreso`) REFERENCES `comprobante_ingreso` (`id_comprobante_ingreso`),
  CONSTRAINT `fk_relationship_72` FOREIGN KEY (`id_medio_pago`) REFERENCES `medio_pago` (`id_medio_pago`),
  CONSTRAINT `fk_relationship_76` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_tipo_ingreso_ingreso` FOREIGN KEY (`id_tipo_ingreso`) REFERENCES `tipo_ingreso` (`id_tipo_ingreso`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingreso`
--

LOCK TABLES `ingreso` WRITE;
/*!40000 ALTER TABLE `ingreso` DISABLE KEYS */;
INSERT INTO `ingreso` VALUES (125,125,NULL,4,NULL,5,30,1,13.00,'349','BOLETA ELECTRONICA',NULL,1,NULL,'2026-06-13 12:57:42',NULL,NULL,NULL,NULL,NULL),(126,126,NULL,4,NULL,1,30,1,14.00,'350','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-13 12:58:32',NULL,NULL,NULL,NULL,NULL),(127,127,NULL,4,NULL,5,30,1,12.00,'351','BOLETA ELECTRONICA',NULL,1,NULL,'2026-06-13 13:02:24',NULL,NULL,NULL,NULL,NULL),(128,128,NULL,4,NULL,5,30,1,26.00,'352','BOLETA ELECTRONICA',NULL,1,NULL,'2026-06-13 18:38:19',NULL,NULL,NULL,NULL,NULL),(129,129,NULL,4,NULL,1,30,1,13.00,'353','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-13 18:47:31',NULL,NULL,NULL,NULL,NULL),(130,130,NULL,4,NULL,1,30,1,13.00,'354','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-13 19:13:41',NULL,NULL,NULL,NULL,NULL),(131,131,NULL,4,NULL,1,30,1,14.00,'355','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-14 00:04:58',NULL,NULL,NULL,NULL,NULL),(132,132,NULL,4,NULL,1,33,1,14.00,'356','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-14 00:08:08',NULL,NULL,NULL,NULL,NULL),(133,133,NULL,4,NULL,1,34,1,14.00,'357','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-14 01:01:49',NULL,NULL,NULL,NULL,NULL),(134,134,NULL,4,NULL,1,34,1,13.00,'358','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-15 01:19:36',NULL,NULL,NULL,NULL,NULL),(135,135,NULL,4,NULL,1,30,1,13.00,'359','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-15 11:26:54',NULL,NULL,NULL,NULL,NULL),(136,136,NULL,4,NULL,1,35,1,13.00,'360','NOTA VENTA ELECTRONICA',NULL,1,NULL,'2026-06-15 12:51:13',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ingreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institucion_financiera`
--

DROP TABLE IF EXISTS `institucion_financiera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `institucion_financiera` (
  `id_institucion_financiera` int NOT NULL AUTO_INCREMENT,
  `glosa_institucion_financiera` varchar(255) DEFAULT NULL,
  `orden_institucion_financiera` int DEFAULT NULL,
  `vigente_institucion_financiera` int DEFAULT '1',
  PRIMARY KEY (`id_institucion_financiera`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institucion_financiera`
--

LOCK TABLES `institucion_financiera` WRITE;
/*!40000 ALTER TABLE `institucion_financiera` DISABLE KEYS */;
/*!40000 ALTER TABLE `institucion_financiera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_producto`
--

DROP TABLE IF EXISTS `inventario_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_producto` (
  `id_inventario_producto` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `numero_inventario_producto` int DEFAULT NULL,
  `fechacreacion_inventario_producto` datetime DEFAULT NULL,
  `estado_inventario_producto` int DEFAULT '1',
  `vigente_inventario_producto` int DEFAULT '1',
  PRIMARY KEY (`id_inventario_producto`) USING BTREE,
  KEY `fk_inventario_producto_idusuario` (`id_usuario`) USING BTREE,
  KEY `fk_inventario_producto_idfolio` (`id_folio`) USING BTREE,
  KEY `fk_inventario_producto_idbodega` (`id_bodega`) USING BTREE,
  CONSTRAINT `fk_inventario_producto_idbodega` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_inventario_producto_idfolio` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_inventario_producto_idusuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_producto`
--

LOCK TABLES `inventario_producto` WRITE;
/*!40000 ALTER TABLE `inventario_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lista_precio`
--

DROP TABLE IF EXISTS `lista_precio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lista_precio` (
  `id_lista_precio` int NOT NULL AUTO_INCREMENT,
  `glosa_lista_precio` varchar(255) DEFAULT NULL,
  `fechacreacion_lista_precio` datetime DEFAULT NULL,
  `fechainicio_lista_precio` date DEFAULT NULL,
  `fechatermino_lista_precio` date DEFAULT NULL,
  `descuentoinicial_lista_precio` float(11,0) DEFAULT NULL,
  `vigente_lista_precio` int DEFAULT '1',
  PRIMARY KEY (`id_lista_precio`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lista_precio`
--

LOCK TABLES `lista_precio` WRITE;
/*!40000 ALTER TABLE `lista_precio` DISABLE KEYS */;
/*!40000 ALTER TABLE `lista_precio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_chat`
--

DROP TABLE IF EXISTS `log_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_chat` (
  `id_log_chat` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `nombre_log_chat` varchar(255) DEFAULT NULL,
  `email_log_chat` varchar(255) DEFAULT NULL,
  `telefono_log_chat` varchar(255) DEFAULT NULL,
  `fechacreacion_log_chat` datetime DEFAULT NULL,
  `conversacion_log_chat` longtext,
  `estado_log_chat` int DEFAULT NULL,
  `identificadorcliente_log_chat` varchar(255) DEFAULT NULL,
  `estado_linea_log_chat` int DEFAULT NULL,
  PRIMARY KEY (`id_log_chat`) USING BTREE,
  KEY `fk_usuario_log_chat` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_usuario_log_chat` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_chat`
--

LOCK TABLES `log_chat` WRITE;
/*!40000 ALTER TABLE `log_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_whatsapp`
--

DROP TABLE IF EXISTS `log_whatsapp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_whatsapp` (
  `id_log_whatsapp` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `fechacreacion_log_whatsapp` datetime DEFAULT NULL,
  `documento_log_whatsapp` varchar(255) DEFAULT NULL,
  `numero_documento_log_whatsapp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_log_whatsapp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_whatsapp`
--

LOCK TABLES `log_whatsapp` WRITE;
/*!40000 ALTER TABLE `log_whatsapp` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_whatsapp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manejo`
--

DROP TABLE IF EXISTS `manejo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `manejo` (
  `id_manejo` int NOT NULL AUTO_INCREMENT,
  `id_sucursal` int DEFAULT NULL,
  `glosa_manejo` varchar(255) DEFAULT NULL,
  `fechacreacion_manejo` datetime DEFAULT NULL,
  `orden_manejo` int DEFAULT NULL,
  `vigente_manejo` int DEFAULT NULL,
  PRIMARY KEY (`id_manejo`) USING BTREE,
  KEY `fk_sucursal_idmanejo` (`id_sucursal`) USING BTREE,
  CONSTRAINT `fk_sucursal_idmanejo` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manejo`
--

LOCK TABLES `manejo` WRITE;
/*!40000 ALTER TABLE `manejo` DISABLE KEYS */;
/*!40000 ALTER TABLE `manejo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marca` (
  `id_marca` int NOT NULL AUTO_INCREMENT,
  `glosa_marca` varchar(255) DEFAULT NULL,
  `vigente_marca` int DEFAULT '1',
  PRIMARY KEY (`id_marca`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (65,'GENERICO',1),(66,'ESIKA',1),(67,'LBEL',1),(68,'AVON',1),(69,'FLORESTA FAMILIAR',1),(70,'ALKHOFAR',1),(71,'GELATTI',1),(72,'NATURAL DIRAS',1),(73,'ALOE VERA',1),(74,'SAL DE ANDREWS',1),(75,'AVAL',1),(76,'KOTEX',1),(77,'NOSOTROSAS',1),(78,'STAYFREE',1),(79,'LADYSOFT',1),(80,'DOVE',1),(81,'NEKO',1),(82,'MONCLER',1),(83,'ORAL-B',1),(84,'KOLYNOS',1),(85,'COLGATE',1),(86,'GILLETTE',1),(87,'DENTO',1),(88,'MIODEL',1),(89,'Labofar ',1),(90,'Gsk',1),(91,'Garden hause',1),(92,'Maver',1),(93,'Maver',1),(94,'UNILEVER',1),(95,'MARCA EDITADA CRM',0),(96,'BANES',1);
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medio_pago`
--

DROP TABLE IF EXISTS `medio_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medio_pago` (
  `id_medio_pago` int NOT NULL AUTO_INCREMENT,
  `glosa_medio_pago` varchar(500) DEFAULT NULL,
  `subsidio_medio_pago` int DEFAULT NULL,
  `orden_medio_pago` int DEFAULT NULL,
  `vigente_medio_pago` int DEFAULT NULL,
  PRIMARY KEY (`id_medio_pago`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medio_pago`
--

LOCK TABLES `medio_pago` WRITE;
/*!40000 ALTER TABLE `medio_pago` DISABLE KEYS */;
INSERT INTO `medio_pago` VALUES (1,'Efectivo',NULL,1,1),(2,'Transferencia',NULL,2,1),(3,'Tarjeta Débito',NULL,3,1),(4,'Tarjeta Crédito',NULL,4,1),(5,'Yape',NULL,5,1);
/*!40000 ALTER TABLE `medio_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mini_banner`
--

DROP TABLE IF EXISTS `mini_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mini_banner` (
  `id_mini_banner` int NOT NULL AUTO_INCREMENT,
  `glosa_mini_banner` varchar(255) DEFAULT NULL,
  `path_mini_banner` varchar(500) DEFAULT NULL,
  `urlimagen_mini_banner` varchar(500) DEFAULT NULL,
  `vigente_mini_banner` int DEFAULT NULL,
  `posicion_mini_banner` int DEFAULT '2',
  PRIMARY KEY (`id_mini_banner`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mini_banner`
--

LOCK TABLES `mini_banner` WRITE;
/*!40000 ALTER TABLE `mini_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `mini_banner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modelo`
--

DROP TABLE IF EXISTS `modelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modelo` (
  `id_modelo` int NOT NULL AUTO_INCREMENT,
  `id_marca` int DEFAULT NULL,
  `id_atributo` int DEFAULT NULL,
  `id_matriz_atributo` int DEFAULT NULL,
  `glosa_modelo` varchar(255) DEFAULT NULL,
  `codigo_modelo` varchar(255) DEFAULT NULL,
  `descripcion_modelo` text,
  `path_modelo` varchar(255) DEFAULT NULL,
  `urlamigable_modelo` varchar(255) DEFAULT NULL,
  `urlimagen_modelo` text,
  `vigente_modelo` int DEFAULT NULL,
  `orden_modelo` int DEFAULT NULL,
  `visible_online` int DEFAULT NULL,
  PRIMARY KEY (`id_modelo`) USING BTREE,
  KEY `fk_idmarca_tablamodelo` (`id_marca`) USING BTREE,
  KEY `fk_idatributo_tablaatributos` (`id_atributo`) USING BTREE,
  KEY `fk_modelo_matrizatributo` (`id_matriz_atributo`) USING BTREE,
  CONSTRAINT `fk_idatributo_tablaatributos` FOREIGN KEY (`id_atributo`) REFERENCES `atributo` (`id_atributo`),
  CONSTRAINT `fk_idmarca_tablamodelo` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `fk_modelo_matrizatributo` FOREIGN KEY (`id_matriz_atributo`) REFERENCES `matriz_atributo` (`id_matriz_atributo`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modelo`
--

LOCK TABLES `modelo` WRITE;
/*!40000 ALTER TABLE `modelo` DISABLE KEYS */;
/*!40000 ALTER TABLE `modelo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `id_modulo` int NOT NULL AUTO_INCREMENT,
  `glosa_modulo` varchar(255) DEFAULT NULL,
  `icono_modulo` varchar(255) DEFAULT NULL,
  `link_modulo` varchar(255) DEFAULT NULL,
  `tipoaccion_modulo` int DEFAULT NULL,
  `idpadre_modulo` int DEFAULT NULL,
  `orden_modulo` int DEFAULT NULL,
  `clase_modulo` varchar(500) DEFAULT NULL,
  `saltopagina_modulo` varchar(500) DEFAULT NULL,
  `vigente_modulo` int DEFAULT '1',
  PRIMARY KEY (`id_modulo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'SUCURSAL',NULL,'SUCURSAL',NULL,NULL,1,'INVENTARIOS',NULL,1),(2,'BODEGAS',NULL,'BODEGAS',NULL,NULL,2,'INVENTARIOS',NULL,1),(3,'MARCAS',NULL,'MARCAS',NULL,NULL,3,'INVENTARIOS',NULL,1),(4,'CATEGORIAS',NULL,'CATEGORIAS',NULL,NULL,4,'INVENTARIOS',NULL,1),(5,'PRODUCTOS',NULL,'PRODUCTOS',NULL,NULL,5,'INVENTARIOS',NULL,1),(6,'ATRIBUTOS',NULL,'ATRIBUTOS',NULL,NULL,6,'INVENTARIOS',NULL,1),(7,'PAGO NOTA VENTA',NULL,'PAGO NOTA VENTA',NULL,NULL,7,'PAGOS',NULL,1),(8,'VENTAS',NULL,'VENTAS',NULL,NULL,8,'PAGOS',NULL,1),(9,'CAJA',NULL,'CAJA',NULL,NULL,9,'PAGOS',NULL,1),(10,'ANULAR DOCUMENTOS',NULL,'ANULAR DOCUMENTOS',NULL,NULL,10,'PAGOS',NULL,1),(11,'REPORTE PRODUCTOS',NULL,'REPORTE PRODUCTOS',NULL,NULL,11,'REPORTE',NULL,1),(12,'REPORTE VENTA PRODUCTO',NULL,'REPORTE VENTA PRODUCTO',NULL,NULL,12,'REPORTE',NULL,1),(13,'LIBRO VENTAS',NULL,'LIBRO VENTAS',NULL,NULL,13,'REPORTE',NULL,1),(14,'PEDIDOS',NULL,'PEDIDOS',NULL,NULL,14,'TIENDA EN LINEA',NULL,1),(15,'SLIDER',NULL,'SLIDER',NULL,NULL,15,'TIENDA EN LINEA',NULL,1),(16,'CHAT CLIENTE',NULL,'CHAT CLIENTE',NULL,NULL,16,'TIENDA EN LINEA',NULL,1),(17,'PROMOCIONES',NULL,'PROMOCIONES',NULL,NULL,17,'TIENDA EN LINEA',NULL,1),(18,'KARDEX',NULL,'KARDEX',NULL,NULL,14,'REPORTE',NULL,1),(19,'VER TODAS LAS CAJAS',NULL,'VER TODAS LAS CAJAS',NULL,NULL,19,'PAGOS',NULL,1);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motivo_despacho`
--

DROP TABLE IF EXISTS `motivo_despacho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivo_despacho` (
  `id_motivo_despacho` int NOT NULL AUTO_INCREMENT,
  `glosa_motivo_despacho` varchar(255) DEFAULT NULL,
  `orden_motivo_despacho` int DEFAULT NULL,
  `vigente_motivo_despacho` int DEFAULT NULL,
  PRIMARY KEY (`id_motivo_despacho`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivo_despacho`
--

LOCK TABLES `motivo_despacho` WRITE;
/*!40000 ALTER TABLE `motivo_despacho` DISABLE KEYS */;
INSERT INTO `motivo_despacho` VALUES (1,'Entrega Gratuita',1,1),(2,'Otros Traslados No Venta',1,1),(3,'Guia Devolución',1,1);
/*!40000 ALTER TABLE `motivo_despacho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motivo_devolucion`
--

DROP TABLE IF EXISTS `motivo_devolucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivo_devolucion` (
  `id_motivo_devolucion` int NOT NULL AUTO_INCREMENT,
  `tipo_devolucion` char(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_devolucion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `glosa_motivo_devolucion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `afectacaja_motivo_devolucion` int DEFAULT NULL,
  `orden_motivo_devolucion` int DEFAULT NULL,
  `vigente_motivo_devolucion` int DEFAULT NULL,
  PRIMARY KEY (`id_motivo_devolucion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motivo_devolucion`
--

LOCK TABLES `motivo_devolucion` WRITE;
/*!40000 ALTER TABLE `motivo_devolucion` DISABLE KEYS */;
INSERT INTO `motivo_devolucion` VALUES (1,'C','01','Anulación de la operación',1,NULL,1),(2,'C','02','Anulación por error en el RUC',1,NULL,1),(3,'C','03','Corrección por error en la descripción',1,NULL,1),(4,'C','04','Descuento global',1,NULL,1),(5,'C','05','Descuento por ítem',1,NULL,1),(6,'C','06','Devolución total',1,NULL,1),(7,'C','07','Devolución por ítem',1,NULL,1),(8,'C','08','Bonificación',1,NULL,1),(9,'C','09','Disminución en el valor',1,NULL,1),(10,'C','10','Otros Conceptos',1,NULL,1),(11,'C','11','Ajustes de operaciones de exportación',1,NULL,1),(12,'C','12','Ajustes afectos al IVAP',1,NULL,1),(13,'D','01','Intereses por mora',1,NULL,1),(14,'D','02','Aumento en el valor',1,NULL,1),(15,'D','03','Penalidades/ otros conceptos',1,NULL,1),(16,'D','10','Ajustes de operaciones de exportación',1,NULL,1),(17,'D','11','Ajustes afectos al IVAP',1,NULL,1);
/*!40000 ALTER TABLE `motivo_devolucion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `negocio`
--

DROP TABLE IF EXISTS `negocio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `negocio` (
  `id_negocio` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_ficha_paciente` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_sucursal` int DEFAULT NULL,
  `id_pedido` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `fechacreacion_negocio` datetime DEFAULT NULL,
  `numero_negocio` int DEFAULT NULL,
  `valor_negocio` float DEFAULT NULL,
  `descuento_negocio` float DEFAULT NULL,
  `porcentajeiva_negocio` float DEFAULT NULL,
  `estado_negocio` int DEFAULT NULL,
  `valorexento_negocio` float DEFAULT NULL,
  `valorafecto_negocio` float DEFAULT NULL,
  `cerrado_negocio` int DEFAULT NULL,
  `pos_negocio` int DEFAULT '0',
  `vigente_negocio` int DEFAULT NULL,
  `efectivo_negocio` float DEFAULT NULL,
  `vuelto_negocio` float DEFAULT NULL,
  PRIMARY KEY (`id_negocio`) USING BTREE,
  KEY `fk_relationship_70` (`id_folio`) USING BTREE,
  KEY `fk_relationship_78` (`id_usuario`) USING BTREE,
  KEY `fk_relationship_83` (`id_cliente`) USING BTREE,
  KEY `fk_relationship_401` (`id_sucursal`) USING BTREE,
  KEY `fk_negocios_pedidos` (`id_pedido`) USING BTREE,
  CONSTRAINT `fk_negocios_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  CONSTRAINT `fk_relationship_401` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  CONSTRAINT `fk_relationship_70` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_relationship_78` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `fk_relationship_83` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `negocio`
--

LOCK TABLES `negocio` WRITE;
/*!40000 ALTER TABLE `negocio` DISABLE KEYS */;
INSERT INTO `negocio` VALUES (125,23242,2,NULL,1,17,NULL,18,'2026-06-13 12:57:42',352,13,NULL,1.98,NULL,NULL,11.02,NULL,0,1,13,0),(126,23242,2,NULL,1,17,NULL,18,'2026-06-13 12:58:32',353,14,NULL,2.14,NULL,NULL,11.86,NULL,0,1,14,0),(127,23242,2,NULL,1,17,NULL,18,'2026-06-13 13:02:24',354,12,NULL,1.83,NULL,NULL,10.17,NULL,0,1,12,0),(128,23242,2,NULL,1,17,NULL,18,'2026-06-13 18:38:19',355,26,NULL,3.97,NULL,NULL,22.03,NULL,0,1,26,0),(129,23242,2,NULL,1,17,NULL,18,'2026-06-13 18:47:31',356,13,NULL,1.98,NULL,NULL,11.02,NULL,0,1,13,0),(130,23242,2,NULL,1,17,NULL,18,'2026-06-13 19:13:41',357,13,NULL,1.98,NULL,NULL,11.02,NULL,0,1,13,0),(131,23242,2,NULL,1,17,NULL,18,'2026-06-14 00:04:58',358,14,NULL,2.14,NULL,NULL,11.86,NULL,0,1,14,0),(132,23260,2,NULL,1,17,NULL,18,'2026-06-14 00:08:08',359,14,NULL,2.14,NULL,NULL,11.86,NULL,0,1,14,0),(133,23260,2,NULL,1,17,NULL,18,'2026-06-14 01:01:49',360,14,NULL,2.14,NULL,NULL,11.86,NULL,0,1,14,0),(134,23260,2,NULL,1,17,NULL,18,'2026-06-15 01:19:36',361,13,NULL,1.98,NULL,NULL,11.02,NULL,0,1,13,0),(135,23242,2,NULL,1,17,NULL,18,'2026-06-15 11:26:54',362,13,NULL,1.98,NULL,NULL,11.02,NULL,0,1,13,0),(136,23260,2,NULL,1,17,NULL,18,'2026-06-15 12:51:13',363,13,NULL,1.98,NULL,NULL,11.02,NULL,0,1,13,0);
/*!40000 ALTER TABLE `negocio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `negocio_detalle`
--

DROP TABLE IF EXISTS `negocio_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `negocio_detalle` (
  `id_negocio_detalle` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `id_negocio_examen` int DEFAULT NULL,
  `id_hospital` int DEFAULT NULL,
  `id_negocio_procedimiento` int DEFAULT NULL,
  `id_reserva` int DEFAULT NULL,
  `id_negocio` int DEFAULT NULL,
  `id_lista_precio` int DEFAULT NULL,
  `id_cirugia` int DEFAULT NULL,
  `id_presupuesto_asignado` int DEFAULT NULL,
  `id_tipo_afectacion` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `valorneto_negocio_detalle` float DEFAULT NULL,
  `descuento_negocio_detalle` float DEFAULT NULL,
  `iva_negocio_detalle` float DEFAULT NULL,
  `total_negocio_detalle` float DEFAULT NULL,
  `fechacreacion_negocio_detalle` datetime DEFAULT NULL,
  `cantidad_negocio_detalle` float DEFAULT NULL,
  `valorafecto_negocio_detalle` float DEFAULT NULL,
  `valorexento_negocio_detalle` float DEFAULT NULL,
  `orden_negocio_detalle` int DEFAULT NULL,
  `horasdiashospital_negocio_detalle` int DEFAULT NULL,
  `tipohoradiashospital_negocio_detalle` int DEFAULT NULL,
  `costohoradiashospital_negocio_detalle` float DEFAULT NULL,
  `eshotel_hospital_negocio_detalle` int DEFAULT NULL,
  `asignadotratamiento_negocio_detalle` int DEFAULT NULL,
  `preciounitario_negocio_detalle` float DEFAULT NULL,
  PRIMARY KEY (`id_negocio_detalle`) USING BTREE,
  KEY `fk_relationship_69` (`id_negocio`) USING BTREE,
  KEY `fk_relationship_80` (`id_producto`) USING BTREE,
  KEY `fk_relationship_8001` (`id_tipo_afectacion`) USING BTREE,
  CONSTRAINT `fk_relationship_69` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_relationship_80` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_relationship_8001` FOREIGN KEY (`id_tipo_afectacion`) REFERENCES `tipo_afectacion` (`id_tipo_afectacion`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `negocio_detalle`
--

LOCK TABLES `negocio_detalle` WRITE;
/*!40000 ALTER TABLE `negocio_detalle` DISABLE KEYS */;
INSERT INTO `negocio_detalle` VALUES (178,90,NULL,NULL,NULL,NULL,125,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-13 12:57:42',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(179,90,NULL,NULL,NULL,NULL,126,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-13 12:58:32',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(180,92,NULL,NULL,NULL,NULL,126,NULL,NULL,NULL,1,18,1,NULL,0.15,0.85,'2026-06-13 12:58:32',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.85),(181,106,NULL,NULL,NULL,NULL,127,NULL,NULL,NULL,1,18,12,NULL,1.83,10.17,'2026-06-13 13:02:24',1,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10.17),(182,92,NULL,NULL,NULL,NULL,128,NULL,NULL,NULL,1,18,1,NULL,0.15,0.85,'2026-06-13 18:38:19',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.85),(183,90,NULL,NULL,NULL,NULL,128,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-13 18:38:19',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(184,106,NULL,NULL,NULL,NULL,128,NULL,NULL,NULL,1,18,12,NULL,1.83,10.17,'2026-06-13 18:38:19',1,12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10.17),(185,90,NULL,NULL,NULL,NULL,129,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-13 18:47:31',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(186,90,NULL,NULL,NULL,NULL,130,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-13 19:13:41',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(187,92,NULL,NULL,NULL,NULL,131,NULL,NULL,NULL,1,18,1,NULL,0.15,0.85,'2026-06-14 00:04:58',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.85),(188,90,NULL,NULL,NULL,NULL,131,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-14 00:04:58',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(189,90,NULL,NULL,NULL,NULL,132,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-14 00:08:08',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(190,92,NULL,NULL,NULL,NULL,132,NULL,NULL,NULL,1,18,1,NULL,0.15,0.85,'2026-06-14 00:08:08',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.85),(191,90,NULL,NULL,NULL,NULL,133,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-14 01:01:49',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(192,92,NULL,NULL,NULL,NULL,133,NULL,NULL,NULL,1,18,1,NULL,0.15,0.85,'2026-06-14 01:01:49',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.85),(193,90,NULL,NULL,NULL,NULL,134,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-15 01:19:36',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(194,90,NULL,NULL,NULL,NULL,135,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-15 11:26:54',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02),(195,90,NULL,NULL,NULL,NULL,136,NULL,NULL,NULL,1,18,13,NULL,1.98,11.02,'2026-06-15 12:51:13',1,13,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.02);
/*!40000 ALTER TABLE `negocio_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nota_credito`
--

DROP TABLE IF EXISTS `nota_credito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nota_credito` (
  `id_nota_credito` int NOT NULL AUTO_INCREMENT,
  `id_folio` int DEFAULT NULL,
  `id_boleta` int DEFAULT NULL,
  `id_factura` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_motivo_devolucion` int DEFAULT NULL,
  `numero_nota_credito` varchar(255) DEFAULT NULL,
  `serie_nota_credito` varchar(255) DEFAULT NULL,
  `fechacreacion_nota_credito` datetime DEFAULT NULL,
  `valorafecto_nota_credito` float DEFAULT NULL,
  `valorexento_nota_credito` float DEFAULT NULL,
  `iva_nota_credito` float DEFAULT NULL,
  `total_nota_credito` float DEFAULT NULL,
  `estado_nota_credito` int DEFAULT NULL,
  `zip_nota_credito` varchar(255) DEFAULT NULL,
  `xml_nota_credito` varchar(255) DEFAULT NULL,
  `path_nota_credito` varchar(255) DEFAULT NULL,
  `respuesta_sunat_nota_credito` longtext,
  `path_ticket_nota_credito` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_nota_credito`) USING BTREE,
  KEY `fk_relationship_232` (`id_factura`) USING BTREE,
  KEY `fk_relationship_233` (`id_folio`) USING BTREE,
  KEY `fk_relationship_240` (`id_usuario`) USING BTREE,
  KEY `fk_relationship` (`id_motivo_devolucion`) USING BTREE,
  CONSTRAINT `fk_relationship` FOREIGN KEY (`id_motivo_devolucion`) REFERENCES `motivo_devolucion` (`id_motivo_devolucion`),
  CONSTRAINT `fk_relationship_232` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_factura`),
  CONSTRAINT `fk_relationship_233` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_relationship_240` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nota_credito`
--

LOCK TABLES `nota_credito` WRITE;
/*!40000 ALTER TABLE `nota_credito` DISABLE KEYS */;
/*!40000 ALTER TABLE `nota_credito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nota_debito`
--

DROP TABLE IF EXISTS `nota_debito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nota_debito` (
  `id_nota_debito` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_caja` int DEFAULT NULL,
  `id_nota_credito` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `numero_nota_debito` int DEFAULT NULL,
  `fechacreacion_nota_debito` datetime DEFAULT NULL,
  `valorafecto_nota_debito` float DEFAULT NULL,
  `valorexento_nota_debito` float DEFAULT NULL,
  `iva_nota_debito` float DEFAULT NULL,
  `total_nota_debito` float DEFAULT NULL,
  `estado_nota_debito` float DEFAULT NULL,
  `urlpdfcarta_nota_debito` varchar(255) DEFAULT NULL,
  `urlpdfticket_nota_debito` varchar(255) DEFAULT NULL,
  `urlxml_nota_debito` varchar(255) DEFAULT NULL,
  `path_nota_debito` varchar(255) DEFAULT NULL,
  `pathticket_nota_debito` varchar(255) DEFAULT NULL,
  `estadosii_nota_debito` int DEFAULT NULL,
  `logestadosii_nota_debito` longtext,
  PRIMARY KEY (`id_nota_debito`) USING BTREE,
  KEY `fk_tablausuario_notadebito` (`id_usuario`) USING BTREE,
  KEY `fk_tablacaja_idcaja` (`id_caja`) USING BTREE,
  KEY `fk_tablanotacredito_idnotacredito` (`id_nota_credito`) USING BTREE,
  KEY `fk_tablafolio_idfolionotadebito` (`id_folio`) USING BTREE,
  CONSTRAINT `fk_tablacaja_idcaja` FOREIGN KEY (`id_caja`) REFERENCES `caja` (`id_caja`),
  CONSTRAINT `fk_tablafolio_idfolionotadebito` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_tablanotacredito_idnotacredito` FOREIGN KEY (`id_nota_credito`) REFERENCES `nota_credito` (`id_nota_credito`),
  CONSTRAINT `fk_tablausuario_notadebito` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nota_debito`
--

LOCK TABLES `nota_debito` WRITE;
/*!40000 ALTER TABLE `nota_debito` DISABLE KEYS */;
/*!40000 ALTER TABLE `nota_debito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nota_venta`
--

DROP TABLE IF EXISTS `nota_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nota_venta` (
  `id_nota_venta` int NOT NULL AUTO_INCREMENT,
  `id_negocio` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `numero_nota_venta` varchar(255) DEFAULT NULL,
  `fechacreacion_nota_venta` datetime DEFAULT NULL,
  `fechavencimiento_nota_venta` datetime DEFAULT NULL,
  `valor_nota_venta` float(11,2) DEFAULT NULL,
  `iva_nota_venta` float(11,2) DEFAULT NULL,
  `total_nota_venta` float(11,2) DEFAULT NULL,
  `estado_nota_venta` int DEFAULT NULL,
  `saldo_nota_venta` float(11,2) DEFAULT NULL,
  `urlpdf_nota_venta` varchar(500) DEFAULT NULL,
  `urlticket_nota_venta` varchar(500) DEFAULT NULL,
  `escotizacion_nota_venta` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id_nota_venta`) USING BTREE,
  KEY `fk_negocio_nota_de_venta` (`id_negocio`) USING BTREE,
  KEY `fk_usuario_nota_de_venta` (`id_usuario`) USING BTREE,
  KEY `fk_folio_nota_de_venta` (`id_folio`) USING BTREE,
  CONSTRAINT `fk_folio_nota_de_venta` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_negocio_nota_de_venta` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_usuario_nota_de_venta` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nota_venta`
--

LOCK TABLES `nota_venta` WRITE;
/*!40000 ALTER TABLE `nota_venta` DISABLE KEYS */;
INSERT INTO `nota_venta` VALUES (55,126,23242,17,'184','2026-06-13 12:58:32',NULL,11.86,2.14,14.00,1,14.00,'Documento_NOTA VENTA202606131781373512.pdf','Ticket_202606131781373512.pdf','0'),(56,129,23242,17,'185','2026-06-13 18:47:31',NULL,11.02,1.98,13.00,1,13.00,'Documento_NOTA VENTA202606131781394452.pdf','Ticket_202606131781394452.pdf','0'),(57,130,23242,17,'186','2026-06-13 19:13:41',NULL,11.02,1.98,13.00,1,13.00,'Documento_NOTA VENTA202606131781396021.pdf','Ticket_202606131781396021.pdf','0'),(58,131,23242,17,'187','2026-06-14 00:04:58',NULL,11.86,2.14,14.00,1,14.00,'Documento_NOTA VENTA202606141781413498.pdf','Ticket_202606141781413498.pdf','0'),(59,132,23260,17,'188','2026-06-14 00:08:08',NULL,11.86,2.14,14.00,1,14.00,'Documento_NOTA VENTA202606141781413688.pdf','Ticket_202606141781413688.pdf','0'),(60,133,23260,17,'189','2026-06-14 01:01:49',NULL,11.86,2.14,14.00,1,14.00,'Documento_NOTA VENTA202606141781416909.pdf','Ticket_202606141781416909.pdf','0'),(61,134,23260,17,'190','2026-06-15 01:19:36',NULL,11.02,1.98,13.00,1,13.00,'Documento_NOTA VENTA202606151781504376.pdf','Ticket_202606151781504376.pdf','0'),(62,135,23242,17,'191','2026-06-15 11:26:54',NULL,11.02,1.98,13.00,1,13.00,'Documento_NOTA VENTA202606151781540814.pdf','Ticket_202606151781540814.pdf','0'),(63,136,23260,17,'192','2026-06-15 12:51:13',NULL,11.02,1.98,13.00,1,13.00,'Documento_NOTA VENTA202606151781545873.pdf','Ticket_202606151781545873.pdf','0');
/*!40000 ALTER TABLE `nota_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion_mercadopago`
--

DROP TABLE IF EXISTS `notificacion_mercadopago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion_mercadopago` (
  `id_notificacion_mercadopago` int NOT NULL AUTO_INCREMENT,
  `data_created_id` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `json_notificacion_mercadopago` text COLLATE utf8mb3_unicode_ci,
  `fecha_creacion_notificacion_mercadopago` datetime DEFAULT NULL,
  PRIMARY KEY (`id_notificacion_mercadopago`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion_mercadopago`
--

LOCK TABLES `notificacion_mercadopago` WRITE;
/*!40000 ALTER TABLE `notificacion_mercadopago` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacion_mercadopago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden_compra`
--

DROP TABLE IF EXISTS `orden_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_compra` (
  `id_orden_compra` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_estado_orden_compra` int DEFAULT NULL,
  `id_proveedor` int DEFAULT NULL,
  `fecha_orden_compra` datetime DEFAULT NULL,
  `numero_orden_compra` int DEFAULT NULL,
  `porcentajeiva_orden_compra` float DEFAULT NULL,
  `total_orden_compra` float DEFAULT NULL,
  `comentario_orden_compra` text,
  `vendedor_orden_compra` varchar(1000) DEFAULT NULL,
  `finalizaroc_orden_compra` int DEFAULT NULL,
  `comentarioentrega_orden_compra` text,
  `vigente_orden_compra` int DEFAULT '1',
  PRIMARY KEY (`id_orden_compra`) USING BTREE,
  KEY `fk_relationship_27` (`id_proveedor`) USING BTREE,
  KEY `fk_relationship_65` (`id_bodega`) USING BTREE,
  KEY `fk_relationship_66` (`id_folio`) USING BTREE,
  KEY `fk_relationship_67` (`id_usuario`) USING BTREE,
  KEY `fk_relationship_68` (`id_estado_orden_compra`) USING BTREE,
  CONSTRAINT `fk_relationship_27` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  CONSTRAINT `fk_relationship_65` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_relationship_66` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_relationship_67` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `fk_relationship_68` FOREIGN KEY (`id_estado_orden_compra`) REFERENCES `estado_orden_compra` (`id_estado_orden_compra`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden_compra`
--

LOCK TABLES `orden_compra` WRITE;
/*!40000 ALTER TABLE `orden_compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pais` (
  `idPais` int NOT NULL AUTO_INCREMENT COMMENT 'id unico de pais',
  `pais` varchar(50) NOT NULL COMMENT 'nombre del pais',
  `nacionalidad` varchar(50) DEFAULT NULL COMMENT 'la nacionalidad, si es peruano por ejemplo',
  PRIMARY KEY (`idPais`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
INSERT INTO `pais` VALUES (1,'Peru','peruana');
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id_pedido` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_sucursal` int DEFAULT NULL,
  `id_estado_pedido` int DEFAULT NULL,
  `id_estado_pago` int DEFAULT NULL,
  `id_estado_preparacion` int DEFAULT NULL,
  `idProvincia` int DEFAULT NULL,
  `id_comuna` int DEFAULT NULL,
  `id_success_mercadopago` int DEFAULT NULL,
  `fechacreacion_pedido` datetime DEFAULT NULL,
  `numero_pedido` varchar(255) DEFAULT NULL,
  `valorneto_pedido` float DEFAULT NULL,
  `valortransporte_pedido` float DEFAULT NULL,
  `descuento_pedido` float DEFAULT NULL,
  `porcentajeiva_pedido` float DEFAULT NULL,
  `iva_pedido` float DEFAULT NULL,
  `valortotal_pedido` float DEFAULT NULL,
  `retiroentienda_pedido` int DEFAULT NULL,
  `peso_pedido` float(11,0) DEFAULT NULL,
  `nota_pedido` text,
  `vigente_pedido` int DEFAULT NULL,
  `rutfactura_pedido` varchar(255) DEFAULT NULL,
  `razonsocialfactura_pedido` varchar(255) DEFAULT NULL,
  `girofactura_pedido` varchar(255) DEFAULT NULL,
  `nombrefactura_pedido` varchar(255) DEFAULT NULL,
  `apellidosfactura_pedido` varchar(255) DEFAULT NULL,
  `direccionfactura_pedido` varchar(255) DEFAULT NULL,
  `comunafactura_pedido` varchar(255) DEFAULT NULL,
  `telefonofactura_pedido` varchar(255) DEFAULT NULL,
  `direccionenvio_pedido` varchar(255) DEFAULT NULL,
  `comunaenvio_pedido` varchar(255) DEFAULT NULL,
  `correoenvio_pedido` varchar(255) DEFAULT NULL,
  `telefonoenvio_pedido` varchar(255) DEFAULT NULL,
  `tipodocumento_pedido` varchar(255) DEFAULT NULL,
  `notaprivada_pedido` text,
  PRIMARY KEY (`id_pedido`) USING BTREE,
  KEY `fk_pedidos_usuario` (`id_usuario`) USING BTREE,
  KEY `fk_pedidos_folio` (`id_folio`) USING BTREE,
  KEY `fk_pedidos_cliente` (`id_cliente`) USING BTREE,
  KEY `fk_pedidos_sucursal` (`id_sucursal`) USING BTREE,
  KEY `fk_pedidos_estado_pago` (`id_estado_pago`) USING BTREE,
  KEY `fk_pedidos_estado_pedido` (`id_estado_pedido`) USING BTREE,
  KEY `fk_pedidos_estado_` (`id_estado_preparacion`) USING BTREE,
  KEY `fk_pedidos_comuna` (`id_comuna`) USING BTREE,
  KEY `fk_provincia_pedido` (`idProvincia`) USING BTREE,
  KEY `fk_success_mercadopago` (`id_success_mercadopago`) USING BTREE,
  CONSTRAINT `fk_pedidos_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_pedidos_comuna` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`),
  CONSTRAINT `fk_pedidos_estado_` FOREIGN KEY (`id_estado_preparacion`) REFERENCES `estado_preparacion` (`id_estado_preparacion`),
  CONSTRAINT `fk_pedidos_estado_pago` FOREIGN KEY (`id_estado_pago`) REFERENCES `estado_pago` (`id_estado_pago`),
  CONSTRAINT `fk_pedidos_estado_pedido` FOREIGN KEY (`id_estado_pedido`) REFERENCES `estado_pedido` (`id_estado_pedido`),
  CONSTRAINT `fk_pedidos_folio` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_pedidos_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `fk_provincia_pedido` FOREIGN KEY (`idProvincia`) REFERENCES `provincia` (`idProvincia`),
  CONSTRAINT `fk_success_mercadopago` FOREIGN KEY (`id_success_mercadopago`) REFERENCES `success_mercadopago` (`id_success_mercadopago`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (45,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-28 17:01:21','1',11.78,NULL,NULL,NULL,2.12,13.9,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-28 17:09:15','2',23.56,NULL,NULL,NULL,4.24,27.8,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:45:31','4',35.34,NULL,NULL,NULL,6.36,41.7,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:48:27','5',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:49:16','6',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:51:14','7',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:52:31','8',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:53:22','9',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:54:52','10',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(54,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 12:55:43','11',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(55,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 13:00:23','12',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(56,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-03-30 13:13:56','13',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(57,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-01 20:37:58','14',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(58,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-01 20:40:51','15',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(59,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-01 20:42:09','16',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(60,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-01 20:42:46','17',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(61,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-01 20:58:31','18',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(62,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 08:47:23','19',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(63,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 08:47:57','20',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(64,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 08:49:16','21',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(65,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 08:54:27','22',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(66,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 08:55:45','23',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(67,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:03:15','24',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(68,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:05:02','25',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(69,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:05:44','26',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(70,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:06:24','27',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(71,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:07:11','28',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(72,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:11:57','29',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(73,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:25:29','30',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(74,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:34:19','31',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(75,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:36:10','32',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(76,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 09:39:40','33',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(77,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:01:23','34',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(78,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:05:37','35',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(79,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:09:46','36',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(80,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:26:25','37',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(81,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:35:06','38',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(82,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:35:51','39',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(83,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 10:36:18','40',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(84,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 11:20:18','41',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(85,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:18:02','42',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(86,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:20:58','43',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(87,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:24:46','44',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(88,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:27:34','45',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(89,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:27:48','46',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(90,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:28:10','47',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(91,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:32:27','48',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(92,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:34:51','49',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(93,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:36:45','50',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(94,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:40:11','51',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(95,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:40:35','52',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(96,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:46:14','53',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(97,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:46:42','54',47.12,NULL,NULL,NULL,8.48,55.6,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(98,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-03 20:49:52','55',50.08,NULL,NULL,NULL,9.02,59.1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(99,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 16:24:44','56',50.08,NULL,NULL,NULL,9.02,59.1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(100,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 16:37:36','57',50.08,NULL,NULL,NULL,9.02,59.1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(101,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 16:37:45','58',50.08,NULL,NULL,NULL,9.02,59.1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(102,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 16:38:15','59',50.08,NULL,NULL,NULL,9.02,59.1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(103,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 16:51:27','60',50.08,NULL,NULL,NULL,9.02,59.1,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(104,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 17:02:42','61',13.47,NULL,NULL,NULL,2.43,15.9,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(105,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-06 17:04:40','62',13.47,NULL,NULL,NULL,2.43,15.9,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(106,23261,11,73,NULL,1,1,5,NULL,NULL,NULL,'2024-04-08 17:38:51','63',13.47,NULL,NULL,NULL,2.43,15.9,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(107,23261,11,NULL,NULL,1,1,5,NULL,NULL,NULL,'2024-04-09 17:25:16','64',7.37,NULL,NULL,NULL,1.33,8.7,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_detalle`
--

DROP TABLE IF EXISTS `pedido_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_detalle` (
  `id_pedido_detalle` int NOT NULL AUTO_INCREMENT,
  `id_boleta` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `id_negocio` int DEFAULT NULL,
  `id_pedido` int DEFAULT NULL,
  `id_lista_precio` int DEFAULT NULL,
  `valorneto_pedido_detalle` float DEFAULT NULL,
  `iva_pedido_detalle` float DEFAULT NULL,
  `descuento_pedido_detalle` float DEFAULT NULL,
  `valortotal_pedido_detalle` float DEFAULT NULL,
  `cantidad_pedido_detalle` int DEFAULT NULL,
  `fechacreacion_pedido_detalle` datetime DEFAULT NULL,
  `orden_pedido_detalle` int DEFAULT NULL,
  PRIMARY KEY (`id_pedido_detalle`) USING BTREE,
  KEY `fk_pedido_detalle_boleta` (`id_boleta`) USING BTREE,
  KEY `fk_pedido_detalle_producto` (`id_producto`) USING BTREE,
  KEY `fk_pedido_detalle_negocios` (`id_negocio`) USING BTREE,
  KEY `fk_pedido_detalle_pedido` (`id_pedido`) USING BTREE,
  KEY `fk_pedido_detalle_precio_lista` (`id_lista_precio`) USING BTREE,
  CONSTRAINT `fk_pedido_detalle_boleta` FOREIGN KEY (`id_boleta`) REFERENCES `boleta` (`id_boleta`),
  CONSTRAINT `fk_pedido_detalle_negocios` FOREIGN KEY (`id_negocio`) REFERENCES `negocio` (`id_negocio`),
  CONSTRAINT `fk_pedido_detalle_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  CONSTRAINT `fk_pedido_detalle_precio_lista` FOREIGN KEY (`id_lista_precio`) REFERENCES `lista_precio` (`id_lista_precio`),
  CONSTRAINT `fk_pedido_detalle_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_detalle`
--

LOCK TABLES `pedido_detalle` WRITE;
/*!40000 ALTER TABLE `pedido_detalle` DISABLE KEYS */;
INSERT INTO `pedido_detalle` VALUES (83,NULL,193,NULL,45,NULL,NULL,2.12034,NULL,13.9,1,'2024-03-28 17:01:21',1),(84,NULL,193,NULL,46,NULL,NULL,2.12034,NULL,13.9,2,'2024-03-28 17:09:15',1),(85,NULL,193,NULL,47,NULL,NULL,2.12034,NULL,13.9,3,'2024-03-30 12:45:32',1),(86,NULL,193,NULL,48,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:48:27',1),(87,NULL,193,NULL,49,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:49:16',1),(88,NULL,193,NULL,50,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:51:14',1),(89,NULL,193,NULL,51,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:52:31',1),(90,NULL,193,NULL,52,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:53:22',1),(91,NULL,193,NULL,53,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:54:52',1),(92,NULL,193,NULL,54,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 12:55:43',1),(93,NULL,193,NULL,55,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 13:00:23',1),(94,NULL,193,NULL,56,NULL,NULL,2.12034,NULL,13.9,4,'2024-03-30 13:13:56',1),(95,NULL,193,NULL,57,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-01 20:37:58',1),(96,NULL,193,NULL,58,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-01 20:40:51',1),(97,NULL,193,NULL,59,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-01 20:42:09',1),(98,NULL,193,NULL,60,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-01 20:42:46',1),(99,NULL,193,NULL,61,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-01 20:58:31',1),(100,NULL,193,NULL,62,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 08:47:23',1),(101,NULL,193,NULL,63,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 08:47:57',1),(102,NULL,193,NULL,64,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 08:49:16',1),(103,NULL,193,NULL,65,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 08:54:27',1),(104,NULL,193,NULL,66,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 08:55:45',1),(105,NULL,193,NULL,67,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:03:15',1),(106,NULL,193,NULL,68,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:05:02',1),(107,NULL,193,NULL,69,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:05:44',1),(108,NULL,193,NULL,70,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:06:24',1),(109,NULL,193,NULL,71,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:07:11',1),(110,NULL,193,NULL,72,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:11:57',1),(111,NULL,193,NULL,73,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:25:29',1),(112,NULL,193,NULL,74,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:34:19',1),(113,NULL,193,NULL,75,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:36:10',1),(114,NULL,193,NULL,76,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 09:39:40',1),(115,NULL,193,NULL,77,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:01:23',1),(116,NULL,193,NULL,78,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:05:37',1),(117,NULL,193,NULL,79,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:09:46',1),(118,NULL,193,NULL,80,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:26:25',1),(119,NULL,193,NULL,81,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:35:06',1),(120,NULL,193,NULL,82,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:35:51',1),(121,NULL,193,NULL,83,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 10:36:18',1),(122,NULL,193,NULL,84,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 11:20:18',1),(123,NULL,193,NULL,85,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:18:02',1),(124,NULL,193,NULL,86,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:20:58',1),(125,NULL,193,NULL,87,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:24:46',1),(126,NULL,193,NULL,88,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:27:34',1),(127,NULL,193,NULL,89,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:27:48',1),(128,NULL,193,NULL,90,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:28:10',1),(129,NULL,193,NULL,91,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:32:27',1),(130,NULL,193,NULL,92,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:34:51',1),(131,NULL,193,NULL,93,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:36:45',1),(132,NULL,193,NULL,94,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:40:11',1),(133,NULL,193,NULL,95,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:40:35',1),(134,NULL,193,NULL,96,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:46:14',1),(135,NULL,193,NULL,97,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:46:42',1),(136,NULL,193,NULL,98,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-03 20:49:52',1),(137,NULL,191,NULL,98,NULL,NULL,0.305085,NULL,2,1,'2024-04-03 20:49:52',2),(138,NULL,182,NULL,98,NULL,NULL,0.228814,NULL,1.5,1,'2024-04-03 20:49:52',3),(139,NULL,193,NULL,99,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-06 16:24:44',1),(140,NULL,191,NULL,99,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 16:24:44',2),(141,NULL,182,NULL,99,NULL,NULL,0.228814,NULL,1.5,1,'2024-04-06 16:24:44',3),(142,NULL,193,NULL,100,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-06 16:37:36',1),(143,NULL,191,NULL,100,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 16:37:37',2),(144,NULL,182,NULL,100,NULL,NULL,0.228814,NULL,1.5,1,'2024-04-06 16:37:37',3),(145,NULL,193,NULL,101,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-06 16:37:45',1),(146,NULL,191,NULL,101,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 16:37:45',2),(147,NULL,182,NULL,101,NULL,NULL,0.228814,NULL,1.5,1,'2024-04-06 16:37:45',3),(148,NULL,193,NULL,102,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-06 16:38:15',1),(149,NULL,191,NULL,102,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 16:38:15',2),(150,NULL,182,NULL,102,NULL,NULL,0.228814,NULL,1.5,1,'2024-04-06 16:38:15',3),(151,NULL,193,NULL,103,NULL,NULL,2.12034,NULL,13.9,4,'2024-04-06 16:51:27',1),(152,NULL,191,NULL,103,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 16:51:27',2),(153,NULL,182,NULL,103,NULL,NULL,0.228814,NULL,1.5,1,'2024-04-06 16:51:27',3),(154,NULL,193,NULL,104,NULL,NULL,2.12034,NULL,13.9,1,'2024-04-06 17:02:42',1),(155,NULL,191,NULL,104,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 17:02:42',2),(156,NULL,193,NULL,105,NULL,NULL,2.12034,NULL,13.9,1,'2024-04-06 17:04:40',1),(157,NULL,191,NULL,105,NULL,NULL,0.305085,NULL,2,1,'2024-04-06 17:04:40',2),(158,NULL,193,NULL,106,NULL,NULL,2.12034,NULL,13.9,1,'2024-04-08 17:38:51',1),(159,NULL,191,NULL,106,NULL,NULL,0.305085,NULL,2,1,'2024-04-08 17:38:51',2),(160,NULL,179,NULL,107,NULL,NULL,0.442373,NULL,2.9,3,'2024-04-09 17:25:17',1);
/*!40000 ALTER TABLE `pedido_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_detalle_atributo_producto`
--

DROP TABLE IF EXISTS `pedido_detalle_atributo_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_detalle_atributo_producto` (
  `id_pedido_detalle_atributo` int NOT NULL AUTO_INCREMENT,
  `id_pedido_detalle` int DEFAULT NULL,
  `id_atributo` int DEFAULT NULL,
  `cantidad_pedido_detalle_atributo_producto` int DEFAULT NULL,
  `hexadecimal_producto_color` varchar(255) DEFAULT NULL,
  `nombre_color_detalle_atributo_producto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pedido_detalle_atributo`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_detalle_atributo_producto`
--

LOCK TABLES `pedido_detalle_atributo_producto` WRITE;
/*!40000 ALTER TABLE `pedido_detalle_atributo_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido_detalle_atributo_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `glosa_perfil` varchar(255) DEFAULT NULL,
  `vigente_perfil` int DEFAULT '1',
  `perfildefecto_cliente` int DEFAULT NULL,
  PRIMARY KEY (`id_perfil`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'ADMINISTRADOR',1,0),(2,'VETERINARIO',0,0),(3,'BODEGUERO',0,0),(4,'RECEPCIONISTA',0,0),(5,'FARMACEUTICO(A)',1,0),(6,'Tecnólogo Médico',0,0),(7,'Clientes',0,0),(8,'ACCESO CUENTA CLIENTES',1,1),(9,'Recepcionista 2',0,0),(10,'Venta Productos',0,0),(11,'Vendedor',0,0);
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_modulo`
--

DROP TABLE IF EXISTS `perfil_modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_modulo` (
  `id_perfil_modulo` int NOT NULL AUTO_INCREMENT,
  `id_perfil` int NOT NULL,
  `id_modulo` int NOT NULL,
  `vigente_perfil_modulo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_perfil_modulo`),
  UNIQUE KEY `uq_perfil_modulo` (`id_perfil`,`id_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_modulo`
--

LOCK TABLES `perfil_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_modulo` DISABLE KEYS */;
INSERT INTO `perfil_modulo` VALUES (22,5,3,1),(23,5,5,1),(24,5,7,1),(25,5,9,1),(26,5,11,1),(27,5,12,1),(28,5,13,1);
/*!40000 ALTER TABLE `perfil_modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto`
--

DROP TABLE IF EXISTS `presupuesto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presupuesto` (
  `id_presupuesto` int NOT NULL AUTO_INCREMENT,
  `glosa_presupuesto` varchar(255) DEFAULT NULL,
  `detalle_presupuesto` text,
  `vigente_presupuesto` int DEFAULT '1',
  `id_cirugia` int DEFAULT NULL,
  PRIMARY KEY (`id_presupuesto`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto`
--

LOCK TABLES `presupuesto` WRITE;
/*!40000 ALTER TABLE `presupuesto` DISABLE KEYS */;
/*!40000 ALTER TABLE `presupuesto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuesto_asignado`
--

DROP TABLE IF EXISTS `presupuesto_asignado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presupuesto_asignado` (
  `id_presupuesto_asignado` int NOT NULL AUTO_INCREMENT,
  `id_folio` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `numero_presupuesto_asignado` varchar(255) DEFAULT NULL,
  `glosa_presupuesto_asignado` varchar(255) DEFAULT NULL,
  `detalle_presupuesto_asignado` text,
  `total_presupuesto_asignado` float DEFAULT NULL,
  `fechacreacion_presupuesto_asignado` datetime DEFAULT NULL,
  `vigente_presupuesto_asignado` int DEFAULT NULL,
  PRIMARY KEY (`id_presupuesto_asignado`) USING BTREE,
  KEY `fk_idfolio_folio` (`id_folio`) USING BTREE,
  KEY `fk_idusuario_usuario` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_idfolio_folio` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_idusuario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuesto_asignado`
--

LOCK TABLES `presupuesto_asignado` WRITE;
/*!40000 ALTER TABLE `presupuesto_asignado` DISABLE KEYS */;
/*!40000 ALTER TABLE `presupuesto_asignado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedimientos`
--

DROP TABLE IF EXISTS `procedimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procedimientos` (
  `id_procedimiento` int NOT NULL AUTO_INCREMENT,
  `glosa_procedimiento` varchar(255) DEFAULT NULL,
  `detalle_procedimiento` text,
  `precio_procedimiento` float DEFAULT NULL,
  `aplicaiva_procedimiento` int DEFAULT NULL,
  `orden_procedimiento` int DEFAULT NULL,
  `vigente_procedimiento` int DEFAULT '1',
  PRIMARY KEY (`id_procedimiento`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedimientos`
--

LOCK TABLES `procedimientos` WRITE;
/*!40000 ALTER TABLE `procedimientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `procedimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `id_tipo_producto` int DEFAULT NULL,
  `id_tipo_concentracion` int DEFAULT NULL,
  `id_tipo_inventario` int DEFAULT NULL,
  `id_unidad` int DEFAULT NULL,
  `id_marca` int DEFAULT NULL,
  `id_proveedor` int DEFAULT NULL,
  `id_tipo_afectacion` int DEFAULT NULL,
  `codigo_producto` longtext,
  `codigo_barra_producto` varchar(255) DEFAULT NULL,
  `codigooriginal_producto` varchar(1000) DEFAULT NULL,
  `glosa_producto` varchar(255) DEFAULT NULL,
  `detalle_producto` text,
  `detallelargo_producto` text,
  `multidosis_producto` varchar(10) DEFAULT NULL,
  `dosis_producto` float DEFAULT NULL,
  `concentracion_producto` float DEFAULT NULL,
  `cantidad_producto` float DEFAULT NULL,
  `stock_producto` float DEFAULT NULL,
  `stockcritico_producto` float DEFAULT NULL,
  `aplicaiva_producto` int DEFAULT NULL,
  `preciocosto_producto` float DEFAULT NULL,
  `precioventa_producto` float DEFAULT NULL,
  `stockentransito_producto` float DEFAULT NULL,
  `fechacreacion_producto` datetime DEFAULT NULL,
  `saldocantidad_producto` float DEFAULT NULL,
  `contenidomultidosis_producto` float DEFAULT NULL,
  `permitirvendersinstock_producto` int DEFAULT '0',
  `promedioglobalpreciocosto_producto` float DEFAULT NULL,
  `urlamigable_producto` varchar(5000) DEFAULT NULL,
  `anchopaquete_producto` float DEFAULT NULL,
  `altopaquete_producto` float DEFAULT NULL,
  `profundidadpaquete_producto` float DEFAULT NULL,
  `pesopaquete_producto` float DEFAULT NULL,
  `vigente_producto` int DEFAULT '1',
  `visibleonline_producto` int DEFAULT '1',
  `tipo_precio_producto` varchar(255) DEFAULT '10',
  PRIMARY KEY (`id_producto`) USING BTREE,
  KEY `fk_relationship_30` (`id_marca`) USING BTREE,
  KEY `fk_relationship_47` (`id_tipo_producto`) USING BTREE,
  KEY `fk_relationship_50` (`id_unidad`) USING BTREE,
  KEY `fk_relationship_53` (`id_tipo_inventario`) USING BTREE,
  KEY `fk_relationship_216` (`id_tipo_concentracion`) USING BTREE,
  KEY `fk_tipo_afectacion` (`id_tipo_afectacion`) USING BTREE,
  CONSTRAINT `fk_relationship_216` FOREIGN KEY (`id_tipo_concentracion`) REFERENCES `tipo_concentracion` (`id_tipo_concentracion`),
  CONSTRAINT `fk_relationship_30` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `fk_relationship_47` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipo_producto` (`id_tipo_producto`),
  CONSTRAINT `fk_relationship_50` FOREIGN KEY (`id_unidad`) REFERENCES `unidad` (`id_unidad`),
  CONSTRAINT `fk_relationship_53` FOREIGN KEY (`id_tipo_inventario`) REFERENCES `tipo_inventario` (`id_tipo_inventario`),
  CONSTRAINT `fk_tipo_afectacion` FOREIGN KEY (`id_tipo_afectacion`) REFERENCES `tipo_afectacion` (`id_tipo_afectacion`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (90,1,NULL,3,NULL,65,1216,1,'AX-DE-AT-FR-Y-FR','7848796973',NULL,'AXE DESODORANTE ATOMIZADOR FRAGANCIA Y FRESCURA','<p>El desodorante atomizador Axe combina fragancia y frescura en un formato práctico. Diseñado para hombres activos, este desodorante ofrece una aplicación fácil y rápida para brindar protección contra el mal olor y proporcionar una sensación de frescura duradera. La fragancia distintiva de Axe complementa el estilo de vida dinámico, brindando confianza y comodidad a lo largo del día.</p>','<p>El desodorante atomizador Axe es una opción diseñada para hombres que buscan una combinación de fragancia distintiva y frescura duradera. Este producto, presentado en un formato práctico de atomizador, proporciona una aplicación fácil y rápida, ideal para el estilo de vida activo y dinámico.</p><p><span style=\"color: var(--tw-prose-bold);\">Características clave:</span></p><ol><li><span style=\"color: var(--tw-prose-bold);\">Fragancia Atractiva:</span> El desodorante Axe no solo ofrece protección contra el mal olor, sino que también incorpora una fragancia distintiva. Cada atomización libera una mezcla cautivadora que complementa el estilo y la personalidad del usuario.</li><li><span style=\"color: var(--tw-prose-bold);\">Formato Atomizador:</span> La presentación en atomizador facilita la aplicación rápida y cómoda del desodorante. Este formato es conveniente para llevarlo consigo a lo largo del día, permitiendo una aplicación fácil en cualquier momento que se desee refrescar.</li><li><span style=\"color: var(--tw-prose-bold);\">Protección Duradera:</span> El desodorante Axe no solo se centra en proporcionar una agradable fragancia, sino que también brinda protección duradera contra el mal olor. Su fórmula está diseñada para mantener la frescura a lo largo del día, incluso en situaciones de alta actividad física.</li><li><span style=\"color: var(--tw-prose-bold);\">Estilo de Vida Activo:</span> Diseñado pensando en hombres con un estilo de vida activo, el desodorante Axe se adapta a las necesidades de quienes buscan mantenerse frescos y seguros, sin importar las demandas diarias.</li><li><span style=\"color: var(--tw-prose-bold);\">Confianza y Comodidad:</span> La combinación de fragancia y frescura no solo ayuda a combatir el mal olor, sino que también aporta a la confianza y comodidad del usuario. Axe se esfuerza por ofrecer una experiencia que va más allá de la simple protección, contribuyendo al bienestar general.</li></ol><p>Es importante seguir las indicaciones de uso proporcionadas por el fabricante para obtener los mejores resultados y evitar posibles irritaciones cutáneas. Además, el desodorante Axe es parte de una línea de productos diseñados para satisfacer las necesidades específicas de los hombres en términos de cuidado personal y estilo.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,'2024-03-04 15:45:11',NULL,NULL,0,NULL,'AXE-DESODORANTE-ATOMIZADOR-FRAGANCIA-Y-FRESCURA-AX-DE-AT-FR-Y-FR',NULL,NULL,NULL,NULL,1,1,'10'),(91,1,NULL,2,NULL,65,1217,1,'TA-IM','7848796974',NULL,'TALCO IMÁGENES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,NULL,'2024-01-07 12:32:01',NULL,NULL,0,NULL,'TA-IM',NULL,NULL,NULL,NULL,1,1,'10'),(92,1,NULL,2,NULL,65,1218,1,'GE-DE-LI-FE-25-ML','7848796975',NULL,'GEL DE LIMPIEZA FEMENINA 250 ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,'2024-07-04 17:33:48',NULL,NULL,0,NULL,'GEL-DE-LIMPIEZA-FEMENINA-250-ML-GE-DE-LI-FE-25-ML',NULL,NULL,NULL,NULL,1,1,'10'),(93,1,NULL,2,NULL,65,1219,1,'QU-ES','7848796976',NULL,'QUITA ESMALTE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:02',NULL,NULL,0,NULL,'QU-ES',NULL,NULL,NULL,NULL,1,1,'10'),(94,1,NULL,2,NULL,66,1220,1,'DO-DE-RO','7848796977',NULL,'DORSAY DESODORANTE ROLL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,'2024-01-07 12:32:02',NULL,NULL,0,NULL,'DO-DE-RO',NULL,NULL,NULL,NULL,1,1,'10'),(95,1,NULL,2,NULL,66,1221,1,'FI-DE-RO','7848796978',NULL,'FIORI DESODORANTE ROLL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,NULL,'2024-01-07 12:32:03',NULL,NULL,0,NULL,'FI-DE-RO',NULL,NULL,NULL,NULL,1,1,'10'),(96,3,NULL,2,NULL,67,1222,1,'JA-VE-LA','7848796979',NULL,'JABON VEGETAL LARGA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14,NULL,'2024-01-07 12:32:03',NULL,NULL,0,NULL,'JA-VE-LA',NULL,NULL,NULL,NULL,1,1,'10'),(97,3,NULL,2,NULL,68,1223,1,'CR-PA-PE','7848796980',NULL,'CREMA PARA PEINAR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10.9,NULL,'2024-01-07 12:32:03',NULL,NULL,0,NULL,'CR-PA-PE',NULL,NULL,NULL,NULL,1,1,'10'),(98,3,NULL,2,NULL,68,1224,1,'CR-PA-PE-AG-Y-MA','7848796981',NULL,'CREMA PARA PEINAR AGUACATE Y MACADAMIA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10.9,NULL,'2024-01-07 12:32:03',NULL,NULL,0,NULL,'CR-PA-PE-AG-Y-MA',NULL,NULL,NULL,NULL,1,1,'10'),(99,1,NULL,3,NULL,65,1225,1,'ME-85','7848796982',NULL,'MENTHOLATUM 85G',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,'2024-01-07 12:32:03',NULL,NULL,0,NULL,'ME-85',NULL,NULL,NULL,NULL,1,1,'10'),(100,2,NULL,2,NULL,65,1226,1,'HI','7848796983',NULL,'HISOPOS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'HI',NULL,NULL,NULL,NULL,1,1,'10'),(101,2,NULL,2,NULL,69,1227,1,'SP-RE-DE-IN','7848796984',NULL,'SPRAY REPELENTES DE INSECTOS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'SP-RE-DE-IN',NULL,NULL,NULL,NULL,1,1,'10'),(102,2,NULL,2,NULL,70,1228,1,'VE-4X','7848796985',NULL,'VENDA 4X5',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'VE-4X',NULL,NULL,NULL,NULL,1,1,'10'),(103,2,NULL,2,NULL,70,1229,1,'VE-5X','7848796986',NULL,'VENDA 5X5',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.8,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'VE-5X',NULL,NULL,NULL,NULL,1,1,'10'),(104,2,NULL,2,NULL,65,1230,1,'FR-FL-10','7848796987',NULL,'FRUTTI FLEX 1000ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7.9,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'FR-FL-10',NULL,NULL,NULL,NULL,1,1,'10'),(105,2,NULL,2,NULL,65,1231,1,'FR-FL-50','7848796988',NULL,'FRUTTI FLEX 500ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6.5,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'FR-FL-50',NULL,NULL,NULL,NULL,1,1,'10'),(106,1,NULL,2,NULL,71,1232,1,'CO-PA-NI','7848796989',NULL,'COLONIA PARA NIÑOS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14.9,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'CO-PA-NI',NULL,NULL,NULL,NULL,1,1,'10'),(107,2,NULL,2,NULL,72,1233,1,'BA-DE-AZ','7848796990',NULL,'BARRA DE AZUFRE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.8,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'BA-DE-AZ',NULL,NULL,NULL,NULL,1,1,'10'),(108,2,NULL,2,NULL,69,1234,1,'RE-DE-IN-SA','7848796991',NULL,'REPELENTES DE INSECTO SACHET',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'RE-DE-IN-SA',NULL,NULL,NULL,NULL,1,1,'10'),(109,1,NULL,2,NULL,65,1235,1,'PI','7848796992',NULL,'PINZA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'PI',NULL,NULL,NULL,NULL,1,1,'10'),(110,1,NULL,3,NULL,65,1236,1,'HE-&-SH-SH-SA-X-18-ML-LI-RE-AZ','7848796993',NULL,'HEAD & SHOULDERS SHAMPOO SACHET X 18 ML LIMPIEZA RENOVADORA AZUL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2024-01-07 12:32:04',NULL,NULL,0,NULL,'HE-&-SH-SH-SA-X-18-ML-LI-RE-AZ',NULL,NULL,NULL,NULL,1,1,'10'),(111,3,NULL,2,NULL,65,1237,1,'PA-CO-CO-SA','7848796994',NULL,'PANTENE CON COLAGENO SACHET',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.3,NULL,'2024-01-07 12:32:05',NULL,NULL,0,NULL,'PA-CO-CO-SA',NULL,NULL,NULL,NULL,1,1,'10'),(112,2,NULL,3,NULL,65,1238,1,'AL-50','7848796995',NULL,'ALGODÓN 50G C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,'2024-01-07 12:32:06',NULL,NULL,0,NULL,'AL-50',NULL,NULL,NULL,NULL,1,1,'10'),(113,1,NULL,3,NULL,65,1239,1,'AL-25','7848796996',NULL,'ALGODÓN 25G C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-01-07 12:32:06',NULL,NULL,0,NULL,'AL-25',NULL,NULL,NULL,NULL,1,1,'10'),(114,2,NULL,3,NULL,65,1240,1,'AL-10','7848796997',NULL,'ALGODÓN 100G C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'AL-10',NULL,NULL,NULL,NULL,1,1,'10'),(115,2,NULL,3,NULL,65,1241,1,'BE-DE-BE-25','7848796998',NULL,'BENZOATO DE BENCILO 25%',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'BE-DE-BE-25',NULL,NULL,NULL,NULL,1,1,'10'),(116,1,NULL,2,NULL,65,1242,1,'FR-DE-CU','7848796999',NULL,'FRASCO DE CULTIVO C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'FR-DE-CU',NULL,NULL,NULL,NULL,1,1,'10'),(117,2,NULL,3,NULL,65,1243,1,'FR-SU-CO-SU-1%','7848797000',NULL,'FRASCO SULFESIDA CON SULFAFIZINA 1%',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,16.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'FR-SU-CO-SU-1%',NULL,NULL,NULL,NULL,1,1,'10'),(118,3,NULL,2,NULL,73,1244,1,'TO-HU-PE-AL-VE---BO-24','7848797001',NULL,'TOALLAS HUMEDAS PEQUENIN ALOE VERA - BOLSA 24 UN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.8,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'TO-HU-PE-AL-VE---BO-24',NULL,NULL,NULL,NULL,1,1,'10'),(119,2,NULL,3,NULL,65,1245,1,'PA-AN','7848797002',NULL,'PANADOL ANTRIGRIPAL C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'PA-AN',NULL,NULL,NULL,NULL,1,1,'10'),(120,2,NULL,3,NULL,65,1246,1,'AB-JA-35-X1','7848797003',NULL,'ABRIMED JARABE 35MG/5ML X120ML','Prueba','Prueba',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,18,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'AB-JA-35-X1',NULL,NULL,NULL,NULL,1,1,'10'),(121,2,NULL,3,NULL,65,1247,1,'DI-GE-1%','7848797004',NULL,'DICLOFENACO GEL 1% C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'DI-GE-1%',NULL,NULL,NULL,NULL,1,1,'10'),(122,2,NULL,3,NULL,74,1248,1,'SA-DE-AN-EF---CA-10','7848797005',NULL,'SAL DE ANDREWS EFERVESCENTE - CAJA 100 UN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.8,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'SA-DE-AN-EF---CA-10',NULL,NULL,NULL,NULL,1,1,'10'),(123,2,NULL,3,NULL,74,1249,1,'AN-SA-DE-AN-TR-AC-PO-EF-SO-7,','7848797006',NULL,'ANTIACIDO SAL DE ANDREWS TRIPLE ACCION POLVO EFERVESCENTE SOBRE 7,9G.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.2,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'AN-SA-DE-AN-TR-AC-PO-EF-SO-7,',NULL,NULL,NULL,NULL,1,1,'10'),(124,2,NULL,3,NULL,65,1250,1,'HI-BA-X1-UN','7848797007',NULL,'HISOPOS BAMBINO X100 UNIDADES C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'HI-BA-X1-UN',NULL,NULL,NULL,NULL,1,1,'10'),(125,1,NULL,2,NULL,65,1251,1,'PE','7848797008',NULL,'PEINE C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'PE',NULL,NULL,NULL,NULL,1,1,'10'),(126,1,NULL,2,NULL,65,1252,1,'PA-HI-NO-X4','7848797009',NULL,'PAPEL HIGENICO NOBLE X4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'PA-HI-NO-X4',NULL,NULL,NULL,NULL,1,1,'10'),(127,1,NULL,2,NULL,65,1253,1,'PA-HI-SU-X4','7848797010',NULL,'PAPEL HIGENICO SUAVE X4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'PA-HI-SU-X4',NULL,NULL,NULL,NULL,1,1,'10'),(128,1,NULL,2,NULL,65,1254,1,'HU-PA-XX','7848797011',NULL,'HUGGIES PAÑAL XXG C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.3,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'HU-PA-XX',NULL,NULL,NULL,NULL,1,1,'10'),(129,1,NULL,2,NULL,65,1255,1,'HU-PA-XG','7848797012',NULL,'HUGGIES PAÑAL XG C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.2,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'HU-PA-XG',NULL,NULL,NULL,NULL,1,1,'10'),(130,3,NULL,2,NULL,65,1256,1,'NU-SA','7848797013',NULL,'NUTRIBELA SACHET C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'NU-SA',NULL,NULL,NULL,NULL,1,1,'10'),(131,2,NULL,3,NULL,65,1257,1,'VI-E-10-CA-BL-X1','7848797014',NULL,'VITAMINA E 100 CAPSULAS BLISTER X10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'VI-E-10-CA-BL-X1',NULL,NULL,NULL,NULL,1,1,'10'),(132,2,NULL,3,NULL,65,1258,1,'VI-E-10-CA-CA','7848797015',NULL,'VITAMINA E 100 CAPSULAS CAJA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,18,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'VI-E-10-CA-CA',NULL,NULL,NULL,NULL,1,1,'10'),(133,2,NULL,3,NULL,65,1259,1,'CL-DE-MA-25-GR','7848797016',NULL,'CLORURO DE MAGNESIO 250 GRS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'CL-DE-MA-25-GR',NULL,NULL,NULL,NULL,1,1,'10'),(134,2,NULL,3,NULL,65,1260,1,'CL-DE-MA-50-GR','7848797017',NULL,'CLORURO DE MAGNESIO 500 GRS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,29,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'CL-DE-MA-50-GR',NULL,NULL,NULL,NULL,1,1,'10'),(135,2,NULL,3,NULL,65,1261,1,'PO-C-X-CA-DE-30-SO','7848797018',NULL,'POWERVIT C X CAJA DE 30 SOBRES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,58,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'PO-C-X-CA-DE-30-SO',NULL,NULL,NULL,NULL,1,1,'10'),(136,2,NULL,3,NULL,65,1262,1,'PO-C-SO-VI','7848797019',NULL,'POWERVIT C SOBRE VITAMINA C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'PO-C-SO-VI',NULL,NULL,NULL,NULL,1,1,'10'),(137,2,NULL,3,NULL,65,1263,1,'FL-PL-CO','7848797020',NULL,'FLEXIGEM PLUS COLAGENO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,85,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'FL-PL-CO',NULL,NULL,NULL,NULL,1,1,'10'),(138,2,NULL,3,NULL,65,1264,1,'FU-SP','7848797021',NULL,'FULL SPECTRUM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,33,NULL,'2024-01-07 12:32:07',NULL,NULL,0,NULL,'FU-SP',NULL,NULL,NULL,NULL,1,1,'10'),(139,1,NULL,2,NULL,65,1265,1,'CO-UÑ-PE','7848797022',NULL,'CORTA UÑA PEQUEÑO C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'CO-UÑ-PE',NULL,NULL,NULL,NULL,1,1,'10'),(140,1,NULL,2,NULL,65,1266,1,'CO-UÑ-GR','7848797023',NULL,'CORTA UÑA GRANDE C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'CO-UÑ-GR',NULL,NULL,NULL,NULL,1,1,'10'),(141,1,NULL,2,NULL,65,1267,1,'HU-TO-X-48','7848797024',NULL,'HUGGIES TOALLITAS X 48 UN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11.8,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'HU-TO-X-48',NULL,NULL,NULL,NULL,1,1,'10'),(142,1,NULL,2,NULL,65,1268,1,'TU-TO-X-10','7848797025',NULL,'TUINIES TOALLITAS X 100 UN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10.9,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'TU-TO-X-10',NULL,NULL,NULL,NULL,1,1,'10'),(143,1,NULL,3,NULL,65,1269,1,'SH-SA','7848797026',NULL,'SHAMPOO SAVITAL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12.9,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'SH-SA',NULL,NULL,NULL,NULL,1,1,'10'),(144,1,NULL,3,NULL,65,1270,1,'SH-HE-&-SH-2-EN-1-SU-Y-MA-CO-CA-25-ML','7848797027',NULL,'SHAMPOO HEAD & SHOULDERS 2 EN 1 SUAVE Y MANEJABLE CONTROL CASPA 250 ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'SH-HE-&-SH-2-EN-1-SU-Y-MA-CO-CA-25-ML',NULL,NULL,NULL,NULL,1,1,'10'),(145,1,NULL,3,NULL,65,1271,1,'PA-SH-30','7848797028',NULL,'PANTENE SHAMPOO 300ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,NULL,'2024-01-07 12:32:08',NULL,NULL,0,NULL,'PA-SH-30',NULL,NULL,NULL,NULL,1,1,'10'),(146,1,NULL,4,NULL,75,1272,1,'JA-AN-AV-40-ML','7848797029',NULL,'JABON ANTIBACTERIAL AVAL 400 ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'JA-AN-AV-40-ML',NULL,NULL,NULL,NULL,1,1,'10'),(147,1,NULL,2,NULL,76,1273,1,'KO-TO-HI','7848797030',NULL,'KOTEX TOALLAS HIGIÉNICAS C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'KO-TO-HI',NULL,NULL,NULL,NULL,1,1,'10'),(148,1,NULL,2,NULL,77,1274,1,'NO-TO-HI','7848797031',NULL,'NOSOTRAS TOALLAS HIGENICAS C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.5,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'NO-TO-HI',NULL,NULL,NULL,NULL,1,1,'10'),(149,1,NULL,2,NULL,78,1275,1,'ST-TO-HI','7848797032',NULL,'STAYFREE TOALLAS HIGIÉNICAS C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.3,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'ST-TO-HI',NULL,NULL,NULL,NULL,1,1,'10'),(150,1,NULL,2,NULL,79,1276,1,'LA-NO','7848797033',NULL,'LADYSOFT NORMAL C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'LA-NO',NULL,NULL,NULL,NULL,1,1,'10'),(151,1,NULL,2,NULL,77,1277,1,'NO-NO','7848797034',NULL,'NOSOTRAS NOCHE C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7.5,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'NO-NO',NULL,NULL,NULL,NULL,1,1,'10'),(152,1,NULL,3,NULL,65,1278,1,'HE-DE-PR-JA-X-15-GR','7848797035',NULL,'HENO DE PRAVIA JABON X 150 GR','<p>El jabón de Heno de Pravia en su presentación de 150 gramos es un producto clásico y reconocido por su fragancia fresca y tradicional que evoca la pureza de los campos asturianos. Elaborado con ingredientes de alta calidad, este jabón ofrece una limpieza suave y efectiva, eliminando impurezas y dejando la piel tersa y suave al tacto. Su aroma fresco perdura en la piel, proporcionando una sensación de frescura y bienestar durante todo el día. Con una espuma cremosa y abundante, este jabón es apto para todo tipo de piel, incluso las más sensibles, gracias a su fórmula cuidadosamente balanceada. Disfruta de una experiencia única de limpieza y cuidado personal con el jabón de Heno de Pravia, un clásico que perdura en el tiempo.</p>','<p>Jabón de Heno de Pravia, con aroma tradicional y fresco, en presentación de 150 gramos. Ideal para una limpieza suave y efectiva, dejando la piel con una sensación refrescante y delicadamente perfumada.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5.5,NULL,'2024-03-26 20:53:54',NULL,NULL,0,NULL,'HENO-DE-PRAVIA-JABON-X-150-GR-HE-DE-PR-JA-X-15-GR',NULL,NULL,NULL,NULL,1,1,'10'),(153,3,NULL,4,NULL,65,1279,1,'HE-DE-PR-OR','7848797036',NULL,'HENO DE PRAVIA ORIGINAL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5.5,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'HE-DE-PR-OR',NULL,NULL,NULL,NULL,1,1,'10'),(154,3,NULL,4,NULL,80,1280,1,'DO-JA','7848797037',NULL,'DOVE JABON',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.9,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'DO-JA',NULL,NULL,NULL,NULL,1,1,'10'),(155,3,NULL,4,NULL,65,1281,1,'PR-JA','7848797038',NULL,'PROTEX JABON',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.2,NULL,'2024-01-07 12:32:09',NULL,NULL,0,NULL,'PR-JA',NULL,NULL,NULL,NULL,1,1,'10'),(156,1,NULL,3,NULL,65,1282,1,'SP-JA','7848797039',NULL,'SPA JABON',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.9,NULL,'2024-01-07 12:32:10',NULL,NULL,0,NULL,'SP-JA',NULL,NULL,NULL,NULL,1,1,'10'),(157,1,NULL,3,NULL,81,1283,1,'JA-NE-12','7848797040',NULL,'JABON NEKO 125GR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.5,NULL,'2024-01-07 12:32:10',NULL,NULL,0,NULL,'JA-NE-12',NULL,NULL,NULL,NULL,1,1,'10'),(158,1,NULL,3,NULL,82,1284,1,'JA-BL-MO','7848797041',NULL,'JABON BLANCO MONCLER C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.8,NULL,'2024-01-07 12:32:10',NULL,NULL,0,NULL,'JA-BL-MO',NULL,NULL,NULL,NULL,1,1,'10'),(159,3,NULL,4,NULL,83,1285,1,'CE-OR-B','7848797042',NULL,'CEPILLO ORAL B C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.7,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'CE-OR-B',NULL,NULL,NULL,NULL,1,1,'10'),(160,3,NULL,4,NULL,84,1286,1,'CE-KO','7848797043',NULL,'CEPILLO KOLYNOS C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'CE-KO',NULL,NULL,NULL,NULL,1,1,'10'),(161,3,NULL,4,NULL,85,1287,1,'CE-KI-NI','7848797044',NULL,'CEPILLO KIDS NINO C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'CE-KI-NI',NULL,NULL,NULL,NULL,1,1,'10'),(162,1,NULL,2,NULL,86,1288,1,'GI-PR-3-HO','7848797045',NULL,'GILLETTE PRESTOBARBA 3 HOJAS C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.5,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'GI-PR-3-HO',NULL,NULL,NULL,NULL,1,1,'10'),(163,1,NULL,4,NULL,65,1289,1,'SC-EX-2-HO','7848797046',NULL,'SCHICK EXACTA 2 HOJAS C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.8,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'SC-EX-2-HO',NULL,NULL,NULL,NULL,1,1,'10'),(164,3,NULL,4,NULL,65,1290,1,'SC-UL','7848797047',NULL,'SCHICK ULTRABARBA C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'SC-UL',NULL,NULL,NULL,NULL,1,1,'10'),(165,3,NULL,4,NULL,85,1291,1,'CO-HE','7848797048',NULL,'COLGATE HERBAL C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.8,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'CO-HE',NULL,NULL,NULL,NULL,1,1,'10'),(166,3,NULL,4,NULL,84,1292,1,'KO-SU-AM-56-GR','7848797049',NULL,'KOLYNOS SUPER AMARILLO 56 GR',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.8,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'KO-SU-AM-56-GR',NULL,NULL,NULL,NULL,1,1,'10'),(167,3,NULL,4,NULL,84,1293,1,'KO-HE-90-GR','7848797050',NULL,'KOLYNOS HERBAL 90 GR C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.5,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'KO-HE-90-GR',NULL,NULL,NULL,NULL,1,1,'10'),(168,1,NULL,3,NULL,85,1294,1,'CO-TR-AC-FA-15-ML','7848797051',NULL,'COLGATE TRIPLE ACCION FAMILIAR 150 ML C/U','<p><strong>\"Colgate Triple Acción Familiar\"</strong> es una pasta dental innovadora que ofrece una protección completa para toda la familia. Su fórmula avanzada combate eficazmente las caries, elimina la placa bacteriana y refresca el aliento, garantizando una higiene bucal óptima para todos. Con ingredientes de calidad y el respaldo de una marca confiable como Colgate, esta pasta dental es la elección perfecta para mantener sonrisas saludables y brillantes todos los días.</p>','<p><strong>\"Colgate Triple Acción Familiar\"</strong> representa la culminación de décadas de experiencia en cuidado bucal por parte de Colgate, una marca líder mundial en higiene oral. Diseñada específicamente para satisfacer las necesidades de toda la familia, esta pasta dental ofrece una protección completa en cada cepillado.</p><p>Su fórmula avanzada incorpora ingredientes de alta calidad que trabajan en sinergia para proporcionar una limpieza profunda y efectiva. En primer lugar, combate las caries, gracias a la inclusión de fluoruro, un mineral esencial que fortalece el esmalte dental y previene la formación de cavidades. Esto es crucial para mantener la salud oral a largo plazo, especialmente en niños en crecimiento y adultos propensos a la caries.</p><p>Además, \"Colgate Triple Acción Familiar\" se destaca por su capacidad para eliminar la placa bacteriana, una de las principales causas de problemas dentales como la gingivitis y la enfermedad periodontal. Sus agentes limpiadores penetran entre los dientes y a lo largo de la línea de las encías, eliminando de manera efectiva las bacterias y los residuos de alimentos que podrían provocar problemas de salud bucal.</p><p>Por último, pero no menos importante, esta pasta dental refresca el aliento, dejando una sensación de limpieza y frescura que perdura. Su aroma y sabor agradable hacen que el cepillado sea una experiencia agradable para toda la familia, fomentando hábitos de higiene bucal consistentes y efectivos.</p><p>En resumen, \"Colgate Triple Acción Familiar\" no solo ofrece una limpieza superior, sino que también brinda tranquilidad a los consumidores al saber que están utilizando un producto respaldado por la experiencia y la reputación de una marca de confianza. Con esta pasta dental, cada miembro de la familia puede disfrutar de una sonrisa más saludable y brillante, día tras día.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7.2,NULL,'2024-03-04 15:24:03',NULL,NULL,0,NULL,'COLGATE-TRIPLE-ACCION-FAMILIAR-150-ML-C-U-CO-TR-AC-FA-15-ML',NULL,NULL,NULL,NULL,1,1,'10'),(169,1,NULL,3,NULL,85,1295,1,'CO-TR-AC-CO-CE','7848797052',NULL,'COLGATE TRIPLE ACCION CON CEPILLO C/U','<p>Una solución de cuidado dental completa que combina una fórmula avanzada con un cepillo diseñado para una limpieza profunda y eficaz.</p>','<p>Colgate Triple Acción con Cepillo es una innovadora solución para el cuidado dental que ofrece una limpieza completa y profunda en cada uso. Esta fórmula avanzada combina tres beneficios principales: limpieza, protección y frescura. El poder de limpieza elimina eficazmente la placa y las bacterias, protegiendo contra la caries dental y fortaleciendo el esmalte. Además, el diseño del cepillo, con cerdas especialmente diseñadas y un cabezal ergonómico, garantiza una limpieza precisa incluso en las áreas de difícil acceso. Con Colgate Triple Acción con Cepillo, disfruta de una sonrisa saludable y fresca en cada cepillado.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6.5,NULL,'2024-02-27 14:45:59',NULL,NULL,0,NULL,'COLGATE-TRIPLE-ACCION-CON-CEPILLO-C-U-CO-TR-AC-CO-CE',NULL,NULL,NULL,NULL,1,1,'10'),(170,1,NULL,4,NULL,87,1296,1,'DE-TR-AC-75-ML','7848797053',NULL,'DENTO TRIPLE ACCION 75 ML C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5.5,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'DE-TR-AC-75-ML',NULL,NULL,NULL,NULL,1,1,'10'),(171,1,NULL,4,NULL,84,1297,1,'KO-SU-BL-PA-DE-X-22-ML','7848797054',NULL,'KOLYNOS SUPER BLANCO PASTA DENTAL X 22 ML',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.7,NULL,'2024-01-07 12:32:11',NULL,NULL,0,NULL,'KO-SU-BL-PA-DE-X-22-ML',NULL,NULL,NULL,NULL,1,1,'10'),(172,1,NULL,3,NULL,65,1298,1,'KI','7848797055',NULL,'KITADOL C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.9,NULL,'2024-01-07 12:32:12',NULL,NULL,0,NULL,'KI',NULL,NULL,NULL,NULL,1,1,'10'),(173,1,2,3,5,88,1299,1,'MI-RE','7848797056',NULL,'MIODEL RELAX C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-02-07 08:58:40',NULL,NULL,0,NULL,'MIODEL-RELAX-C-U-MI-RE',NULL,NULL,NULL,NULL,1,1,'10'),(174,1,NULL,3,NULL,65,1300,1,'FL-AD','7848797057',NULL,'FLATUZYM ADVANCE C/U',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-01-07 12:32:12',NULL,NULL,0,NULL,'FL-AD',NULL,NULL,NULL,NULL,0,1,'10'),(175,1,NULL,3,NULL,65,1301,1,'BA-FO','7848797058',NULL,'BACTRIM FORTE C/U','<p><strong>BACTRIM FORTE</strong> es un antibiótico que combina sulfametoxazol y trimetoprim para tratar diversas infecciones bacterianas. Su amplio espectro de acción lo hace efectivo contra una variedad de bacterias. Se utiliza comúnmente para tratar infecciones del tracto urinario, respiratorias y gastrointestinales. Es esencial seguir las indicaciones del médico y completar el curso de tratamiento para evitar la resistencia bacteriana y asegurar la erradicación completa de la infección.</p>','<p><strong>BACTRIM FORTE</strong> es un medicamento que combina dos ingredientes activos, el sulfametoxazol y el trimetoprim. Ambos compuestos pertenecen a la clase de antibióticos y se utilizan en conjunto para tratar diversas infecciones bacterianas. Este medicamento es conocido por su amplio espectro de acción, abordando un rango variado de bacterias.</p><p>El sulfametoxazol y el trimetoprim actúan sinérgicamente, interfiriendo con la síntesis de ácidos nucleicos en las bacterias, lo que resulta en la inhibición de su crecimiento y reproducción. <strong>BACTRIM FORTE</strong> se prescribe comúnmente para tratar infecciones del tracto urinario, infecciones respiratorias, infecciones gastrointestinales y otras afecciones bacterianas.</p><p>Es crucial seguir las indicaciones precisas del médico y completar el curso de tratamiento, incluso si los síntomas mejoran antes. Esto ayuda a prevenir la resistencia bacteriana y garantiza la erradicación completa de la infección. Además, se debe informar al médico sobre cualquier alergia a medicamentos o efectos secundarios experimentados durante el tratamiento.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-02-27 22:24:52',NULL,NULL,0,NULL,'BACTRIM-FORTE-C-U-BA-FO',NULL,NULL,NULL,NULL,1,1,'10'),(176,1,5,3,5,65,1302,1,'APX','7848797059',NULL,'APRONAX C/U','<p>APRONAX es un medicamento antiinflamatorio no esteroideo (AINE) que contiene naproxeno como ingrediente activo. Se utiliza para aliviar el dolor y reducir la inflamación en condiciones como artritis, osteoartritis y dolores musculares. Aunque proporciona alivio sintomático, su uso debe ser supervisado por un profesional de la salud debido a posibles efectos secundarios y contraindicaciones.</p>','<p>APRONAX es una marca comercial de un medicamento antiinflamatorio no esteroideo (AINE) ampliamente utilizado para aliviar el dolor y reducir la inflamación. Su ingrediente activo es el naproxeno, que pertenece a la clase de medicamentos AINEs. Estos fármacos actúan bloqueando la acción de ciertas sustancias en el cuerpo responsables de la inflamación y el dolor.</p><p>El naproxeno presente en APRONAX se utiliza comúnmente para tratar una variedad de condiciones, como artritis, osteoartritis, dolor menstrual, dolores musculares y otros tipos de inflamación. A menudo se prescribe para proporcionar alivio a corto plazo de los síntomas molestos asociados con estas condiciones.</p><p>Es importante tener en cuenta que APRONAX no está exento de efectos secundarios, y su uso debe ser supervisado por un profesional de la salud. Puede interactuar con otros medicamentos y tener contraindicaciones en ciertos pacientes, por lo que se recomienda seguir las indicaciones del médico y la información proporcionada en el prospecto del medicamento.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-02-05 09:23:01',NULL,NULL,0,NULL,'APRONAX-C-U-APX',NULL,NULL,NULL,NULL,1,1,'10'),(177,1,NULL,3,NULL,65,1303,1,'NO-SA','7848797060',NULL,'NOPUCID SACHET C/U','<p><strong>NOPUCID SHAMPOO</strong> es un producto dermatológico que contiene nifuroxazida, un agente antimicrobiano. Se utiliza para tratar condiciones del cuero cabelludo asociadas con infecciones bacterianas, como la dermatitis seborreica y la caspa bacteriana. Este shampoo ayuda a aliviar la irritación y la descamación, contribuyendo a la salud del cuero cabelludo. Se recomienda seguir las indicaciones de uso para obtener resultados óptimos.</p>','<p><strong>NOPUCID SHAMPOO </strong>es un producto dermatológico que contiene nifuroxazida como su componente principal. La nifuroxazida, con propiedades antimicrobianas, se utiliza en este shampoo para abordar condiciones del cuero cabelludo asociadas con infecciones bacterianas, como la dermatitis seborreica y la caspa de origen bacteriano.</p><p>La acción antimicrobiana de la nifuroxazida en <strong>NOPUCID SHAMPOO</strong> se dirige a inhibir el crecimiento de bacterias en el cuero cabelludo, ayudando a aliviar la irritación y reducir la descamación asociada con las condiciones mencionadas. Además, este shampoo puede tener propiedades antiinflamatorias y calmantes, contribuyendo así a mejorar la salud general del cuero cabelludo.</p><p>Es fundamental seguir las instrucciones de uso proporcionadas por el médico o las indicaciones del envase. La consistencia en la aplicación y el tiempo recomendado de uso son esenciales para obtener los mejores resultados. En caso de persistencia de los síntomas o cualquier reacción adversa, se debe buscar asesoramiento médico.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.2,NULL,'2024-03-04 16:03:56',NULL,NULL,0,NULL,'NOPUCID-SACHET-C-U-NO-SA',NULL,NULL,NULL,NULL,1,1,'10'),(178,1,NULL,2,NULL,84,1304,1,'BO-DE-BA-X-10','7848797061',NULL,'BOLSA DE BASURA X 10 UN',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2024-01-07 12:32:13',NULL,NULL,0,NULL,'BO-DE-BA-X-10',NULL,NULL,NULL,NULL,1,1,'10'),(179,1,2,3,1,89,NULL,1,'Muco',NULL,NULL,'MUCOASMAT 600 MG C/U','<p><strong>MUCOASMAT 600 mg</strong> es un medicamento que contiene acetilcisteína como ingrediente activo. Se utiliza para tratar afecciones respiratorias caracterizadas por la acumulación de mucosidad espesa, como la bronquitis crónica y la EPOC. La acetilcisteína actúa como un mucolítico, disolviendo las secreciones mucosas para facilitar su eliminación. También tiene propiedades antioxidantes que pueden ayudar a mejorar la función pulmonar. Se recomienda seguir las indicaciones del médico y leer el prospecto del medicamento.</p>','<p><strong>MUCOASMAT 600 mg</strong> es una marca de medicamento que contiene acetilcisteína como su principio activo. La acetilcisteína es un agente mucolítico que actúa disolviendo y aflojando las secreciones mucosas, facilitando así su eliminación. Este medicamento se utiliza comúnmente para tratar afecciones respiratorias que involucran la acumulación de mucosidad espesa y viscosa, como la bronquitis crónica, la enfermedad pulmonar obstructiva crónica (EPOC) y otras condiciones respiratorias.</p><p>La acetilcisteína en <strong>MUCOASMAT 600 mg</strong> también tiene propiedades antioxidantes, lo que significa que puede ayudar a reducir el daño causado por los radicales libres en las vías respiratorias. Esto puede contribuir a aliviar los síntomas y mejorar la función pulmonar en pacientes con enfermedades respiratorias crónicas.</p><p>Como con cualquier medicamento, es esencial seguir las indicaciones del médico y leer cuidadosamente el prospecto del medicamento. Además, se debe tener en cuenta cualquier posible interacción con otros medicamentos y las precauciones específicas para cada paciente.</p>',NULL,NULL,NULL,NULL,-3,NULL,NULL,NULL,1.5,NULL,'2024-02-27 22:18:50',NULL,NULL,0,NULL,'MUCOASMAT-600-MG-C-U-Muco',NULL,NULL,NULL,NULL,1,1,'10'),(180,1,NULL,3,NULL,NULL,NULL,1,'APNAX',NULL,NULL,'APRONAX TB','<p><strong>APRONAX</strong> es un medicamento antiinflamatorio no esteroideo (AINE) que contiene naproxeno como ingrediente activo. Se utiliza para aliviar el dolor y reducir la inflamación en condiciones como artritis, osteoartritis y dolores musculares. Aunque proporciona alivio sintomático, su uso debe ser supervisado por un profesional de la salud debido a posibles efectos secundarios y contraindicaciones.</p>','<p><strong>APRONAX</strong> es una marca comercial de un medicamento antiinflamatorio no esteroideo (AINE) ampliamente utilizado para aliviar el dolor y reducir la inflamación. Su ingrediente activo es el naproxeno, que pertenece a la clase de medicamentos AINEs. Estos fármacos actúan bloqueando la acción de ciertas sustancias en el cuerpo responsables de la inflamación y el dolor.</p><p>El naproxeno presente en <strong>APRONAX</strong> se utiliza comúnmente para tratar una variedad de condiciones, como artritis, osteoartritis, dolor menstrual, dolores musculares y otros tipos de inflamación. A menudo se prescribe para proporcionar alivio a corto plazo de los síntomas molestos asociados con estas condiciones.</p><p>Es importante tener en cuenta que <strong>APRONAX</strong> no está exento de efectos secundarios, y su uso debe ser supervisado por un profesional de la salud. Puede interactuar con otros medicamentos y tener contraindicaciones en ciertos pacientes, por lo que se recomienda seguir las indicaciones del médico y la información proporcionada en el prospecto del medicamento.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,'2024-02-27 22:22:08',NULL,NULL,0,NULL,'APRONAX-TB-APNAX',NULL,NULL,NULL,NULL,1,0,'10'),(181,1,2,3,5,NULL,NULL,1,'Caja',NULL,NULL,'DOLOCORDRALAN EXTRA FORTE','<p><strong>\"Dolocordralan Extra Forte\"</strong> es un medicamento que probablemente esté formulado para proporcionar alivio rápido y potente contra el dolor. Su nombre sugiere una combinación de analgésicos y posiblemente otros ingredientes para abordar dolencias específicas. Consulta con un profesional de la salud o la información del empaque para conocer detalles precisos sobre su uso y dosificación.</p>','<p><strong>\"Dolocordralan Extra Forte\"</strong> es un fármaco diseñado para ofrecer un alivio efectivo ante condiciones dolorosas. Su formulación posiblemente incluye analgésicos potentes y, quizás, otros componentes destinados a mejorar la eficacia del tratamiento. Este medicamento podría estar indicado para diversas dolencias, pero la información específica sobre su composición y uso preciso se puede obtener consultando con un profesional de la salud o revisando las indicaciones proporcionadas en el empaque del producto. Es fundamental seguir las indicaciones médicas y respetar las dosis recomendadas para garantizar un uso seguro y eficaz.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-02-05 08:49:14',NULL,NULL,0,NULL,'DOLOCORDRALAN-EXTRA-FORTE-Caja',NULL,NULL,NULL,NULL,1,1,'10'),(182,1,NULL,3,NULL,NULL,NULL,1,'FLA-ZYN',NULL,NULL,'FLATUZYN ADVANCE','<p>Para las flatulencia&nbsp;</p>','<p>Capsula para la flatulencia y inchason&nbsp; de la barriga</p>',NULL,NULL,NULL,NULL,-6,NULL,NULL,NULL,2.5,NULL,'2024-02-01 13:05:48',NULL,NULL,0,NULL,'FLATUZYN-ADVANCE-FLA-ZYN',NULL,NULL,NULL,NULL,1,1,'10'),(183,1,NULL,3,NULL,NULL,NULL,1,'KIDOL',NULL,NULL,'KITADOL TB CAJA','<p><strong>\"KITADOL MIGRAÑA\"</strong> es un medicamento diseñado para aliviar los síntomas asociados con las migrañas. Su fórmula puede incluir ingredientes destinados a reducir el dolor de cabeza, la sensibilidad a la luz y otros síntomas comúnmente experimentados durante un episodio de migraña. Este medicamento se prescribe con el objetivo de brindar alivio rápido y mejorar la calidad de vida de aquellos que sufren de migrañas.</p>','<p><strong>\"KITADOL MIGRAÑA\" </strong>es un fármaco diseñado específicamente para abordar los síntomas relacionados con las migrañas, una condición neurológica que se caracteriza por episodios recurrentes de dolor de cabeza pulsátil, a menudo acompañados de náuseas, vómitos y sensibilidad a la luz y al sonido. La formulación de este medicamento puede comprender agentes analgésicos, antiinflamatorios o incluso medicamentos específicos para el tratamiento de las migrañas.</p><p>Los componentes activos de \"KITADOL migraña\" están destinados a actuar sobre las vías neurales involucradas en el desencadenamiento de las migrañas, proporcionando alivio rápido y efectivo. Es crucial seguir las indicaciones del médico en cuanto a la dosificación y la frecuencia de uso para garantizar un tratamiento seguro y eficaz.</p><p>Es importante destacar que esta descripción es general y no específica para un producto real llamado \"KITADOL migraña\". Recomiendo verificar con fuentes médicas actualizadas o consultar a un profesional de la salud para obtener información precisa y actualizada sobre cualquier medicamento específico.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,NULL,'2024-02-01 13:03:34',NULL,NULL,0,NULL,'KITADOL-TB-CAJA-KIDOL',NULL,NULL,NULL,NULL,1,0,'10'),(184,1,NULL,3,NULL,NULL,NULL,1,'COMPB',NULL,NULL,'COMPLEJO. B CAJA','<p>El <strong>\"Complejo B\"</strong> es un grupo de vitaminas esenciales para el funcionamiento adecuado del cuerpo humano. Incluye vitaminas B1 (tiamina), B2 (riboflavina), B3 (niacina), B5 (ácido pantoténico), B6 (piridoxina), B7 (biotina), B9 (ácido fólico) y B12 (cobalamina). Estas vitaminas desempeñan un papel crucial en el metabolismo celular, la producción de energía, la salud del sistema nervioso y la formación de glóbulos rojos. El Complejo B se encuentra comúnmente en alimentos y suplementos nutricionales.</p>','<p>El <strong>\"Complejo B\"</strong> es un conjunto de vitaminas hidrosolubles esenciales para el correcto funcionamiento del organismo humano. Este complejo incluye varias vitaminas del grupo B, cada una con funciones específicas y beneficios para la salud.</p><ol><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B1 (Tiamina):</strong> Es esencial para el metabolismo de carbohidratos y contribuye al funcionamiento adecuado del sistema nervioso.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B2 (Riboflavina):</strong> Juega un papel clave en la producción de energía a través del metabolismo de grasas, proteínas y carbohidratos, y es esencial para la salud de la piel, ojos y sistema nervioso.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B3 (Niacina):</strong> Participa en procesos metabólicos importantes, incluyendo la síntesis de ácidos grasos y la generación de energía. También es crucial para la salud de la piel, sistema nervioso y digestivo.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B5 (Ácido Pantoténico):</strong> Es fundamental para la síntesis de coenzima A, que desempeña un papel vital en el metabolismo de carbohidratos, grasas y proteínas.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B6 (Piridoxina): </strong>Contribuye al metabolismo de aminoácidos y ayuda en la formación de neurotransmisores. También es esencial para la formación de glóbulos rojos.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B7 (Biotina):</strong> Participa en el metabolismo de grasas, carbohidratos y proteínas, y desempeña un papel importante en la salud de la piel, cabello y uñas.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B9 (Ácido Fólico):</strong> Es crucial para la síntesis de ADN y ARN, la formación de glóbulos rojos y el desarrollo fetal durante el embarazo.</li><li><strong style=\"color: rgb(102, 185, 102);\">Vitamina B12 (Cobalamina):</strong> Necesaria para la formación de glóbulos rojos, la función neurológica y la síntesis de ADN.</li></ol><p>Estas vitaminas se encuentran en una variedad de alimentos, como carne, pescado, lácteos, huevos, legumbres, frutas y verduras. Además, el Complejo B está disponible en forma de suplementos nutricionales para aquellos que pueden tener deficiencias o necesitan un aumento en su ingesta diaria. Consultar con un profesional de la salud antes de tomar suplementos es importante para asegurar un equilibrio adecuado y evitar posibles efectos secundarios.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,NULL,'2024-01-31 00:58:43',NULL,NULL,0,NULL,'COMPLEJO.-B-CAJA-COMPB',NULL,NULL,NULL,NULL,1,0,'10'),(185,1,NULL,3,NULL,NULL,NULL,1,'prueba',NULL,NULL,'prueba',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-25 01:19:35',NULL,NULL,0,NULL,'prueba-prueba',NULL,NULL,NULL,NULL,0,1,'10'),(186,1,NULL,3,NULL,NULL,NULL,1,'prueba 2',NULL,NULL,'prueba 2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-25 01:23:16',NULL,NULL,0,NULL,'prueba-2-prueba-2',NULL,NULL,NULL,NULL,0,1,'10'),(187,1,NULL,3,NULL,NULL,NULL,1,'prueba 3',NULL,NULL,'prueba 3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2024-01-25 01:26:39',NULL,NULL,0,NULL,'prueba-3-prueba-3',NULL,NULL,NULL,NULL,0,1,'10'),(188,1,NULL,3,NULL,90,NULL,1,'Pa-dol',NULL,NULL,'PANADOL FORTE TB','<p><strong>Panadol Forte</strong> es un medicamento analgésico y antipirético que contiene paracetamol como ingrediente activo. Diseñado para aliviar el dolor moderado a severo y reducir la fiebre, Panadol Forte es ampliamente utilizado para el alivio temporal de dolores de cabeza, dolores musculares, dolor de espalda, fiebre y otros malestares. Su fórmula de liberación rápida proporciona alivio efectivo en un corto período de tiempo.</p>','<p><strong>Panadol Forte</strong> es un medicamento ampliamente reconocido y utilizado, formulado para proporcionar alivio rápido y efectivo del dolor moderado a severo, así como para reducir la fiebre. Su ingrediente activo, el paracetamol, actúa como analgésico y antipirético, inhibiendo la producción de sustancias en el cuerpo que causan dolor y fiebre. Este medicamento es especialmente útil para el alivio temporal de dolores de cabeza, dolores musculares, dolor de espalda, dolores menstruales, fiebre y otros malestares.</p><p>La fórmula de <strong>Panadol Forte</strong> está diseñada para una liberación rápida, lo que significa que los efectos terapéuticos se experimentan en un corto período de tiempo después de la administración. Esto hace que sea una opción popular para aquellos que buscan alivio inmediato en situaciones de malestar agudo.</p><p>Es esencial seguir las indicaciones del médico o las instrucciones del envase para garantizar un uso seguro y eficaz. Además, es importante tener en cuenta las precauciones y advertencias asociadas con el uso de este medicamento, como respetar las dosis recomendadas y evitar el consumo simultáneo de otros productos que contengan paracetamol para prevenir posibles efectos secundarios no deseados.</p><p><strong>Panadol Forte</strong> se presenta en diversas presentaciones, como tabletas o jarabe, lo que facilita su administración según las preferencias del paciente. Sin embargo, siempre se debe buscar asesoramiento médico antes de iniciar cualquier tratamiento a largo plazo o en caso de condiciones médicas preexistentes para garantizar la seguridad y eficacia del uso de <strong>Panadol Forte.</strong></p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.5,NULL,'2024-01-31 00:45:33',NULL,NULL,0,NULL,'PANADOL-FORTE-TB-Pa-dol',NULL,NULL,NULL,NULL,1,1,'10'),(189,1,7,3,1,NULL,NULL,1,'Sal_andrews',NULL,NULL,'SAL DE ANDREWS','<p><span class=\"ql-font-serif\">La </span><strong class=\"ql-font-serif\">\"Sal de Andrews\" </strong><span class=\"ql-font-serif\">es un producto que comúnmente se utiliza como antiácido efervescente. Contiene carbonato de sodio y ácido cítrico, y se disuelve en agua para formar una solución que ayuda a aliviar la acidez estomacal y la indigestión.</span></p>','<p><span class=\"ql-font-serif\">La \"Sal de Andrews\" es un antiácido efervescente que suele emplearse para aliviar trastornos estomacales leves, como la acidez y la indigestión. Su composición incluye ingredientes activos como el carbonato de sodio y el ácido cítrico, que al combinarse con agua forman una solución efervescente. Esta solución ayuda a neutralizar el exceso de ácido gástrico en el estómago, aliviando así la sensación de ardor y malestar asociada con la acidez estomacal.</span></p><p><br></p><p><span class=\"ql-font-serif\">La </span><strong class=\"ql-font-serif\">\"Sal de Andrews\" </strong><span class=\"ql-font-serif\">es conocida por su acción rápida y su capacidad para aliviar temporalmente los síntomas digestivos incómodos. Es importante seguir las indicaciones del fabricante y las recomendaciones de un profesional de la salud al utilizar este tipo de productos, ya que el uso inadecuado o excesivo de antiácidos puede tener consecuencias negativas. Si los síntomas persisten o empeoran, se recomienda buscar orientación médica.</span></p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-03-04 15:02:35',NULL,NULL,0,NULL,'SAL-DE-ANDREWS-Sal_andrews',NULL,NULL,NULL,NULL,1,1,'10'),(190,1,1,3,4,91,NULL,1,'Cir_uelax',NULL,NULL,'CIRUALEX FORTE','<p>CIRUELAX FORTE\" podría ser un laxante o suplemento que contiene ciruelas secas u otros ingredientes con propiedades laxantes. Los laxantes son sustancias diseñadas para aliviar el estreñimiento, facilitando la evacuación intestinal.</p>','<p><strong>\"CIRUELAX FORTE\" </strong>podría ser un producto destinado a mejorar la regularidad intestinal y aliviar el estreñimiento. Si contiene ciruelas secas, es posible que esté formulado con compuestos naturales, como sorbitol o fibra, que pueden ayudar a suavizar las heces y promover el movimiento intestinal. Los laxantes suelen funcionar de diversas maneras, ya sea aumentando el contenido de agua en las heces, estimulando el movimiento intestinal o suavizando las heces para facilitar su paso.</p><p>Es importante destacar que la información precisa sobre este producto, incluyendo sus ingredientes, indicaciones y posibles efectos secundarios, debe obtenerse directamente de fuentes médicas confiables, como la etiqueta del producto, la información proporcionada por el fabricante, o consultando con un profesional de la salud.</p><p><strong>Contiene:</strong></p><p><strong style=\"color: rgb(61, 70, 77);\">Extracto seco de hojas Cassia angustifolia Vahl 20% p/p…125,00 mg (equivalente a 25 mg de derivados hidroxiantracénicos, expresados como senósidos B)Excipientes: Maltodextrina, Croscarmelosa Sódica, Lauril, Sulfato de Sodio, Dióxido de Silicio, Celulosa Microcristalina, Almidón Pregelatinizado, Almidón Glicolato de Sodio, Estearato de Magnesio,Dextrina, Dextrosa Monohidrato, Carboximetilcelulosa Sódica, Lecitina de Soya, Citrato de Sodio Dihidrato</strong></p><p><br></p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,NULL,'2024-02-11 22:15:24',NULL,NULL,0,NULL,'CIRUALEX-FORTE-Cir_uelax',NULL,NULL,NULL,NULL,1,1,'10'),(191,1,NULL,3,NULL,92,NULL,1,'Tap_sin_dia',NULL,NULL,'TAPSIN PLUS DIA SOBRE','<p>Tapsin Plus Día es un medicamento de venta libre diseñado para aliviar los síntomas de resfriados y gripes durante el día. Su fórmula combina ingredientes activos como paracetamol, clorfenamina y fenilefrina para proporcionar alivio eficaz contra la congestión nasal, la fiebre, el dolor de cabeza y otros malestares asociados con el resfriado común.</p>','<p>Tapsin Plus Día es un medicamento de uso oral disponible sin receta médica, específicamente formulado para proporcionar alivio efectivo de los síntomas asociados con resfriados y gripes durante el día. Su composición incluye tres ingredientes activos clave:</p><ol><li><span style=\"color: var(--tw-prose-bold);\">Paracetamol:</span> Un analgésico y antipirético que ayuda a reducir la fiebre y aliviar el dolor asociado con el resfriado o la gripe.</li><li><span style=\"color: var(--tw-prose-bold);\">Clorfenamina:</span> Un antihistamínico que combate la congestión nasal y alivia los síntomas de la alergia, como la picazón en los ojos y la nariz.</li><li><span style=\"color: var(--tw-prose-bold);\">Fenilefrina:</span> Un descongestionante que actúa reduciendo la hinchazón de los conductos nasales, facilitando la respiración y aliviando la congestión.</li></ol><p>La combinación de estos ingredientes proporciona un enfoque integral para combatir los malestares típicos de los resfriados y las gripes, como dolores de cabeza, fiebre, congestión nasal y síntomas alérgicos. Es importante seguir las indicaciones del envase y consultar a un profesional de la salud antes de su uso, especialmente en casos de condiciones médicas preexistentes o si se está tomando otros medicamentos. Tapsin Plus Día está diseñado para uso diurno, y se recomienda precaución al conducir u operar maquinaria debido a posibles efectos secundarios como somnolencia.</p>',NULL,NULL,NULL,NULL,-9,NULL,NULL,NULL,1.5,NULL,'2024-02-11 22:13:54',NULL,NULL,0,NULL,'TAPSIN-PLUS-DIA-SOBRE-Tap_sin_dia',NULL,NULL,NULL,NULL,1,1,'10'),(192,1,7,3,1,93,NULL,1,'Tap_sin',NULL,NULL,'TAPSIN NOCHE','<p><span class=\"ql-font-serif\">Tapsin Noche es un medicamento diseñado para aliviar los síntomas del resfriado y la gripe durante la noche, proporcionando un alivio rápido y eficaz para una mejor calidad de sueño</span></p>','<p><span class=\"ql-font-serif\">Tapsin Noche es un medicamento especialmente formulado para abordar los síntomas molestos del resfriado y la gripe durante las horas nocturnas. Con una combinación cuidadosamente seleccionada de ingredientes activos, Tapsin Noche proporciona alivio rápido y eficaz, permitiendo a quienes lo utilizan disfrutar de un sueño reparador. Este medicamento está diseñado para combatir la congestión nasal, los dolores corporales, la fiebre y otros malestares asociados con los resfriados y la gripe. Su fórmula única se adapta específicamente a las necesidades nocturnas, ayudando a reducir la incomodidad y permitiendo que quienes lo consumen descansen de manera más cómoda. Tapsin Noche es la elección confiable para aquellos que buscan alivio nocturno efectivo frente a los síntomas del resfriado y la gripe, mejorando la calidad del sueño y promoviendo una recuperación más rápida.</span></p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.5,NULL,'2024-02-11 22:12:05',NULL,NULL,0,NULL,'TAPSIN-NOCHE-Tap_sin',NULL,NULL,NULL,NULL,1,1,'10'),(193,1,NULL,3,3,94,NULL,1,'D-A-E-F',NULL,NULL,'DESODORANTE AXE EPIC FRESH 150ML C/U','<p><span style=\"color: rgb(0, 0, 0);\">Audaz, fresco y deportivo, el nuevo desodorante AXE Epic Fresh llegó para quedarse. Con una fragancia de notas amaderadas y un giro de frescura que le aportan el ananá y el pomelo, te va a llevar sin dudas a una experiencia épica. Para ti que estabas buscando experimentar esa frescura que se siente ni bien sales de la ducha pero todo el día, es hora de que pruebes el nuevo AXE Epic Fresh. Épico.</span></p><p><span style=\"color: rgb(0, 0, 0);\">Llevá la frescura instantánea a otro nivel con el nuevo desodorante AXE Epic Fresh. Nuestra nueva y revolucionaria tecnología de doble acción combate las bacterias que causan el mal olor para que puedas oler increíblemente épico durante 48 horas. Sentí vos también el nuevo efecto AXE.</span></p>','<p><strong>\"Desodorante Axe Epic Fresh 150ml\"</strong> es mucho más que un simple desodorante; es un compañero confiable para hombres modernos que valoran la frescura y la seguridad en su rutina diaria. Esta fórmula innovadora combina una fragancia estimulante con ingredientes activos que combaten el olor corporal, garantizando una protección duradera incluso en las situaciones más exigentes.</p><p>Con un aroma fresco y vigorizante, este desodorante no solo neutraliza los olores desagradables, sino que también deja una estela de frescura que acompaña al usuario a lo largo del día. Su conveniente presentación en un envase de 150ml lo hace ideal para llevarlo consigo a cualquier parte, ya sea en la bolsa de gimnasio, en la mochila de viaje o en el bolso del trabajo.</p><p>La fórmula de \"Desodorante Axe Epic Fresh\" ha sido cuidadosamente desarrollada para brindar una protección efectiva sin comprometer la comodidad ni la frescura. Es suave con la piel, evitando la irritación y la incomodidad que pueden surgir con algunos desodorantes más agresivos, mientras que su eficacia en el control del olor garantiza una sensación de confianza en todo momento.</p><p>Ya sea en una intensa sesión de entrenamiento en el gimnasio, durante una larga jornada laboral o en una salida con amigos, \"Desodorante Axe Epic Fresh\" se mantiene firme, proporcionando una frescura duradera que acompaña al hombre moderno en todas sus aventuras. Con este desodorante, cada día se convierte en una oportunidad para sentirse seguro, fresco y listo para enfrentar cualquier desafío.</p>',NULL,NULL,NULL,NULL,-233,NULL,NULL,NULL,13.5,NULL,'2026-06-12 01:46:52',NULL,NULL,0,NULL,'DESODORANTE-AXE-EPIC-FRESH-150ML-C-U-D-A-E-F',NULL,NULL,NULL,NULL,1,1,'10'),(194,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'PRODUCTO EDITADO CRM',NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,NULL,NULL,20,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,1,'10'),(195,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'SKU1','123',NULL,'PROD TEST','corta',NULL,NULL,NULL,NULL,NULL,4,2,NULL,9.5,15,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'10'),(196,NULL,NULL,NULL,1,70,NULL,1,NULL,NULL,NULL,'P FK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'10'),(197,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'PT1',NULL,NULL,'PARCIAL EDIT',NULL,NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,7,10,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,1,'10'),(200,1,5,3,5,96,NULL,1,'PRO-MODL-PRMOL',NULL,NULL,'PARACETAMOL 500 G TABLETA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-06-15 16:48:13',NULL,NULL,0,NULL,'PARACETAMOL-500-G-TABLETA-PRO-MODL-PRMOL',NULL,NULL,NULL,NULL,1,0,'10');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_color`
--

DROP TABLE IF EXISTS `producto_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_color` (
  `id_producto_color` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `nombre_producto_color` varchar(255) DEFAULT NULL,
  `hexadecimal_producto_color` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_producto_color`) USING BTREE,
  KEY `fk_id_producto_1` (`id_producto`) USING BTREE,
  CONSTRAINT `fk_id_producto_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_color`
--

LOCK TABLES `producto_color` WRITE;
/*!40000 ALTER TABLE `producto_color` DISABLE KEYS */;
/*!40000 ALTER TABLE `producto_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_historial`
--

DROP TABLE IF EXISTS `producto_historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_historial` (
  `id_producto_historial` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_tipo_movimiento` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `id_proveedor` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `cantidadmovimiento_producto_historial` float DEFAULT NULL,
  `fecha_producto_historial` datetime DEFAULT NULL,
  `comentario_producto_historial` text,
  `preciocompra_producto_historial` double DEFAULT NULL,
  `id_tipo_documento` int DEFAULT NULL,
  `numerotipodocumento_producto_historial` varchar(255) DEFAULT NULL,
  `idtratamiento_producto_historial` int DEFAULT NULL,
  `precioventa_producto_historial` double DEFAULT NULL,
  PRIMARY KEY (`id_producto_historial`) USING BTREE,
  KEY `fk_relationship_46` (`id_tipo_movimiento`) USING BTREE,
  KEY `fk_relationship_52` (`id_producto`) USING BTREE,
  KEY `fk_relationship_54` (`id_usuario`) USING BTREE,
  KEY `fk_relationship_250` (`id_proveedor`) USING BTREE,
  KEY `fk_relationship_251` (`id_bodega`) USING BTREE,
  KEY `fk_id_tipo_documento1` (`id_tipo_documento`) USING BTREE,
  CONSTRAINT `fk_id_tipo_documento1` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`),
  CONSTRAINT `fk_relationship_250` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  CONSTRAINT `fk_relationship_251` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_relationship_46` FOREIGN KEY (`id_tipo_movimiento`) REFERENCES `tipo_movimiento` (`id_tipo_movimiento`),
  CONSTRAINT `fk_relationship_52` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_relationship_54` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=489 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_historial`
--

LOCK TABLES `producto_historial` WRITE;
/*!40000 ALTER TABLE `producto_historial` DISABLE KEYS */;
INSERT INTO `producto_historial` VALUES (467,23242,2,90,NULL,18,1,'2026-06-13 12:57:42','BOLETA DE VENTA ELECTRONICA',NULL,1,NULL,NULL,NULL),(468,23242,2,90,NULL,18,1,'2026-06-13 12:58:32','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(469,23242,2,92,NULL,18,1,'2026-06-13 12:58:32','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(470,23242,2,106,NULL,18,1,'2026-06-13 13:02:24','BOLETA DE VENTA ELECTRONICA',NULL,1,NULL,NULL,NULL),(471,23242,2,92,NULL,18,1,'2026-06-13 18:38:19','BOLETA DE VENTA ELECTRONICA',NULL,1,NULL,NULL,NULL),(472,23242,2,90,NULL,18,1,'2026-06-13 18:38:19','BOLETA DE VENTA ELECTRONICA',NULL,1,NULL,NULL,NULL),(473,23242,2,106,NULL,18,1,'2026-06-13 18:38:19','BOLETA DE VENTA ELECTRONICA',NULL,1,NULL,NULL,NULL),(474,23242,2,90,NULL,18,1,'2026-06-13 18:47:31','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(475,23242,2,90,NULL,18,1,'2026-06-13 19:13:41','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(476,23242,2,92,NULL,18,1,'2026-06-14 00:04:58','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(477,23242,2,90,NULL,18,1,'2026-06-14 00:04:58','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(478,23260,2,90,NULL,18,1,'2026-06-14 00:08:08','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(479,23260,2,92,NULL,18,1,'2026-06-14 00:08:08','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(480,23260,2,90,NULL,18,1,'2026-06-14 01:01:49','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(481,23260,2,92,NULL,18,1,'2026-06-14 01:01:49','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(482,23260,1,196,NULL,18,12,'2026-06-14 10:09:54',NULL,100,NULL,NULL,NULL,12),(483,23260,1,195,NULL,18,2,'2026-06-14 11:08:44',NULL,0,NULL,NULL,NULL,120),(484,23260,1,195,NULL,18,4,'2026-06-14 11:09:08',NULL,0,NULL,NULL,NULL,101),(485,23260,1,195,NULL,18,5,'2026-06-14 11:15:49',NULL,0,NULL,NULL,NULL,101),(486,23260,2,90,NULL,18,1,'2026-06-15 01:19:36','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(487,23242,2,90,NULL,18,1,'2026-06-15 11:26:54','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL),(488,23260,2,90,NULL,18,1,'2026-06-15 12:51:13','NOTA VENTA DE VENTA ELECTRONICA',NULL,4,NULL,NULL,NULL);
/*!40000 ALTER TABLE `producto_historial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_imagen`
--

DROP TABLE IF EXISTS `producto_imagen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_imagen` (
  `id_producto_imagen` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `nombre_producto_imagen` varchar(1000) DEFAULT NULL,
  `extension_producto_imagen` varchar(255) DEFAULT NULL,
  `peso_producto_imagen` varchar(255) DEFAULT NULL,
  `path_producto_imagen` varchar(5000) DEFAULT NULL,
  `fechacreacion_producto_imagen` datetime DEFAULT NULL,
  `estado_producto_imagen` int DEFAULT NULL,
  `orden_producto_imagen` int DEFAULT NULL,
  `portada_producto_imagen` int DEFAULT NULL,
  `public_id_producto_imagen` longtext,
  `url_producto_imagen` longtext,
  PRIMARY KEY (`id_producto_imagen`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_imagen`
--

LOCK TABLES `producto_imagen` WRITE;
/*!40000 ALTER TABLE `producto_imagen` DISABLE KEYS */;
INSERT INTO `producto_imagen` VALUES (46,90,NULL,NULL,NULL,NULL,'2024-01-07 12:32:01',1,0,1,'localhost/archivo/imagen_producto/ff1uhd73zvzzzspw2zmt','https://res.cloudinary.com/do7dzakiw/image/upload/v1699592754/localhost/archivo/imagen_producto/ff1uhd73zvzzzspw2zmt.jpg'),(47,91,NULL,NULL,NULL,NULL,'2024-01-07 12:32:02',1,0,1,'localhost/archivo/imagen_producto/mprgn72aqn5jk9zb8kai','https://res.cloudinary.com/do7dzakiw/image/upload/v1699594666/localhost/archivo/imagen_producto/mprgn72aqn5jk9zb8kai.png'),(48,92,NULL,NULL,NULL,NULL,'2024-01-07 12:32:02',1,0,1,'localhost/archivo/imagen_producto/el8gjdvjdt4vtjehkt4h','https://res.cloudinary.com/do7dzakiw/image/upload/v1699593278/localhost/archivo/imagen_producto/el8gjdvjdt4vtjehkt4h.jpg'),(49,93,NULL,NULL,NULL,NULL,'2024-01-07 12:32:02',1,0,1,'localhost/archivo/imagen_producto/cnnsavdu2p6tsvdxztz9','https://res.cloudinary.com/do7dzakiw/image/upload/v1699592874/localhost/archivo/imagen_producto/cnnsavdu2p6tsvdxztz9.jpg'),(50,94,NULL,NULL,NULL,NULL,'2024-01-07 12:32:03',1,0,1,'localhost/archivo/imagen_producto/foi5z5ljenbnf50w4xwg','https://res.cloudinary.com/do7dzakiw/image/upload/v1699594549/localhost/archivo/imagen_producto/foi5z5ljenbnf50w4xwg.jpg'),(51,95,NULL,NULL,NULL,NULL,'2024-01-07 12:32:03',1,0,1,'localhost/archivo/imagen_producto/ou6rmexenflm1eqyojga','https://res.cloudinary.com/do7dzakiw/image/upload/v1699596008/localhost/archivo/imagen_producto/ou6rmexenflm1eqyojga.png'),(52,99,NULL,NULL,NULL,NULL,'2024-01-07 12:32:04',1,0,1,'localhost/archivo/imagen_producto/ejgsxwu3f3pgms3ui2u5','https://res.cloudinary.com/do7dzakiw/image/upload/v1699593126/localhost/archivo/imagen_producto/ejgsxwu3f3pgms3ui2u5.png'),(53,108,NULL,NULL,NULL,NULL,'2024-01-07 12:32:04',1,0,1,'www.crm.sistemasdurand.com/archivo/imagen_producto/mnim1hufj36emstoulop','https://res.cloudinary.com/do7dzakiw/image/upload/v1681307512/www.crm.sistemasdurand.com/archivo/imagen_producto/mnim1hufj36emstoulop.jpg'),(54,109,NULL,NULL,NULL,NULL,'2024-01-07 12:32:04',1,0,1,'www.crm.sistemasdurand.com/archivo/imagen_producto/odhylxyubhxvspoxlbh9','https://res.cloudinary.com/do7dzakiw/image/upload/v1682092518/www.crm.sistemasdurand.com/archivo/imagen_producto/odhylxyubhxvspoxlbh9.jpg'),(55,110,NULL,NULL,NULL,NULL,'2024-01-07 12:32:04',1,0,1,'localhost/archivo/imagen_producto/eckik05x9w27e7p4gy3n','https://res.cloudinary.com/do7dzakiw/image/upload/v1699898098/localhost/archivo/imagen_producto/eckik05x9w27e7p4gy3n.jpg'),(56,111,NULL,NULL,NULL,NULL,'2024-01-07 12:32:05',1,0,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/iwqwqende7gus3qmzf69','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093014/www.crm.sistemasdurand.com/archivo/imagen_producto/iwqwqende7gus3qmzf69.jpg'),(57,111,NULL,NULL,NULL,NULL,'2024-01-07 12:32:05',1,1,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/hczrquvku8del5h9g4w6','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093014/www.crm.sistemasdurand.com/archivo/imagen_producto/hczrquvku8del5h9g4w6.jpg'),(58,111,NULL,NULL,NULL,NULL,'2024-01-07 12:32:05',1,2,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/mhmqwm1cbw9z9q5xtce9','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093015/www.crm.sistemasdurand.com/archivo/imagen_producto/mhmqwm1cbw9z9q5xtce9.jpg'),(59,111,NULL,NULL,NULL,NULL,'2024-01-07 12:32:06',1,3,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/vycjkh9qk8osb0ziktrz','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093016/www.crm.sistemasdurand.com/archivo/imagen_producto/vycjkh9qk8osb0ziktrz.jpg'),(60,112,NULL,NULL,NULL,NULL,'2024-01-07 12:32:06',1,0,1,'www.crm.sistemasdurand.com/archivo/imagen_producto/uhcaymtmtlhz47mn5pjz','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093433/www.crm.sistemasdurand.com/archivo/imagen_producto/uhcaymtmtlhz47mn5pjz.jpg'),(61,113,NULL,NULL,NULL,NULL,'2024-01-07 12:32:06',1,0,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/v30mz5rx2yrrub0hlcmc','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093628/www.crm.sistemasdurand.com/archivo/imagen_producto/v30mz5rx2yrrub0hlcmc.jpg'),(62,113,NULL,NULL,NULL,NULL,'2024-01-07 12:32:07',1,1,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/fwieugjitdlmyrfvk9jz','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093726/www.crm.sistemasdurand.com/archivo/imagen_producto/fwieugjitdlmyrfvk9jz.jpg'),(63,113,NULL,NULL,NULL,NULL,'2024-01-07 12:32:07',1,2,0,'www.crm.sistemasdurand.com/archivo/imagen_producto/fvp8egnjl82ykk1qrzck','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093727/www.crm.sistemasdurand.com/archivo/imagen_producto/fvp8egnjl82ykk1qrzck.jpg'),(64,114,NULL,NULL,NULL,NULL,'2024-01-07 12:32:07',1,0,1,'www.crm.sistemasdurand.com/archivo/imagen_producto/vf0eyttlwjw15j8vcjzr','https://res.cloudinary.com/do7dzakiw/image/upload/v1682093976/www.crm.sistemasdurand.com/archivo/imagen_producto/vf0eyttlwjw15j8vcjzr.jpg'),(65,115,NULL,NULL,NULL,NULL,'2024-01-07 12:32:07',1,0,1,'www.crm.sistemasdurand.com/archivo/imagen_producto/jiroxwn6v5lujbqwd2sc','https://res.cloudinary.com/do7dzakiw/image/upload/v1682094087/www.crm.sistemasdurand.com/archivo/imagen_producto/jiroxwn6v5lujbqwd2sc.jpg'),(66,143,NULL,NULL,NULL,NULL,'2024-01-07 12:32:08',1,0,1,'localhost/archivo/imagen_producto/diaeopyhhaz03uvtardu','https://res.cloudinary.com/do7dzakiw/image/upload/v1699898237/localhost/archivo/imagen_producto/diaeopyhhaz03uvtardu.png'),(67,144,NULL,NULL,NULL,NULL,'2024-01-07 12:32:08',1,0,0,'localhost/archivo/imagen_producto/a3ifppubiwimqs6qa6xx','https://res.cloudinary.com/do7dzakiw/image/upload/v1699898318/localhost/archivo/imagen_producto/a3ifppubiwimqs6qa6xx.jpg'),(68,144,NULL,NULL,NULL,NULL,'2024-01-07 12:32:08',1,1,0,'localhost/archivo/imagen_producto/o7vejv6uwijvit16mg20','https://res.cloudinary.com/do7dzakiw/image/upload/v1699898319/localhost/archivo/imagen_producto/o7vejv6uwijvit16mg20.jpg'),(69,144,NULL,NULL,NULL,NULL,'2024-01-07 12:32:08',1,2,0,'localhost/archivo/imagen_producto/bkzyboqldfqre5x1nqlj','https://res.cloudinary.com/do7dzakiw/image/upload/v1699898321/localhost/archivo/imagen_producto/bkzyboqldfqre5x1nqlj.jpg'),(70,145,NULL,NULL,NULL,NULL,'2024-01-07 12:32:09',1,0,1,'localhost/archivo/imagen_producto/gzw7pcwmk58ptlevth9w','https://res.cloudinary.com/do7dzakiw/image/upload/v1699898396/localhost/archivo/imagen_producto/gzw7pcwmk58ptlevth9w.jpg'),(71,146,NULL,NULL,NULL,NULL,'2024-01-07 12:32:09',1,0,0,'localhost/archivo/imagen_producto/mrovzkljo7gt5rkuawyi','https://res.cloudinary.com/do7dzakiw/image/upload/v1699905617/localhost/archivo/imagen_producto/mrovzkljo7gt5rkuawyi.png'),(72,146,NULL,NULL,NULL,NULL,'2024-01-07 12:32:09',1,1,0,'localhost/archivo/imagen_producto/mclmyhnz0i5e2f6nrw75','https://res.cloudinary.com/do7dzakiw/image/upload/v1699905620/localhost/archivo/imagen_producto/mclmyhnz0i5e2f6nrw75.png'),(73,152,NULL,NULL,NULL,NULL,'2024-01-07 12:32:09',1,0,1,'localhost/archivo/imagen_producto/odnph6wm8xzss9xmzncc','https://res.cloudinary.com/do7dzakiw/image/upload/v1699905225/localhost/archivo/imagen_producto/odnph6wm8xzss9xmzncc.jpg'),(74,156,NULL,NULL,NULL,NULL,'2024-01-07 12:32:10',1,0,1,'localhost/archivo/imagen_producto/sl6rcl5ptlv2sxnuntsz','https://res.cloudinary.com/do7dzakiw/image/upload/v1699899514/localhost/archivo/imagen_producto/sl6rcl5ptlv2sxnuntsz.png'),(75,157,NULL,NULL,NULL,NULL,'2024-01-07 12:32:10',1,0,1,'localhost/archivo/imagen_producto/s34c0x3e3v6wckpdhzev','https://res.cloudinary.com/do7dzakiw/image/upload/v1699899441/localhost/archivo/imagen_producto/s34c0x3e3v6wckpdhzev.png'),(76,158,NULL,NULL,NULL,NULL,'2024-01-07 12:32:11',1,0,1,'localhost/archivo/imagen_producto/ypyftgivjc8hcneiexcl','https://res.cloudinary.com/do7dzakiw/image/upload/v1699899321/localhost/archivo/imagen_producto/ypyftgivjc8hcneiexcl.jpg'),(77,169,NULL,NULL,NULL,NULL,'2024-01-07 12:32:11',1,0,1,'localhost/archivo/imagen_producto/x2ye2tnjs9tlz7qoohen','https://res.cloudinary.com/do7dzakiw/image/upload/v1698802821/localhost/archivo/imagen_producto/x2ye2tnjs9tlz7qoohen.png'),(78,170,NULL,NULL,NULL,NULL,'2024-01-07 12:32:11',1,0,1,'localhost/archivo/imagen_producto/c7ybd2ldoicuzcu70t20','https://res.cloudinary.com/do7dzakiw/image/upload/v1698802695/localhost/archivo/imagen_producto/c7ybd2ldoicuzcu70t20.png'),(79,171,NULL,NULL,NULL,NULL,'2024-01-07 12:32:12',1,0,1,'localhost/archivo/imagen_producto/rtknwkjarah91bhlknya','https://res.cloudinary.com/do7dzakiw/image/upload/v1698801922/localhost/archivo/imagen_producto/rtknwkjarah91bhlknya.png'),(80,172,NULL,NULL,NULL,NULL,'2024-01-07 12:32:12',1,0,1,'localhost/archivo/imagen_producto/uuwvb7nxhdxwuazwomur','https://res.cloudinary.com/do7dzakiw/image/upload/v1698801465/localhost/archivo/imagen_producto/uuwvb7nxhdxwuazwomur.jpg'),(81,173,NULL,NULL,NULL,NULL,'2024-01-07 12:32:12',1,0,1,'localhost/archivo/imagen_producto/zz5tfcsvbmormu0o7lqy','https://res.cloudinary.com/do7dzakiw/image/upload/v1698800736/localhost/archivo/imagen_producto/zz5tfcsvbmormu0o7lqy.jpg'),(82,174,NULL,NULL,NULL,NULL,'2024-01-07 12:32:12',1,0,1,'localhost/archivo/imagen_producto/apvudel6sq3enhrmneff','https://res.cloudinary.com/do7dzakiw/image/upload/v1698799042/localhost/archivo/imagen_producto/apvudel6sq3enhrmneff.png'),(85,177,NULL,NULL,NULL,NULL,'2024-01-07 12:32:13',1,0,1,'localhost/archivo/imagen_producto/l2rtwfkf3eo9txcmo1bp','https://res.cloudinary.com/do7dzakiw/image/upload/v1698797992/localhost/archivo/imagen_producto/l2rtwfkf3eo9txcmo1bp.jpg'),(86,178,NULL,NULL,NULL,NULL,'2024-01-07 12:32:14',1,0,0,'localhost/archivo/imagen_producto/okg6j7m3ew6id8g24afl','https://res.cloudinary.com/do7dzakiw/image/upload/v1698797465/localhost/archivo/imagen_producto/okg6j7m3ew6id8g24afl.png'),(87,178,NULL,NULL,NULL,NULL,'2024-01-07 12:32:14',1,1,0,'localhost/archivo/imagen_producto/jhylm9kt27uoanztkx0q','https://res.cloudinary.com/do7dzakiw/image/upload/v1698797468/localhost/archivo/imagen_producto/jhylm9kt27uoanztkx0q.png'),(88,192,'Tap sin noche',NULL,NULL,NULL,'2024-01-29 22:58:25',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/Tap sin noche_1706587104','https://res.cloudinary.com/do7dzakiw/image/upload/v1706587105/api.sistemaboticarosa.com/archivo/imagen_producto/Tap%20sin%20noche_1706587104.png'),(89,191,'',NULL,NULL,NULL,'2024-01-30 12:57:07',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706637426','https://res.cloudinary.com/do7dzakiw/image/upload/v1706637427/api.sistemaboticarosa.com/archivo/imagen_producto/_1706637426.png'),(90,190,'',NULL,NULL,NULL,'2024-01-30 17:09:25',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706652564','https://res.cloudinary.com/do7dzakiw/image/upload/v1706652565/api.sistemaboticarosa.com/archivo/imagen_producto/_1706652564.jpg'),(91,189,'',NULL,NULL,NULL,'2024-01-30 17:21:53',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706653312','https://res.cloudinary.com/do7dzakiw/image/upload/v1706653313/api.sistemaboticarosa.com/archivo/imagen_producto/_1706653312.jpg'),(92,188,'',NULL,NULL,NULL,'2024-01-31 00:40:44',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706679643','https://res.cloudinary.com/do7dzakiw/image/upload/v1706679644/api.sistemaboticarosa.com/archivo/imagen_producto/_1706679643.jpg'),(93,184,'',NULL,NULL,NULL,'2024-01-31 00:51:14',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706680273','https://res.cloudinary.com/do7dzakiw/image/upload/v1706680274/api.sistemaboticarosa.com/archivo/imagen_producto/_1706680273.jpg'),(94,183,'',NULL,NULL,NULL,'2024-02-01 13:03:34',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706810614','https://res.cloudinary.com/do7dzakiw/image/upload/v1706810615/api.sistemaboticarosa.com/archivo/imagen_producto/_1706810614.jpg'),(95,182,'',NULL,NULL,NULL,'2024-02-01 13:05:26',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706810726','https://res.cloudinary.com/do7dzakiw/image/upload/v1706810726/api.sistemaboticarosa.com/archivo/imagen_producto/_1706810726.jpg'),(96,181,'',NULL,NULL,NULL,'2024-02-01 13:08:18',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1706810897','https://res.cloudinary.com/do7dzakiw/image/upload/v1706810898/api.sistemaboticarosa.com/archivo/imagen_producto/_1706810897.jpg'),(97,180,'',NULL,NULL,NULL,'2024-02-05 08:53:05',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1707141184','https://res.cloudinary.com/do7dzakiw/image/upload/v1707141185/api.sistemaboticarosa.com/archivo/imagen_producto/_1707141184.jpg'),(98,179,'',NULL,NULL,NULL,'2024-02-05 09:02:50',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1707141769','https://res.cloudinary.com/do7dzakiw/image/upload/v1707141769/api.sistemaboticarosa.com/archivo/imagen_producto/_1707141769.jpg'),(99,176,'',NULL,NULL,NULL,'2024-02-05 09:23:02',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1707142981','https://res.cloudinary.com/do7dzakiw/image/upload/v1707142982/api.sistemaboticarosa.com/archivo/imagen_producto/_1707142981.jpg'),(100,175,'',NULL,NULL,NULL,'2024-02-05 09:55:45',1,1,1,'api.sistemaboticarosa.com/archivo/imagen_producto/_1707144945','https://res.cloudinary.com/do7dzakiw/image/upload/v1707144945/api.sistemaboticarosa.com/archivo/imagen_producto/_1707144945.jpg'),(101,168,'',NULL,NULL,NULL,'2024-03-04 15:09:17',1,1,1,'api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_producto/_1709582956','https://res.cloudinary.com/do7dzakiw/image/upload/v1709582957/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_producto/_1709582956.jpg'),(102,193,'',NULL,NULL,NULL,'2024-03-04 15:53:58',1,1,1,'api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_producto/_1709585637','https://res.cloudinary.com/do7dzakiw/image/upload/v1709585638/api.sistemaboticarosa.com/archivo/sistemaboticarosa/imagen_producto/_1709585637.png');
/*!40000 ALTER TABLE `producto_imagen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_relacionado`
--

DROP TABLE IF EXISTS `producto_relacionado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_relacionado` (
  `id_producto_relacionado` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `idproductopadre_producto_relacionado` int DEFAULT NULL,
  `order_producto_relacionado` int DEFAULT NULL,
  `vigente_producto_relacionado` int DEFAULT NULL,
  PRIMARY KEY (`id_producto_relacionado`) USING BTREE,
  KEY `fk_producto_relacionado_producto` (`id_producto`) USING BTREE,
  CONSTRAINT `fk_producto_relacionado_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_relacionado`
--

LOCK TABLES `producto_relacionado` WRITE;
/*!40000 ALTER TABLE `producto_relacionado` DISABLE KEYS */;
/*!40000 ALTER TABLE `producto_relacionado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promocion`
--

DROP TABLE IF EXISTS `promocion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promocion` (
  `id_promocion` int NOT NULL AUTO_INCREMENT,
  `titulo_promocion` varchar(255) DEFAULT NULL,
  `fecha_promocion` date DEFAULT NULL,
  `descripcion_promocion` text,
  `url_promocion` longtext,
  `id_url_promocion` longtext,
  `fecha_creacion_promocion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_promocion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocion`
--

LOCK TABLES `promocion` WRITE;
/*!40000 ALTER TABLE `promocion` DISABLE KEYS */;
INSERT INTO `promocion` VALUES (25,'MAGENESOL + REJUVENESCEDOR','2024-03-31','<p><font face=\"Comic Sans MS\"><b><b>Ayudamos a mejorar tu salud , tu bienestar y tu bolsillo a la mejor promoción ofrecida.<br></b><span style=\"font-size: 1rem;\"><b>Ayuda equilibrar el sistema nervioso</b><br></span><span style=\"font-size: 1rem;\"><b>Mejora la hidratación</b><br></span><span style=\"font-size: 1rem;\"><b>Elimina manchas</b></span></b></font></p><p><br></p>','https://res.cloudinary.com/do7dzakiw/image/upload/v1706048978/api.sistemaboticarosa.com/archivo/imagen_promocion/phpiqKVDN_1706048977.png','api.sistemaboticarosa.com/archivo/imagen_promocion/phpiqKVDN_1706048977','2024-01-23 17:29:37'),(26,'CREIMAX PLUS SUSPENSIÓN ORAL','2024-03-31','<p><span style=\"white-space-collapse: preserve;\"><font face=\"Comic Sans MS\"><b style=\"font-weight: bold;\">Llegaron las promociones de remate, y ahorro pensando en el bienestar de los niños.\nCalcio\nVitamina D\nMagnesio\nZinc</b><b>\n<b>Sabor a fresa</b>\n<b>Suplemento dietético</b></b></font></span><br></p>','https://res.cloudinary.com/do7dzakiw/image/upload/v1706105709/api.sistemaboticarosa.com/archivo/imagen_promocion/phpeOQAEW_1706105708.png','api.sistemaboticarosa.com/archivo/imagen_promocion/phpeOQAEW_1706105708','2024-01-24 09:15:08'),(27,'KALI-FLEX HP','2024-02-26','<p><b><font face=\"Comic Sans MS\"><b>Llegaron las promociones del día con KALI-FLEX COLAGENO HIDROLIZADO.<br></b></font></b><b style=\"font-size: 1rem;\"><span style=\"font-family: &quot;Comic Sans MS&quot;; font-size: 1rem;\"><b>Posee propiedades antibacterianas<br></b></span></b><b style=\"font-size: 1rem;\"><span style=\"font-family: &quot;Comic Sans MS&quot;; font-size: 1rem;\"><b>Reduce la producción del sebo<br></b></span></b><b style=\"font-size: 1rem;\"><span style=\"font-family: &quot;Comic Sans MS&quot;; font-size: 1rem;\"><b>Antioxidante</b></span></b></p>','https://res.cloudinary.com/do7dzakiw/image/upload/v1706281851/api.sistemaboticarosa.com/archivo/imagen_promocion/phprQaxLa_1706281850.png','api.sistemaboticarosa.com/archivo/imagen_promocion/phprQaxLa_1706281850','2024-01-26 10:10:50'),(28,'FLORESTA','2024-06-26','<h6><strong>No te lo las nuevas promociones para evitar todo tipo de picaduras</strong></h6><ul><li><strong class=\"ql-font-serif\">Ahuyenta insecto</strong></li><li><strong class=\"ql-font-serif\">Ayuda a disminuir las picaduras</strong></li><li><strong class=\"ql-font-serif\">Camufla el olor corporal</strong></li></ul>','https://res.cloudinary.com/do7dzakiw/image/upload/v1706282527/api.sistemaboticarosa.com/archivo/imagen_promocion/php3utBFf_1706282526.png','api.sistemaboticarosa.com/archivo/imagen_promocion/php3utBFf_1706282526','2024-01-26 10:22:06'),(29,'SIMPLY','2024-05-31','<h6><strong class=\"ql-font-monospace ql-size-large\">Vinieron las promociones nuevo para ti para estar limpia y segura para ti</strong></h6><ul><li><strong class=\"ql-font-monospace ql-size-small\">Ph balanceado</strong></li><li><strong class=\"ql-font-monospace ql-size-small\">Testeado ginecológicamente</strong></li><li><strong class=\"ql-font-monospace ql-size-small\">Adecuado para uso diario</strong></li></ul>','https://res.cloudinary.com/do7dzakiw/image/upload/v1706637928/api.sistemaboticarosa.com/archivo/imagen_promocion/phpYVDlp9_1706637928.jpg','api.sistemaboticarosa.com/archivo/imagen_promocion/phpYVDlp9_1706637928','2024-01-30 13:05:28');
/*!40000 ALTER TABLE `promocion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedor` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `id_comuna` int DEFAULT NULL,
  `rut_proveedor` varchar(255) DEFAULT NULL,
  `glosa_proveedor` varchar(255) DEFAULT NULL,
  `direccion_proveedor` varchar(255) DEFAULT NULL,
  `telefono_proveedor` varchar(255) DEFAULT NULL,
  `e_mail_proveedor` varchar(255) DEFAULT NULL,
  `nombrecontacto_proveedor` varchar(255) DEFAULT NULL,
  `comentario_proveedor` text,
  `vigente_proveedor` int DEFAULT '1',
  PRIMARY KEY (`id_proveedor`) USING BTREE,
  KEY `fk_relationship_35` (`id_comuna`) USING BTREE,
  CONSTRAINT `fk_relationship_35` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`)
) ENGINE=InnoDB AUTO_INCREMENT=1305 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
INSERT INTO `proveedor` VALUES (1216,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1217,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1218,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1219,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1220,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1221,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1222,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1223,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1224,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1225,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1226,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1227,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1228,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1229,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1230,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1231,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1232,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1233,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1234,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1235,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1236,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1237,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1238,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1239,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1240,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1241,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1242,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1243,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1244,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1245,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1246,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1247,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1248,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1249,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1250,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1251,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1252,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1253,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1254,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1255,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1256,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1257,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1258,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1259,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1260,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1261,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1262,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1263,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1264,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1265,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1266,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1267,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1268,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1269,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1270,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1271,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1272,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1273,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1274,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1275,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1276,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1277,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1278,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1279,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1280,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1281,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1282,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1283,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1284,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1285,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1286,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1287,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1288,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1289,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1290,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1291,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1292,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1293,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1294,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1295,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1296,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1297,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1298,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1299,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1300,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1301,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1302,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1303,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1),(1304,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Migrado desde Excel',1);
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincia`
--

DROP TABLE IF EXISTS `provincia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provincia` (
  `idProvincia` int NOT NULL AUTO_INCREMENT,
  `provincia` varchar(50) NOT NULL COMMENT 'nombre de provincia',
  `idDepartamento` int NOT NULL COMMENT 'llave foranea',
  PRIMARY KEY (`idProvincia`) USING BTREE,
  KEY `idDepartamento1` (`idDepartamento`) USING BTREE,
  CONSTRAINT `Provincia.Departamento` FOREIGN KEY (`idDepartamento`) REFERENCES `departamentos` (`idDepartamento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincia`
--

LOCK TABLES `provincia` WRITE;
/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
INSERT INTO `provincia` VALUES (1,'BAGUA',1),(2,'BONGARA',1),(3,'CHACHAPOYAS',1),(4,'CONDORCANQUI',1),(5,'LUYA',1),(6,'RODRIGUEZ DE MENDOZA',1),(7,'UTCUBAMBA',1),(8,'AIJA',2),(9,'ANTONIO RAYMONDI',2),(10,'ASUNCION',2),(11,'BOLOGNESI',2),(12,'CARHUAZ',2),(13,'CARLOS F.FITZCARRALD',2),(14,'CASMA',2),(15,'CORONGO',2),(16,'HUARAZ',2),(17,'HUARI',2),(18,'HUARMEY',2),(19,'HUAYLAS',2),(20,'MARISCAL LUZURIAGA',2),(21,'OCROS',2),(22,'PALLASCA',2),(23,'POMABAMBA',2),(24,'RECUAY',2),(25,'SANTA',2),(26,'SIHUAS',2),(27,'YUNGAY',2),(28,'ABANCAY',3),(29,'ANDAHUAYLAS',3),(30,'ANTABAMBA',3),(31,'AYMARAES',3),(32,'CHINCHEROS',3),(33,'COTABAMBAS',3),(34,'GRAU',3),(35,'AREQUIPA',4),(36,'CAMANA',4),(37,'CARAVELI',4),(38,'CASTILLA',4),(39,'CAYLLOMA',4),(40,'CONDESUYOS',4),(41,'ISLAY',4),(42,'LA UNION',4),(43,'CANGALLO',5),(44,'HUAMANGA',5),(45,'HUANCA SANCOS',5),(46,'HUANTA',5),(47,'LA MAR',5),(48,'LUCANAS',5),(49,'PARINACOCHAS',5),(50,'PAUCAR DEL SARA SARA',5),(51,'SUCRE',5),(52,'VICTOR FAJARDO',5),(53,'VILCASHUAMAN',5),(54,'CAJABAMBA',6),(55,'CAJAMARCA',6),(56,'CELENDIN',6),(57,'CHOTA',6),(58,'CONTUMAZA',6),(59,'CUTERVO',6),(60,'HUALGAYOC',6),(61,'JAEN',6),(62,'SAN IGNACIO',6),(63,'SAN MARCOS',6),(64,'SAN MIGUEL',6),(65,'SAN PABLO',6),(66,'SANTA CRUZ',6),(67,'ACOMAYO',7),(68,'ANTA',7),(69,'CALCA',7),(70,'CANAS',7),(71,'CANCHIS',7),(72,'CHUMBIVILCAS',7),(73,'CUSCO',7),(74,'ESPINAR',7),(75,'LA CONVENCION',7),(76,'PARURO',7),(77,'PAUCARTAMBO',7),(78,'QUISPICANCHI',7),(79,'URUBAMBA',7),(80,'ACOBAMBA',8),(81,'ANGARAES',8),(82,'CASTROVIRREYNA',8),(83,'CHURCAMPA',8),(84,'HUANCAVELICA',8),(85,'HUAYTARA',8),(86,'TAYACAJA',8),(87,'AMBO',9),(88,'DOS DE MAYO',9),(89,'HUACAYBAMBA',9),(90,'HUAMALIES',9),(91,'HUANUCO',9),(92,'LAURICOCHA',9),(93,'LEONCIO PRADO',9),(94,'MARAÑON',9),(95,'PACHITEA',9),(96,'PUERTO INCA',9),(97,'YAROWILCA',9),(98,'CHINCHA',10),(99,'ICA',10),(100,'NAZCA',10),(101,'PALPA',10),(102,'PISCO',10),(103,'CHANCHAMAYO',11),(104,'CHUPACA',11),(105,'CONCEPCION',11),(106,'HUANCAYO',11),(107,'JAUJA',11),(108,'JUNIN',11),(109,'SATIPO',11),(110,'TARMA',11),(111,'YAULI',11),(112,'ASCOPE',12),(113,'BOLIVAR',12),(114,'CHEPEN',12),(115,'GRAN CHIMU',12),(116,'JULCAN',12),(117,'OTUZCO',12),(118,'PACASMAYO',12),(119,'PATAZ',12),(120,'SANCHEZ CARRION',12),(121,'SANTIAGO DE CHUCO',12),(122,'TRUJILLO',12),(123,'VIRU',12),(124,'CHICLAYO',13),(125,'FERREÑAFE',13),(126,'LAMBAYEQUE',13),(127,'BARRANCA',14),(128,'CAJATAMBO',14),(129,'CALLAO',14),(130,'CANTA',14),(131,'CAÑETE',14),(132,'HUARAL',14),(133,'HUAROCHIRI',14),(134,'HUAURA',14),(135,'LIMA',14),(136,'OYON',14),(137,'YAUYOS',14),(138,'ALTO AMAZONAS',15),(139,'LORETO',15),(140,'MARISCAL R.CASTILLA',15),(141,'MAYNAS',15),(142,'REQUENA',15),(143,'UCAYALI',15),(144,'MANU',16),(145,'TAHUAMANU',16),(146,'TAMBOPATA',16),(147,'GENERAL SANCHEZ CERRO',17),(148,'ILO',17),(149,'MARISCAL NIETO',17),(150,'DANIEL ALCIDES CARRION',18),(151,'OXAPAMPA',18),(152,'PASCO',18),(153,'AYABACA',19),(154,'HUANCABAMBA',19),(155,'MORROPON',19),(156,'PAITA',19),(157,'PIURA',19),(158,'SECHURA',19),(159,'SULLANA',19),(160,'TALARA',19),(161,'AZANGARO',20),(162,'CARABAYA',20),(163,'CHUCUITO',20),(164,'EL COLLAO',20),(165,'HUANCANE',20),(166,'LAMPA',20),(167,'MELGAR',20),(168,'MOHO',20),(169,'PUNO',20),(170,'SAN ANTONIO DE PUTINA',20),(171,'SAN ROMAN',20),(172,'SANDIA',20),(173,'YUNGUYO',20),(174,'BELLAVISTA',21),(175,'EL DORADO',21),(176,'HUALLAGA',21),(177,'LAMAS',21),(178,'MARISCAL CACERES',21),(179,'MOYOBAMBA',21),(180,'PICOTA',21),(181,'RIOJA',21),(182,'SAN MARTIN',21),(183,'TOCACHE',21),(184,'CANDARAVE',22),(185,'JORGE BASADRE',22),(186,'TACNA',22),(187,'TARATA',22),(188,'CONTRALMIRANTE VILLAR',23),(189,'TUMBES',23),(190,'ZARUMILLA',23),(191,'ATALAYA',24),(192,'CORONEL PORTILLO',24),(193,'PADRE ABAD',24),(194,'PURUS',24);
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `region`
--

DROP TABLE IF EXISTS `region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `region` (
  `id_region` int NOT NULL AUTO_INCREMENT,
  `glosa_region` varchar(255) DEFAULT NULL,
  `orden_region` int DEFAULT NULL,
  `vigente_region` int DEFAULT '1',
  `codigochilexpress_region` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_region`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `region`
--

LOCK TABLES `region` WRITE;
/*!40000 ALTER TABLE `region` DISABLE KEYS */;
/*!40000 ALTER TABLE `region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `id_tipo_servicio` int DEFAULT NULL,
  `glosa_servicio` varchar(255) DEFAULT NULL,
  `descripcion_servicio` text,
  `id_servicio_padre` int DEFAULT NULL,
  `costo_servicio` float DEFAULT NULL,
  `costoferiado_servicio` float DEFAULT NULL,
  `aplicaiva_servicio` int DEFAULT NULL,
  `detalleportal_servicio` int DEFAULT NULL,
  `visibleportal_servicio` int DEFAULT NULL,
  `orden_servicio` int DEFAULT NULL,
  `tituloweb_servicio` varchar(255) DEFAULT NULL,
  `pathimagen_servicio` varchar(255) DEFAULT NULL,
  `aplicapagoonline_servicio` int DEFAULT '0',
  `tiposolicitud_servicio` int DEFAULT NULL,
  `textoadjuntocorreo` longtext,
  `mostrarprecioreservaonline_servicio` int DEFAULT '1',
  `mostraravisoreservaonline_servicio` int DEFAULT '0',
  `textoavisoreservaonline_servicio` varchar(5000) DEFAULT NULL,
  `historico_servicio` int DEFAULT '0',
  `vigente_servicio` int DEFAULT '1',
  PRIMARY KEY (`id_servicio`) USING BTREE,
  KEY `fk_relationship_84` (`id_tipo_servicio`) USING BTREE,
  CONSTRAINT `fk_relationship_84` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tipo_servicio` (`id_tipo_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slider`
--

DROP TABLE IF EXISTS `slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slider` (
  `id_slider` int NOT NULL AUTO_INCREMENT,
  `id_categoria` int DEFAULT NULL,
  `nombre_slider` varchar(255) DEFAULT NULL,
  `tipoarchivo_slider` varchar(255) DEFAULT NULL,
  `peso_slider` varchar(255) DEFAULT NULL,
  `fechacreacion_slider` datetime DEFAULT NULL,
  `pathmobile_slider` varchar(255) DEFAULT NULL,
  `pathescritorio_slider` varchar(255) DEFAULT NULL,
  `urlimagen_slider` varchar(1000) DEFAULT NULL,
  `orden_slider` int DEFAULT NULL,
  `vigente_slider` int DEFAULT NULL,
  `texto_slider` longtext,
  PRIMARY KEY (`id_slider`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slider`
--

LOCK TABLES `slider` WRITE;
/*!40000 ALTER TABLE `slider` DISABLE KEYS */;
INSERT INTO `slider` VALUES (33,33,'VITAMINAS',NULL,NULL,'2024-01-22 16:50:07','1705966231clorurovitaminamagnesolmobilmin.png','1705966231imagen1principalmin.png',NULL,NULL,1,NULL),(34,37,'VITAMINAS',NULL,NULL,'2024-01-22 18:55:08','1705967708xiomaramobil.png','1705967863xiomara.png',NULL,NULL,1,NULL),(35,67,'SL FK',NULL,NULL,'2026-06-11 13:11:16',NULL,NULL,NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id_staff` int NOT NULL AUTO_INCREMENT,
  `id_comuna` int DEFAULT NULL,
  `id_sucursal` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `dni_staff` varchar(255) DEFAULT NULL,
  `dv_staff` char(1) DEFAULT NULL,
  `nombre_staff` varchar(255) DEFAULT NULL,
  `apellidopaterno_staff` varchar(255) DEFAULT NULL,
  `apellidomaterno_staff` varchar(255) DEFAULT NULL,
  `e_mail_staff` varchar(255) DEFAULT NULL,
  `telefono_staff` varchar(255) DEFAULT NULL,
  `celular_staff` varchar(255) DEFAULT NULL,
  `img_staff` varchar(255) DEFAULT NULL,
  `aplicacomision_staff` int DEFAULT NULL,
  `valorcomision_staff` float DEFAULT NULL,
  `sexo_staff` varchar(255) DEFAULT NULL,
  `fechanacimiento_staff` date DEFAULT NULL,
  `reservaonline_staff` int DEFAULT NULL,
  `gestionarhorario_staff` int DEFAULT NULL,
  `visibleportal_staff` int DEFAULT NULL,
  `descripcion_staff` text,
  `porcentajeurgencia_staff` float DEFAULT NULL,
  `internoexterno_staff` varchar(255) DEFAULT NULL,
  `tituloweb_staff` varchar(255) DEFAULT NULL,
  `pathimgen_staff` varchar(255) DEFAULT NULL,
  `vigente_staff` int DEFAULT '1',
  `porcentajeprocedimiento_staff` float DEFAULT NULL,
  `porcentajecirugia_staff` float DEFAULT NULL,
  `segmento_staff` int DEFAULT NULL,
  `detalleportal_staff` int DEFAULT NULL,
  `veratenciones_staff` int DEFAULT '1',
  `verregistroshospital_staff` int DEFAULT '1',
  `maximodiasparareservar_staff` int DEFAULT NULL,
  `recibirresumenreserva_staff` int DEFAULT NULL,
  `pathfirma_staff` varchar(1000) DEFAULT NULL,
  `profesioncargo_staff` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_staff`) USING BTREE,
  KEY `fk_relationship_98` (`id_comuna`) USING BTREE,
  CONSTRAINT `fk_relationship_98` FOREIGN KEY (`id_comuna`) REFERENCES `tipo_agenda` (`id_tipo_agenda`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (108,NULL,17,18,'75144370',NULL,'RONALDO','DURAND','LUNA','smithxd118@gmail.com','931585523','980535377',NULL,NULL,NULL,'M',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL),(122,NULL,17,18,'75144375',NULL,'ROSA YOVANNA','LUNA','ABAN','lunaabanyovanna@gmail.com',NULL,'989656783',NULL,NULL,NULL,'F',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL),(123,NULL,NULL,NULL,NULL,NULL,'Test','User','','testuser_crm@x.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL),(124,NULL,NULL,NULL,NULL,NULL,'Test2-edit','','','testuser2_crm@x.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_producto_bodega`
--

DROP TABLE IF EXISTS `stock_producto_bodega`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_producto_bodega` (
  `id_stock_producto_bodega` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `id_bodega` int DEFAULT NULL,
  `total_stock_producto_bodega` float(11,0) DEFAULT NULL,
  `totalcritico_stock_producto_bodega` float(11,0) DEFAULT NULL,
  `stockentransito_stock_producto_bodega` float(11,0) DEFAULT NULL,
  `saldocantidad_stock_producto_bodega` float(11,0) DEFAULT NULL,
  `ultimopreciocompra_stock_producto_bodega` float DEFAULT NULL,
  `precioventa_stock_producto_bodega` float DEFAULT NULL,
  PRIMARY KEY (`id_stock_producto_bodega`) USING BTREE,
  KEY `fk_idproducto_stok_bodega` (`id_producto`) USING BTREE,
  KEY `fk_idbodega_stock_ producto` (`id_bodega`) USING BTREE,
  CONSTRAINT `fk_idbodega_stock_ producto` FOREIGN KEY (`id_bodega`) REFERENCES `bodega` (`id_bodega`),
  CONSTRAINT `fk_idproducto_stok_bodega` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_producto_bodega`
--

LOCK TABLES `stock_producto_bodega` WRITE;
/*!40000 ALTER TABLE `stock_producto_bodega` DISABLE KEYS */;
INSERT INTO `stock_producto_bodega` VALUES (90,90,18,89,NULL,NULL,100,14,13),(91,91,18,100,NULL,NULL,100,0,NULL),(92,92,18,95,NULL,NULL,100,1,1),(93,93,18,100,NULL,NULL,100,0,NULL),(94,94,18,100,NULL,NULL,100,0,0),(95,95,18,100,NULL,NULL,100,0,NULL),(96,96,18,100,NULL,NULL,100,0,NULL),(97,97,18,100,NULL,NULL,100,0,NULL),(98,98,18,100,NULL,NULL,100,0,NULL),(99,99,18,100,NULL,NULL,100,0,NULL),(100,100,18,100,NULL,NULL,100,0,NULL),(101,101,18,100,NULL,NULL,100,0,NULL),(102,102,18,100,NULL,NULL,100,0,NULL),(103,103,18,100,NULL,NULL,100,0,0),(104,104,18,100,NULL,NULL,100,0,NULL),(105,105,18,100,NULL,NULL,100,0,NULL),(106,106,18,98,NULL,NULL,100,0,12),(107,107,18,100,NULL,NULL,100,0,NULL),(108,108,18,100,NULL,NULL,100,0,NULL),(109,109,18,100,NULL,NULL,100,0,NULL),(110,110,18,100,NULL,NULL,100,0,NULL),(111,111,18,100,NULL,NULL,100,0,NULL),(112,112,18,100,NULL,NULL,100,0,NULL),(113,113,18,100,NULL,NULL,100,0,NULL),(114,114,18,100,NULL,NULL,100,0,NULL),(115,115,18,100,NULL,NULL,100,0,NULL),(116,116,18,100,NULL,NULL,100,0,NULL),(117,117,18,100,NULL,NULL,100,0,NULL),(118,118,18,100,NULL,NULL,100,0,NULL),(119,119,18,100,NULL,NULL,100,0,NULL),(120,120,18,100,NULL,NULL,100,0,NULL),(121,121,18,100,NULL,NULL,100,0,NULL),(122,122,18,100,NULL,NULL,100,0,NULL),(123,123,18,100,NULL,NULL,100,0,NULL),(124,124,18,100,NULL,NULL,100,0,NULL),(125,125,18,100,NULL,NULL,100,0,NULL),(126,126,18,100,NULL,NULL,100,0,NULL),(127,127,18,100,NULL,NULL,100,0,NULL),(128,128,18,100,NULL,NULL,100,0,NULL),(129,129,18,100,NULL,NULL,100,0,NULL),(130,130,18,100,NULL,NULL,100,0,NULL),(131,131,18,100,NULL,NULL,100,0,NULL),(132,132,18,100,NULL,NULL,100,0,NULL),(133,133,18,100,NULL,NULL,100,0,NULL),(134,134,18,100,NULL,NULL,100,0,NULL),(135,135,18,100,NULL,NULL,100,0,NULL),(136,136,18,100,NULL,NULL,100,0,NULL),(137,137,18,100,NULL,NULL,100,0,NULL),(138,138,18,100,NULL,NULL,100,0,NULL),(139,139,18,100,NULL,NULL,100,0,NULL),(140,140,18,100,NULL,NULL,100,0,NULL),(141,141,18,100,NULL,NULL,100,0,NULL),(142,142,18,100,NULL,NULL,100,0,NULL),(143,143,18,100,NULL,NULL,100,0,NULL),(144,144,18,100,NULL,NULL,100,0,NULL),(145,145,18,100,NULL,NULL,100,0,NULL),(146,146,18,100,NULL,NULL,100,0,NULL),(147,147,18,100,NULL,NULL,100,0,NULL),(148,148,18,100,NULL,NULL,100,0,NULL),(149,149,18,100,NULL,NULL,100,0,NULL),(150,150,18,100,NULL,NULL,100,0,NULL),(151,151,18,100,NULL,NULL,100,0,NULL),(152,152,18,100,NULL,NULL,100,0,0),(153,153,18,100,NULL,NULL,100,0,NULL),(154,154,18,100,NULL,NULL,100,0,NULL),(155,155,18,100,NULL,NULL,100,0,NULL),(156,156,18,100,NULL,NULL,100,0,NULL),(157,157,18,100,NULL,NULL,100,0,NULL),(158,158,18,100,NULL,NULL,100,0,NULL),(159,159,18,100,NULL,NULL,100,0,NULL),(160,160,18,100,NULL,NULL,100,0,NULL),(161,161,18,100,NULL,NULL,100,0,NULL),(162,162,18,100,NULL,NULL,100,0,NULL),(163,163,18,100,NULL,NULL,100,0,NULL),(164,164,18,100,NULL,NULL,100,0,NULL),(165,165,18,100,NULL,NULL,100,0,NULL),(166,166,18,100,NULL,NULL,100,0,NULL),(167,167,18,100,NULL,NULL,100,0,NULL),(168,168,18,100,NULL,NULL,100,11,10),(169,169,18,100,NULL,NULL,100,0,0),(170,170,18,100,NULL,NULL,100,0,NULL),(171,171,18,100,NULL,NULL,100,0,NULL),(172,172,18,100,NULL,NULL,100,0,NULL),(173,173,18,100,NULL,NULL,100,0,0),(174,174,18,100,NULL,NULL,100,0,NULL),(175,175,18,100,NULL,NULL,100,1,1.5),(176,176,18,100,NULL,NULL,100,0,0),(177,177,18,100,NULL,NULL,100,2,2.5),(178,178,18,100,NULL,NULL,100,0,NULL),(179,179,18,100,NULL,NULL,100,2,2.9),(180,180,18,100,NULL,NULL,100,2.1,3),(181,181,18,100,NULL,NULL,100,1.75,2.5),(182,182,18,100,NULL,NULL,100,1.75,2.5),(183,183,18,100,NULL,NULL,100,10.5,15),(184,184,18,100,NULL,NULL,100,8.4,12),(185,185,18,100,NULL,NULL,100,2.3,3.4),(186,186,18,100,NULL,NULL,100,2,2),(187,187,18,100,NULL,NULL,100,3.45,4),(188,188,18,100,NULL,NULL,100,1.75,2.5),(189,189,18,100,NULL,NULL,100,1.05,1.5),(190,190,18,100,NULL,NULL,100,1.4,2),(191,191,18,100,NULL,NULL,100,1.05,1.5),(192,192,18,100,NULL,NULL,100,1.05,1.5),(193,193,18,100,NULL,NULL,100,9.45,13.5),(194,195,18,111,0,0,100,NULL,101),(195,196,18,112,0,0,100,100,12),(199,200,18,100,NULL,NULL,NULL,1.5,1.76);
/*!40000 ALTER TABLE `stock_producto_bodega` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_producto_bodega_atributo`
--

DROP TABLE IF EXISTS `stock_producto_bodega_atributo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_producto_bodega_atributo` (
  `id_stock_producto_bodega_atributo` int NOT NULL AUTO_INCREMENT,
  `id_stock_producto_bodega` int DEFAULT NULL,
  `id_atributo` int DEFAULT NULL,
  `id_staff` int DEFAULT NULL,
  `cantidad_stock_producto_bodega_atributo` float DEFAULT NULL,
  PRIMARY KEY (`id_stock_producto_bodega_atributo`) USING BTREE,
  KEY `fk_stock_producto_bodega_atributo_idstockproductobodega` (`id_stock_producto_bodega`) USING BTREE,
  KEY `fk_idstaff_stock_producto_bodega_atributo` (`id_staff`) USING BTREE,
  CONSTRAINT `fk_idstaff_stock_producto_bodega_atributo` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`),
  CONSTRAINT `fk_stock_producto_bodega_atributo_idstockproductobodega` FOREIGN KEY (`id_stock_producto_bodega`) REFERENCES `stock_producto_bodega` (`id_stock_producto_bodega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_producto_bodega_atributo`
--

LOCK TABLES `stock_producto_bodega_atributo` WRITE;
/*!40000 ALTER TABLE `stock_producto_bodega_atributo` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_producto_bodega_atributo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `success_mercadopago`
--

DROP TABLE IF EXISTS `success_mercadopago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `success_mercadopago` (
  `id_success_mercadopago` int NOT NULL AUTO_INCREMENT,
  `id_notificacion_mercadopago` int DEFAULT NULL,
  `collection_id_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `collection_status_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_id_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `external_reference_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_type_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `merchant_order_id_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `preference_id_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `site_id_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `processing_mode_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `merchant_account_id_success_mercadopago` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_creacion_success_mercadopago` date DEFAULT NULL,
  PRIMARY KEY (`id_success_mercadopago`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `success_mercadopago`
--

LOCK TABLES `success_mercadopago` WRITE;
/*!40000 ALTER TABLE `success_mercadopago` DISABLE KEYS */;
/*!40000 ALTER TABLE `success_mercadopago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sucursal` (
  `id_sucursal` int NOT NULL AUTO_INCREMENT,
  `idDistrito` int DEFAULT NULL,
  `codigo_sucursal` varchar(255) DEFAULT NULL,
  `glosa_sucursal` varchar(255) DEFAULT NULL,
  `encargado_sucursal` varchar(255) DEFAULT NULL,
  `direccion_sucursal` varchar(255) DEFAULT NULL,
  `telefono_sucursal` varchar(255) DEFAULT NULL,
  `e_mail_sucursal` varchar(255) DEFAULT NULL,
  `mapa_sucursal` text,
  `descripcion_sucursal` text,
  `horario_sucursal` text,
  `vigente_sucursal` int DEFAULT NULL,
  `idclientedefectopos_sucursal` int DEFAULT NULL,
  `mediopagodefectopos_sucursal` int DEFAULT NULL,
  `idusuarioventaonlinedefecto_sucursal` int DEFAULT NULL,
  `idbodegadefecto_sucursal` int DEFAULT NULL,
  `idbodegaonlinedefecto_sucursal` int DEFAULT NULL,
  PRIMARY KEY (`id_sucursal`) USING BTREE,
  KEY `fk_id_distrito` (`idDistrito`) USING BTREE,
  CONSTRAINT `fk_id_distrito` FOREIGN KEY (`idDistrito`) REFERENCES `distrito` (`idDistrito`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursal`
--

LOCK TABLES `sucursal` WRITE;
/*!40000 ALTER TABLE `sucursal` DISABLE KEYS */;
INSERT INTO `sucursal` VALUES (17,1330,'15137','SUCURSAL HUACHO',NULL,'Cruz de Cano 101',NULL,NULL,NULL,NULL,NULL,1,1,NULL,23242,NULL,NULL),(18,250,NULL,'SFK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `sucursal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sucursal_staff`
--

DROP TABLE IF EXISTS `sucursal_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sucursal_staff` (
  `id_sucursal_staff` int NOT NULL AUTO_INCREMENT,
  `id_sucursal` int DEFAULT NULL,
  `id_staff` int DEFAULT NULL,
  `sucursal_staff_vigente` int DEFAULT '1',
  PRIMARY KEY (`id_sucursal_staff`) USING BTREE,
  KEY `fk_relationship_5` (`id_sucursal`) USING BTREE,
  KEY `fk_relationship_6` (`id_staff`) USING BTREE,
  CONSTRAINT `fk_relationship_5` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  CONSTRAINT `fk_relationship_6` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`)
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursal_staff`
--

LOCK TABLES `sucursal_staff` WRITE;
/*!40000 ALTER TABLE `sucursal_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `sucursal_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_afectacion`
--

DROP TABLE IF EXISTS `tipo_afectacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_afectacion` (
  `id_tipo_afectacion` int NOT NULL AUTO_INCREMENT,
  `codigo` char(2) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `codigo_afectacion` varchar(255) DEFAULT NULL,
  `nombre_afectacion` varchar(255) DEFAULT NULL,
  `tipo_afectacion` varchar(255) DEFAULT NULL,
  `vigente_afectacion` int DEFAULT NULL,
  PRIMARY KEY (`id_tipo_afectacion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_afectacion`
--

LOCK TABLES `tipo_afectacion` WRITE;
/*!40000 ALTER TABLE `tipo_afectacion` DISABLE KEYS */;
INSERT INTO `tipo_afectacion` VALUES (1,'10','OP. GRAVADAS (18%)','1000','IGV','VAT',1),(2,'20','OP. EXONERADAS (0%)','9997','EXO','VAT',1),(3,'30','OP. INAFECTAS (0%)','9998','INA','FRE',1);
/*!40000 ALTER TABLE `tipo_afectacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_agenda`
--

DROP TABLE IF EXISTS `tipo_agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_agenda` (
  `id_tipo_agenda` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_agenda` varchar(255) DEFAULT NULL,
  `orden_tipo_agenda` int DEFAULT NULL,
  `vigente_tipo_agenda` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_agenda`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_agenda`
--

LOCK TABLES `tipo_agenda` WRITE;
/*!40000 ALTER TABLE `tipo_agenda` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_concentracion`
--

DROP TABLE IF EXISTS `tipo_concentracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_concentracion` (
  `id_tipo_concentracion` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_concentracion` varchar(255) DEFAULT NULL,
  `orden_tipo_concentracion` int DEFAULT NULL,
  `vigente_tipo_concentracion` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_concentracion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_concentracion`
--

LOCK TABLES `tipo_concentracion` WRITE;
/*!40000 ALTER TABLE `tipo_concentracion` DISABLE KEYS */;
INSERT INTO `tipo_concentracion` VALUES (1,'ml',1,1),(2,'mg',2,1),(3,'mg/ml',3,1),(4,'mg/g',4,1),(5,'g',5,1),(6,'g/ml',6,1),(7,'otros',7,1);
/*!40000 ALTER TABLE `tipo_concentracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_cuenta`
--

DROP TABLE IF EXISTS `tipo_cuenta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_cuenta` (
  `id_tipo_cuenta` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_cuenta` varchar(255) DEFAULT NULL,
  `orden_tipo_cuenta` int DEFAULT NULL,
  `vigente_tipo_cuenta` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_cuenta`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_cuenta`
--

LOCK TABLES `tipo_cuenta` WRITE;
/*!40000 ALTER TABLE `tipo_cuenta` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_cuenta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_descuento`
--

DROP TABLE IF EXISTS `tipo_descuento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_descuento` (
  `id_tipo_descuento` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_descuento` varchar(255) DEFAULT NULL,
  `vigente_tipo_descuento` int DEFAULT NULL,
  PRIMARY KEY (`id_tipo_descuento`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_descuento`
--

LOCK TABLES `tipo_descuento` WRITE;
/*!40000 ALTER TABLE `tipo_descuento` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_descuento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_despacho`
--

DROP TABLE IF EXISTS `tipo_despacho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_despacho` (
  `id_tipo_despacho` int NOT NULL AUTO_INCREMENT,
  `nombre_tipo_despacho` varchar(255) DEFAULT NULL,
  `preciodefecto_tipo_despacho` float DEFAULT NULL,
  `pathlogo_tipo_despacho` varchar(1000) DEFAULT NULL,
  `orden_tipo_despacho` int DEFAULT NULL,
  `vigente_tipo_despacho` int DEFAULT NULL,
  PRIMARY KEY (`id_tipo_despacho`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_despacho`
--

LOCK TABLES `tipo_despacho` WRITE;
/*!40000 ALTER TABLE `tipo_despacho` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_despacho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_documento`
--

DROP TABLE IF EXISTS `tipo_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_documento` (
  `id_tipo_documento` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_documento` varchar(255) DEFAULT NULL,
  `orden_tipo_documento` int DEFAULT NULL,
  `vigente_tipo_documento` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_documento`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_documento`
--

LOCK TABLES `tipo_documento` WRITE;
/*!40000 ALTER TABLE `tipo_documento` DISABLE KEYS */;
INSERT INTO `tipo_documento` VALUES (1,'Factura',2,1),(2,'Guía de despacho',3,0),(3,'Boleta',4,1),(4,'Sin Documento',1,1),(5,'Nota debito',5,0),(6,'Nota de Credito',6,1);
/*!40000 ALTER TABLE `tipo_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_egreso`
--

DROP TABLE IF EXISTS `tipo_egreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_egreso` (
  `id_tipo_egreso` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_egreso` varchar(255) DEFAULT NULL,
  `orden_tipo_egreso` int DEFAULT NULL,
  `afectacaja_tipo_egreso` int DEFAULT '1',
  `afectaventa_tipo_egreso` int DEFAULT '1',
  `vigente_tipo_egreso` int DEFAULT NULL,
  PRIMARY KEY (`id_tipo_egreso`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_egreso`
--

LOCK TABLES `tipo_egreso` WRITE;
/*!40000 ALTER TABLE `tipo_egreso` DISABLE KEYS */;
INSERT INTO `tipo_egreso` VALUES (1,'Retiro en Efectivo',1,1,0,1),(2,'Nota de Credito Devolucion de Dinero',1,1,0,1),(3,'Retiro Abono Cliente de Cuenta Corriente',1,0,0,1),(4,'Devolución Abono Cliente Efectivo',1,1,0,1),(5,'Salida de Credito Cliente',1,0,0,1),(6,'Vuelto',1,1,1,1),(7,'Reversa Tarjeta Credito',1,0,0,1),(8,'Devolución Abono Cliente Transferencia',1,1,0,1);
/*!40000 ALTER TABLE `tipo_egreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_ingreso`
--

DROP TABLE IF EXISTS `tipo_ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_ingreso` (
  `id_tipo_ingreso` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_ingreso` varchar(255) DEFAULT NULL,
  `glosapdf_tipo_ingreso` varchar(255) DEFAULT NULL,
  `afectacaja_tipo_ingreso` int DEFAULT '1',
  `afectaventas_tipo_ingreso` int DEFAULT '1',
  `orden_tipo_ingreso` int DEFAULT NULL,
  `vigente_tipo_ingreso` int DEFAULT NULL,
  PRIMARY KEY (`id_tipo_ingreso`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_ingreso`
--

LOCK TABLES `tipo_ingreso` WRITE;
/*!40000 ALTER TABLE `tipo_ingreso` DISABLE KEYS */;
INSERT INTO `tipo_ingreso` VALUES (1,'Ingreso','Comprobante Ingreso',1,1,1,1),(2,'Nota de Credito','Comprobante Ingreso',0,1,2,1),(3,'Ingreso Abono Cliente','Comprobante Ingreso de Abono',0,0,3,1),(4,'Salida Credito Cliente','Comprobante Credito',0,1,4,1),(5,'Salida Abono Cliente','Comprobante Ingreso',0,1,5,1),(6,'Ingreso Credito Cliente','Comprobante Pago Credito',1,0,6,1),(7,'Otros Ingresos','Comprobante Ingreso',0,0,7,1),(8,'Ingreso por Devolucion Abono Cliente','Comprobante Ingreso por Devolución Abono ',0,0,8,1),(9,'Tarjeta de Credito','Comprobante Ingreso',0,0,9,1);
/*!40000 ALTER TABLE `tipo_ingreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_inventario`
--

DROP TABLE IF EXISTS `tipo_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_inventario` (
  `id_tipo_inventario` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_inventario` varchar(255) DEFAULT NULL,
  `ventaproducto_tipo_inventario` int DEFAULT NULL,
  `orden_tipo_inventario` int DEFAULT NULL,
  `vigente_tipo_inventario` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_inventario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_inventario`
--

LOCK TABLES `tipo_inventario` WRITE;
/*!40000 ALTER TABLE `tipo_inventario` DISABLE KEYS */;
INSERT INTO `tipo_inventario` VALUES (1,'ROPA',1,NULL,1),(2,'PRODUCTO',1,NULL,1),(3,'MEDICAMENTO',1,NULL,1),(4,'LIMPIEZA',1,NULL,1);
/*!40000 ALTER TABLE `tipo_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_movimiento`
--

DROP TABLE IF EXISTS `tipo_movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_movimiento` (
  `id_tipo_movimiento` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_movimiento` varchar(255) DEFAULT NULL,
  `vigente_tipo_movimiento` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_movimiento`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_movimiento`
--

LOCK TABLES `tipo_movimiento` WRITE;
/*!40000 ALTER TABLE `tipo_movimiento` DISABLE KEYS */;
INSERT INTO `tipo_movimiento` VALUES (1,'Añadir',1),(2,'Quitar',1),(3,'Actualizar',1);
/*!40000 ALTER TABLE `tipo_movimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_producto`
--

DROP TABLE IF EXISTS `tipo_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_producto` (
  `id_tipo_producto` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_producto` varchar(255) DEFAULT NULL,
  `orden_tipo_producto` int DEFAULT NULL,
  `tipomedicamento_tipo_producto` int DEFAULT NULL,
  `vigente_tipo_producto` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_producto`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_producto`
--

LOCK TABLES `tipo_producto` WRITE;
/*!40000 ALTER TABLE `tipo_producto` DISABLE KEYS */;
INSERT INTO `tipo_producto` VALUES (1,'PRODUCTO',1,0,1),(2,'MEDICAMENTO',2,1,1),(3,'LIMPIEZA',3,0,1);
/*!40000 ALTER TABLE `tipo_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` int NOT NULL AUTO_INCREMENT,
  `glosa_tipo_usuario` varchar(255) DEFAULT NULL,
  `tipo_usuario` int DEFAULT NULL,
  `comision_tipo_usuario` int DEFAULT NULL,
  `orden_tipo_usuario` int DEFAULT NULL,
  `vigente_tipo_usuario` int DEFAULT '1',
  PRIMARY KEY (`id_tipo_usuario`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_usuario`
--

LOCK TABLES `tipo_usuario` WRITE;
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;
INSERT INTO `tipo_usuario` VALUES (1,'Bodeguero',1,NULL,1,1),(2,'Recepcionista',2,NULL,1,2),(3,'Administrador',3,NULL,1,3),(4,'Médico',4,NULL,1,4),(5,'Enfermero',4,NULL,1,6),(6,'Tecnólogo Médico',4,NULL,1,5),(7,'Cliente',6,NULL,1,7),(8,'Cirujano',4,NULL,1,8),(9,'Anestesista',4,NULL,1,9),(10,'Administador - Médico',7,NULL,1,10),(11,'Médico de hospital',4,NULL,1,11);
/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transportista`
--

DROP TABLE IF EXISTS `transportista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transportista` (
  `id_transportista` int NOT NULL AUTO_INCREMENT,
  `rut_transportista` varchar(255) DEFAULT NULL,
  `dv_transportista` varchar(2) DEFAULT NULL,
  `nombreorazonsocial_transportista` varchar(1000) DEFAULT NULL,
  `direccion_transportista` varchar(1000) DEFAULT NULL,
  `telefono_transportista` varchar(255) DEFAULT NULL,
  `email_transportista` varchar(255) DEFAULT NULL,
  `contacto_transportista` varchar(1000) DEFAULT NULL,
  `descripcion_transportista` varchar(5000) DEFAULT NULL,
  `consultaapi_transportista` int DEFAULT NULL,
  `pathlogo_transportista` varchar(1000) DEFAULT NULL,
  `visibleonline_transportista` int DEFAULT NULL,
  `vigente_transportista` int DEFAULT NULL,
  PRIMARY KEY (`id_transportista`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transportista`
--

LOCK TABLES `transportista` WRITE;
/*!40000 ALTER TABLE `transportista` DISABLE KEYS */;
/*!40000 ALTER TABLE `transportista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transportista_comuna`
--

DROP TABLE IF EXISTS `transportista_comuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transportista_comuna` (
  `id_transportista_comuna` int NOT NULL AUTO_INCREMENT,
  `id_transportista` int DEFAULT NULL,
  `id_comuna` int DEFAULT NULL,
  `preciodefecto_transportista_comuna` float DEFAULT NULL,
  `vigente_transportista_comuna` int DEFAULT NULL,
  PRIMARY KEY (`id_transportista_comuna`) USING BTREE,
  KEY `fk_transporte_comuna` (`id_transportista`) USING BTREE,
  KEY `fk_comuna_transporte_comuna` (`id_comuna`) USING BTREE,
  CONSTRAINT `fk_comuna_transporte_comuna` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id_comuna`),
  CONSTRAINT `fk_transporte_comuna` FOREIGN KEY (`id_transportista`) REFERENCES `transportista` (`id_transportista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transportista_comuna`
--

LOCK TABLES `transportista_comuna` WRITE;
/*!40000 ALTER TABLE `transportista_comuna` DISABLE KEYS */;
/*!40000 ALTER TABLE `transportista_comuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transportista_pedido`
--

DROP TABLE IF EXISTS `transportista_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transportista_pedido` (
  `id_transportista_pedido` int NOT NULL AUTO_INCREMENT,
  `id_transportista` int DEFAULT NULL,
  `id_pedido` int DEFAULT NULL,
  `valor_transportista_pedido` float DEFAULT NULL,
  `vigente_transportista_pedido` int DEFAULT NULL,
  `peso_transportista_pedido` float DEFAULT NULL,
  PRIMARY KEY (`id_transportista_pedido`) USING BTREE,
  KEY `fk_transportista_pedido` (`id_transportista`) USING BTREE,
  KEY `fk_pedido_transportista_pedido` (`id_pedido`) USING BTREE,
  CONSTRAINT `fk_pedido_transportista_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  CONSTRAINT `fk_transportista_pedido` FOREIGN KEY (`id_transportista`) REFERENCES `transportista` (`id_transportista`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transportista_pedido`
--

LOCK TABLES `transportista_pedido` WRITE;
/*!40000 ALTER TABLE `transportista_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `transportista_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traspaso`
--

DROP TABLE IF EXISTS `traspaso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `traspaso` (
  `id_traspaso` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_folio` int DEFAULT NULL,
  `numero_traspaso` varchar(255) DEFAULT NULL,
  `idbodegadesde_traspaso` int DEFAULT NULL,
  `idbodegahasta_traspaso` int DEFAULT NULL,
  `fechacreacion_traspaso` datetime DEFAULT NULL,
  `fecharecepcion_traspaso` datetime DEFAULT NULL,
  `estado_traspaso` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_traspaso`) USING BTREE,
  KEY `fk_traspaso_folio` (`id_folio`) USING BTREE,
  KEY `fk_traspaso_usuario` (`id_usuario`) USING BTREE,
  CONSTRAINT `fk_traspaso_folio` FOREIGN KEY (`id_folio`) REFERENCES `folio` (`id_folio`),
  CONSTRAINT `fk_traspaso_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traspaso`
--

LOCK TABLES `traspaso` WRITE;
/*!40000 ALTER TABLE `traspaso` DISABLE KEYS */;
/*!40000 ALTER TABLE `traspaso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno`
--

DROP TABLE IF EXISTS `turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turno` (
  `id_turno` int NOT NULL AUTO_INCREMENT,
  `id_turno_sucursal` int DEFAULT NULL,
  `id_sucursal_staff` int DEFAULT NULL,
  `fechacreacion_turno` datetime DEFAULT NULL,
  `vigente_turno` int DEFAULT '1',
  PRIMARY KEY (`id_turno`) USING BTREE,
  KEY `fk_relationship_145` (`id_sucursal_staff`) USING BTREE,
  KEY `fk_relationship_177` (`id_turno_sucursal`) USING BTREE,
  CONSTRAINT `fk_relationship_145` FOREIGN KEY (`id_sucursal_staff`) REFERENCES `sucursal_staff` (`id_sucursal_staff`),
  CONSTRAINT `fk_relationship_177` FOREIGN KEY (`id_turno_sucursal`) REFERENCES `turno_sucursal` (`id_turno_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno`
--

LOCK TABLES `turno` WRITE;
/*!40000 ALTER TABLE `turno` DISABLE KEYS */;
/*!40000 ALTER TABLE `turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad`
--

DROP TABLE IF EXISTS `unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidad` (
  `id_unidad` int NOT NULL AUTO_INCREMENT,
  `glosa_unidad` varchar(255) DEFAULT NULL,
  `order_unidad` int DEFAULT NULL,
  `vigente_unidad` int DEFAULT '1',
  PRIMARY KEY (`id_unidad`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad`
--

LOCK TABLES `unidad` WRITE;
/*!40000 ALTER TABLE `unidad` DISABLE KEYS */;
INSERT INTO `unidad` VALUES (1,'POLVOS',1,1),(2,'GRANULADOS',2,1),(3,'CAPSULAS',3,1),(4,'TABLETAS',4,1),(5,'COMPRIMIDOS',5,1),(6,'SUPOSITORIOS',6,1),(7,'OVULOS',7,1),(8,'POMADAS',8,1),(9,'PASTAS',9,1),(10,'CREMAS',10,1),(11,'JALEAS',11,1),(12,'EMPLASTOS',12,1),(13,'INYECTABLES',13,1),(14,'JARABES',14,1),(15,'EMULSIONES',15,1),(16,'SUSPENSIONES',16,1),(17,'COLIRIOS',17,1),(18,'INHALADORES',18,1),(19,'AEROSOLES',19,1),(20,'OTROS',20,1);
/*!40000 ALTER TABLE `unidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `id_tipo_usuario` int DEFAULT NULL,
  `id_staff` int DEFAULT NULL,
  `id_perfil` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `password_usuario` varchar(255) DEFAULT NULL,
  `fechacreacion_usuario` datetime DEFAULT NULL,
  `ultimoacceso_usuario` datetime DEFAULT NULL,
  `session_id` text,
  `permisoabrirnegocio_usuario` int DEFAULT '0',
  `pathfoto_usuario` varchar(10000) DEFAULT NULL,
  `vigente_usuario` int DEFAULT '1',
  PRIMARY KEY (`id_usuario`) USING BTREE,
  KEY `fk_relationship_22` (`id_tipo_usuario`) USING BTREE,
  KEY `fk_relationship_3` (`id_staff`) USING BTREE,
  KEY `fk_relationship_4` (`id_cliente`) USING BTREE,
  KEY `fk_relationship_64` (`id_perfil`) USING BTREE,
  CONSTRAINT `fk_relationship_22` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `tipo_usuario` (`id_tipo_usuario`),
  CONSTRAINT `fk_relationship_3` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`),
  CONSTRAINT `fk_relationship_4` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_relationship_64` FOREIGN KEY (`id_perfil`) REFERENCES `perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=23264 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (23242,NULL,108,1,NULL,'$2y$10$9fKk5EBaefjhyOSsTc2obeSVJfsgsj25uBCRUtMkuSa/IWcHZE4L6',NULL,NULL,'5whygv5eueh9',0,NULL,1),(23260,NULL,122,5,NULL,'$2y$10$VIQIeyWNL4quX7ym4Ycd6e9DnJcwvo4kQvz5pMvFGLvC6p3MPyrVe','2024-01-09 21:33:34',NULL,NULL,0,NULL,1),(23261,7,NULL,8,73,'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','2024-03-28 17:00:40',NULL,NULL,0,NULL,1),(23262,NULL,123,NULL,NULL,'5ac0852e770506dcd80f1a36d20ba7878bf82244b836d9324593bd14bc56dcb5','2026-06-11 13:34:58',NULL,NULL,0,NULL,0),(23263,NULL,124,NULL,NULL,'5ac0852e770506dcd80f1a36d20ba7878bf82244b836d9324593bd14bc56dcb5','2026-06-11 13:35:15',NULL,NULL,0,NULL,0);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona`
--

DROP TABLE IF EXISTS `zona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona` (
  `id_zona` int NOT NULL AUTO_INCREMENT,
  `id_sucursal` int DEFAULT NULL,
  `glosa_zona` varchar(255) DEFAULT NULL,
  `color_zona` varchar(255) DEFAULT NULL,
  `orden_zona` int DEFAULT NULL,
  `vigente_zona` int DEFAULT '1',
  PRIMARY KEY (`id_zona`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona`
--

LOCK TABLES `zona` WRITE;
/*!40000 ALTER TABLE `zona` DISABLE KEYS */;
/*!40000 ALTER TABLE `zona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zona_oferta`
--

DROP TABLE IF EXISTS `zona_oferta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zona_oferta` (
  `id_zona_oferta` int NOT NULL AUTO_INCREMENT,
  `nombre_zona_oferta` varchar(255) DEFAULT NULL,
  `cantidahoras_zona_oferta` int DEFAULT NULL,
  `fechainicio_zona_oferta` datetime DEFAULT NULL,
  `fechatermino_zona_oferta` datetime DEFAULT NULL,
  `vigente_zona_oferta` int DEFAULT NULL,
  PRIMARY KEY (`id_zona_oferta`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zona_oferta`
--

LOCK TABLES `zona_oferta` WRITE;
/*!40000 ALTER TABLE `zona_oferta` DISABLE KEYS */;
/*!40000 ALTER TABLE `zona_oferta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'farxcfyq_boticarosa'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-15 16:53:55
