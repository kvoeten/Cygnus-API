-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.26 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table cygnus.accounts
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `nAccountID` int(11) unsigned NOT NULL,
  `nNexonCash` int(11) unsigned NOT NULL DEFAULT '0',
  `nMaplePoint` int(11) unsigned NOT NULL DEFAULT '0',
  `nMileage` int(11) unsigned NOT NULL DEFAULT '0',
  `sIP` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `sSecondPW` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nGradeCode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `nState` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `nGender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `nBanned` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `pBanDate` timestamp NOT NULL DEFAULT '1971-01-01 00:00:01',
  `pCreateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pLastLoadDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pBirthDate` timestamp NOT NULL DEFAULT '1971-01-01 00:00:01',
  `nLastWorldID` tinyint(2) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`nAccountID`),
  UNIQUE KEY `nAccountID_UNIQUE` (`nAccountID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.activations
DROP TABLE IF EXISTS `activations`;
CREATE TABLE IF NOT EXISTS `activations` (
  `id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.avatar
DROP TABLE IF EXISTS `avatar`;
CREATE TABLE IF NOT EXISTS `avatar` (
  `dwCharacterID` int(11) NOT NULL,
  `nAccountID` int(11) NOT NULL,
  `nWorld` int(11) NOT NULL,
  `nOverallRank` int(11) NOT NULL,
  `nOverallRankMove` int(11) NOT NULL,
  `nRank` int(11) NOT NULL,
  `nRankMove` int(11) NOT NULL,
  `nLevel` int(11) NOT NULL,
  `nJob` int(11) NOT NULL,
  `nExp64` bigint(20) NOT NULL,
  `nPop` int(11) NOT NULL,
  `nGender` int(11) NOT NULL,
  `nSkin` int(11) NOT NULL,
  `nHair` int(11) NOT NULL,
  `nFace` int(11) NOT NULL,
  `sCharacterName` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.avatarequip
DROP TABLE IF EXISTS `avatarequip`;
CREATE TABLE IF NOT EXISTS `avatarequip` (
  `dwCharacterID` int(11) NOT NULL,
  `nPos` tinyint(4) NOT NULL,
  `nItemID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.banlog
DROP TABLE IF EXISTS `banlog`;
CREATE TABLE IF NOT EXISTS `banlog` (
  `nType` int(11) NOT NULL,
  `sReason` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nDuration` bigint(20) NOT NULL,
  `nAccountID` int(11) NOT NULL,
  `sIP` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sMac` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sHWID` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.news
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.oauth_access_tokens
DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.oauth_auth_codes
DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.oauth_clients
DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.oauth_personal_access_clients
DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.oauth_refresh_tokens
DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.servers
DROP TABLE IF EXISTS `servers`;
CREATE TABLE IF NOT EXISTS `servers` (
  `name` varchar(250) NOT NULL,
  `ip` varchar(250) NOT NULL,
  `users` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `access_level` tinyint(11) NOT NULL DEFAULT '0',
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table cygnus.wz
DROP TABLE IF EXISTS `wz`;
CREATE TABLE IF NOT EXISTS `wz` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `type` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
