# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.01 (MySQL 5.5.5-10.4.10-MariaDB-1:10.4.10+maria~bionic)
# Database: api
# Generation Time: 2019-12-04 08:21:04 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table articles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(512) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `created_at` datetime NOT NULL,
  `author_id` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`),
  KEY `articles_users` (`author_id`),
  CONSTRAINT `articles_users` FOREIGN KEY (`author_id`) REFERENCES `users` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;

INSERT INTO `articles` (`id`, `uuid`, `title`, `content`, `created_at`, `author_id`)
VALUES
	(1,'780fdc7e-adeb-4cf5-9521-e53c52557a6d','PHP is great!','It\'s a wonderful language.','2019-12-01 18:55:57','186206f9-1ed6-42cf-ab02-3f4d1226a113');

/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `author_id` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `content` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `commentable_type` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `commentable_id` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `root_type` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `root_id` varchar(64) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_2` (`uuid`),
  KEY `uuid` (`uuid`),
  KEY `author_id` (`author_id`),
  KEY `commentable_id` (`commentable_id`),
  KEY `root_id` (`root_id`),
  CONSTRAINT `comments_users` FOREIGN KEY (`author_id`) REFERENCES `users` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;

INSERT INTO `comments` (`id`, `uuid`, `author_id`, `content`, `created_at`, `commentable_type`, `commentable_id`, `root_type`, `root_id`)
VALUES
	(1,'7c14a5be-55ce-4822-b48d-527e8e967da2','186206f9-1ed6-42cf-ab02-3f4d1226a113','Great article, mate','2019-12-01 19:26:01','article','780fdc7e-adeb-4cf5-9521-e53c52557a6d','article','780fdc7e-adeb-4cf5-9521-e53c52557a6d'),
	(2,'276847ec-a8a8-4781-b957-70b4926867cf','186206f9-1ed6-42cf-ab02-3f4d1226a113','Nah, not my thing','2019-12-01 19:35:15','comment','7c14a5be-55ce-4822-b48d-527e8e967da2','article','780fdc7e-adeb-4cf5-9521-e53c52557a6d'),
	(3,'4159e08d-e7b6-40af-97ef-9fd593c8944a','186206f9-1ed6-42cf-ab02-3f4d1226a113','These new features in PHP are awesome!','2019-12-03 08:21:00','article','780fdc7e-adeb-4cf5-9521-e53c52557a6d','article','780fdc7e-adeb-4cf5-9521-e53c52557a6d'),
	(4,'3d5ba900-acbe-49b4-956b-7becfff70d87','186206f9-1ed6-42cf-ab02-3f4d1226a113','I disagree','2019-12-03 19:04:00','comment','276847ec-a8a8-4781-b957-70b4926867cf','article','780fdc7e-adeb-4cf5-9521-e53c52557a6d');

/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(64) NOT NULL DEFAULT '',
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `uuid_2` (`uuid`),
  KEY `username_2` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `uuid`, `username`, `password`)
VALUES
	(1,'186206f9-1ed6-42cf-ab02-3f4d1226a113','tijmen','$argon2id$v=19$m=65536,t=4,p=1$ZlVLS2VONlZvaDZ6amN1Uw$ZxDCXGdZ2djLQ3T7oKnxbRELtXzhc4Gk8++/5ji2DIs');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
