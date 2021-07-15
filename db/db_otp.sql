-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `action`;
CREATE TABLE `action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_id` varchar(50) NOT NULL,
  `action_id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `action` (`id`, `controller_id`, `action_id`, `name`) VALUES
(12,	'site',	'index',	'Index'),
(13,	'site',	'profile',	'Profile'),
(14,	'site',	'login',	'Login'),
(15,	'site',	'logout',	'Logout'),
(16,	'site',	'contact',	'Contact'),
(17,	'site',	'about',	'About'),
(18,	'menu',	'index',	'Index'),
(19,	'menu',	'view',	'View'),
(20,	'menu',	'create',	'Create'),
(21,	'menu',	'update',	'Update'),
(22,	'menu',	'delete',	'Delete'),
(23,	'role',	'index',	'Index'),
(24,	'role',	'view',	'View'),
(25,	'role',	'create',	'Create'),
(26,	'role',	'update',	'Update'),
(27,	'role',	'delete',	'Delete'),
(28,	'role',	'detail',	'Detail'),
(29,	'user',	'index',	'Index'),
(30,	'user',	'view',	'View'),
(31,	'user',	'create',	'Create'),
(32,	'user',	'update',	'Update'),
(33,	'user',	'delete',	'Delete'),
(34,	'site',	'register',	'Register'),
(35,	'site',	'register-guru',	'Register Guru'),
(36,	'site',	'get-kota',	'Get Kota'),
(37,	'site',	'get-kecamatan',	'Get Kecamatan'),
(38,	'site',	'get-desa',	'Get Desa'),
(39,	'menu',	'save',	'Save'),
(40,	'sumber-artikel',	'index',	'Index'),
(41,	'sumber-artikel',	'create',	'Create'),
(42,	'sumber-artikel',	'update',	'Update'),
(43,	'sumber-artikel',	'delete',	'Delete'),
(44,	'sumber-artikel',	'view',	'View'),
(45,	'kategori-kegiatan',	'index',	'Index'),
(46,	'kategori-kegiatan',	'create',	'Create'),
(47,	'kategori-kegiatan',	'update',	'Update'),
(48,	'kategori-kegiatan',	'delete',	'Delete'),
(49,	'kategori-kegiatan',	'view',	'View'),
(50,	'kategori-artikel',	'index',	'Index'),
(51,	'kategori-artikel',	'create',	'Create'),
(52,	'kategori-artikel',	'update',	'Update'),
(53,	'kategori-artikel',	'delete',	'Delete'),
(54,	'kategori-artikel',	'view',	'View'),
(55,	'artikel',	'index',	'Index'),
(56,	'artikel',	'create',	'Create'),
(57,	'artikel',	'update',	'Update'),
(58,	'artikel',	'delete',	'Delete'),
(59,	'artikel',	'view',	'View'),
(60,	'kegiatan',	'index',	'Index'),
(61,	'kegiatan',	'create',	'Create'),
(62,	'kegiatan',	'update',	'Update'),
(63,	'kegiatan',	'delete',	'Delete'),
(64,	'kegiatan',	'view',	'View'),
(65,	'rumah-sakit',	'index',	'Index'),
(66,	'rumah-sakit',	'create',	'Create'),
(67,	'rumah-sakit',	'update',	'Update'),
(68,	'rumah-sakit',	'delete',	'Delete'),
(69,	'rumah-sakit',	'view',	'View');

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL DEFAULT 'index',
  `icon` varchar(50) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `menu` (`id`, `name`, `controller`, `action`, `icon`, `order`, `parent_id`) VALUES
(1,	'Home',	'site',	'index',	'fa fa-home',	1,	NULL),
(2,	'Master',	'',	'index',	'fa fa-database',	2,	NULL),
(3,	'Menu',	'menu',	'index',	'fa fa-circle-o',	3,	2),
(4,	'Role',	'role',	'index',	'fa fa-circle-o',	4,	2),
(5,	'User',	'user',	'index',	'fa fa-circle-o',	5,	2),
(6,	'Menu',	'#',	'index',	'fa fa-database',	1,	NULL),
(7,	'Sumber Artikel',	'sumber-artikel',	'index',	'fa fa-code-fork',	1,	6),
(8,	'Kategori Kegiatan',	'kategori-kegiatan',	'index',	'fa fa-code-fork',	1,	6),
(9,	'Kategori Artikel',	'kategori-artikel',	'index',	'fa fa-code-fork',	1,	6),
(10,	'Artikel',	'artikel',	'index',	'fa fa-newspaper-o',	1,	NULL),
(11,	'Kegiatan',	'kegiatan',	'index',	'fa fa-female',	1,	NULL),
(12,	'Rumah Sakit',	'rumah-sakit',	'index',	'fa fa-hospital-o',	1,	NULL);

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `role` (`id`, `name`) VALUES
(1,	'Super Administrator'),
(2,	'Administrator'),
(3,	'Regular User');

DROP TABLE IF EXISTS `role_action`;
CREATE TABLE `role_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `action_id` (`action_id`),
  CONSTRAINT `role_action_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `role_action_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `action` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `role_action` (`id`, `role_id`, `action_id`) VALUES
(33,	2,	12),
(34,	2,	13),
(35,	2,	14),
(36,	2,	15),
(37,	2,	16),
(38,	2,	17),
(39,	2,	18),
(40,	2,	19),
(41,	2,	20),
(42,	2,	21),
(43,	2,	22),
(44,	2,	23),
(45,	2,	24),
(46,	2,	25),
(47,	2,	26),
(48,	2,	27),
(49,	2,	28),
(50,	2,	29),
(51,	2,	30),
(52,	2,	31),
(53,	2,	32),
(54,	2,	33),
(98,	3,	12),
(99,	3,	13),
(100,	3,	14),
(101,	3,	15),
(102,	3,	16),
(103,	3,	17),
(104,	3,	18),
(105,	3,	19),
(106,	3,	20),
(107,	3,	21),
(108,	3,	22),
(109,	3,	23),
(110,	3,	24),
(111,	3,	25),
(112,	3,	26),
(113,	3,	27),
(114,	3,	28),
(115,	3,	29),
(116,	3,	30),
(117,	3,	31),
(118,	3,	32),
(119,	3,	33),
(202,	1,	12),
(203,	1,	13),
(204,	1,	14),
(205,	1,	15),
(206,	1,	17),
(207,	1,	18),
(208,	1,	19),
(209,	1,	20),
(210,	1,	21),
(211,	1,	22),
(212,	1,	23),
(213,	1,	24),
(214,	1,	25),
(215,	1,	26),
(216,	1,	27),
(217,	1,	28),
(218,	1,	29),
(219,	1,	30),
(220,	1,	31),
(221,	1,	32),
(222,	1,	33),
(223,	1,	40),
(224,	1,	41),
(225,	1,	42),
(226,	1,	43),
(227,	1,	44),
(228,	1,	45),
(229,	1,	46),
(230,	1,	47),
(231,	1,	48),
(232,	1,	49),
(233,	1,	50),
(234,	1,	51),
(235,	1,	52),
(236,	1,	53),
(237,	1,	54),
(238,	1,	55),
(239,	1,	56),
(240,	1,	57),
(241,	1,	58),
(242,	1,	59),
(243,	1,	60),
(244,	1,	61),
(245,	1,	62),
(246,	1,	63),
(247,	1,	64),
(248,	1,	65),
(249,	1,	66),
(250,	1,	67),
(251,	1,	68),
(252,	1,	69);

DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `role_menu_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `role_menu_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `role_menu` (`id`, `role_id`, `menu_id`) VALUES
(56,	2,	1),
(57,	2,	2),
(58,	2,	3),
(59,	2,	4),
(60,	2,	5),
(71,	3,	1),
(72,	3,	2),
(73,	3,	3),
(74,	3,	4),
(75,	3,	5),
(96,	1,	1),
(97,	1,	2),
(98,	1,	3),
(99,	1,	4),
(100,	1,	5),
(101,	1,	6),
(102,	1,	7),
(103,	1,	8),
(104,	1,	9),
(105,	1,	10),
(106,	1,	11),
(107,	1,	12);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL,
  `provinsi_id` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `kota_id` char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `kecamatan_id` char(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `desa_id` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `secret_token` varchar(200) DEFAULT NULL,
  `fcm_token` varchar(200) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `verifikasi` int(11) NOT NULL DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `provinsi_id` (`provinsi_id`),
  KEY `kota_id` (`kota_id`),
  KEY `kecamatan_id` (`kecamatan_id`),
  KEY `desa_id` (`desa_id`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `user_ibfk_2` FOREIGN KEY (`provinsi_id`) REFERENCES `wilayah_provinsi` (`id`),
  CONSTRAINT `user_ibfk_3` FOREIGN KEY (`kota_id`) REFERENCES `wilayah_kota` (`id`),
  CONSTRAINT `user_ibfk_4` FOREIGN KEY (`kecamatan_id`) REFERENCES `wilayah_kecamatan` (`id`),
  CONSTRAINT `user_ibfk_5` FOREIGN KEY (`desa_id`) REFERENCES `wilayah_desa` (`id`),
  CONSTRAINT `user_ibfk_6` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user` (`id`, `username`, `password`, `name`, `role_id`, `provinsi_id`, `kota_id`, `kecamatan_id`, `desa_id`, `alamat`, `no_hp`, `profile_id`, `secret_token`, `fcm_token`, `photo_url`, `verifikasi`, `last_login`, `last_logout`) VALUES
(1,	'admin@email.com',	'21232f297a57a5a743894a0e4a801fc3',	'Administrator',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'Bt19MTYyMzErejVsQzNpSUtUZHZ2QnkybGt4ZWZG_51scu_U21ISE1pQy1ab3dISHNsbERTQis0MDIyMg==s3cr37k3Y',	NULL,	'ID6jM8Az7Yh_R6LR44Ezh02VECKTQ_Ya.png',	1,	'2015-12-16 22:35:47',	'2015-12-16 22:35:47'),
(2,	'user@email.com',	'ee11cbb19052e40b07aac0ca060c23ee',	'Regular User',	3,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'Bt19MTYyMzAra0Z2QTF3RFkxRVVpcWk5ek1iTGVi_A2pg8_d3hpaDFQYjZXdVVqV1JUWGZNNUNEKzQ5NTM2s3cr37k3Y',	NULL,	'default.png',	0,	NULL,	NULL),
(15,	'defrindr@lanis.co.id',	'6c68e4b7461ef94333a44f4cdfc9490e',	'Defri',	3,	'35',	'3502',	'3502070',	'3502070002',	'Pulung',	'6285604845435',	12,	'Bt19MTYyMzUrYnYwbFNUUWlGd29YYjgxSnhZQ0RoSXM3eU_PBq-c_FwYlZqUGh0N3huUEtWVzFtTFdLSnJQLTArNzYzMTQ=s3cr37k3Y',	NULL,	'user/6a3236f5f213960de5c415fd15c6d1cb5a46d04e.png',	1,	'2021-06-13 11:25:14',	NULL),
(17,	'hardiansah7101@gmail.com',	'8ce87b8ec346ff4c80635f667d1592ae',	'Aku Sendiri',	3,	'35',	'3502',	'3502190',	'3502190009',	'Setutup',	'6281911106262',	13,	'Bt19MTYyMzUrbHFGZXlWdEhXNGl1eTcrQjBOVHVx_WKWrZ_Z1E3bmliK1k1TTBNbVhKVWx5MSszMTI5MQ==s3cr37k3Y',	NULL,	NULL,	1,	'2021-06-12 22:54:51',	NULL);

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `user_otp`;
CREATE TABLE `user_otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `is_used` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_otp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 2021-06-22 07:15:51
