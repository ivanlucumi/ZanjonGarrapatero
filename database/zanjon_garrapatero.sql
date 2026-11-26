-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: zanjon_garrapatero
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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asamblea',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `status` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Convocatoria Abierta',
  `created_by` bigint unsigned DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activities_slug_unique` (`slug`),
  KEY `activities_created_by_foreign` (`created_by`),
  KEY `activities_category_index` (`category`),
  KEY `activities_is_visible_index` (`is_visible`),
  CONSTRAINT `activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'Gran Asamblea General Extraordinaria de Comuneros','gran-asamblea-extraordinaria-censo-digital','Asamblea','Socialización plenaria de los módulos de software del Consejo Comunitario, validación de estatutos internos y ruta metodológica para la titulación colectiva.','Salón Comunitario El Palmar','2026-11-27 09:00:00','programada',1,1,'2026-11-19 05:37:02','2026-11-19 05:37:02',NULL),(2,'Recorrido de Protección y Cuidado Ambiental en Cuenca Hídrica','recorrido-ambiental-cuenca-san-francisco','Minga','Jornada de siembra comunitaria de especies nativas, verificación de linderos ancestrales y limpieza de afluentes.','Bajo San Francisco y Santa Lucía','2026-12-04 07:30:00','programada',1,1,'2026-11-19 05:37:02','2026-11-19 05:37:02',NULL);
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_role` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `entity_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `properties` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  KEY `audit_logs_action_index` (`action`),
  KEY `audit_logs_entity_type_index` (`entity_type`),
  KEY `audit_logs_entity_id_index` (`entity_id`),
  KEY `audit_logs_created_at_index` (`created_at`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,'Gobernador Mayor Territorial','Super Administrador','inicializacion_plataforma','System',1,'Portal Comunitario Garrapatero','Inicialización exitosa del sistema administrativo, auditoría y control de contenidos.','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/130.0','{\"version\": \"1.0.0\", \"environment\": \"production\"}','2026-11-19 05:37:03');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `board_members`
--

DROP TABLE IF EXISTS `board_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `board_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `committee` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Junta de Gobierno',
  `vereda` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_url` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `board_members_created_by_foreign` (`created_by`),
  KEY `board_members_is_visible_sort_order_index` (`is_visible`,`sort_order`),
  CONSTRAINT `board_members_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `board_members`
--

LOCK TABLES `board_members` WRITE;
/*!40000 ALTER TABLE `board_members` DISABLE KEYS */;
INSERT INTO `board_members` VALUES (1,'Eustorgio Balanta Lucumí','Representante Legal y Gobernador Mayor','Junta de Gobierno','Zanjón de Garrapatero','Líder histórico con más de 25 años en la defensa de los derechos territoriales, la gobernanza autónoma y la titulación colectiva.','3128901234','gobernador@zanjon.gov.co',NULL,1,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(2,'Rosa Elena Carabalí Mina','Coordinadora de Tierras y Territorio','Tierras y Ambiente','San Antonio','Ingeniera comunitaria especializada en delimitación participativa, cartografía social y monitoreo de microcuencas.','3147890123','tierras@zanjon.gov.co',NULL,2,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(3,'Marcos Aurelio Larrahondo','Coordinador General de Guardia Cimarrona','Guardia Cimarrona','Dominguillo','Guardián del orden y la convivencia comunitaria, custodio de caminos ancestrales y la protección de linderos colectivos.','3156789012','guardia@zanjon.gov.co',NULL,3,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(4,'Dora Inés Balanta Viáfara','Sabedora Mayor y Coordinadora de Género','Comité de Mujeres','La Toma','Partera tradicional afrodescendiente, formadora de nuevas generaciones y custodia de la memoria oral y soberanía alimentaria.','3114567890','mujeres@zanjon.gov.co',NULL,4,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02');
/*!40000 ALTER TABLE `board_members` ENABLE KEYS */;
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
-- Table structure for table `carousel_slides`
--

DROP TABLE IF EXISTS `carousel_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carousel_slides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `badge_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `media_url` text COLLATE utf8mb4_unicode_ci,
  `poster_url` text COLLATE utf8mb4_unicode_ci,
  `projection_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carousel_slides_created_by_foreign` (`created_by`),
  KEY `carousel_slides_is_visible_sort_order_index` (`is_visible`,`sort_order`),
  CONSTRAINT `carousel_slides_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carousel_slides`
--

LOCK TABLES `carousel_slides` WRITE;
/*!40000 ALTER TABLE `carousel_slides` DISABLE KEYS */;
INSERT INTO `carousel_slides` VALUES (1,'Territorio de Paz, Memoria Ancestral y Autonomía Étnica','Consejo Comunitario Zanjón de Garrapatero · Norte del Cauca · Gobernanza colectiva, protección del agua y afirmación cultural.','Consejo Comunitario Mayor','image','https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=85',NULL,'Panorámica Territorial del Zanjón','Explorar Territorio','/territorio','Nuestra Historia','/nosotros',1,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(2,'Saberes Ancestrales, Partería y Soberanía Alimentaria','Comité de Sabedoras y Mujeres Negras · Nuestras mayoras transmiten la memoria curativa de las plantas y los cantos ancestrales.','Patrimonio Vivo','video','https://www.youtube.com/embed/dQw4w9WgXcQ','https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=85','Cápsula de Saberes Tradicionales','Ver Videoteca','/videos','Conoce Nuestra Identidad','/nosotros',2,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(3,'Censo Territorial y Titulación Colectiva de Tierras','Gobernanza Propia · Ley 70 de 1993 · Fortalecemos el registro unificado de familias, parcelas y reservas naturales comunitarias.','Gestión Comunitaria','none',NULL,NULL,NULL,'Llenar Censo','/login','Ver Actividades','/actividades',3,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(4,'Guardia Cimarrona: Custodios de la Vida y la Tierra','Justicia Propia, Convivencia y Protección Integral · Hombres y mujeres que caminan el territorio garantizando la paz.','Defensa Colectiva','video','https://www.youtube.com/embed/dQw4w9WgXcQ','https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=85','Guardia Cimarrona en Recorrido','Conoce la Guardia','/nosotros','Canal de Contacto','/contacto',4,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02');
/*!40000 ALTER TABLE `carousel_slides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `territory` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `attended_by` bigint unsigned DEFAULT NULL,
  `attended_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_attended_by_foreign` (`attended_by`),
  KEY `contact_messages_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `contact_messages_attended_by_foreign` FOREIGN KEY (`attended_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Carlos Alberto Mina','carlos.mina@example.com','3101234567','El Palmar','Buenas tardes autoridades del Consejo, solicito orientación para expedir la constancia de pertenencia territorial requerida para un trámite de educación superior.','pendiente','Recibido en web. Se contactará para solicitar copia del documento de identidad.',NULL,NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64)',NULL,'2026-11-19 05:37:03','2026-11-19 05:37:03');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normatividad',
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint unsigned NOT NULL DEFAULT '0',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'application/pdf',
  `download_count` int unsigned NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_slug_unique` (`slug`),
  KEY `documents_created_by_foreign` (`created_by`),
  KEY `documents_is_visible_category_index` (`is_visible`,`category`),
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,'Estatuto de Gobierno Propio y Reglamento Interno Territorial','estatuto-gobierno-propio-y-reglamento-interno-territorial','Normatividad y Ley 70','Texto aprobado por la Asamblea General que establece las normas de convivencia, guardia cimarrona y cuidado del territorio colectivo.','documents/estatuto-gobierno-propio-zanjon.pdf','estatuto-gobierno-propio-zanjon.pdf',2450000,'application/pdf',14,1,1,NULL,'2026-11-19 05:37:03','2026-11-19 05:37:03'),(2,'Plan Comunitario de Manejo Ambiental y Protección de Microcuencas','plan-comunitario-de-manejo-ambiental-y-proteccion-de-microcuencas','Planes de Manejo Ambiental','Directrices colectivas para la reforestación de riberas, veda de tala en nacimientos y monitoreo del Río Garrapatero.','documents/plan-ambiental-microcuencas-garrapatero.pdf','plan-ambiental-microcuencas-garrapatero.pdf',3820000,'application/pdf',28,1,1,NULL,'2026-11-19 05:37:03','2026-11-19 05:37:03'),(3,'Formato Único de Caracterización de Núcleo Familiar (Censo Étnico)','formato-unico-de-caracterizacion-de-nucleo-familiar-censo-etnico','Formatos y Trámites','Formato oficial para inscripción en el libro de padrón del Consejo Comunitario de acuerdo a los requisitos de la Ley 70 de 1993.','documents/formato-censo-etnico-garrapatero.pdf','formato-censo-etnico-garrapatero.pdf',850000,'application/pdf',45,1,1,NULL,'2026-11-19 05:37:03','2026-11-19 05:37:03');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
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
-- Table structure for table `login_logs`
--

DROP TABLE IF EXISTS `login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `user_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `login_logs_user_id_foreign` (`user_id`),
  KEY `login_logs_role_index` (`role`),
  KEY `login_logs_login_at_index` (`login_at`),
  CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_logs`
--

LOCK TABLES `login_logs` WRITE;
/*!40000 ALTER TABLE `login_logs` DISABLE KEYS */;
INSERT INTO `login_logs` VALUES (1,1,'Gobernador Mayor Territorial','superadmin@zanjon.gov.co','Super Administrador','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/130.0','2026-11-19 05:00:00');
/*!40000 ALTER TABLE `login_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_items`
--

DROP TABLE IF EXISTS `media_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Territorio',
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_items_slug_unique` (`slug`),
  KEY `media_items_created_by_foreign` (`created_by`),
  KEY `media_items_type_index` (`type`),
  KEY `media_items_category_index` (`category`),
  KEY `media_items_is_visible_index` (`is_visible`),
  CONSTRAINT `media_items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_items`
--

LOCK TABLES `media_items` WRITE;
/*!40000 ALTER TABLE `media_items` DISABLE KEYS */;
INSERT INTO `media_items` VALUES (1,'Montañas y cuencas hídricas del Zanjón de Garrapatero','paisaje-montanas-cuencas-garrapatero','image','Territorio','Territorio ancestral que se cuida, se defiende y se protege.','https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=85',NULL,NULL,1,1,1,'2026-11-19 05:37:02','2026-11-19 05:37:02',NULL),(2,'Zanjón de Garrapatero: Raíces, Resistencia y Autonomía Étnica','video-documental-raices-resistencia','video','Territorio','Documental con entrevistas a mayoras y sabedores de El Palmar y Santa Lucía.','https://www.youtube.com/embed/dQw4w9WgXcQ','https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1400&q=85','12:30 min',1,1,1,'2026-11-19 05:37:02','2026-11-19 05:37:02',NULL);
/*!40000 ALTER TABLE `media_items` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_16_230812_add_role_and_profile_to_users_table',1),(5,'2026_11_16_231031_create_surveys_and_responses_tables',1),(6,'2026_11_16_235524_create_publications_table',1),(7,'2026_11_16_235525_create_activities_table',1),(8,'2026_11_16_235526_create_media_items_table',1),(9,'2026_11_16_235527_create_audit_logs_table',1),(10,'2026_11_16_235528_create_login_logs_table',1),(11,'2026_11_17_002550_create_carousel_slides_table',1),(12,'2026_11_17_002556_create_contact_messages_table',1),(13,'2026_11_17_002601_create_territories_table',1),(14,'2026_11_17_002602_create_board_members_table',1),(15,'2026_11_17_004736_create_documents_table',1),(16,'2026_11_17_004741_add_attachments_to_publications_table',1);
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
-- Table structure for table `publications`
--

DROP TABLE IF EXISTS `publications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Noticia',
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_size` bigint unsigned DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `publications_slug_unique` (`slug`),
  KEY `publications_author_id_foreign` (`author_id`),
  KEY `publications_category_index` (`category`),
  KEY `publications_is_visible_index` (`is_visible`),
  CONSTRAINT `publications_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publications`
--

LOCK TABLES `publications` WRITE;
/*!40000 ALTER TABLE `publications` DISABLE KEYS */;
INSERT INTO `publications` VALUES (1,'Padrón Comunitario 2026: Pasos para registrar a tu núcleo familiar sin intermediarios','padron-comunitario-2026-registro-familiar','Comunicado','Guía completa sobre el censo digital y los requisitos para mantener actualizado el libro de comuneros del Consejo.','El Consejo Comunitario de la Comunidad Negra de Zanjón de Garrapatero abre formalmente la convocatoria para la actualización del padrón poblacional.\n\nEste proceso es fundamental para la defensa del territorio colectivo, la expedición transparente de certificaciones de pertenencia étnica y la postulación a proyectos de vivienda y desarrollo agropecuario bajo la Ley 70 de 1993.\n\nInvitamos a cada jefe de hogar a diligenciar el formulario en línea o acercarse con su cédula a los puntos de atención veredales.','https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=85',NULL,NULL,NULL,1,1,'2026-11-18 05:37:02','2026-11-19 05:37:02','2026-11-19 05:37:02',NULL),(2,'Jornada Comunitaria de Protección y Siembra en Microcuencas Hídricas','jornada-proteccion-microcuencas-garrapatero','Territorio','La Guardia Cimarrona y comités ambientales avanzaron en la reforestación de riberas con más de 400 árboles nativos.','Con una alta participación de jóvenes comuneros de Santa Lucía y El Palmar, se llevó a cabo la primera minga ambiental de la temporada.\n\nEl objetivo central es proteger las fuentes de abastecimiento hídrico del territorio y frenar los procesos de degradación en los linderos ancestrales.\n\nSe acordó mantener un cronograma bimestral de seguimiento y monitoreo con el respaldo de las autoridades tradicionales.','https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=85',NULL,NULL,NULL,2,1,'2026-11-16 05:37:02','2026-11-19 05:37:02','2026-11-19 05:37:02',NULL),(3,'Taller de Saberes Ancestrales: Memoria y Resistencia en el Cabello Afro','taller-saberes-ancestrales-peinados-afro','Cultura','Encuentro intergeneracional entre mayoras trenzadoras y jóvenes sobre la simbología y los mapas de libertad.','Las sabedoras del Zanjón de Garrapatero compartieron sus técnicas y memorias vivas con las nuevas generaciones.\n\nDurante dos jornadas se explicaron los orígenes de los tejidos como mapas de escape en la época colonial y la trascendencia de mantener viva la estética y dignidad de nuestro pueblo.','https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=85',NULL,NULL,NULL,2,1,'2026-11-14 05:37:02','2026-11-19 05:37:02','2026-11-19 05:37:02',NULL);
/*!40000 ALTER TABLE `publications` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('6CVEGxENI0VLCCMdoDXI7XSBwZwQM5jZwWY3K0M5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJkNGxjRDdnbVRTT0l5ZkhDQ05uSU82dUR6dmdQYk5PcjJYV0l3YTVpIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDkwXC9aYW5qb25HYXJyYXBhdGVyb1wvcHVibGljIiwicm91dGUiOiJpbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1795048784);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survey_responses`
--

DROP TABLE IF EXISTS `survey_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `survey_responses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `answers` json NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `survey_responses_survey_id_user_id_unique` (`survey_id`,`user_id`),
  KEY `survey_responses_user_id_foreign` (`user_id`),
  CONSTRAINT `survey_responses_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE,
  CONSTRAINT `survey_responses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survey_responses`
--

LOCK TABLES `survey_responses` WRITE;
/*!40000 ALTER TABLE `survey_responses` DISABLE KEYS */;
INSERT INTO `survey_responses` VALUES (1,1,4,'{\"acceso_agua\": \"Sí\", \"personas_hogar\": \"5\", \"tenencia_predio\": \"Propiedad colectiva con título ancestral\"}','Requerimos ampliación de la red hacia el sector alto de Dominguillo.','verified','2026-11-19 05:37:02','2026-11-19 05:37:02'),(2,1,5,'{\"acceso_agua\": \"Parcialmente / En proceso\", \"personas_hogar\": \"4\", \"tenencia_predio\": \"Posesión tradicional heredada\"}','Agradecemos el acompañamiento de la Junta de Gobierno.','submitted','2026-11-19 05:37:02','2026-11-19 05:37:02');
/*!40000 ALTER TABLE `survey_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surveys`
--

DROP TABLE IF EXISTS `surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `surveys` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Censo Territorial',
  `target_vereda` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `questions` json DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surveys_slug_unique` (`slug`),
  KEY `surveys_created_by_foreign` (`created_by`),
  CONSTRAINT `surveys_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surveys`
--

LOCK TABLES `surveys` WRITE;
/*!40000 ALTER TABLE `surveys` DISABLE KEYS */;
INSERT INTO `surveys` VALUES (1,'Censo Demográfico y Familiar Territorial 2026','censo-demografico-territorial-2026','Levantamiento de caracterización del núcleo familiar, tenencia del predio y condiciones habitacionales en las veredas del Consejo Comunitario.','Censo Territorial',NULL,'active',1,'[{\"id\": \"personas_hogar\", \"type\": \"number\", \"label\": \"¿Cuántas personas integran permanentemente su hogar en el territorio?\", \"required\": true}, {\"id\": \"tenencia_predio\", \"type\": \"select\", \"label\": \"¿Cuál es la condición de tenencia de la vivienda o predio?\", \"options\": [\"Propiedad colectiva con título ancestral\", \"Posesión tradicional heredada\", \"Arriendo\", \"Otro\"], \"required\": true}, {\"id\": \"acceso_agua\", \"type\": \"radio\", \"label\": \"¿Cuenta su predio con acceso a acueducto veredal permanente?\", \"required\": true}]',1,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(2,'Priorización de Proyectos Productivos Agroecológicos','proyectos-productivos-agroecologia-2026','Consulta comunitaria para definir los rubros productivos priorizados en los convenios de soberanía alimentaria y comercialización asociativa.','Proyectos Productivos',NULL,'active',1,'[{\"id\": \"linea_productiva\", \"type\": \"select\", \"label\": \"¿Cuál es la principal vocación productiva de su familia?\", \"options\": [\"Cacao tradicional bajo sombra\", \"Piscicultura / Tilapia y Cachama\", \"Plátano y frutales amazónicos\", \"Avicultura campesina\", \"Transformación artesanal\"], \"required\": true}, {\"id\": \"interes_asociacion\", \"type\": \"radio\", \"label\": \"¿Tiene interés en integrarse a la red asociativa de comercialización del Consejo?\", \"required\": true}]',2,'2026-11-19 05:37:02','2026-11-19 05:37:02');
/*!40000 ALTER TABLE `surveys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `territories`
--

DROP TABLE IF EXISTS `territories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `territories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sector_badge` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_families` int DEFAULT NULL,
  `watersheds` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_activities` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `territories_slug_unique` (`slug`),
  KEY `territories_created_by_foreign` (`created_by`),
  KEY `territories_is_visible_sort_order_index` (`is_visible`,`sort_order`),
  CONSTRAINT `territories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `territories`
--

LOCK TABLES `territories` WRITE;
/*!40000 ALTER TABLE `territories` DISABLE KEYS */;
INSERT INTO `territories` VALUES (1,'El Palmar','el-palmar','Sector Alto','Cabecera histórica del Consejo y punto de encuentro tradicional de las asambleas generales y comités de sabedores.',120,'Microcuenca El Palmar, Quebrada La Honda','Caficultura, huertas familiares y gobernanza',NULL,1,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(2,'Santa Lucía','santa-lucia','Sector Medio','Zona de alta vocación agrícola, trapiches paneleros comunitarios, caña de azúcar y huertas familiares sostenibles.',85,'Río Garrapatero, Quebrada Seca','Producción panelera tradicional y plátano',NULL,2,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(3,'La Toma','la-toma','Sector Ribereño','Territorio ancestral de minería artesanal en batea, corredor ecológico hídrico y protección comunitaria de fauna nativa.',95,'Río Ovejas, Caño San José','Minería artesanal de batea y pesca ancestral',NULL,3,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02'),(4,'Mazamorrero','mazamorrero','Sector Veredal','Núcleo de tradición oral, medicina botánica ancestral y resguardo de bancos de semillas criollas del territorio.',60,'Quebrada Mazamorrero','Medicina tradicional, cacao y cítricos',NULL,4,1,1,NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02');
/*!40000 ALTER TABLE `territories` ENABLE KEYS */;
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
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `document_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vereda` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Gobernador Mayor Territorial','superadmin@zanjon.gov.co','super_admin','1061728394','3128901234','Zanjón de Garrapatero','2026-11-19 05:36:59','$2y$12$Jd.FCwcp3vtOBDMM5sSiNe5O/paSGBJEURB9NTAmbDN3B5LhhxHYC',NULL,'2026-11-19 05:36:59','2026-11-19 05:36:59'),(2,'Coordinadora de Censo y Territorio','admin@zanjon.gov.co','admin','1061987456','3147890123','San Antonio','2026-11-19 05:37:00','$2y$12$Mz5GM/CelVvHfo6BiCs09OgR8f/.3DIrZGgxXHQZRYyNUAaVPgaku',NULL,'2026-11-19 05:37:00','2026-11-19 05:37:00'),(3,'Amalia Mina Lucumí','usuario@zanjon.gov.co','usuario','34567890','3114567890','Zanjón de Garrapatero','2026-11-19 05:37:01','$2y$12$gQUKAz1wzBjkcCbvG7JuHutXPQWsOdDuVgvQ1XgSbOUU02p4SlOsK',NULL,'2026-11-19 05:37:01','2026-11-19 05:37:01'),(4,'Marcos Balanta Carabalí','marcos.balanta@zanjon.gov.co','usuario','1058923412','3156789012','Dominguillo','2026-11-19 05:37:01','$2y$12$LonQ5MC70VOM6LeliauWEuOhiqtq67vPXtJIWXWyVCuqKhmpg7wKy',NULL,'2026-11-19 05:37:01','2026-11-19 05:37:01'),(5,'Clemencia Larrahondo','clemencia.larrahondo@zanjon.gov.co','usuario','25890123','3109876543','La Toma','2026-11-19 05:37:02','$2y$12$dI3w6a8f.B6Lya7TSAF7yOINyUuTgFHowdoDEWhvUKQJ3C1LZ5.q.',NULL,'2026-11-19 05:37:02','2026-11-19 05:37:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'zanjon_garrapatero'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-18 20:28:39
