-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for event network.event_change_period_active
DELIMITER //
CREATE EVENT `event_change_period_active` ON SCHEDULE EVERY 1 DAY STARTS '2020-10-30 00:22:51' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
	
END//
DELIMITER ;

-- Dumping structure for table network.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table network.failed_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Dumping structure for table network.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table network.jobs: ~10 rows (approximately)
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
	(24, 'OrderQueueJobBuyer', '{"uuid":"b486f3a2-cbeb-4fe6-89f9-8a97cbed5035","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:36;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:18:\\"OrderQueueJobBuyer\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(25, 'OrderQueueJobAncestors', '{"uuid":"96fd3c57-326c-463d-bb11-15b5f763f028","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:37;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(26, 'OrderQueueJobAncestors', '{"uuid":"fdf794fa-c229-4084-ba93-6284a4bd37d0","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:38;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(27, 'OrderQueueJobAncestors', '{"uuid":"413d1793-d3c5-413d-8550-71189cf30909","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:39;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(28, 'OrderQueueJobAncestors', '{"uuid":"04a6964b-1675-463e-876e-651e03607b94","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:40;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(29, 'OrderQueueJobBuyer', '{"uuid":"0ae582cf-6a8a-42a8-84f9-5c0deae8c9e4","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:41;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:18:\\"OrderQueueJobBuyer\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(30, 'OrderQueueJobAncestors', '{"uuid":"f1d9735c-b440-4f06-af6c-4d120ff6f1fe","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:42;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(31, 'OrderQueueJobAncestors', '{"uuid":"87c05838-b7c1-4d5c-bbf3-4dcdbb72d257","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:43;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(32, 'OrderQueueJobAncestors', '{"uuid":"a25212f7-817f-4250-b5cd-cf156297f88c","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:44;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502),
	(33, 'OrderQueueJobAncestors', '{"uuid":"ee5a041f-3492-41e2-b95b-680d9bb8a226","displayName":"App\\\\Jobs\\\\FireOrderQueueJob","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\\\Jobs\\\\FireOrderQueueJob","command":"O:26:\\"App\\\\Jobs\\\\FireOrderQueueJob\\":10:{s:43:\\"\\u0000App\\\\Jobs\\\\FireOrderQueueJob\\u0000orderQueueJobId\\";i:45;s:3:\\"job\\";N;s:10:\\"connection\\";N;s:5:\\"queue\\";s:22:\\"OrderQueueJobAncestors\\";s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}}"}}', 0, NULL, 1604003502, 1604003502);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;

-- Dumping structure for table network.log
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table network.log: ~10 rows (approximately)
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
INSERT INTO `log` (`id`, `title`) VALUES
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 0),
	(17, 0),
	(18, 0),
	(19, 0),
	(20, 0);
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- Dumping structure for table network.marketer
CREATE TABLE IF NOT EXISTS `marketer` (
  `user_id` int(11) NOT NULL,
  `hierarchy` text NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table network.marketer: ~5 rows (approximately)
/*!40000 ALTER TABLE `marketer` DISABLE KEYS */;
INSERT INTO `marketer` (`user_id`, `hierarchy`) VALUES
	(1, '/1/'),
	(2, '/1/2/'),
	(3, '/1/2/3'),
	(4, '/1/2/3/4/'),
	(5, '/1/2/3/4/5/');
/*!40000 ALTER TABLE `marketer` ENABLE KEYS */;

-- Dumping structure for table network.marketer_proccess
CREATE TABLE IF NOT EXISTS `marketer_proccess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `point` float NOT NULL DEFAULT 0,
  `group_point` float NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `period_id` (`period_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table network.marketer_proccess: ~0 rows (approximately)
/*!40000 ALTER TABLE `marketer_proccess` DISABLE KEYS */;
/*!40000 ALTER TABLE `marketer_proccess` ENABLE KEYS */;

-- Dumping structure for table network.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table network.migrations: ~3 rows (approximately)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2020_10_29_145948_create_jobs_table', 2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table network.order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `point` float NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `period_id` (`period_id`),
  KEY `user_id_period_id_status` (`user_id`,`period_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.order: ~1 rows (approximately)
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` (`id`, `user_id`, `period_id`, `point`, `status`, `created_at`, `updated_at`) VALUES
	(1, 5, 1, 50, 1, '2020-10-29 23:12:19', '2020-10-29 23:16:17');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

-- Dumping structure for table network.order_queue_job
CREATE TABLE IF NOT EXISTS `order_queue_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `point` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `buyer` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `period_id` (`period_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.order_queue_job: ~10 rows (approximately)
/*!40000 ALTER TABLE `order_queue_job` DISABLE KEYS */;
INSERT INTO `order_queue_job` (`id`, `user_id`, `order_id`, `period_id`, `point`, `level`, `buyer`, `status`, `created_at`, `updated_at`) VALUES
	(36, 5, 1, 1, NULL, 0, 1, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(37, 4, 1, 1, NULL, 1, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(38, 3, 1, 1, NULL, 2, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(39, 2, 1, 1, NULL, 3, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(40, 1, 1, 1, NULL, 4, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(41, 5, 1, 1, NULL, 0, 1, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(42, 4, 1, 1, NULL, 1, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(43, 3, 1, 1, NULL, 2, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(44, 2, 1, 1, NULL, 3, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42'),
	(45, 1, 1, 1, NULL, 4, 0, 0, '2020-10-29 20:31:42', '2020-10-30 00:01:42');
/*!40000 ALTER TABLE `order_queue_job` ENABLE KEYS */;

-- Dumping structure for table network.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table network.password_resets: ~0 rows (approximately)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Dumping structure for table network.period
CREATE TABLE IF NOT EXISTS `period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `hidden` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.period: ~0 rows (approximately)
/*!40000 ALTER TABLE `period` DISABLE KEYS */;
INSERT INTO `period` (`id`, `title`, `from`, `to`, `status`, `hidden`, `active`, `created_at`, `updated_at`) VALUES
	(1, 'دوره یک', '2020-10-29 23:13:24', '2020-10-29 23:13:25', 0, 0, 1, '2020-10-29 23:13:28', '2020-10-29 23:38:35');
/*!40000 ALTER TABLE `period` ENABLE KEYS */;

-- Dumping structure for procedure network.sp_calculate_marketer_plan_and_point
DELIMITER //
CREATE PROCEDURE `sp_calculate_marketer_plan_and_point`(
	IN `id` INT
)
BEGIN

	SELECT user_id, period_id, order_id INTO @user_id, @period_id, @order_id FROM order_queue_job WHERE id = id_p;
	

	-- personal point
	SELECT SUM(`point`) INTO @personal_point FROM `order` WHERE user_id = marketerID AND period_id = periodId AND `status` = 1;
	
	-- group point
	SELECT SUM(`point`) INTO @group_point FROM `order` WHERE user_id LIKE '/1/2/3/' AND period_id = periodId AND `status` = 1;
	
	-- check marketer exist in marketer active table
	SELECT COUNT(id) INTO @has_record FROM marketer_proccess WHERE period_id = periodID AND user_id = marketerId;
	
	IF @has_record > 0
	THEN
		UPDATE marketer_proccess SET `point` = @personal_point, group_point = @group_point WHERE period_id = periodID AND user_id = marketerId;
	ELSE
		INSERT INTO marketer_proccess (user_id, period_id, `point`, `group_point`) VALUES(marketerId, periodId, @personal_point, @group_point);
	END IF;
END//
DELIMITER ;

-- Dumping structure for table network.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.user: ~100 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Gladyce Denesik', 'reginald.nienow@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ki2EM4zBTT', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(2, 'Hershel Abbott PhD', 'corwin.viola@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ZCCUu8k58f', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(3, 'Lacey Huels II', 'kreiger.kailee@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mRbUu3o69K', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(4, 'Marcel Bartoletti', 'xkeebler@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vL4Goki2E6', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(5, 'Sophia Kuhn', 'jbrakus@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'diz613vE1L', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(6, 'Mrs. Orie Swift', 'shudson@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bp9jkrEp54', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(7, 'Lily Kuphal', 'hettinger.clair@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'L7QP4z4W29', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(8, 'Zackery Lockman', 'sheila.ruecker@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'xzMU2EOXlv', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(9, 'Felipe Mraz', 'gwolff@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'oP0Dp4siTe', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(10, 'Clark Bartell', 'kkreiger@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'RaFQQAfRMw', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(11, 'Chloe Hyatt', 'nelson.hessel@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hG5sZ6oZxY', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(12, 'Charity Wyman', 'angelina.carter@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'eVZE1p5SN6', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(13, 'Sydnie Purdy', 'wfranecki@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'udywaw2uvD', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(14, 'Albin Champlin', 'zfeest@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PchZp5hgJg', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(15, 'Rodger Larkin', 'icremin@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'noLjFO9WKm', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(16, 'Valentine Collier', 'abshire.german@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '29VMoezqA1', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(17, 'Ms. Loraine Hane II', 'swisozk@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0sgF1Wb9hR', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(18, 'Dr. Grady Schulist DDS', 'sonny.renner@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'L95yzZVB9D', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(19, 'Jarrell Tillman Jr.', 'levi84@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8B8X3R1ojN', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(20, 'Miss Esmeralda Runolfsdottir II', 'caroline07@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gawNVjlV8u', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(21, 'Dr. Noemy Hintz', 'awisoky@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'WELfYtDwHY', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(22, 'Betsy Jacobs Jr.', 'christopher.hagenes@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nAxaIDgTiy', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(23, 'Glennie Willms PhD', 'ffritsch@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'twOgmuSUNh', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(24, 'Ms. Alexandrea Roob MD', 'cleveland58@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'y6O0YGrDKJ', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(25, 'Dale Bechtelar', 'retha83@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hCLw2QGy2c', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(26, 'Prof. Freeman Weissnat', 'jeromy83@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'OZEsNBRYkt', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(27, 'Miss Mikayla Berge III', 'jason97@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uzoCuvo1Go', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(28, 'Eda Schuster', 'treva.okeefe@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'm89t0z9Uqz', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(29, 'Scarlett Gaylord', 'jordane86@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'MyxbBnKZLL', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(30, 'Etha Kutch MD', 'schultz.demarcus@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '8eH85fobGe', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(31, 'Makenna Hodkiewicz', 'sawayn.ariane@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BM547tywk1', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(32, 'Alva Gorczany', 'hills.abigail@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gbfziBrvi7', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(33, 'Dr. Eudora Upton', 'prosacco.hubert@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'IrEV6oEd9g', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(34, 'Bailey Sporer', 'greenfelder.herbert@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nCySKmm4YX', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(35, 'Dr. Herman Kuhn', 'akshlerin@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BaD6wDg9Rv', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(36, 'Dora Adams Jr.', 'josiane.schroeder@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HZ8LUx2aUj', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(37, 'Mr. Preston Fritsch', 'lane65@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'E4NUmibUCT', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(38, 'Raven Schroeder', 'lstehr@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vrnZY0WH7L', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(39, 'Lucious Bauch', 'jones.triston@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uKrPoNmuMd', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(40, 'Kyleigh Frami IV', 'skyla07@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'AdX80PL1Co', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(41, 'Dr. Lexi Cartwright', 'breanna65@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fG72dXbhTe', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(42, 'Prof. Garnet Torphy DVM', 'nbailey@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CTvMfd4gfu', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(43, 'Graciela Labadie V', 'horace12@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'VKezRpP2IX', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(44, 'Madonna Shanahan', 'marjolaine.wyman@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lHkGipvSNx', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(45, 'Prof. Gretchen Dooley Sr.', 'fsporer@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BgVugLLKxK', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(46, 'Elyssa Stark', 'ewalker@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'oiIyGc3Sht', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(47, 'Concepcion Corwin II', 'sandrine.rogahn@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'eQQQIrA3GL', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(48, 'Ceasar Lowe', 'tatyana25@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tWtnnAtZAv', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(49, 'Israel D\'Amore', 'bogisich.fletcher@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'wApamDa8EG', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(50, 'Loma Nikolaus', 'wuckert.jabari@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'OJRA1HQ4LY', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(51, 'Brendan McCullough', 'blanca19@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'SPDvyhaXKz', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(52, 'Birdie Effertz DDS', 'georgette83@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vpkPuqKH9e', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(53, 'Elva Schaefer', 'iparisian@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BsQrUN8tzf', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(54, 'Enos Ryan', 'kertzmann.nathaniel@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'VENymueGHq', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(55, 'Reynold Koss', 'ebony.ziemann@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vjNOhKIjnb', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(56, 'Kody Bradtke', 'xhomenick@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Gr68dUZEH6', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(57, 'Lennie Hahn', 'caesar.reilly@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kI9q05GIzl', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(58, 'Dr. Millie Kuvalis', 'antonia08@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fyvVeCPgh4', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(59, 'Keyon Littel', 'murazik.coty@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'IxKJg04hdG', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(60, 'Timmy Schmeler II', 'claude80@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1PpR04bNU2', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(61, 'Frieda Greenholt', 'eromaguera@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'v3rnqlpI28', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(62, 'Dr. Bette Feil', 'kirlin.julianne@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'yTU6QefNZz', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(63, 'Prof. Leanna Stiedemann', 'oran70@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jFIcz9TKmZ', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(64, 'Onie Gibson', 'marc56@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HptB9AxMb1', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(65, 'Xander Crist', 'vbartell@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'U9zmIqDrEY', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(66, 'Myriam Borer DDS', 'gusikowski.lane@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'GEig1iNd8c', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(67, 'Arvel Hartmann', 'qmetz@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'q7WEl1OsN1', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(68, 'Nicole D\'Amore', 'rico.weimann@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pfyvQAPqgA', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(69, 'Mr. Elmo Rohan', 'walsh.edward@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DVnYeucspp', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(70, 'Lawson Jenkins DDS', 'casimir58@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'BOUZH471bU', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(71, 'Icie Morar II', 'fblock@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'zckxCPPd4P', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(72, 'Emilia Hansen', 'dangelo85@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'z0tOQwmUVi', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(73, 'Chyna Homenick', 'ggerlach@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '94D85592ur', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(74, 'Madison Brekke', 'fledner@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uNXAANto5J', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(75, 'Kaycee Steuber', 'magnolia59@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'xHhJZOgaLq', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(76, 'Tressie McGlynn', 'zieme.miles@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '4ZWka8l6NP', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(77, 'Lelah Runolfsdottir', 'upton.jameson@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tWyLfbae2y', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(78, 'Emmanuelle Wuckert', 'ottis21@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'WFER5hfAUy', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(79, 'Maxime Powlowski', 'luz35@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'RUj1lrIgx9', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(80, 'Remington Russel', 'grant.lisandro@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'y9KhsgEeHf', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(81, 'Travis Tillman', 'xschmitt@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fFgE58hBMX', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(82, 'Christop Moen', 'veum.tiffany@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'FPpmxuPQTn', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(83, 'Carissa Purdy', 'glarkin@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CQIsdy1iOL', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(84, 'Prof. Newton Hansen', 'miller.violet@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'VdyAIzuCwO', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(85, 'Sven Renner', 'zharber@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pAGtIjkPDX', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(86, 'Rollin Larkin DDS', 'jules.runolfsdottir@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'RZjsxza9I3', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(87, 'Camden Prohaska', 'lia.schaden@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '26rmcXVZVH', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(88, 'Mr. Ali Schuster PhD', 'camryn74@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ZYP8ctOrCz', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(89, 'Bernita Osinski Jr.', 'afeest@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jrwBKVCsci', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(90, 'Prof. Meggie Murphy I', 'leuschke.katelynn@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '03e7l1Eirw', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(91, 'Raquel Schimmel', 'rohan.ivah@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DZFbQ2Zeb4', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(92, 'Macie Jacobson', 'weissnat.aliza@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'VPwmkXH9d6', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(93, 'Daphney Purdy', 'ratke.adolf@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CYcJRthIz4', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(94, 'Carroll Stehr', 'odooley@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'T1BBdxOk2F', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(95, 'Zola Cole', 'lessie76@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'RwhFoJDbfH', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(96, 'Travon Heaney', 'darion.lehner@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jYUXRWTVJm', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(97, 'Connor Wuckert', 'schmeler.manley@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ZTHBMl4TdO', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(98, 'Ms. Heather Hudson IV', 'ohyatt@example.com', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Oj5fyCKNbW', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(99, 'Dr. Paige Harber', 'jovany.macejkovic@example.net', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fIASZwcQXf', '2020-10-29 19:39:16', '2020-10-29 19:39:16'),
	(100, 'Wiley Murray', 'walter.muriel@example.org', '2020-10-29 19:39:16', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bmHbvdB1nf', '2020-10-29 19:39:16', '2020-10-29 19:39:16');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for trigger network.tiger_order_queue_job_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `tiger_order_queue_job_before_insert` BEFORE INSERT ON `order_queue_job` FOR EACH ROW BEGIN

	IF NEW.period_id IS NULL || NEW.period_id = 0
	THEN
		SET NEW.period_id = (SELECT id FROM period WHERE active = 1);
	END IF;
	
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger network.triger_period_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `triger_period_after_update` AFTER UPDATE ON `period` FOR EACH ROW BEGIN
	IF OLD.status = 1 AND  NEW.status = 0 
	THEN
		DELETE FROM order_queue_job WHERE period_id = NEW.id;
	END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
