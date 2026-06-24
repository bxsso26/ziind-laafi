-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 24 juin 2026 à 08:44
-- Version du serveur :  8.0.21
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u_auben_immo`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `property_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_id_property_id_unique` (`user_id`,`property_id`),
  KEY `favorites_property_id_foreign` (`property_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_17_222828_create_properties_table', 1),
(5, '2026_06_19_115705_create_visit_requests_table', 1),
(6, '2026_06_19_123026_add_user_id_to_properties_table', 1),
(7, '2026_06_20_142556_add_agent_id_to_users_table', 1),
(8, '2026_06_23_211139_add_nullable_visit_date_to_visit_requests_table', 1),
(9, '2026_06_23_222701_create_favorites_table', 1),
(10, '2026_06_23_223908_fix_favorites_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `property_usage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contract_option` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` decimal(10,2) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `photo_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Publiée',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `properties_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `properties`
--

INSERT INTO `properties` (`id`, `user_id`, `type`, `property_usage`, `contract_option`, `zone`, `size`, `price`, `description`, `photo_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Villa', 'résidence', 'Vente', 'Ouaga 2000', '450.00', '75000000.00', 'Superbe villa F4 moderne avec piscine et jardin.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(2, 3, 'Appartement', 'résidence', 'Location', 'Somgandé', '120.00', '250000.00', 'Appartement de standing comprenant un salon lumineux, deux chambres climatisées et une cuisine équipée.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(3, 3, 'Terrain', 'commerce', 'Vente', 'Saaba', '300.00', '12000000.00', 'Parcelle d\'angle idéale pour investissement commercial.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(4, 3, 'Bureau', 'commerce', 'Location', 'Koulouba', '85.00', '450000.00', 'Local professionnel idéal pour cabinet de conseil ou startup.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(5, 3, 'Appartement', 'résidence', 'Location', 'Patte d\'Oie', '150.00', '350000.00', 'Bel appartement F3 meublé, proche de toutes commodités.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(6, 3, 'Terrain', 'résidence', 'Vente', 'Zinarié', '500.00', '4500000.00', 'Grande parcelle viabilisée dans une zone résidentielle en plein essor.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(7, 3, 'Villa', 'résidence', 'Vente', 'Zone du Bois', '600.00', '140000000.00', 'Propriété d\'exception avec piscine et grand jardin arboré.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(8, 3, 'Villa', 'résidence', 'Location', 'Bobo - Tounouma', '350.00', '180000.00', 'Maison basse F4 spacieuse avec grande terrasse et cour ombragée.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(9, 3, 'Immeuble', 'bureau', 'Vente', 'Dassasgho', '400.00', '280000000.00', 'Immeuble R+2 comprenant 6 appartements déjà loués.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(10, 3, 'Appartement', 'résidence', 'Location', 'Karpala', '90.00', '110000.00', 'Mini-villa F2 récent, entièrement carrelé.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(11, 3, 'Magasin', 'commerce', 'Location', 'Bobo - Sya', '65.00', '150000.00', 'Boutique idéalement située sur un axe principal très passant.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(12, 3, 'Terrain', 'résidence', 'Vente', 'Tanghin', '240.00', '8500000.00', 'Parcelle clôturée prête pour construction.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(13, 3, 'Villa', 'résidence', 'Location', 'Ouaga 2000', '380.00', '600000.00', 'Villa F5 de standing à louer, salon spacieux.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(14, 3, 'Bureau', 'bureau', 'Location', 'Gounghin', '110.00', '300000.00', 'Espace de bureaux cloisonné avec salle de réunion.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(15, 3, 'Villa', 'résidence', 'Vente', 'Loumbila', '500.00', '42000000.00', 'Jolie villa de vacances F3 non loin de l\'échangeur.', NULL, 'publiée', '2026-06-23 23:15:58', '2026-06-23 23:15:58'),
(16, 3, 'Villa', 'résidence', 'Vente', 'Ouaga 2000', '450.00', '75000000.00', 'Superbe villa F4 moderne avec piscine et jardin.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(17, 3, 'Appartement', 'résidence', 'Location', 'Somgandé', '120.00', '250000.00', 'Appartement de standing comprenant un salon lumineux, deux chambres climatisées et une cuisine équipée.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(18, 3, 'Terrain', 'commerce', 'Vente', 'Saaba', '300.00', '12000000.00', 'Parcelle d\'angle idéale pour investissement commercial.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(19, 3, 'Bureau', 'commerce', 'Location', 'Koulouba', '85.00', '450000.00', 'Local professionnel idéal pour cabinet de conseil ou startup.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(20, 3, 'Appartement', 'résidence', 'Location', 'Patte d\'Oie', '150.00', '350000.00', 'Bel appartement F3 meublé, proche de toutes commodités.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(21, 3, 'Terrain', 'résidence', 'Vente', 'Zinarié', '500.00', '4500000.00', 'Grande parcelle viabilisée dans une zone résidentielle en plein essor.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(22, 3, 'Villa', 'résidence', 'Vente', 'Zone du Bois', '600.00', '140000000.00', 'Propriété d\'exception avec piscine et grand jardin arboré.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(23, 3, 'Villa', 'résidence', 'Location', 'Bobo - Tounouma', '350.00', '180000.00', 'Maison basse F4 spacieuse avec grande terrasse et cour ombragée.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(24, 3, 'Immeuble', 'bureau', 'Vente', 'Dassasgho', '400.00', '280000000.00', 'Immeuble R+2 comprenant 6 appartements déjà loués.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(25, 3, 'Appartement', 'résidence', 'Location', 'Karpala', '90.00', '110000.00', 'Mini-villa F2 récent, entièrement carrelé.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(26, 3, 'Magasin', 'commerce', 'Location', 'Bobo - Sya', '65.00', '150000.00', 'Boutique idéalement située sur un axe principal très passant.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(27, 3, 'Terrain', 'résidence', 'Vente', 'Tanghin', '240.00', '8500000.00', 'Parcelle clôturée prête pour construction.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(28, 3, 'Villa', 'résidence', 'Location', 'Ouaga 2000', '380.00', '600000.00', 'Villa F5 de standing à louer, salon spacieux.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(29, 3, 'Bureau', 'bureau', 'Location', 'Gounghin', '110.00', '300000.00', 'Espace de bureaux cloisonné avec salle de réunion.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56'),
(30, 3, 'Villa', 'résidence', 'Vente', 'Loumbila', '500.00', '42000000.00', 'Jolie villa de vacances F3 non loin de l\'échangeur.', NULL, 'publiée', '2026-06-23 23:17:56', '2026-06-23 23:17:56');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ZQEs8DmSlVfgBoXKQbfo81dVsm79C2iKBxQIeSkw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eyJfdG9rZW4iOiJYZUFsSE9HalFmQjF5WjBQQ1lEY2hYcEFRcGFLdmY2Smp0akhmVmhwIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Byb3BlcnRpZXMiLCJyb3V0ZSI6InByb3BlcnRpZXMuaW5kZXgifX0=', 1782256711),
('I3bL6czvNC2HIwYHFg8vz9Wi8gZMnnaTXeq7egb5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'eyJfdG9rZW4iOiI3RXVFckc2bXRReEpxREVKMllUQjFJNEI5RWFYWTBnQ2QxQ0tDcmtoIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1782261556);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('client','bailleur','agent','manager') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agent_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_agent_id_foreign` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `telephone`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `agent_id`) VALUES
(1, 'Manager Principal', 'manager@ziindlaafi.com', '70000000', 'manager', NULL, '$2y$12$BccPMoxR7XNFAx.Op07X1O.yR6lv4yM9YBNyDV9PSyA8K.aQ3VO2e', NULL, '2026-06-23 23:15:57', '2026-06-23 23:17:55', NULL),
(2, 'Agent Immobilier', 'agent@ziindlaafi.com', '71000000', 'agent', NULL, '$2y$12$Zar7jJp.hwBigNFmETzgXe6nlOMkaUFnBS/RjxWVSrmbqgGQaLnea', NULL, '2026-06-23 23:15:57', '2026-06-23 23:17:56', NULL),
(3, 'Bailleur Test', 'bailleur@ziindlaafi.com', '72000000', 'bailleur', NULL, '$2y$12$.I0iDFpbFgxCjzlcaBjULutzJksX/nTorjuh./JFvdVm25ew0kO3i', NULL, '2026-06-23 23:15:57', '2026-06-23 23:17:56', NULL),
(4, 'Client Test', 'client@ziindlaafi.com', '73000000', 'client', NULL, '$2y$12$wsA2Y722U591eNO4HpVgcePE6q5dks9srGhnU8PsYFD.MLLBRdBv6', NULL, '2026-06-23 23:15:58', '2026-06-23 23:17:56', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `visit_requests`
--

DROP TABLE IF EXISTS `visit_requests`;
CREATE TABLE IF NOT EXISTS `visit_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `property_id` bigint UNSIGNED NOT NULL,
  `visit_date` date DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visit_requests_user_id_foreign` (`user_id`),
  KEY `visit_requests_property_id_foreign` (`property_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
