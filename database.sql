-- KickTime Database Export
-- Generated: 2026-05-05 17:54:38

SET FOREIGN_KEY_CHECKS=0;



-- Structure for table `activity_logs` --
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `activity_logs` --
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('1', '10', 'user_login', 'Email: ahmedmedo1334010@gmail.com', '::1', '2026-05-01 22:39:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('2', '18', 'user_registered', 'Email: newuser_1777664455@example.com, Role: user', '::1', '2026-05-01 22:40:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('3', '19', 'user_registered', 'Email: logintest@example.com, Role: user', '::1', '2026-05-01 22:41:27');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('4', '19', 'user_login', 'Email: logintest@example.com', '::1', '2026-05-01 22:42:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('5', '20', 'user_registered', 'Email: fulltest_1777667522@example.com, Role: user', '::1', '2026-05-01 23:32:03');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('6', '21', 'user_registered', 'Email: e2e_1777667611@example.com, Role: user', '::1', '2026-05-01 23:33:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('7', '21', 'user_login', 'Email: e2e_1777667611@example.com', '::1', '2026-05-01 23:33:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('8', '10', 'user_login', 'Email: ahmedmedo1334010@gmail.com', '::1', '2026-05-01 23:43:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('9', '10', 'user_login', 'Email: ahmedmedo1334010@gmail.com', '::1', '2026-05-01 23:43:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('10', '10', 'user_login', 'Email: ahmedmedo1334010@gmail.com', '::1', '2026-05-01 23:44:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('11', '22', 'user_registered', 'Email: ahme@gmail.com, Role: user', '::1', '2026-05-01 23:44:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('12', '22', 'user_login', 'Email: ahme@gmail.com', '::1', '2026-05-01 23:44:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('13', '23', 'user_registered', 'Email: ahmedmedo122@gmail.com, Role: owner', '::1', '2026-05-01 23:45:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('14', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-01 23:45:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('15', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-01 23:52:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('16', '24', 'user_registered', 'Email: e2e_1777668929@example.com, Role: user', '::1', '2026-05-01 23:55:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('17', '24', 'user_login', 'Email: e2e_1777668929@example.com', '::1', '2026-05-01 23:55:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('18', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-01 23:58:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('19', '25', 'user_registered', 'Email: ahm@gmail.com, Role: user', '::1', '2026-05-02 01:54:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('20', '25', 'user_login', 'Email: ahm@gmail.com', '::1', '2026-05-02 01:54:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('21', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 01:56:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('22', '25', 'user_login', 'Email: ahm@gmail.com', '::1', '2026-05-02 02:06:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('23', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 02:07:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('24', '25', 'user_login', 'Email: ahm@gmail.com', '::1', '2026-05-02 02:15:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('25', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 02:19:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('26', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 12:57:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('27', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:08:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('28', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:10:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('29', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:16:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('30', '10', 'user_login', 'Email: ahmedmedo1334010@gmail.com', '::1', '2026-05-02 13:23:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('31', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:24:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('32', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:25:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('33', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:27:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('34', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:32:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('35', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:43:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('36', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 13:55:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('37', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 14:13:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('38', '26', 'user_registered', 'Email: testuser@example.com, Role: user', '::1', '2026-05-02 14:32:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('39', '26', 'user_login', 'Email: testuser@example.com', '::1', '2026-05-02 14:32:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('40', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-02 14:46:21');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('41', '27', 'user_registered', 'Email: a@gmail.com, Role: user', '::1', '2026-05-03 16:18:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('42', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 16:18:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('43', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 16:19:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('44', '1', 'user_login', 'Email: admin@sportsbooking.com', '::1', '2026-05-03 16:22:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('45', '28', 'user_registered', 'Email: uniqueuser123@example.com, Role: user', '::1', '2026-05-03 16:26:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('46', '1', 'user_login', 'Email: admin@sportsbooking.com', '::1', '2026-05-03 16:32:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('47', '1', 'user_login', 'Email: admin@sportsbooking.com', '::1', '2026-05-03 16:38:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('48', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 16:40:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('49', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 16:43:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('50', '2', 'user_login', 'Email: owner@sportsbooking.com', '::1', '2026-05-03 16:46:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('51', '2', 'user_login', 'Email: owner@sportsbooking.com', '::1', '2026-05-03 16:52:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('52', '26', 'user_login', 'Email: testuser@example.com', '::1', '2026-05-03 16:58:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('53', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 17:05:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('54', '2', 'user_login', 'Email: owner@sportsbooking.com', '::1', '2026-05-03 17:13:27');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('55', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 17:17:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('56', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 21:27:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('57', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 21:28:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('58', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 21:32:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('59', '10', 'user_login', 'Email: ahmedmedo1334010@gmail.com', '::1', '2026-05-03 21:35:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('60', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 22:24:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('61', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 22:24:48');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('62', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 22:25:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('68', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 22:59:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('69', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:00:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('70', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:01:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('71', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:03:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('72', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:07:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('73', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:35:48');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('74', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 23:38:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('75', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:38:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('76', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-03 23:39:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('77', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-03 23:40:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('78', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-04 00:19:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('82', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-05 18:39:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('83', '27', 'user_login', 'Email: a@gmail.com', '::1', '2026-05-05 19:02:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('84', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 19:04:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('85', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 19:18:00');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('86', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 19:36:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('87', '34', 'user_registered', 'Email: owner@gmail.com, Role: owner', '::1', '2026-05-05 20:28:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('88', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 20:29:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('89', '34', 'user_login', 'Email: owner@gmail.com', '::1', '2026-05-05 20:29:31');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('90', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 20:30:57');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('91', '35', 'user_registered', 'Email: user@gmail.com, Role: user', '::1', '2026-05-05 20:31:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('92', '35', 'user_login', 'Email: user@gmail.com', '::1', '2026-05-05 20:31:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('93', '34', 'user_login', 'Email: owner@gmail.com', '::1', '2026-05-05 20:32:25');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('94', '35', 'user_login', 'Email: user@gmail.com', '::1', '2026-05-05 20:33:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('95', '36', 'user_registered', 'Email: owner2@gmail.com, Role: owner', '::1', '2026-05-05 20:42:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('96', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 20:42:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('97', '36', 'user_login', 'Email: owner2@gmail.com', '::1', '2026-05-05 20:43:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('98', '23', 'user_login', 'Email: ahmedmedo122@gmail.com', '::1', '2026-05-05 20:44:03');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('99', '35', 'user_login', 'Email: user@gmail.com', '::1', '2026-05-05 20:44:27');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('100', '36', 'user_login', 'Email: owner2@gmail.com', '::1', '2026-05-05 20:44:48');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES ('101', '35', 'user_login', 'Email: user@gmail.com', '::1', '2026-05-05 20:45:12');


-- Structure for table `bookings` --
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stadium_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','rejected','cancelled','completed') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_stadium` (`stadium_id`),
  KEY `idx_date` (`booking_date`),
  KEY `idx_status` (`status`),
  KEY `idx_stadium_date` (`stadium_id`,`booking_date`),
  KEY `idx_stadium_date_time` (`stadium_id`,`booking_date`,`start_time`,`end_time`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `bookings` --
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('3', '27', '5', '2026-05-03', '08:00:00', '09:00:00', '1.00', '200.00', '', NULL, '2026-05-03 22:25:08', '2026-05-03 22:25:47');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('4', '23', '5', '2026-05-03', '22:44:00', '23:44:00', '1.00', '0.00', '', NULL, '2026-05-03 22:44:55', '2026-05-03 22:44:55');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('5', '23', '5', '2026-05-03', '22:46:00', '23:46:00', '1.00', '0.00', '', NULL, '2026-05-03 22:46:46', '2026-05-03 22:46:46');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('7', '27', '5', '2026-05-03', '10:00:00', '11:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-03 22:59:58', '2026-05-03 23:00:39');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('8', '27', '5', '2026-05-03', '11:00:00', '13:00:00', '2.00', '400.00', 'confirmed', NULL, '2026-05-03 23:38:23', '2026-05-03 23:39:45');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('9', '27', '5', '2026-05-07', '08:00:00', '09:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:33:54', '2026-05-05 19:04:51');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('10', '27', '5', '2026-05-03', '14:00:00', '16:00:00', '2.00', '400.00', 'confirmed', NULL, '2026-05-04 00:36:14', '2026-05-05 19:05:17');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('11', '27', '5', '2026-05-03', '16:00:00', '17:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:37:52', '2026-05-05 19:05:15');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('12', '27', '5', '2026-05-03', '17:00:00', '18:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:38:02', '2026-05-05 19:05:12');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('13', '27', '5', '2026-05-04', '09:00:00', '10:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:38:24', '2026-05-05 19:05:08');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('14', '27', '5', '2026-05-03', '21:00:00', '22:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:39:11', '2026-05-05 19:05:10');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('16', '27', '5', '2026-05-05', '08:00:00', '09:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:51:48', '2026-05-05 19:04:57');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('17', '27', '5', '2026-05-04', '08:00:00', '09:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:53:16', '2026-05-05 19:05:09');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('18', '27', '5', '2026-05-04', '10:00:00', '11:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:53:34', '2026-05-05 19:05:05');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('19', '27', '5', '2026-05-04', '12:00:00', '13:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 00:59:14', '2026-05-05 19:05:03');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('21', '27', '5', '2026-05-04', '17:00:00', '18:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-04 01:05:53', '2026-05-05 19:04:59');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('22', '27', '5', '2026-05-05', '09:00:00', '10:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-05 18:39:44', '2026-05-05 19:04:55');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('23', '27', '5', '2026-05-05', '10:00:00', '11:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-05 18:42:44', '2026-05-05 19:04:53');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('24', '27', '5', '2026-05-05', '11:00:00', '12:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-05 18:45:59', '2026-05-05 19:04:52');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('25', '35', '5', '2026-05-05', '12:00:00', '13:00:00', '1.00', '200.00', 'pending', NULL, '2026-05-05 20:34:19', '2026-05-05 20:34:19');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('26', '35', '11', '2026-05-05', '14:00:00', '15:00:00', '1.00', '250.00', 'pending', NULL, '2026-05-05 20:41:13', '2026-05-05 20:41:13');
INSERT INTO `bookings` (`id`, `user_id`, `stadium_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES ('27', '35', '12', '2026-05-05', '16:00:00', '17:00:00', '1.00', '200.00', 'confirmed', NULL, '2026-05-05 20:44:33', '2026-05-05 20:44:55');


-- Structure for table `community_matches` --
DROP TABLE IF EXISTS `community_matches`;
CREATE TABLE `community_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `stadium_id` int(11) NOT NULL,
  `match_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `players_needed` int(11) DEFAULT 1,
  `skill_level` enum('beginner','intermediate','advanced') DEFAULT 'intermediate',
  `description` text DEFAULT NULL,
  `status` enum('open','full','cancelled','completed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `creator_id` (`creator_id`),
  KEY `stadium_id` (`stadium_id`),
  CONSTRAINT `community_matches_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `community_matches_ibfk_2` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Structure for table `payments` --
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('cash','visa','mastercard','apple_pay','google_pay') NOT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`),
  KEY `idx_booking` (`booking_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_method` (`method`),
  KEY `idx_status` (`status`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `payments` --
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('3', '3', '27', '200.00', 'cash', '', 'SIM-69f7a11412165', NULL, '2026-05-03 22:25:08', '2026-05-03 22:25:47');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('5', '7', '27', '200.00', 'cash', 'completed', 'SIM-69f7a93eb1fa9', NULL, '2026-05-03 22:59:58', '2026-05-03 23:00:39');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('6', '8', '27', '400.00', 'cash', 'completed', 'SIM-69f7b23f1e69c', NULL, '2026-05-03 23:38:23', '2026-05-03 23:39:45');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('7', '9', '27', '200.00', 'cash', 'completed', 'SIM-69f7bf42cdcee', NULL, '2026-05-04 00:33:54', '2026-05-05 19:04:51');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('8', '10', '27', '400.00', 'cash', 'completed', 'SIM-69f7bfce7bd5f', NULL, '2026-05-04 00:36:14', '2026-05-05 19:05:17');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('9', '11', '27', '200.00', 'cash', 'completed', 'SIM-69f7c0309c24f', NULL, '2026-05-04 00:37:52', '2026-05-05 19:05:15');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('10', '12', '27', '200.00', 'cash', 'completed', 'SIM-69f7c03a6e62e', NULL, '2026-05-04 00:38:02', '2026-05-05 19:05:12');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('11', '13', '27', '200.00', 'cash', 'completed', 'SIM-69f7c05045739', NULL, '2026-05-04 00:38:24', '2026-05-05 19:05:08');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('12', '14', '27', '200.00', 'cash', 'completed', 'SIM-69f7c07f76673', NULL, '2026-05-04 00:39:11', '2026-05-05 19:05:10');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('14', '16', '27', '200.00', 'cash', 'completed', 'SIM-69f7c3748596b', NULL, '2026-05-04 00:51:48', '2026-05-05 19:04:57');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('15', '17', '27', '200.00', 'cash', 'completed', 'SIM-69f7c3cc5c445', NULL, '2026-05-04 00:53:16', '2026-05-05 19:05:09');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('16', '18', '27', '200.00', 'cash', 'completed', 'SIM-69f7c3dee29b4', NULL, '2026-05-04 00:53:34', '2026-05-05 19:05:05');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('17', '19', '27', '200.00', 'cash', 'completed', 'SIM-69f7c532df230', NULL, '2026-05-04 00:59:14', '2026-05-05 19:05:03');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('19', '21', '27', '200.00', 'cash', 'completed', 'SIM-69f7c6c12f98d', NULL, '2026-05-04 01:05:53', '2026-05-05 19:04:59');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('20', '22', '27', '200.00', 'cash', 'completed', 'SIM-69fa0f4014f20', NULL, '2026-05-05 18:39:44', '2026-05-05 19:04:55');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('21', '23', '27', '200.00', 'cash', 'completed', 'SIM-69fa0ff451129', NULL, '2026-05-05 18:42:44', '2026-05-05 19:04:53');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('22', '24', '27', '200.00', 'cash', 'completed', 'SIM-69fa10b7e9d1b', NULL, '2026-05-05 18:45:59', '2026-05-05 19:04:52');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('23', '25', '35', '200.00', 'cash', 'pending', 'SIM-69fa2a1be084a', NULL, '2026-05-05 20:34:19', '2026-05-05 20:34:19');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('24', '26', '35', '250.00', 'cash', 'pending', 'SIM-69fa2bb90d1e1', NULL, '2026-05-05 20:41:13', '2026-05-05 20:41:13');
INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `amount`, `method`, `status`, `transaction_id`, `payment_data`, `created_at`, `updated_at`) VALUES ('25', '27', '35', '200.00', 'cash', 'completed', 'SIM-69fa2c8103714', NULL, '2026-05-05 20:44:33', '2026-05-05 20:44:55');


-- Structure for table `reviews` --
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stadium_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `idx_stadium` (`stadium_id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Structure for table `stadium_availability` --
DROP TABLE IF EXISTS `stadium_availability`;
CREATE TABLE `stadium_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stadium_id` int(11) NOT NULL,
  `day_of_week` tinyint(4) NOT NULL CHECK (`day_of_week` between 0 and 6),
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_stadium_day` (`stadium_id`,`day_of_week`),
  CONSTRAINT `stadium_availability_ibfk_1` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Structure for table `stadiums` --
DROP TABLE IF EXISTS `stadiums`;
CREATE TABLE `stadiums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` enum('football_5','football_7','football_11','padel','tennis') NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(500) NOT NULL,
  `address` varchar(500) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `opening_time` time DEFAULT '08:00:00',
  `closing_time` time DEFAULT '22:00:00',
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_owner` (`owner_id`),
  KEY `idx_type` (`type`),
  KEY `idx_status` (`status`),
  KEY `idx_price` (`price_per_hour`),
  FULLTEXT KEY `idx_search` (`name`,`description`,`location`),
  CONSTRAINT `stadiums_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `stadiums` --
INSERT INTO `stadiums` (`id`, `owner_id`, `name`, `type`, `description`, `location`, `address`, `latitude`, `longitude`, `price_per_hour`, `image_url`, `images`, `amenities`, `opening_time`, `closing_time`, `status`, `created_at`, `updated_at`) VALUES ('5', '23', 'الامل', 'football_5', '', 'te', 'te', NULL, NULL, '200.00', '/uploads/stadiums/69f539bb68a9a.png', '[\"uploads\\/stadiums\\/69f7954adb1e8.png\"]', NULL, '08:00:00', '22:00:00', 'approved', '2026-05-02 01:52:33', '2026-05-03 21:34:50');
INSERT INTO `stadiums` (`id`, `owner_id`, `name`, `type`, `description`, `location`, `address`, `latitude`, `longitude`, `price_per_hour`, `image_url`, `images`, `amenities`, `opening_time`, `closing_time`, `status`, `created_at`, `updated_at`) VALUES ('11', '34', 'fc', 'football_5', '', 'التجمع', 'التجمع', NULL, NULL, '250.00', NULL, '[\"uploads\\/stadiums\\/69fa2937dd93c.png\"]', NULL, '12:00:00', '00:00:00', 'approved', '2026-05-05 20:30:31', '2026-05-05 20:32:53');
INSERT INTO `stadiums` (`id`, `owner_id`, `name`, `type`, `description`, `location`, `address`, `latitude`, `longitude`, `price_per_hour`, `image_url`, `images`, `amenities`, `opening_time`, `closing_time`, `status`, `created_at`, `updated_at`) VALUES ('12', '36', 'test', 'padel', '', 'التجمع', 'التجمع', NULL, NULL, '200.00', NULL, '[\"uploads\\/stadiums\\/69fa2c52722e8.png\"]', NULL, '12:00:00', '00:00:00', 'approved', '2026-05-05 20:43:46', '2026-05-05 20:44:07');


-- Structure for table `users` --
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('user','owner','admin') DEFAULT 'user',
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `avatar` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users` --
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('1', 'System Admin', 'admin@sportsbooking.com', '$2y$10$0EvIemMvEWeWjWo9vIYzm.cG7Npiey/.yWWznbRd/HR8s9Zi9xjGW', NULL, 'admin', 'approved', NULL, '2026-05-01 17:38:38', '2026-05-01 19:38:59');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('9', 'Test Admin', 'admin@test.com', '$2y$10$4w8yZgTDhS1GDETvE60lZ.BUfytaEIZHX9G8fEUbHLZfQ8w128zI.', NULL, 'admin', 'approved', NULL, '2026-05-01 20:21:59', '2026-05-01 20:21:59');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('10', 'Ahmed', 'ahmedmedo1334010@gmail.com', '$2y$10$ag0MSJNUi4WUMgsrDteIIuTqUAI4z1TM5h.8isOth0jdnyvERHsDe', '', 'user', 'approved', NULL, '2026-05-01 20:24:53', '2026-05-01 20:24:53');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('11', 'Final Test', 'final@test.com', '$2y$10$LYj7iAxPUX5ImFporOjI1un3T1xqibOO4TPVmT.AZJcYxuYAfJmZO', '', 'user', 'approved', NULL, '2026-05-01 20:25:06', '2026-05-01 20:25:06');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('12', 'Ahmed', 'ahmedmedo@gmail.com', '$2y$10$sXnie0P9ma7qcyqD.vr.mOsveDLloy6uU8IfmQ5KFe74lgPQhOdu.', '', 'user', 'approved', NULL, '2026-05-01 20:26:34', '2026-05-01 20:26:34');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('13', 'ads', 'ahmedmedo1@gmail.com', '$2y$10$Ka.4d0Gs0sa7MJImhXgsyeSZwezg5G9UaI8Qq31JW9OZ1k1E1tReK', '', 'owner', 'approved', NULL, '2026-05-01 20:27:57', '2026-05-01 20:28:38');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('14', 'Test User', 'test_278@example.com', '$2y$10$meAP0IjSPcIaY5KZuE4pQeU5nlkN5tuHD9hHZOKJd5imKa5BjINEO', '', 'user', 'approved', NULL, '2026-05-01 22:31:58', '2026-05-01 22:31:58');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('15', 'Test User', 'test_890@example.com', '$2y$10$wM/z/jfN4RRXo7r8xad0fO6qozseobmj6kP2XQmlV1eoValCoxk12', '', 'user', 'approved', NULL, '2026-05-01 22:34:24', '2026-05-01 22:34:24');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('16', 'Test', 'test123@example.com', '$2y$10$hDoUu4pylyvmN8HHMciCMuQyFWPBFygsSLox9SnW8PaNHZOgBwqk.', '', 'user', 'approved', NULL, '2026-05-01 22:35:45', '2026-05-01 22:35:45');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('17', 'Test', 'test_1777664329@example.com', '$2y$10$N6ZsiHIYgz0nZ5tvL0mP3e.SF0GSNoZPoIMbBqiaIB9YhNy/AIQLO', '', 'user', 'approved', NULL, '2026-05-01 22:38:50', '2026-05-01 22:38:50');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('18', 'Test User', 'newuser_1777664455@example.com', '$2y$10$kwetQEBz.Re14prXgaovg.QHMjZN0Nm7f4f9albQLZUnWhAC2ewyy', '', 'user', 'approved', NULL, '2026-05-01 22:40:55', '2026-05-01 22:40:55');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('19', 'Test Login', 'logintest@example.com', '$2y$10$PdNeRl9.NqiJzAMjAE80SuWcWTimFE37Q.Rt31Mt5aiwvc6RbjCOu', '', 'user', 'approved', NULL, '2026-05-01 22:41:27', '2026-05-01 22:41:27');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('20', 'Test Full Flow', 'fulltest_1777667522@example.com', '$2y$10$69.N2dHJBJiYj4R1dzjW3OCcbDdBFUwkrCCDjOaokrDHxg859pooG', '', 'user', 'approved', NULL, '2026-05-01 23:32:03', '2026-05-01 23:32:03');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('21', 'E2E Test', 'e2e_1777667611@example.com', '$2y$10$whOyFbaY.sLQweM3rUoxm.GoRe33rDC9tCHRzgW.ngn0eCpFSiGTK', '', 'user', 'approved', NULL, '2026-05-01 23:33:31', '2026-05-01 23:33:31');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('22', 'Ahmed', 'ahme@gmail.com', '$2y$10$7dTr3gucHt4mOyzyUPqKuOU7gGt8Jg.dZEHJsX6.MjlLYeFJWFXfK', '', 'user', 'approved', NULL, '2026-05-01 23:44:21', '2026-05-01 23:44:21');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('23', 'ads', 'ahmedmedo122@gmail.com', '$2y$10$FsuXHqxd4/DqwtgGfzxENOP/ZDMfZ6TuIhNLjb1cKUr9U.fhXSqa2', '', 'admin', 'approved', NULL, '2026-05-01 23:45:15', '2026-05-05 19:36:39');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('24', 'E2E Final', 'e2e_1777668929@example.com', '$2y$10$9yVAhM1F4T6HERFf46SP5e4iYlqkro6oQ81otkqb6TED5HnCwM2iS', '', 'user', 'approved', NULL, '2026-05-01 23:55:29', '2026-05-01 23:55:29');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('25', 'Ahmed', 'ahm@gmail.com', '$2y$10$omp104kb63tr/RpN.SOjkubzyGpBvOYGS7yZMP0KqbrJmRbtG24L.', '', 'user', 'approved', NULL, '2026-05-02 01:54:05', '2026-05-02 01:54:05');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('26', 'Test User', 'testuser@example.com', '$2y$10$CAlFI4GlgP/iVRONM6vnNOJRAXXPoGRrAZwz8Y4.pS48JbjFoQira', '', 'user', 'approved', NULL, '2026-05-02 14:32:15', '2026-05-02 14:32:15');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('27', 'Ahmed', 'a@gmail.com', '$2y$12$2wkqdz1ulzo0w4kkaneN8OIUzcqLtu8jHB7CxR5rat8X5pLeUVsca', '', 'user', 'approved', NULL, '2026-05-03 16:18:30', '2026-05-03 16:18:30');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('28', 'Test User', 'uniqueuser123@example.com', '$2y$12$7QDc81jBZDF8x04904Vm9.wZCUk48QQxgQiVH4Hdw27MhTevs3XbO', '', 'user', 'approved', NULL, '2026-05-03 16:26:58', '2026-05-03 16:26:58');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('34', 'owner_test', 'owner@gmail.com', '$2y$12$IW7N2tExOiv317MkbPxqt.ogjYP3FVCeoac3le7lpg3tqmO0F6hb.', '', 'owner', 'approved', NULL, '2026-05-05 20:28:45', '2026-05-05 20:29:18');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('35', 'user_test', 'user@gmail.com', '$2y$12$SOVc.gy4K97TpjdLJWWGZuGG8U4xyH6zEVfAn7fxYCriNd0nLwUcK', '0222222222', 'user', 'approved', '', '2026-05-05 20:31:41', '2026-05-05 20:45:24');
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`, `avatar`, `created_at`, `updated_at`) VALUES ('36', 'Owner Test 2', 'owner2@gmail.com', '$2y$12$TCbtkg1IO.g/Ek5/8SjogedhNl6ypaXr8cGFudpTMBF3MomqeoBHC', '', 'owner', 'approved', NULL, '2026-05-05 20:42:28', '2026-05-05 20:42:51');

SET FOREIGN_KEY_CHECKS=1;
