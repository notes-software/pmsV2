-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table pmsv2.permissions: ~9 rows (approximately)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `title`, `date`) VALUES
	(1, 'settings_access', '0000-00-00'),
	(2, 'user_access', '0000-00-00'),
	(3, 'permission_access', '0000-00-00'),
	(4, 'role_access', '0000-00-00'),
	(5, 'request_book_access', '2022-01-04'),
	(6, 'notebook_access', '2022-01-04'),
	(7, 'project_access', '2022-01-04'),
	(8, 'dashboard_access', '2022-01-04'),
	(9, 'my_calendar_access', '2022-01-25'),
	(10, 'activity_log_access', '2022-03-14'),
	(11, 'project_add_access', '2022-03-14'),
	(12, 'calendar_access', '2022-03-14'),
	(13, 'project_update_settings_access', '2022-03-14');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Dumping data for table pmsv2.roles: ~2 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `role`, `permission`, `created_at`) VALUES
	(1, 'Project Manager', '1,2,3,4,5,6,7,8,9,10,11,12', '2022-01-04 10:33:09'),
	(2, 'Collaborator', '5,6,7,8,9,12', '2022-01-04 00:00:00');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
