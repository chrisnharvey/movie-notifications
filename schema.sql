# ************************************************************
# Sequel Pro SQL dump
# Version 4703
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.42)
# Database: notifs
# Generation Time: 2016-05-01 14:50:03 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ci_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table collections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `collections`;

CREATE TABLE `collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table collections_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `collections_meta`;

CREATE TABLE `collections_meta` (
  `collection_id` int(11) unsigned NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text,
  KEY `collectionsmeta_person_id` (`collection_id`),
  CONSTRAINT `collectionsmeta_person_id` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table countries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT '',
  `short` char(2) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`country_id`),
  UNIQUE KEY `short_UNIQUE` (`short`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`country_id`, `name`, `short`, `enabled`)
VALUES
	(1,'Afghanistan','AF',0),
	(2,'Albania','AL',0),
	(3,'Algeria','DZ',0),
	(4,'American Samoa','AS',0),
	(5,'Andorra','AD',0),
	(6,'Angola','AO',0),
	(7,'Anguilla','AI',0),
	(8,'Antarctica','AQ',0),
	(9,'Antigua and Barbuda','AG',0),
	(10,'Argentina','AR',0),
	(11,'Armenia','AM',0),
	(12,'Aruba','AW',0),
	(13,'Australia','AU',0),
	(14,'Austria','AT',0),
	(15,'Azerbaijan','AZ',0),
	(16,'Bahamas','BS',0),
	(17,'Bahrain','BH',0),
	(18,'Bangladesh','BD',0),
	(19,'Barbados','BB',0),
	(20,'Belarus','BY',0),
	(21,'Belgium','BE',0),
	(22,'Belize','BZ',0),
	(23,'Benin','BJ',0),
	(24,'Bermuda','BM',0),
	(25,'Bhutan','BT',0),
	(26,'Bolivia','BO',0),
	(27,'Bosnia and Herzegovina','BA',0),
	(28,'Botswana','BW',0),
	(29,'Bouvet Island','BV',0),
	(30,'Brazil','BR',0),
	(31,'British Indian Ocean Territory','IO',0),
	(32,'Brunei Darussalam','BN',0),
	(33,'Bulgaria','BG',0),
	(34,'Burkina Faso','BF',0),
	(35,'Burundi','BI',0),
	(36,'Cambodia','KH',0),
	(37,'Cameroon','CM',0),
	(38,'Canada','CA',0),
	(39,'Cape Verde','CV',0),
	(40,'Cayman Islands','KY',0),
	(41,'Central African Republic','CF',0),
	(42,'Chad','TD',0),
	(43,'Chile','CL',0),
	(44,'China','CN',0),
	(45,'Christmas Island','CX',0),
	(46,'Cocos (Keeling) Islands','CC',0),
	(47,'Colombia','CO',0),
	(48,'Comoros','KM',0),
	(49,'Congo','CG',0),
	(50,'Congo, the Democratic Republic of the','CD',0),
	(51,'Cook Islands','CK',0),
	(52,'Costa Rica','CR',0),
	(53,'Cote D\'Ivoire','CI',0),
	(54,'Croatia','HR',0),
	(55,'Cuba','CU',0),
	(56,'Cyprus','CY',0),
	(57,'Czech Republic','CZ',0),
	(58,'Denmark','DK',0),
	(59,'Djibouti','DJ',0),
	(60,'Dominica','DM',0),
	(61,'Dominican Republic','DO',0),
	(62,'Ecuador','EC',0),
	(63,'Egypt','EG',0),
	(64,'El Salvador','SV',0),
	(65,'Equatorial Guinea','GQ',0),
	(66,'Eritrea','ER',0),
	(67,'Estonia','EE',0),
	(68,'Ethiopia','ET',0),
	(69,'Falkland Islands (Malvinas)','FK',0),
	(70,'Faroe Islands','FO',0),
	(71,'Fiji','FJ',0),
	(72,'Finland','FI',0),
	(73,'France','FR',0),
	(74,'French Guiana','GF',0),
	(75,'French Polynesia','PF',0),
	(76,'French Southern Territories','TF',0),
	(77,'Gabon','GA',0),
	(78,'Gambia','GM',0),
	(79,'Georgia','GE',0),
	(80,'Germany','DE',0),
	(81,'Ghana','GH',0),
	(82,'Gibraltar','GI',0),
	(83,'Greece','GR',0),
	(84,'Greenland','GL',0),
	(85,'Grenada','GD',0),
	(86,'Guadeloupe','GP',0),
	(87,'Guam','GU',0),
	(88,'Guatemala','GT',0),
	(89,'Guinea','GN',0),
	(90,'Guinea-Bissau','GW',0),
	(91,'Guyana','GY',0),
	(92,'Haiti','HT',0),
	(93,'Heard Island and Mcdonald Islands','HM',0),
	(94,'Holy See (Vatican City State)','VA',0),
	(95,'Honduras','HN',0),
	(96,'Hong Kong','HK',0),
	(97,'Hungary','HU',0),
	(98,'Iceland','IS',0),
	(99,'India','IN',0),
	(100,'Indonesia','ID',0),
	(101,'Iran, Islamic Republic of','IR',0),
	(102,'Iraq','IQ',0),
	(103,'Ireland','IE',0),
	(104,'Israel','IL',0),
	(105,'Italy','IT',0),
	(106,'Jamaica','JM',0),
	(107,'Japan','JP',0),
	(108,'Jordan','JO',0),
	(109,'Kazakhstan','KZ',0),
	(110,'Kenya','KE',0),
	(111,'Kiribati','KI',0),
	(112,'Korea, Democratic People\'s Republic of','KP',0),
	(113,'Korea, Republic of','KR',0),
	(114,'Kuwait','KW',0),
	(115,'Kyrgyzstan','KG',0),
	(116,'Lao People\'s Democratic Republic','LA',0),
	(117,'Latvia','LV',0),
	(118,'Lebanon','LB',0),
	(119,'Lesotho','LS',0),
	(120,'Liberia','LR',0),
	(121,'Libyan Arab Jamahiriya','LY',0),
	(122,'Liechtenstein','LI',0),
	(123,'Lithuania','LT',0),
	(124,'Luxembourg','LU',0),
	(125,'Macao','MO',0),
	(126,'Macedonia, the Former Yugoslav Republic of','MK',0),
	(127,'Madagascar','MG',0),
	(128,'Malawi','MW',0),
	(129,'Malaysia','MY',0),
	(130,'Maldives','MV',0),
	(131,'Mali','ML',0),
	(132,'Malta','MT',0),
	(133,'Marshall Islands','MH',0),
	(134,'Martinique','MQ',0),
	(135,'Mauritania','MR',0),
	(136,'Mauritius','MU',0),
	(137,'Mayotte','YT',0),
	(138,'Mexico','MX',0),
	(139,'Micronesia, Federated States of','FM',0),
	(140,'Moldova, Republic of','MD',0),
	(141,'Monaco','MC',0),
	(142,'Mongolia','MN',0),
	(143,'Montserrat','MS',0),
	(144,'Morocco','MA',0),
	(145,'Mozambique','MZ',0),
	(146,'Myanmar','MM',0),
	(147,'Namibia','NA',0),
	(148,'Nauru','NR',0),
	(149,'Nepal','NP',0),
	(150,'Netherlands','NL',0),
	(151,'Netherlands Antilles','AN',0),
	(152,'New Caledonia','NC',0),
	(153,'New Zealand','NZ',0),
	(154,'Nicaragua','NI',0),
	(155,'Niger','NE',0),
	(156,'Nigeria','NG',0),
	(157,'Niue','NU',0),
	(158,'Norfolk Island','NF',0),
	(159,'Northern Mariana Islands','MP',0),
	(160,'Norway','NO',0),
	(161,'Oman','OM',0),
	(162,'Pakistan','PK',0),
	(163,'Palau','PW',0),
	(164,'Palestinian Territory, Occupied','PS',0),
	(165,'Panama','PA',0),
	(166,'Papua New Guinea','PG',0),
	(167,'Paraguay','PY',0),
	(168,'Peru','PE',0),
	(169,'Philippines','PH',0),
	(170,'Pitcairn','PN',0),
	(171,'Poland','PL',0),
	(172,'Portugal','PT',0),
	(173,'Puerto Rico','PR',0),
	(174,'Qatar','QA',0),
	(175,'Reunion','RE',0),
	(176,'Romania','RO',0),
	(177,'Russian Federation','RU',0),
	(178,'Rwanda','RW',0),
	(179,'Saint Helena','SH',0),
	(180,'Saint Kitts and Nevis','KN',0),
	(181,'Saint Lucia','LC',0),
	(182,'Saint Pierre and Miquelon','PM',0),
	(183,'Saint Vincent and the Grenadines','VC',0),
	(184,'Samoa','WS',0),
	(185,'San Marino','SM',0),
	(186,'Sao Tome and Principe','ST',0),
	(187,'Saudi Arabia','SA',0),
	(188,'Senegal','SN',0),
	(189,'Serbia and Montenegro','CS',0),
	(190,'Seychelles','SC',0),
	(191,'Sierra Leone','SL',0),
	(192,'Singapore','SG',0),
	(193,'Slovakia','SK',0),
	(194,'Slovenia','SI',0),
	(195,'Solomon Islands','SB',0),
	(196,'Somalia','SO',0),
	(197,'South Africa','ZA',0),
	(198,'South Georgia and the South Sandwich Islands','GS',0),
	(199,'Spain','ES',0),
	(200,'Sri Lanka','LK',0),
	(201,'Sudan','SD',0),
	(202,'Suriname','SR',0),
	(203,'Svalbard and Jan Mayen','SJ',0),
	(204,'Swaziland','SZ',0),
	(205,'Sweden','SE',0),
	(206,'Switzerland','CH',0),
	(207,'Syrian Arab Republic','SY',0),
	(208,'Taiwan, Province of China','TW',0),
	(209,'Tajikistan','TJ',0),
	(210,'Tanzania, United Republic of','TZ',0),
	(211,'Thailand','TH',0),
	(212,'Timor-Leste','TL',0),
	(213,'Togo','TG',0),
	(214,'Tokelau','TK',0),
	(215,'Tonga','TO',0),
	(216,'Trinidad and Tobago','TT',0),
	(217,'Tunisia','TN',0),
	(218,'Turkey','TR',0),
	(219,'Turkmenistan','TM',0),
	(220,'Turks and Caicos Islands','TC',0),
	(221,'Tuvalu','TV',0),
	(222,'Uganda','UG',0),
	(223,'Ukraine','UA',0),
	(224,'United Arab Emirates','AE',0),
	(225,'United Kingdom','GB',1),
	(226,'United States','US',1),
	(227,'United States Minor Outlying Islands','UM',0),
	(228,'Uruguay','UY',0),
	(229,'Uzbekistan','UZ',0),
	(230,'Vanuatu','VU',0),
	(231,'Venezuela','VE',0),
	(232,'Viet Nam','VN',0),
	(233,'Virgin Islands, British','VG',0),
	(234,'Virgin Islands, U.s.','VI',0),
	(235,'Wallis and Futuna','WF',0),
	(236,'Western Sahara','EH',0),
	(237,'Yemen','YE',0),
	(238,'Zambia','ZM',0),
	(239,'Zimbabwe','ZW',0);

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meta`;

CREATE TABLE `meta` (
  `key` varchar(255) NOT NULL DEFAULT '',
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table movies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `movies`;

CREATE TABLE `movies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table movies_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `movies_meta`;

CREATE TABLE `movies_meta` (
  `movie_id` int(11) unsigned NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text,
  KEY `moviesmeta_movie_id` (`movie_id`),
  CONSTRAINT `moviesmeta_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `notify_id` int(11) unsigned DEFAULT NULL,
  `notification` text NOT NULL,
  `sent_via` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `notifications_user_id` (`user_id`),
  CONSTRAINT `notifications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table notify
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notify`;

CREATE TABLE `notify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `movie_id` int(11) unsigned NOT NULL,
  `type` enum('Theaters','DVD') NOT NULL DEFAULT 'Theaters',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`user_id`,`movie_id`,`type`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `notify_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notify_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table people
# ------------------------------------------------------------

DROP TABLE IF EXISTS `people`;

CREATE TABLE `people` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table people_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `people_meta`;

CREATE TABLE `people_meta` (
  `person_id` int(11) unsigned NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text,
  KEY `peoplemeta_person_id` (`person_id`),
  CONSTRAINT `peoplemeta_person_id` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `movie_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `rating` tinyint(2) unsigned NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `ratings_movie_id` (`movie_id`),
  KEY `ratings_user_id` (`user_id`),
  CONSTRAINT `ratings_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ratings_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table releases
# ------------------------------------------------------------

DROP TABLE IF EXISTS `releases`;

CREATE TABLE `releases` (
  `movie_id` int(11) unsigned NOT NULL,
  `country_id` int(11) unsigned NOT NULL DEFAULT '226',
  `type` enum('Theaters','DVD') NOT NULL DEFAULT 'Theaters',
  `date` date NOT NULL,
  UNIQUE KEY `movie_id` (`movie_id`,`country_id`,`type`),
  KEY `release_country_id` (`country_id`),
  CONSTRAINT `release_country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `release_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table sms_sent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sms_sent`;

CREATE TABLE `sms_sent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `number` varchar(11) NOT NULL,
  `message` varchar(160) NOT NULL DEFAULT '',
  `sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sms_sent_user_id` (`user_id`),
  CONSTRAINT `sms_sent_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_autologin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_autologin`;

CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table user_profiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_profiles`;

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(256) NOT NULL DEFAULT '',
  `country` int(11) unsigned NOT NULL DEFAULT '226',
  `verify_hash` char(32) DEFAULT '',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `join_ip` varchar(15) NOT NULL DEFAULT '',
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `lift_suspension` timestamp NULL DEFAULT NULL,
  `deactivated` tinyint(1) NOT NULL DEFAULT '0',
  `deactivation_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country` (`country`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`country`) REFERENCES `countries` (`country_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_meta`;

CREATE TABLE `users_meta` (
  `user_id` int(11) unsigned NOT NULL,
  `key` varchar(255) NOT NULL DEFAULT '',
  `value` text,
  KEY `users_meta_user_id` (`user_id`),
  CONSTRAINT `users_meta_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
