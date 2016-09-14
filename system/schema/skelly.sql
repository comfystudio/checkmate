-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.9-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table skelly.backend_users
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table skelly.backend_users: ~3 rows (approximately)
/*!40000 ALTER TABLE `backend_users` DISABLE KEYS */;
INSERT INTO `backend_users` (`id`, `user_name`, `user_pass`, `user_email`, `salt`, `display_name`, `is_super`, `created`, `modified`) VALUES
	(1, 'creative', '44e32df05cb0bc59d45534e152c54e4dcf5cfc1b192fe2d2396971a5c4858b36', 'william@outputdigital.com', 1435578356, 'William', 1, '2016-01-11 15:13:56', '0000-00-00 00:00:00');
/*!40000 ALTER TABLE `backend_users` ENABLE KEYS */;


-- Dumping structure for table skelly.frontend_users
CREATE TABLE IF NOT EXISTS `frontend_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) DEFAULT NULL,
  `salt` int(11) unsigned DEFAULT NULL,
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=542 DEFAULT CHARSET=utf8;

-- Dumping data for table skelly.frontend_users: ~4 rows (approximately)
/*!40000 ALTER TABLE `frontend_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `frontend_users` ENABLE KEYS */;


-- Dumping structure for table skelly.frontend_users_pw_recovery
CREATE TABLE IF NOT EXISTS `frontend_users_pw_recovery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) unsigned NOT NULL,
  `security_key` varchar(255) NOT NULL,
  `exp_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_frontend_users_pw_recovery_frontend_users` (`users_id`),
  CONSTRAINT `FK_frontend_users_pw_recovery_frontend_users` FOREIGN KEY (`users_id`) REFERENCES `frontend_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table skelly.frontend_users_pw_recovery: ~0 rows (approximately)
/*!40000 ALTER TABLE `frontend_users_pw_recovery` DISABLE KEYS */;
/*!40000 ALTER TABLE `frontend_users_pw_recovery` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
