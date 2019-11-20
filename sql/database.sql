-- DATABASE ARXIU

--
-- Table structure for table `boxes`
--

DROP TABLE IF EXISTS `boxes`;
CREATE TABLE `boxes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `choirTypes`
--

DROP TABLE IF EXISTS `choirTypes`;
CREATE TABLE `choirTypes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `composers`
--

DROP TABLE IF EXISTS `composers`;
CREATE TABLE `composers` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `naixement` int(4) DEFAULT NULL,
  `mort` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=471 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `cupboards`
--

DROP TABLE IF EXISTS `cupboards`;
CREATE TABLE `cupboards` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `lyricists`
--

DROP TABLE IF EXISTS `lyricists`;
CREATE TABLE `lyricists` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `naixement` int(4) DEFAULT NULL,
  `mort` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `scores`
--

DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `composerId` int(3) NOT NULL,
  `lyricistId` int(3) DEFAULT NULL,
  `century` varchar(4) DEFAULT NULL,
  `styleId` int(3) DEFAULT NULL,
  `choirTypeId` int(2) DEFAULT NULL,
  `languageId` int(3) DEFAULT NULL,
  `cupboardId` int(2) DEFAULT NULL,
  `boxId` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=709 DEFAULT CHARSET=utf8mb4;
-- TODO: Restore Foreing KEYS


--
-- Table structure for table `styles`
--

DROP TABLE IF EXISTS `styles`;
CREATE TABLE `styles` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL DEFAULT '',
  `firstName` varchar(50) NOT NULL DEFAULT '',
  `lastName` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `lastLoginDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastLoginAddress` varchar(60) NOT NULL DEFAULT '',
  `creationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastChangeDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visits` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

