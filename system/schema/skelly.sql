-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.16-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table checkmate.about_us
DROP TABLE IF EXISTS `about_us`;
CREATE TABLE IF NOT EXISTS `about_us` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.about_us: ~0 rows (approximately)
/*!40000 ALTER TABLE `about_us` DISABLE KEYS */;
INSERT INTO `about_us` (`id`, `title`, `text`, `image`) VALUES
	(4, 'zdsaasd', '<p>asdsadasd</p>\r\n\r\n<p>asdasd</p>\r\n\r\n<p>sad</p>\r\n\r\n<p>asdsad</p>\r\n', 'Chrysanthemum.jpg');
/*!40000 ALTER TABLE `about_us` ENABLE KEYS */;


-- Dumping structure for table checkmate.backend_users
DROP TABLE IF EXISTS `backend_users`;
CREATE TABLE IF NOT EXISTS `backend_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `salt` int(11) unsigned NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `is_super` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.backend_users: ~0 rows (approximately)
/*!40000 ALTER TABLE `backend_users` DISABLE KEYS */;
INSERT INTO `backend_users` (`id`, `user_name`, `user_pass`, `user_email`, `salt`, `display_name`, `is_super`, `created`, `modified`) VALUES
	(1, 'creative', '44e32df05cb0bc59d45534e152c54e4dcf5cfc1b192fe2d2396971a5c4858b36', 'william@websiteni.com', 1435578356, 'William', 1, '2016-01-11 15:13:56', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `backend_users` ENABLE KEYS */;


-- Dumping structure for table checkmate.blogs
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `pub_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.blogs: ~0 rows (approximately)
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;


-- Dumping structure for table checkmate.check_in_items
DROP TABLE IF EXISTS `check_in_items`;
CREATE TABLE IF NOT EXISTS `check_in_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_rooms_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `tenant_comment` text,
  `lord_comment` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_report_items_report_rooms` (`report_rooms_id`),
  KEY `FK_report_items_items` (`item_id`),
  CONSTRAINT `FK_report_items_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_report_items_report_rooms` FOREIGN KEY (`report_rooms_id`) REFERENCES `check_in_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.check_in_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `check_in_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `check_in_items` ENABLE KEYS */;


-- Dumping structure for table checkmate.check_in_rooms
DROP TABLE IF EXISTS `check_in_rooms`;
CREATE TABLE IF NOT EXISTS `check_in_rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  `clean` int(1) unsigned NOT NULL DEFAULT '0',
  `tenant_comment` text,
  `lord_comment` text,
  PRIMARY KEY (`id`),
  KEY `FK_report_rooms_reports` (`report_id`),
  KEY `FK_report_rooms_rooms` (`room_id`),
  CONSTRAINT `FK_report_rooms_reports` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_report_rooms_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.check_in_rooms: ~0 rows (approximately)
/*!40000 ALTER TABLE `check_in_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `check_in_rooms` ENABLE KEYS */;


-- Dumping structure for table checkmate.check_out_items
DROP TABLE IF EXISTS `check_out_items`;
CREATE TABLE IF NOT EXISTS `check_out_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_rooms_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `tenant_comment` text,
  `lord_comment` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_report_items_report_rooms` (`report_rooms_id`),
  KEY `FK_report_items_items` (`item_id`),
  CONSTRAINT `check_out_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `check_out_items_ibfk_2` FOREIGN KEY (`report_rooms_id`) REFERENCES `check_out_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table checkmate.check_out_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `check_out_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `check_out_items` ENABLE KEYS */;


-- Dumping structure for table checkmate.check_out_rooms
DROP TABLE IF EXISTS `check_out_rooms`;
CREATE TABLE IF NOT EXISTS `check_out_rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  `clean` int(1) unsigned NOT NULL DEFAULT '0',
  `tenant_comment` text,
  `lord_comment` text,
  PRIMARY KEY (`id`),
  KEY `FK_report_rooms_reports` (`report_id`),
  KEY `FK_report_rooms_rooms` (`room_id`),
  CONSTRAINT `check_out_rooms_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `check_out_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table checkmate.check_out_rooms: ~0 rows (approximately)
/*!40000 ALTER TABLE `check_out_rooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `check_out_rooms` ENABLE KEYS */;


-- Dumping structure for table checkmate.contact_us
DROP TABLE IF EXISTS `contact_us`;
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facebook` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `google` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `text` text,
  `location` text NOT NULL,
  `lat` float NOT NULL,
  `lang` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.contact_us: ~0 rows (approximately)
/*!40000 ALTER TABLE `contact_us` DISABLE KEYS */;
INSERT INTO `contact_us` (`id`, `facebook`, `instagram`, `google`, `twitter`, `email`, `phone`, `text`, `location`, `lat`, `lang`) VALUES
	(1, 'sdfdsfsd', 'sdfdsfdsf', '', '', 'w@w.com', '12345678', '<p>sdfdsfdfsdfdf</p>\r\n\r\n<p>sdfsdfd</p>\r\n\r\n<p>fsdfsdfsd</p>\r\n', '<p>sdfdsfsdfsdfs</p>\r\n\r\n<p>sdfsdfsd</p>\r\n', 54.6034, -5.8992);
/*!40000 ALTER TABLE `contact_us` ENABLE KEYS */;


-- Dumping structure for table checkmate.faqs
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.faqs: ~3 rows (approximately)
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` (`id`, `question`, `answer`, `sort`, `is_active`) VALUES
	(2, 'This is a question', 'test', 1, 1),
	(3, 'question two?', 'yo!', 3, 1),
	(4, 'So many questions but no answers?', 'yo', 2, 1);
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;


-- Dumping structure for table checkmate.items
DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.items: ~5 rows (approximately)
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` (`id`, `name`, `is_active`) VALUES
	(2, 'TV', 1),
	(3, 'Chair', 1),
	(4, 'Table', 1),
	(5, 'In active', 0),
	(6, 'Drawer', 1),
	(7, 'Bed', 1);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;


-- Dumping structure for table checkmate.notifications
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_notifications_users` (`user_id`),
  CONSTRAINT `FK_notifications_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.notifications: ~25 rows (approximately)
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` (`id`, `user_id`, `text`, `read`, `created`) VALUES
	(1, 10, 'Administrator has made changes to your property please review at the following link. Link', 0, '2016-09-13 15:43:29'),
	(2, 10, 'Administrator has made changes to your property please review at the following link. Link', 0, '2016-09-13 15:44:44'),
	(3, 10, 'Administrator has made changes to your property please review at the following link. Link', 0, '2016-09-13 15:45:49'),
	(4, 10, 'Administrator has made changes to your property please review at the following link. Link', 0, '2016-09-13 15:46:50'),
	(5, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 15:48:56'),
	(6, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:14:26'),
	(7, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:16:55'),
	(8, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:18:08'),
	(9, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:18:27'),
	(10, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:18:43'),
	(11, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:18:59'),
	(12, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:22:31'),
	(13, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:23:21'),
	(14, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:23:29'),
	(15, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:34:25'),
	(16, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:38:37'),
	(17, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:41:11'),
	(18, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:42:32'),
	(19, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:45:43'),
	(20, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 16:49:16'),
	(21, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 17:01:25'),
	(22, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 17:03:51'),
	(23, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 17:18:01'),
	(24, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 17:19:07'),
	(25, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 17:22:57'),
	(26, 10, 'Administrator has made changes to your property please review at the following link. <a href = "http://checkmate.local/properties/edit/1">Link</a>', 0, '2016-09-13 17:25:37');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;


-- Dumping structure for table checkmate.payments
DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type` int(1) unsigned NOT NULL DEFAULT '0',
  `last_payment` datetime DEFAULT NULL,
  `remaining_credits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_payments_users` (`user_id`),
  CONSTRAINT `FK_payments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.payments: ~1 rows (approximately)
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` (`id`, `user_id`, `type`, `last_payment`, `remaining_credits`) VALUES
	(1, 10, 1, '2016-09-07 10:02:15', 10);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;


-- Dumping structure for table checkmate.prices
DROP TABLE IF EXISTS `prices`;
CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Dumping data for table checkmate.prices: ~0 rows (approximately)
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` (`id`, `title`, `text`, `image`) VALUES
	(4, 'test', '<p>asddsda</p>\r\n\r\n<p>adsadsdsdasdasd</p>\r\n\r\n<p>asdasdasdd</p>\r\n', 'Jellyfish.jpg');
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;


-- Dumping structure for table checkmate.properties
DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `house_number` int(11) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `address_3` varchar(255) DEFAULT NULL,
  `address_4` varchar(255) DEFAULT NULL,
  `postcode` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_properties_users` (`created_by`),
  CONSTRAINT `FK_properties_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.properties: ~0 rows (approximately)
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` (`id`, `created_by`, `title`, `house_number`, `address_1`, `address_2`, `address_3`, `address_4`, `postcode`, `image`, `created`) VALUES
	(1, 10, 'new house', 15, 'Gransha Park', 'Glen Road', 'Belfast', 'Antrim', 'Bt11 8AT', 'Koala.jpg', '2016-09-07 11:26:56'),
	(2, 10, 'new house', 15, 'Gransha Park', 'Glen Road', 'Belfast', 'Antrim', 'Bt11 8AT', 'Hydrangeas.jpg', '2016-09-07 11:26:56');
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;


-- Dumping structure for table checkmate.property_templates
DROP TABLE IF EXISTS `property_templates`;
CREATE TABLE IF NOT EXISTS `property_templates` (
  `property_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  KEY `FK__properties` (`property_id`),
  KEY `FK__templates` (`template_id`),
  CONSTRAINT `FK__properties` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__templates` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.property_templates: ~1 rows (approximately)
/*!40000 ALTER TABLE `property_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_templates` ENABLE KEYS */;


-- Dumping structure for table checkmate.queries
DROP TABLE IF EXISTS `queries`;
CREATE TABLE IF NOT EXISTS `queries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.queries: ~1 rows (approximately)
/*!40000 ALTER TABLE `queries` DISABLE KEYS */;
/*!40000 ALTER TABLE `queries` ENABLE KEYS */;


-- Dumping structure for table checkmate.references
DROP TABLE IF EXISTS `references`;
CREATE TABLE IF NOT EXISTS `references` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_references_users` (`author_id`),
  KEY `FK_references_users_2` (`user_id`),
  CONSTRAINT `FK_references_users` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_references_users_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.references: ~0 rows (approximately)
/*!40000 ALTER TABLE `references` DISABLE KEYS */;
/*!40000 ALTER TABLE `references` ENABLE KEYS */;


-- Dumping structure for table checkmate.reports
DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int(11) unsigned NOT NULL,
  `lord_id` int(11) unsigned NOT NULL,
  `lead_tenant_id` int(11) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL DEFAULT '0',
  `meter_type` int(1) unsigned NOT NULL DEFAULT '0',
  `meter_reading` int(11) unsigned NOT NULL,
  `meter_image` varchar(255) DEFAULT NULL,
  `tenant_agreement` varchar(255) DEFAULT NULL,
  `oil_level` int(11) DEFAULT NULL,
  `keys_acquired` int(1) unsigned NOT NULL DEFAULT '0',
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `tenant_approved_check_in` tinyint(1) NOT NULL DEFAULT '0',
  `lord_approved_check_in` tinyint(1) NOT NULL DEFAULT '0',
  `tenant_approved_check_out` tinyint(1) NOT NULL DEFAULT '0',
  `lord_approved_check_out` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_reports_properties` (`property_id`),
  KEY `FK_reports_users` (`lord_id`),
  KEY `FK_reports_users_2` (`lead_tenant_id`),
  CONSTRAINT `FK_reports_properties` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_reports_users` FOREIGN KEY (`lord_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_reports_users_2` FOREIGN KEY (`lead_tenant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.reports: ~1 rows (approximately)
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` (`id`, `property_id`, `lord_id`, `lead_tenant_id`, `status`, `meter_type`, `meter_reading`, `meter_image`, `tenant_agreement`, `oil_level`, `keys_acquired`, `check_in`, `check_out`, `tenant_approved_check_in`, `lord_approved_check_in`, `tenant_approved_check_out`, `lord_approved_check_out`) VALUES
	(5, 1, 11, 10, 0, 0, 0, NULL, NULL, NULL, 0, '2016-09-12', '2017-09-13', 0, 0, 0, 0);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;


-- Dumping structure for table checkmate.rooms
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `property_template_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_rooms_property_templates` (`property_template_id`),
  CONSTRAINT `FK_rooms_property_templates` FOREIGN KEY (`property_template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.rooms: ~1 rows (approximately)
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` (`id`, `property_template_id`, `name`, `is_active`) VALUES
	(1, NULL, 'Sitting room', 1),
	(2, NULL, 'Bedroom', 1);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;


-- Dumping structure for table checkmate.room_items
DROP TABLE IF EXISTS `room_items`;
CREATE TABLE IF NOT EXISTS `room_items` (
  `room_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  KEY `FK_room_items_rooms` (`room_id`),
  KEY `FK_room_items_items` (`item_id`),
  CONSTRAINT `FK_room_items_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_room_items_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.room_items: ~3 rows (approximately)
/*!40000 ALTER TABLE `room_items` DISABLE KEYS */;
INSERT INTO `room_items` (`room_id`, `item_id`) VALUES
	(1, 4),
	(1, 3),
	(1, 2),
	(2, 7);
/*!40000 ALTER TABLE `room_items` ENABLE KEYS */;


-- Dumping structure for table checkmate.templates
DROP TABLE IF EXISTS `templates`;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_property_templates_users` (`created_by`),
  CONSTRAINT `FK_property_templates_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.templates: ~0 rows (approximately)
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` (`id`, `created_by`, `title`, `description`, `created`) VALUES
	(9, NULL, 'Bedroom Template', 'adlsdmsads\r\ndsa\r\nds\r\nad\r\nsa\r\nd&quot;', '2016-09-09 11:53:12');
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;


-- Dumping structure for table checkmate.template_rooms
DROP TABLE IF EXISTS `template_rooms`;
CREATE TABLE IF NOT EXISTS `template_rooms` (
  `template_id` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  KEY `FK__templates_to_rooms` (`template_id`),
  KEY `FK__rooms_to_templates` (`room_id`),
  CONSTRAINT `FK__rooms_to_templates` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__templates_to_rooms` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.template_rooms: ~4 rows (approximately)
/*!40000 ALTER TABLE `template_rooms` DISABLE KEYS */;
INSERT INTO `template_rooms` (`template_id`, `room_id`) VALUES
	(9, 2),
	(9, 2),
	(9, 1),
	(9, 1);
/*!40000 ALTER TABLE `template_rooms` ENABLE KEYS */;


-- Dumping structure for table checkmate.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(1) unsigned NOT NULL DEFAULT '0',
  `firstname` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `salt` int(11) unsigned NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `logo_image` varchar(255) NOT NULL,
  `email_verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `type`, `firstname`, `surname`, `email`, `salt`, `password`, `contact_num`, `logo_image`, `email_verified`, `is_active`) VALUES
	(10, 1, 'test', 'test', 'test@test.com', 1473162252, '402ec5dafcccc126c3d91d33a48d00c652e601182b3749b09b2db619393828b5', '1234567', 'Lighthouse.jpg', 0, 1),
	(11, 0, 'bob', 'boo', 'boo@bob.com', 1473433040, 'ff1dee94c9fdf9e9f177afb8e3ba37ae54a36542da1102750e00dbd24f096cf5', '1121291290', '', 0, 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Dumping structure for table checkmate.user_reports
DROP TABLE IF EXISTS `user_reports`;
CREATE TABLE IF NOT EXISTS `user_reports` (
  `user_id` int(11) unsigned NOT NULL,
  `report_id` int(11) unsigned NOT NULL,
  KEY `FK_user_reports_users` (`user_id`),
  KEY `FK_user_reports_reports` (`report_id`),
  CONSTRAINT `FK_user_reports_reports` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_reports_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table checkmate.user_reports: ~0 rows (approximately)
/*!40000 ALTER TABLE `user_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_reports` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
