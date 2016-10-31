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
CREATE TABLE IF NOT EXISTS `about_us` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.backend_users
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.check_in_items
CREATE TABLE IF NOT EXISTS `check_in_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_rooms_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `tenant_comment` text,
  `lord_comment` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `tenant_approved` tinyint(1) NOT NULL DEFAULT '1',
  `lord_approved` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_report_items_items_check_in_items` (`item_id`),
  KEY `FK_report_items_report_rooms_check_in_items` (`report_rooms_id`),
  CONSTRAINT `FK_report_items_items_check_in_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_report_items_report_rooms_check_in_items` FOREIGN KEY (`report_rooms_id`) REFERENCES `check_in_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.check_in_rooms
CREATE TABLE IF NOT EXISTS `check_in_rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  `clean` int(1) unsigned NOT NULL DEFAULT '0',
  `tenant_comment` text,
  `lord_comment` text,
  PRIMARY KEY (`id`),
  KEY `FK_report_rooms_reports_test` (`report_id`),
  KEY `FK_report_rooms_rooms_test` (`room_id`),
  CONSTRAINT `FK_report_rooms_reports_test` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_report_rooms_rooms_test` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.check_out_items
CREATE TABLE IF NOT EXISTS `check_out_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_rooms_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `tenant_comment` text,
  `lord_comment` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `tenant_approved` tinyint(1) NOT NULL DEFAULT '1',
  `lord_approved` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_report_items_report_rooms` (`report_rooms_id`),
  KEY `FK_report_items_items` (`item_id`),
  CONSTRAINT `check_out_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `check_out_items_ibfk_2` FOREIGN KEY (`report_rooms_id`) REFERENCES `check_out_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.check_out_rooms
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

-- Data exporting was unselected.


-- Dumping structure for table checkmate.contact_us
CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facebook` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `phone_2` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.faqs
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.items
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `text_clean` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_notifications_users` (`user_id`),
  CONSTRAINT `FK_notifications_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `stripe_cus_id` varchar(255) NOT NULL,
  `stripe_sub_id` varchar(255) NOT NULL,
  `type` int(1) unsigned NOT NULL DEFAULT '0',
  `last_payment` datetime DEFAULT NULL,
  `active_until` datetime DEFAULT NULL,
  `remaining_credits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_payments_users` (`user_id`),
  CONSTRAINT `FK_payments_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.prices
CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.properties
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.property_templates
CREATE TABLE IF NOT EXISTS `property_templates` (
  `property_id` int(11) unsigned NOT NULL,
  `template_id` int(11) unsigned NOT NULL,
  KEY `FK__properties` (`property_id`),
  KEY `FK__templates` (`template_id`),
  CONSTRAINT `FK__properties` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__templates` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.queries
CREATE TABLE IF NOT EXISTS `queries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.references
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

-- Data exporting was unselected.


-- Dumping structure for table checkmate.reports
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` int(11) unsigned NOT NULL,
  `lord_id` int(11) unsigned NOT NULL,
  `lead_tenant_id` int(11) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL DEFAULT '0',
  `meter_type` int(1) unsigned NOT NULL DEFAULT '0',
  `meter_reading` varchar(255) NOT NULL,
  `meter_measurement` varchar(255) NOT NULL,
  `meter_image` varchar(255) DEFAULT NULL,
  `tenant_agreement` varchar(255) DEFAULT NULL,
  `oil_level` varchar(255) DEFAULT NULL,
  `keys_acquired` int(1) unsigned NOT NULL DEFAULT '0',
  `fire_extin` varchar(255) DEFAULT NULL,
  `fire_blanket` varchar(255) DEFAULT NULL,
  `smoke_alarm` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `property_template_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_rooms_property_templates` (`property_template_id`),
  CONSTRAINT `FK_rooms_property_templates` FOREIGN KEY (`property_template_id`) REFERENCES `templates` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.room_items
CREATE TABLE IF NOT EXISTS `room_items` (
  `room_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  KEY `FK_room_items_rooms` (`room_id`),
  KEY `FK_room_items_items` (`item_id`),
  CONSTRAINT `FK_room_items_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_room_items_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.templates
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` int(11) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_property_templates_users` (`created_by`),
  CONSTRAINT `FK_property_templates_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.template_rooms
CREATE TABLE IF NOT EXISTS `template_rooms` (
  `template_id` int(11) unsigned NOT NULL,
  `room_id` int(11) unsigned NOT NULL,
  KEY `FK__templates_to_rooms` (`template_id`),
  KEY `FK__rooms_to_templates` (`room_id`),
  CONSTRAINT `FK__rooms_to_templates` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__templates_to_rooms` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(1) unsigned NOT NULL DEFAULT '0',
  `firstname` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `salt` int(11) unsigned NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `logo_image` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.users_pw_recovery
CREATE TABLE IF NOT EXISTS `users_pw_recovery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) unsigned NOT NULL,
  `security_key` varchar(255) NOT NULL,
  `exp_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table checkmate.user_reports
CREATE TABLE IF NOT EXISTS `user_reports` (
  `user_id` int(11) unsigned NOT NULL,
  `report_id` int(11) unsigned NOT NULL,
  `check_in_signature` varchar(255) DEFAULT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_signature` varchar(255) DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  KEY `FK_user_reports_users` (`user_id`),
  KEY `FK_user_reports_reports` (`report_id`),
  CONSTRAINT `FK_user_reports_reports` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_reports_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
