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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table network.failed_jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Dumping structure for function network.fn_random_char
DELIMITER //
CREATE FUNCTION `fn_random_char`() RETURNS char(10) CHARSET utf8 COLLATE utf8_unicode_ci
BEGIN
	select concat(
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65),
    char(round(rand()*25)+65)
    ) INTO  @Random10CharacterString;
   RETURN @Random10CharacterString;
END//
DELIMITER ;

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
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table network.jobs: ~0 rows (approximately)
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
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
  `parent_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `position` tinyint(1) NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `ancestry` varchar(4000) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `ancestry` (`ancestry`) USING HASH,
  KEY `parent_id` (`parent_id`),
  KEY `level` (`level`),
  KEY `position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.marketer: ~5 rows (approximately)
/*!40000 ALTER TABLE `marketer` DISABLE KEYS */;
INSERT INTO `marketer` (`user_id`, `parent_id`, `level`, `position`, `code`, `ancestry`, `created_at`, `updated_at`) VALUES
	(1, NULL, 0, 1, '', '/1/', '2020-11-04 21:52:50', '2020-11-04 21:52:50'),
	(108, 1, 1, 1, 'Bee20201104215358', '/1/108/', '2020-11-04 21:53:58', '2020-11-04 21:53:58'),
	(110, 1, 1, 1, 'Bee20201104220337', '/1/110/', '2020-11-04 22:03:37', '2020-11-04 22:03:37'),
	(111, 110, 2, 6, 'Bee20201104220722', '/1/110/111/', '2020-11-04 22:07:22', '2020-11-04 22:07:22'),
	(116, 111, 3, 6, 'Bee20201104221811', '/1/110/111/116/', '2020-11-04 22:18:11', '2020-11-04 22:18:11'),
	(118, 111, 3, 6, 'Bee20201104222418228', '/1/110/111/118/', '2020-11-04 22:24:18', '2020-11-04 22:24:18');
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

-- Dumping structure for table network.notification
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('sms','email') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'sms',
  `status` enum('pending','done','failed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `err` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.notification: ~5 rows (approximately)
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
INSERT INTO `notification` (`id`, `to`, `type`, `status`, `text`, `err`, `created_at`, `updated_at`) VALUES
	(26, '09398624739', 'sms', 'failed', NULL, 'Client error: `GET https://api.kavenegar.com/v1/58472B612B7156664978356558685845645A5851704B387A416D464D75527361532F376E7A5737597861303D/verify/lookup.json?receptor=09398624739&template=OrderQueueJobFailed&token=13&template=OrderQueueJobFailed&` resulted in a `424 Failed Dependency` response:\n{"return":{"status":424,"message":"الگوی مورد نظر پیدا نشد یا هنوز تائید نشده"},"entri (truncated...)\n', '2020-10-30 19:05:19', '2020-10-30 19:05:20'),
	(27, '09398624739', 'sms', 'failed', NULL, 'Client error: `GET https://api.kavenegar.com/v1/58472B612B7156664978356558685845645A5851704B387A416D464D75527361532F376E7A5737597861303D/verify/lookup.json?receptor=09398624739&template=OrderQueueJobFailed&token=13&template=OrderQueueJobFailed&` resulted in a `424 Failed Dependency` response:\n{"return":{"status":424,"message":"الگوی مورد نظر پیدا نشد یا هنوز تائید نشده"},"entri (truncated...)\n', '2020-10-30 19:06:38', '2020-10-30 19:06:38'),
	(28, '09398624739', 'sms', 'failed', NULL, 'Magic request methods require a URI and optional options array', '2020-10-30 19:07:31', '2020-10-30 19:07:34'),
	(29, '09398624739', 'sms', 'failed', NULL, 'Undefined variable: tokens', '2020-10-30 19:29:33', '2020-10-30 19:29:33'),
	(30, '09398624739', 'sms', 'done', 'کیهان کالا پارس\nمحاسبه سفارش با کد 13 با خطا مواجه شده است.\n', NULL, '2020-10-30 19:30:09', '2020-10-30 19:30:10');
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;

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

-- Dumping structure for table network.refral_code
CREATE TABLE IF NOT EXISTS `refral_code` (
  `marketer_id` int(11) NOT NULL,
  `position` tinyint(1) NOT NULL,
  `direct_id` int(11) DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`marketer_id`,`position`),
  UNIQUE KEY `code` (`code`),
  KEY `direct_id` (`direct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.refral_code: ~31 rows (approximately)
/*!40000 ALTER TABLE `refral_code` DISABLE KEYS */;
INSERT INTO `refral_code` (`marketer_id`, `position`, `direct_id`, `code`, `created_at`) VALUES
	(1, 1, 110, 'RF1P1@21212', '2020-11-04 22:03:37'),
	(107, 1, NULL, 'RF107P1@RETKQADQWJ', '2020-11-04 21:46:35'),
	(107, 2, NULL, 'RF107P2@FZHKGWUJLZ', '2020-11-04 21:46:35'),
	(107, 3, NULL, 'RF107P3@RLGTBZUCYN', '2020-11-04 21:46:35'),
	(107, 4, NULL, 'RF107P4@RWLMDGSVDZ', '2020-11-04 21:46:35'),
	(107, 5, NULL, 'RF107P5@RIPDRERYUC', '2020-11-04 21:46:35'),
	(107, 6, NULL, 'RF107P6@XMQSRHGMQU', '2020-11-04 21:46:35'),
	(108, 1, NULL, 'RF108P1@CATTPQJXOC', '2020-11-04 21:53:58'),
	(108, 2, NULL, 'RF108P2@RFYEZLGXWP', '2020-11-04 21:53:58'),
	(108, 3, NULL, 'RF108P3@IUASMHWQNQ', '2020-11-04 21:53:58'),
	(108, 4, NULL, 'RF108P4@TVXBMGSXJC', '2020-11-04 21:53:58'),
	(108, 5, NULL, 'RF108P5@KQZCNGQMMZ', '2020-11-04 21:53:58'),
	(108, 6, NULL, 'RF108P6@NOIVFOGLPO', '2020-11-04 21:53:58'),
	(110, 1, NULL, 'RF110P1@CTQYTASOSW', '2020-11-04 22:03:37'),
	(110, 2, NULL, 'RF110P2@EELQVIBHFF', '2020-11-04 22:03:37'),
	(110, 3, NULL, 'RF110P3@KKVANQOYBI', '2020-11-04 22:03:37'),
	(110, 4, NULL, 'RF110P4@NSXLOJFVQP', '2020-11-04 22:03:37'),
	(110, 5, NULL, 'RF110P5@EYIRKCCGZF', '2020-11-04 22:03:37'),
	(110, 6, 111, 'RF110P6@CSLBXMQUDD', '2020-11-04 22:07:22'),
	(111, 1, NULL, 'RF111P1@CBWJCKRHKB', '2020-11-04 22:07:22'),
	(111, 2, NULL, 'RF111P2@AYUELQWLNH', '2020-11-04 22:07:22'),
	(111, 3, NULL, 'RF111P3@YWPKCGXWQO', '2020-11-04 22:07:22'),
	(111, 4, NULL, 'RF111P4@VNEGRSOSUW', '2020-11-04 22:07:22'),
	(111, 5, NULL, 'RF111P5@CSHGJBCJNO', '2020-11-04 22:07:22'),
	(111, 6, 118, 'RF111P6@FKIKCEOJEP', '2020-11-04 22:24:18'),
	(116, 1, NULL, 'RF116P1@VTKPVKNKJR', '2020-11-04 22:18:11'),
	(116, 2, NULL, 'RF116P2@IQDSESEPPC', '2020-11-04 22:18:11'),
	(116, 3, NULL, 'RF116P3@REVPNUNCWG', '2020-11-04 22:18:11'),
	(116, 4, NULL, 'RF116P4@MSERXOCRDN', '2020-11-04 22:18:11'),
	(116, 5, NULL, 'RF116P5@JERZWKLASO', '2020-11-04 22:18:11'),
	(116, 6, NULL, 'RF116P6@OEEJGFGNZI', '2020-11-04 22:18:11'),
	(118, 1, NULL, 'RF118P1@MRWMRYRQDT', '2020-11-04 22:24:18'),
	(118, 2, NULL, 'RF118P2@KSKXIUCXNR', '2020-11-04 22:24:18'),
	(118, 3, NULL, 'RF118P3@XNVRWLLZOX', '2020-11-04 22:24:18'),
	(118, 4, NULL, 'RF118P4@VLQVIEUNEI', '2020-11-04 22:24:18'),
	(118, 5, NULL, 'RF118P5@BGDXECWGOY', '2020-11-04 22:24:18'),
	(118, 6, NULL, 'RF118P6@GHQKBYTWFG', '2020-11-04 22:24:18');
/*!40000 ALTER TABLE `refral_code` ENABLE KEYS */;

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

-- Dumping structure for procedure network.sp_marketer_info
DELIMITER //
CREATE PROCEDURE `sp_marketer_info`(
	IN `id` INT
)
BEGIN
	SELECT u.id, u.name, u.family, u.gender, u.mobile, u.email, u.`status`, u.national_code, u.nickname, m.code, m.`level` FROM `user` AS u
	LEFT JOIN marketer AS m ON m.user_id = u.id
	WHERE u.id = id;
END//
DELIMITER ;

-- Dumping structure for procedure network.sp_marketer_register
DELIMITER //
CREATE PROCEDURE `sp_marketer_register`(
	IN `rcp` VARCHAR(50),
	IN `nickname_p` VARCHAR(50),
	IN `name_p` VARCHAR(50),
	IN `family_p` VARCHAR(50),
	IN `gender_p` VARCHAR(1),
	IN `mobile_p` VARCHAR(11),
	IN `email_p` VARCHAR(50),
	IN `national_code_p` VARCHAR(10),
	IN `password_p` VARCHAR(255)
)
BEGIN

	DECLARE i int;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
	BEGIN
	 ROLLBACK;
	  GET DIAGNOSTICS CONDITION 1
     @err = MESSAGE_TEXT;
	  SELECT @err AS err;
	END;
	
	START TRANSACTION;


	-- fetch refral code info
	SELECT marketer_id, refral_code.`position`, marketer.ancestry, marketer.`level` 
	INTO @parent_id, @state, @ancestry, @levelId 
	FROM refral_code 
	LEFT JOIN marketer ON marketer.user_id = refral_code.marketer_id
	WHERE refral_code.`code` = rcp LIMIT 1;


	IF @parent_id IS not NULL AND  @parent_id <> 0
	then
	

	INSERT INTO user (`role_id`, `name`, `family`, `gender`, mobile, email, `password`, national_code, remember_token, nickname, created_at) 
	VALUES('marketer', name_p, family_p, IF(gender_p = '', 'm', gender_p), mobile_p, if(email_p = '', NULL, email_p), password_p, if(
	national_code_p = '', NULL, national_code_p), MD5(fn_random_char()), if(nickname_p = '', NULL, nickname_p), NOW());
	
	SELECT LAST_INSERT_ID() into @userId;
	
	
	INSERT INTO marketer (user_id, parent_id, `position`, ancestry, `level`, `code`, created_at) VALUES(@userId, @parent_id, @state, CONCAT(@ancestry, @userId, '/'), @levelId + 1, CONCAT('Bee', REPLACE(REPLACE( REPLACE(REPLACE(NOW(3), '.', ''), '-', ''), ':', ''), ' ', '')), NOW());
	

	
	-- update refral code table
	UPDATE refral_code SET direct_id = @userId WHERE `code` = rcp;
	
	-- insert to refral code
	SET i = 1;
	label: LOOP 
		IF(i > 6) THEN
			LEAVE label;
		END IF;
			INSERT INTO refral_code (marketer_id, `position`, `code`) VALUES(@userId, i, CONCAT('RF', @userId, 'P', i, '@', fn_random_char()));
			SET i = i + 1;
	END LOOP;
	
	CALL sp_marketer_info(@userId);
			
	END if;
	
	COMMIT;
END//
DELIMITER ;

-- Dumping structure for table network.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'marketer',
  `national_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'marketer',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('m','f') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'm',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `national_code` (`national_code`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table network.user: ~4 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `mobile`, `nickname`, `national_code`, `role_id`, `name`, `family`, `gender`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'admin@gmail.com', '09398624739', 'marketer', NULL, 'marketer', '', '', 'm', '', NULL, 1, NULL, '2020-11-04 21:49:26'),
	(108, '', '09361753251', 'mehrdad', '3920206231', 'marketer', 'mehrdads', 'masoumi', '', 'asdasd', '1fa433301c8e545216a3a34d227dc9c8', 1, NULL, '2020-11-04 21:53:58'),
	(110, NULL, '09153264512', '', '', 'marketer', 'm', 'd', 'm', 'dddd', '05070cd114bb4745f01f45199b3882cc', 1, '2020-11-04 22:03:37', '2020-11-04 22:03:37'),
	(111, NULL, '09361753255', NULL, NULL, 'marketer', 'مهرداد', 'معصومی', 'm', 'asasa', 'd303ab91905fcd4617fcb5eca8afc9b3', 1, '2020-11-04 22:07:22', '2020-11-04 22:07:22'),
	(116, NULL, '09153265147', NULL, NULL, 'marketer', 'aaa', 'aaa', 'm', 'asas', '9d1de64deb3ae28c2ee3800bdea82d82', 1, '2020-11-04 22:18:11', '2020-11-04 22:18:11'),
	(118, NULL, '09153265146', NULL, NULL, 'marketer', 'aaa', 'aaa', 'm', 'asas', 'f42e6439957433c1adc5740b7dd2ea66', 1, '2020-11-04 22:24:18', '2020-11-04 22:24:18');
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
