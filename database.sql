-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 09 2018 г., 09:51
-- Версия сервера: 5.6.38
-- Версия PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `database`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_actions_log`
--

CREATE TABLE `tbl_actions_log` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `action` int(2) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_user` text,
  `affected_file` int(11) DEFAULT NULL,
  `affected_account` int(11) DEFAULT NULL,
  `affected_file_name` text,
  `affected_account_name` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_actions_log`
--

INSERT INTO `tbl_actions_log` (`id`, `timestamp`, `action`, `owner_id`, `owner_user`, `affected_file`, `affected_account`, `affected_file_name`, `affected_account_name`) VALUES
(1, '2018-03-28 18:31:47', 0, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(2, '2018-03-28 18:32:20', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(3, '2018-03-28 18:41:34', 5, 1, 'Bunyodbek Jumanazarov', 1, NULL, 'svod branchesandcashieroffices2016-1', 'admin'),
(4, '2018-03-28 18:43:05', 34, 1, 'Bunyodbek Jumanazarov', NULL, 1, NULL, 'кредит'),
(5, '2018-03-28 18:46:07', 23, 1, 'Bunyodbek Jumanazarov', NULL, 1, NULL, 'xorazm'),
(6, '2018-03-28 18:46:36', 3, 1, 'Bunyodbek Jumanazarov', NULL, 2, NULL, 'audit1'),
(7, '2018-03-28 18:46:42', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(8, '2018-03-28 18:46:49', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(9, '2018-03-28 18:47:11', 6, 2, 'audit1', 2, NULL, 'employee-master-list', 'audit1'),
(10, '2018-03-28 18:47:35', 9, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(11, '2018-03-28 18:47:50', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(12, '2018-03-28 18:47:55', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(13, '2018-03-28 18:48:28', 2, 1, 'Bunyodbek Jumanazarov', NULL, 3, NULL, 'caudit1'),
(14, '2018-03-28 18:48:31', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(15, '2018-03-28 18:48:34', 1, 3, 'caudit1', NULL, NULL, NULL, 'caudit1'),
(16, '2018-03-28 18:49:25', 5, 3, 'caudit1', 3, NULL, '2018-02-28104748', 'caudit1'),
(17, '2018-03-28 18:49:25', 26, 3, 'caudit1', 3, 1, '2018-02-28104748', 'xorazm'),
(18, '2018-03-28 18:49:28', 31, 3, 'caudit1', NULL, NULL, NULL, NULL),
(19, '2018-03-28 18:49:34', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(20, '2018-03-28 18:55:38', 7, 1, 'Bunyodbek Jumanazarov', 3, 1, '2018-02-28104748', 'Bunyodbek Jumanazarov'),
(21, '2018-03-28 18:56:28', 26, 1, 'Bunyodbek Jumanazarov', 2, 1, 'employee-master-list', 'xorazm'),
(22, '2018-03-28 18:56:28', 32, 1, 'admin', 2, NULL, 'employee-master-list', NULL),
(23, '2018-03-28 19:01:24', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(24, '2018-03-28 19:03:22', 2, 1, 'Bunyodbek Jumanazarov', NULL, 4, NULL, 'manager'),
(25, '2018-03-28 19:04:03', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(26, '2018-03-28 19:04:10', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(27, '2018-03-28 19:05:16', 6, 2, 'audit1', 4, NULL, 'affillangan shaxslar', 'audit1'),
(28, '2018-03-28 19:06:04', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(29, '2018-03-28 19:06:09', 1, 3, 'caudit1', NULL, NULL, NULL, 'caudit1'),
(30, '2018-03-28 19:07:55', 31, 3, 'caudit1', NULL, NULL, NULL, NULL),
(31, '2018-03-28 19:07:59', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(32, '2018-03-28 19:08:26', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(33, '2018-03-28 19:08:31', 1, 4, 'manager', NULL, NULL, NULL, 'manager'),
(34, '2018-03-28 19:08:58', 32, 4, 'manager', 4, NULL, 'affillangan shaxslar', NULL),
(35, '2018-03-28 19:09:01', 31, 4, 'manager', NULL, NULL, NULL, NULL),
(36, '2018-03-28 19:09:05', 1, 3, 'caudit1', NULL, NULL, NULL, 'caudit1'),
(37, '2018-03-28 19:09:48', 7, 3, 'caudit1', 3, 3, '2018-02-28104748', 'caudit1'),
(38, '2018-03-28 19:10:21', 31, 3, 'caudit1', NULL, NULL, NULL, NULL),
(39, '2018-03-28 19:10:28', 1, 4, 'manager', NULL, NULL, NULL, 'manager'),
(40, '2018-03-28 19:22:32', 31, 4, 'manager', NULL, NULL, NULL, NULL),
(41, '2018-03-28 19:22:36', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(42, '2018-03-28 19:23:27', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(43, '2018-03-28 19:25:42', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(44, '2018-03-28 19:43:01', 35, 1, 'Bunyodbek Jumanazarov', NULL, 1, NULL, 'Хоразм'),
(45, '2018-03-28 19:43:21', 15, 1, 'Bunyodbek Jumanazarov', NULL, 1, NULL, 'Бухгалтерия'),
(46, '2018-03-29 04:00:23', 34, 1, 'Bunyodbek Jumanazarov', NULL, 2, NULL, 'Андижон'),
(47, '2018-03-29 04:00:27', 34, 1, 'Bunyodbek Jumanazarov', NULL, 3, NULL, 'Андижон'),
(48, '2018-03-29 04:01:14', 34, 1, 'Bunyodbek Jumanazarov', NULL, 4, NULL, 'Самарқанд'),
(49, '2018-03-29 04:01:19', 34, 1, 'Bunyodbek Jumanazarov', NULL, 5, NULL, 'Самарқанд'),
(50, '2018-03-29 04:01:39', 34, 1, 'Bunyodbek Jumanazarov', NULL, 6, NULL, 'Бухоро'),
(51, '2018-03-29 04:01:52', 36, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Андижон'),
(52, '2018-03-29 04:01:52', 36, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Андижон'),
(53, '2018-03-29 04:01:52', 36, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Самарқанд'),
(54, '2018-03-29 10:32:46', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(55, '2018-03-29 10:32:49', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(56, '2018-03-29 13:28:48', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(57, '2018-03-29 13:28:59', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(58, '2018-03-29 13:29:46', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(59, '2018-03-29 13:29:49', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(60, '2018-03-29 13:31:53', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(61, '2018-03-29 13:31:57', 1, 3, 'caudit1', NULL, NULL, NULL, 'caudit1'),
(62, '2018-03-29 13:32:03', 31, 3, 'caudit1', NULL, NULL, NULL, NULL),
(63, '2018-03-29 13:32:07', 1, 4, 'manager', NULL, NULL, NULL, 'manager'),
(64, '2018-03-29 13:32:13', 31, 4, 'manager', NULL, NULL, NULL, NULL),
(65, '2018-03-29 13:32:16', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(66, '2018-03-29 14:02:16', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(67, '2018-03-29 14:02:20', 1, 3, 'caudit1', NULL, NULL, NULL, 'caudit1'),
(68, '2018-03-29 14:07:09', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(69, '2018-03-29 21:25:58', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(70, '2018-03-29 21:34:50', 6, 2, 'audit1', 5, NULL, 'akt', 'audit1'),
(71, '2018-03-29 21:35:54', 6, 2, 'audit1', 6, NULL, 'ui-auditcontrolsystem', 'audit1'),
(72, '2018-03-29 22:02:36', 33, 2, 'audit1', 6, NULL, 'ui-auditcontrolsystem', NULL),
(73, '2018-03-29 22:03:06', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(74, '2018-03-29 22:03:11', 1, 3, 'caudit1', NULL, NULL, NULL, 'caudit1'),
(75, '2018-03-29 22:07:29', 31, 3, 'caudit1', NULL, NULL, NULL, NULL),
(76, '2018-03-29 22:07:33', 1, 4, 'manager', NULL, NULL, NULL, 'manager'),
(77, '2018-03-29 22:10:10', 32, 4, 'manager', 6, NULL, 'ui-auditcontrolsystem', NULL),
(78, '2018-03-29 22:10:22', 26, 4, 'manager', 6, 1, 'ui-auditcontrolsystem', 'Бухгалтерия'),
(79, '2018-03-29 22:10:22', 32, 4, 'manager', 6, NULL, 'ui-auditcontrolsystem', NULL),
(80, '2018-03-29 22:10:54', 5, 4, 'manager', 7, NULL, 'login', 'manager'),
(81, '2018-03-29 22:10:54', 26, 4, 'manager', 7, 1, 'login', 'Бухгалтерия'),
(82, '2018-03-29 22:11:15', 31, 4, 'manager', NULL, NULL, NULL, NULL),
(83, '2018-03-29 22:11:19', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(84, '2018-03-29 22:18:16', 6, 2, 'audit1', 8, NULL, 'table', 'audit1'),
(85, '2018-03-29 22:18:46', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(86, '2018-03-29 22:18:49', 1, 4, 'manager', NULL, NULL, NULL, 'manager'),
(87, '2018-03-29 22:18:51', 30, 4, 'manager', NULL, NULL, NULL, 'cs2903'),
(88, '2018-03-29 22:20:03', 26, 4, 'manager', 8, 1, 'table', 'Бухгалтерия'),
(89, '2018-03-29 22:20:03', 32, 4, 'manager', 8, NULL, 'table', NULL),
(90, '2018-03-29 22:20:13', 31, 4, 'manager', NULL, NULL, NULL, NULL),
(91, '2018-03-29 22:20:18', 1, 2, 'audit1', NULL, NULL, NULL, 'audit1'),
(92, '2018-03-29 22:20:30', 8, 2, 'audit1', 8, 2, 'table', 'audit1'),
(93, '2018-03-29 22:24:53', 31, 2, 'audit1', NULL, NULL, NULL, NULL),
(94, '2018-03-29 22:25:01', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(95, '2018-03-29 22:26:16', 32, 1, 'admin', 7, NULL, 'login', NULL),
(96, '2018-03-29 22:34:12', 12, 1, 'Bunyodbek Jumanazarov', 2, NULL, 'employee-master-list', NULL),
(97, '2018-03-29 22:34:12', 12, 1, 'Bunyodbek Jumanazarov', 3, NULL, '2018-02-28104748', NULL),
(98, '2018-03-29 22:34:12', 12, 1, 'Bunyodbek Jumanazarov', 4, NULL, 'affillangan shaxslar', NULL),
(99, '2018-03-29 22:34:12', 12, 1, 'Bunyodbek Jumanazarov', 5, NULL, 'akt', NULL),
(100, '2018-03-29 22:34:12', 12, 1, 'Bunyodbek Jumanazarov', 6, NULL, 'ui-auditcontrolsystem', NULL),
(101, '2018-03-29 22:34:12', 12, 1, 'Bunyodbek Jumanazarov', 7, NULL, 'login', NULL),
(102, '2018-03-29 22:34:53', 36, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Бухоро'),
(103, '2018-03-29 22:34:53', 36, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Самарқанд'),
(104, '2018-03-29 22:34:53', 36, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Хоразм'),
(105, '2018-03-29 22:36:37', 17, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'audit1'),
(106, '2018-03-29 22:36:48', 16, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'caudit1'),
(107, '2018-03-29 22:36:48', 16, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'manager'),
(108, '2018-03-29 22:39:17', 23, 1, 'Bunyodbek Jumanazarov', NULL, 2, NULL, '00560_Пластик'),
(109, '2018-03-29 22:40:14', 23, 1, 'Bunyodbek Jumanazarov', NULL, 3, NULL, '00560_Кредит'),
(110, '2018-03-29 22:45:01', 34, 1, 'Bunyodbek Jumanazarov', NULL, 7, NULL, 'Хоразм_00560'),
(111, '2018-03-29 22:48:10', 3, 1, 'Bunyodbek Jumanazarov', NULL, 5, NULL, 'Авезов Ҳушнуд'),
(112, '2018-03-29 22:53:12', 3, 1, 'Bunyodbek Jumanazarov', NULL, 6, NULL, 'Маткаримов Мурод'),
(113, '2018-03-29 22:55:23', 3, 1, 'Bunyodbek Jumanazarov', NULL, 7, NULL, 'Ортиқов Олимжон Саматович'),
(114, '2018-03-29 22:56:38', 2, 1, 'Bunyodbek Jumanazarov', NULL, 8, NULL, 'Қаландаров Темур Шарипович'),
(115, '2018-03-29 22:58:58', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(116, '2018-03-29 22:59:03', 1, 5, 'Авезов Ҳушнуд', NULL, NULL, NULL, 'Авезов Ҳушнуд'),
(117, '2018-03-29 23:03:42', 6, 5, 'Авезов Ҳушнуд', 9, NULL, 'login', 'audit1'),
(118, '2018-03-29 23:03:56', 33, 5, 'audit1', 9, NULL, '00560_Xonqa', NULL),
(119, '2018-03-29 23:04:10', 8, 5, 'Авезов Ҳушнуд', 9, 5, '00560_Xonqa', 'Авезов Ҳушнуд'),
(120, '2018-03-29 23:05:15', 6, 5, 'Авезов Ҳушнуд', 10, NULL, 'Kredit bolimi uchun umumlashtirgan AKT', 'audit1'),
(121, '2018-03-29 23:05:45', 31, 5, 'Авезов Ҳушнуд', NULL, NULL, NULL, NULL),
(122, '2018-03-29 23:05:50', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(123, '2018-03-29 23:06:06', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(124, '2018-03-29 23:06:09', 1, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, 'Қаландаров Темур Шарипович'),
(125, '2018-03-29 23:06:57', 10, 8, 'Қаландаров Темур Шарипович', 10, 5, 'Kredit bolimi uchun umumlashtirgan AKT', 'Авезов Ҳушнуд'),
(126, '2018-03-29 23:06:57', 26, 8, 'Қаландаров Темур Шарипович', 10, 3, 'Kredit bolimi uchun umumlashtirgan AKT', '00560_Кредит'),
(127, '2018-03-29 23:06:57', 32, 8, 'manager', 10, NULL, 'Kredit bolimi uchun umumlashtirgan AKT', NULL),
(128, '2018-03-29 23:07:25', 10, 8, 'Қаландаров Темур Шарипович', 9, 5, '00560_Xonqa', 'Авезов Ҳушнуд'),
(129, '2018-03-29 23:07:25', 26, 8, 'Қаландаров Темур Шарипович', 9, 3, '00560_Xonqa', '00560_Кредит'),
(130, '2018-03-29 23:07:25', 32, 8, 'manager', 9, NULL, '00560_Xonqa', NULL),
(131, '2018-03-29 23:07:42', 31, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, NULL),
(132, '2018-03-29 23:07:47', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(133, '2018-03-29 23:21:17', 2, 1, 'Bunyodbek Jumanazarov', NULL, 9, NULL, 'Хакимов Жавохир Оллоёров'),
(134, '2018-03-29 23:21:28', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(135, '2018-03-29 23:21:31', 1, 9, 'Хакимов Жавохир Оллоёров', NULL, NULL, NULL, 'Хакимов Жавохир Оллоёров'),
(136, '2018-03-29 23:21:51', 31, 9, 'Хакимов Жавохир Оллоёров', NULL, NULL, NULL, NULL),
(137, '2018-03-29 23:21:55', 1, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, 'Қаландаров Темур Шарипович'),
(138, '2018-03-30 04:03:04', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(139, '2018-03-30 04:04:41', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(140, '2018-03-30 04:05:23', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(141, '2018-03-30 04:55:34', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(142, '2018-03-30 05:12:59', 1, 5, 'Авезов Ҳушнуд', NULL, NULL, NULL, 'Авезов Ҳушнуд'),
(143, '2018-03-30 06:43:59', 1, 5, 'Авезов Ҳушнуд', NULL, NULL, NULL, 'Авезов Ҳушнуд'),
(144, '2018-03-30 06:48:53', 6, 5, 'Авезов Ҳушнуд', 11, NULL, 'andijon svod kredit', 'audit1'),
(145, '2018-03-30 06:50:01', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(146, '2018-03-30 06:50:11', 8, 5, 'Авезов Ҳушнуд', 11, 5, 'andijon svod kredit', 'Авезов Ҳушнуд'),
(147, '2018-03-30 06:58:58', 6, 5, 'Авезов Ҳушнуд', 12, NULL, 'Андижон-бухгалтерия', 'audit1'),
(148, '2018-03-30 07:04:11', 31, 5, 'Авезов Ҳушнуд', NULL, NULL, NULL, NULL),
(149, '2018-03-30 07:04:57', 1, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, 'Қаландаров Темур Шарипович'),
(150, '2018-03-30 07:10:35', 10, 8, 'Қаландаров Темур Шарипович', 12, 5, 'Андижон-бухгалтерия', 'Авезов Ҳушнуд'),
(151, '2018-03-30 07:10:35', 26, 8, 'Қаландаров Темур Шарипович', 12, 2, 'Андижон-бухгалтерия', '00560_Пластик'),
(152, '2018-03-30 07:10:35', 32, 8, 'manager', 12, NULL, 'Андижон-бухгалтерия', NULL),
(153, '2018-03-30 07:11:43', 31, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, NULL),
(154, '2018-03-30 07:12:12', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(155, '2018-03-30 09:06:36', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(156, '2018-03-30 10:21:29', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(157, '2018-03-30 12:20:08', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(158, '2018-03-30 12:20:44', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(159, '2018-03-30 12:23:10', 6, 5, 'Анваржон Абдуллаев', 13, NULL, 'Халқаро пул ўтказма', 'audit1'),
(160, '2018-03-30 13:46:46', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(161, '2018-03-30 13:48:04', 6, 5, 'Анваржон Абдуллаев', 14, NULL, 'yyyyyyyyyy', 'audit1'),
(162, '2018-03-30 13:49:28', 6, 5, 'Анваржон Абдуллаев', 15, NULL, 'uuuuuuuuuuuu', 'audit1'),
(163, '2018-03-30 13:50:20', 8, 5, 'Анваржон Абдуллаев', 15, 5, 'uuuuuuuuuuuu', 'Анваржон Абдуллаев'),
(164, '2018-03-30 14:05:44', 31, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, NULL),
(165, '2018-03-30 14:05:58', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(166, '2018-03-30 14:07:10', 13, 1, 'Bunyodbek Jumanazarov', NULL, 1, NULL, 'Bunyodbek Jumanazarov'),
(167, '2018-03-30 14:12:44', 21, 1, 'Bunyodbek Jumanazarov', 9, NULL, '00560_Xonqa', '00560_Кредит'),
(168, '2018-03-30 14:12:44', 21, 1, 'Bunyodbek Jumanazarov', 10, NULL, 'Kredit bolimi uchun umumlashtirgan AKT', '00560_Кредит'),
(169, '2018-03-30 14:32:06', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(170, '2018-03-30 14:32:25', 1, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, 'Қаландаров Темур Шарипович'),
(171, '2018-03-30 14:33:33', 13, 8, 'Қаландаров Темур Шарипович', NULL, 8, NULL, 'Қаландаров Темур Шарипович'),
(172, '2018-03-30 14:51:46', 31, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, NULL),
(173, '2018-03-31 14:14:00', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(174, '2018-03-31 14:14:49', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(175, '2018-03-31 14:15:14', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(176, '2018-04-02 08:50:41', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(177, '2018-04-02 11:11:18', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(178, '2018-04-03 05:07:44', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(179, '2018-04-03 05:25:15', 16, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Хакимов Жавохир Оллоёров'),
(180, '2018-04-03 06:56:02', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(181, '2018-04-03 07:33:39', 5, 1, 'Bunyodbek Jumanazarov', 16, NULL, 'report', 'admin'),
(182, '2018-04-03 07:33:39', 25, 1, 'Bunyodbek Jumanazarov', 16, 6, 'report', 'Зафаржон Каримов'),
(183, '2018-04-03 07:33:39', 26, 1, 'Bunyodbek Jumanazarov', 16, 3, 'report', '00560_Кредит'),
(184, '2018-04-03 07:47:15', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(185, '2018-04-03 07:47:24', 1, 8, 'Қаландаров Темур Шарипович', NULL, NULL, NULL, 'Қаландаров Темур Шарипович'),
(186, '2018-04-03 07:49:28', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(187, '2018-04-03 07:51:10', 6, 5, 'Анваржон Абдуллаев', 17, NULL, '00071_Shokhrukhbek_Kredit_kunlik hisoboti', 'audit1'),
(188, '2018-04-03 07:53:46', 32, 8, 'manager', 17, NULL, '00071_Shokhrukhbek_Kredit_kunlik hisoboti', NULL),
(189, '2018-04-03 07:54:24', 31, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, NULL),
(190, '2018-04-03 07:56:04', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(191, '2018-04-03 08:45:45', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(192, '2018-04-03 08:51:42', 31, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, NULL),
(193, '2018-04-03 08:51:51', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(194, '2018-04-03 11:06:32', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(195, '2018-04-04 07:04:30', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(196, '2018-04-05 05:21:12', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(197, '2018-04-05 05:55:51', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(198, '2018-04-05 05:57:00', 31, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, NULL),
(199, '2018-04-05 05:57:42', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(200, '2018-04-05 06:00:13', 7, 1, 'Bunyodbek Jumanazarov', 17, 1, '00071_Shokhrukhbek_Kredit_kunlik hisoboti', 'Bunyodbek Jumanazarov'),
(201, '2018-04-05 06:00:13', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(202, '2018-04-05 06:06:11', 31, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, NULL),
(203, '2018-04-05 06:06:17', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(204, '2018-04-05 06:06:32', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(205, '2018-04-06 09:31:43', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov'),
(206, '2018-04-06 09:33:30', 26, 1, 'Bunyodbek Jumanazarov', 12, 3, 'Андижон-бухгалтерия', '00560_Кредит'),
(207, '2018-04-06 09:33:30', 32, 1, 'admin', 12, NULL, 'Андижон-бухгалтерия', NULL),
(208, '2018-04-06 09:37:09', 31, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, NULL),
(209, '2018-04-06 09:37:16', 1, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, 'Анваржон Абдуллаев'),
(210, '2018-04-06 09:37:31', 6, 5, 'Анваржон Абдуллаев', 18, NULL, '481506131', 'audit1'),
(211, '2018-04-06 09:38:44', 6, 5, 'Анваржон Абдуллаев', 19, NULL, '481506131 1', 'audit1'),
(212, '2018-04-06 11:16:23', 31, 5, 'Анваржон Абдуллаев', NULL, NULL, NULL, NULL),
(213, '2018-04-06 11:16:29', 1, 1, 'Bunyodbek Jumanazarov', NULL, NULL, NULL, 'Bunyodbek Jumanazarov');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `description` text,
  `created_by` varchar(60) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_categories`
--

INSERT INTO `tbl_categories` (`id`, `name`, `parent`, `description`, `created_by`, `timestamp`) VALUES
(7, 'Хоразм_00560', NULL, 'Хоразм вилоят бошқармаси, Ҳонқа филиали', 'admin', '2018-03-29 22:45:01');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_categories_relations`
--

CREATE TABLE `tbl_categories_relations` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_categories_relations`
--

INSERT INTO `tbl_categories_relations` (`id`, `timestamp`, `file_id`, `cat_id`) VALUES
(6, '2018-03-29 23:06:57', 10, 7),
(7, '2018-03-29 23:07:25', 9, 7),
(8, '2018-04-03 07:33:39', 16, 7),
(9, '2018-04-06 09:33:30', 12, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_downloads`
--

CREATE TABLE `tbl_downloads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remote_ip` varchar(45) DEFAULT NULL,
  `remote_host` text,
  `anonymous` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_downloads`
--

INSERT INTO `tbl_downloads` (`id`, `user_id`, `file_id`, `timestamp`, `remote_ip`, `remote_host`, `anonymous`) VALUES
(5, 5, 9, '2018-03-29 23:04:10', '127.0.0.1', NULL, 0),
(6, 5, 11, '2018-03-30 06:50:11', '190.44.127.247', NULL, 0),
(7, 5, 15, '2018-03-30 13:50:20', '190.44.129.9', NULL, 0),
(8, 1, 17, '2018-04-05 06:00:13', '190.44.127.71', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_files`
--

CREATE TABLE `tbl_files` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `original_url` text NOT NULL,
  `filename` text NOT NULL,
  `description` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uploader` varchar(60) NOT NULL,
  `expires` int(1) NOT NULL DEFAULT '0',
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `public_allow` int(1) NOT NULL DEFAULT '0',
  `public_token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_files`
--

INSERT INTO `tbl_files` (`id`, `url`, `original_url`, `filename`, `description`, `timestamp`, `uploader`, `expires`, `expiry_date`, `public_allow`, `public_token`) VALUES
(9, '1522364622-b7938bc052013cea39895ceec4edc1fb04378766-login.txt', 'login.txt', '00560_Xonqa', '&lt;p&gt;Ushbu hujjat Xonqa filiali Kreditlash bolimidan olingan&lt;/p&gt;', '2018-03-29 23:03:42', 'audit1', 0, '2018-03-29 19:00:00', 1, 'GXhQBbWdq8KRZLhtCyCictn22YDVZ20E'),
(10, '1522364715-b7938bc052013cea39895ceec4edc1fb04378766-akt.doc', 'akt.doc', 'Kredit bolimi uchun umumlashtirgan AKT', '&lt;p&gt;00560 Xonqa filial uchun&amp;nbsp;umumlashtirgan AKT&lt;/p&gt;', '2018-03-29 23:05:15', 'audit1', 0, '2018-03-29 19:00:00', 1, 'vacY16dOVhRq5CJvODViYg1vUZ8mtLPy'),
(11, '1522392533-b7938bc052013cea39895ceec4edc1fb04378766-andijon_svod_kredit.doc', 'andijon_svod_kredit.doc', 'andijon svod kredit', '&lt;p&gt;Men ushbu habarni korib chiqish uchun yubordim&lt;/p&gt;', '2018-03-30 06:48:53', 'audit1', 0, '2018-03-29 19:00:00', 0, 'VZq68GVYCOKolYCHNhLLVjSUd9cYCprj'),
(12, '1522393138-b7938bc052013cea39895ceec4edc1fb04378766--.docx', '-.docx', 'Андижон-бухгалтерия', '&lt;p&gt;Андижон 0040&lt;/p&gt;', '2018-03-30 06:58:58', 'audit1', 1, '2018-04-06 19:00:00', 1, '820XK5H4eKAXDexAAgTra3uuYkEb6jgu'),
(13, '1522412590-b7938bc052013cea39895ceec4edc1fb04378766--_1.docx', '-_1.docx', 'Халқаро пул ўтказма', '', '2018-03-30 12:23:10', 'audit1', 0, '2018-03-29 19:00:00', 0, '061PMuYk3SSNzBblwuRyEUnwbHHQOcjB'),
(14, '1522417684-b7938bc052013cea39895ceec4edc1fb04378766-lexuz_1491803-1.doc', 'lexuz_1491803-1.doc', 'yyyyyyyyyy', '', '2018-03-30 13:48:04', 'audit1', 0, '2018-03-29 19:00:00', 0, 'cxisTdRsjIj2GTEMI5kMD9jCaq4pxLAJ'),
(15, '1522417768-b7938bc052013cea39895ceec4edc1fb04378766-lexuz_1491803.doc', 'lexuz_1491803.doc', 'uuuuuuuuuuuu', '&lt;p&gt;888888888&lt;/p&gt;', '2018-03-30 13:49:28', 'audit1', 0, '2018-03-29 19:00:00', 0, 'xKYrdYCN957tVOPVEmB7VsLxOja8WE9o'),
(16, '1522740819-d033e22ae348aeb5660fc2140aec35850c4da997-report.txt', 'report.txt', 'report', '&lt;p&gt;malumotnoma&lt;/p&gt;', '2018-04-03 07:33:39', 'admin', 1, '2018-04-24 19:00:00', 1, 'EpPdvZCNxNTYeVSzyXgWX0iVqDHSqYFD'),
(17, '1522741870-b7938bc052013cea39895ceec4edc1fb04378766-kompyuterlar-soni.docx', 'kompyuterlar-soni.docx', '00071_Shokhrukhbek_Kredit_kunlik hisoboti', '&lt;p&gt;kompyuterlar-soni haqida malumot&lt;/p&gt;', '2018-04-03 07:51:10', 'audit1', 0, '2018-04-02 19:00:00', 0, 'yXbcsS6COE2xCUHhkKjIUJBaQYMu44z0'),
(18, '1523007451-b7938bc052013cea39895ceec4edc1fb04378766-481506131.jpg', '481506131.jpg', '481506131', '&lt;p&gt;fsdfsdfsdf&lt;/p&gt;', '2018-04-06 09:37:31', 'audit1', 0, '2018-04-05 19:00:00', 0, '2MVUzPulp219KftWjMlOluAkxIFB7jye'),
(19, '1523007524-b7938bc052013cea39895ceec4edc1fb04378766-481506131_1.jpg', '481506131_1.jpg', '481506131 1', '', '2018-04-06 09:38:44', 'audit1', 0, '2018-04-05 19:00:00', 0, 'XLGoZvWN12YQwr2orkPLhMzOZoZT2giP');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_files_relations`
--

CREATE TABLE `tbl_files_relations` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `hidden` int(1) NOT NULL,
  `download_count` int(16) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_files_relations`
--

INSERT INTO `tbl_files_relations` (`id`, `timestamp`, `file_id`, `client_id`, `group_id`, `folder_id`, `hidden`, `download_count`) VALUES
(13, '2018-03-29 23:06:57', 10, NULL, 3, NULL, 1, 0),
(14, '2018-03-29 23:07:25', 9, NULL, 3, NULL, 1, 0),
(15, '2018-03-30 06:48:53', 11, 5, NULL, NULL, 0, 0),
(17, '2018-03-30 07:10:35', 12, NULL, 2, NULL, 0, 0),
(18, '2018-03-30 12:23:10', 13, 5, NULL, NULL, 0, 0),
(19, '2018-03-30 13:48:04', 14, 5, NULL, NULL, 0, 0),
(20, '2018-03-30 13:49:28', 15, 5, NULL, NULL, 0, 0),
(21, '2018-04-03 07:33:39', 16, 6, NULL, NULL, 0, 0),
(22, '2018-04-03 07:33:39', 16, NULL, 3, NULL, 0, 0),
(23, '2018-04-03 07:51:10', 17, 5, NULL, NULL, 0, 0),
(24, '2018-04-06 09:33:30', 12, NULL, 3, NULL, 0, 0),
(25, '2018-04-06 09:37:31', 18, 5, NULL, NULL, 0, 0),
(26, '2018-04-06 09:38:44', 19, 5, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_folders`
--

CREATE TABLE `tbl_folders` (
  `id` int(11) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `client_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_groups`
--

CREATE TABLE `tbl_groups` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `public_token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_groups`
--

INSERT INTO `tbl_groups` (`id`, `timestamp`, `created_by`, `name`, `description`, `public`, `public_token`) VALUES
(2, '2018-03-29 22:39:17', 'admin', '00560_Пластик', ' Пластик карталар билан ишлаш бўлими', 1, 'hOcBlq9fNoVgm5XHgjfbcrgNojX8zgOi'),
(3, '2018-03-29 22:40:14', 'admin', '00560_Кредит', 'Кредитлаш бўлими', 1, 'X9MRgjlWHzt70iCtnkMDy0oFJbsbEbpg');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_members`
--

CREATE TABLE `tbl_members` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `added_by` varchar(32) NOT NULL,
  `client_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_members`
--

INSERT INTO `tbl_members` (`id`, `timestamp`, `added_by`, `client_id`, `group_id`) VALUES
(3, '2018-03-29 22:48:10', 'admin', 5, 3),
(4, '2018-03-29 22:53:12', 'admin', 6, 3),
(5, '2018-03-29 22:55:23', 'admin', 7, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_members_requests`
--

CREATE TABLE `tbl_members_requests` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `requested_by` varchar(32) NOT NULL,
  `client_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `denied` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `upload_type` int(11) NOT NULL,
  `sent_status` int(2) NOT NULL,
  `times_failed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`id`, `timestamp`, `file_id`, `client_id`, `upload_type`, `sent_status`, `times_failed`) VALUES
(11, '2018-03-29 23:03:42', 9, 5, 0, 0, 0),
(12, '2018-03-29 23:05:15', 10, 5, 0, 0, 0),
(13, '2018-03-29 23:06:57', 10, 5, 1, 0, 0),
(14, '2018-03-29 23:06:57', 10, 6, 1, 0, 0),
(15, '2018-03-29 23:06:57', 10, 7, 1, 0, 0),
(16, '2018-03-29 23:07:25', 9, 5, 1, 0, 0),
(17, '2018-03-29 23:07:25', 9, 6, 1, 0, 0),
(18, '2018-03-29 23:07:25', 9, 7, 1, 0, 0),
(19, '2018-03-30 06:48:53', 11, 5, 0, 0, 0),
(20, '2018-03-30 06:58:58', 12, 5, 0, 0, 0),
(21, '2018-03-30 12:23:10', 13, 5, 0, 0, 0),
(22, '2018-03-30 13:48:04', 14, 5, 0, 0, 0),
(23, '2018-03-30 13:49:28', 15, 5, 0, 0, 0),
(24, '2018-04-03 07:33:39', 16, 6, 1, 0, 0),
(25, '2018-04-03 07:33:39', 16, 5, 1, 0, 0),
(26, '2018-04-03 07:33:39', 16, 7, 1, 0, 0),
(27, '2018-04-03 07:51:10', 17, 5, 0, 0, 0),
(28, '2018-04-06 09:33:30', 12, 5, 1, 0, 0),
(29, '2018-04-06 09:33:30', 12, 6, 1, 0, 0),
(30, '2018-04-06 09:33:30', 12, 7, 1, 0, 0),
(31, '2018-04-06 09:37:31', 18, 5, 0, 0, 0),
(32, '2018-04-06 09:38:44', 19, 5, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_options`
--

CREATE TABLE `tbl_options` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_options`
--

INSERT INTO `tbl_options` (`id`, `name`, `value`) VALUES
(1, 'base_uri', 'http://190.44.127.51/'),
(2, 'max_thumbnail_width', '100'),
(3, 'max_thumbnail_height', '100'),
(4, 'thumbnails_folder', '../../img/custom/thumbs/'),
(5, 'thumbnail_default_quality', '90'),
(6, 'max_logo_width', '300'),
(7, 'max_logo_height', '300'),
(8, 'this_install_title', 'Audit Control System'),
(9, 'selected_clients_template', 'default'),
(10, 'logo_thumbnails_folder', '/img/custom/thumbs'),
(11, 'timezone', 'Asia/Tashkent'),
(12, 'timeformat', 'd/m/Y h:i:s'),
(13, 'allowed_file_types', 'doc,docx,jpeg,jpg,pdf,png,ppt,pptx,rar,rtf,txt,xls,xlsx,zip'),
(14, 'logo_filename', 'logo.png'),
(15, 'admin_email_address', 'worldalgorithms@gmail.com'),
(16, 'clients_can_register', '0'),
(17, 'last_update', 'cs2903'),
(18, 'mail_system_use', 'smtp'),
(19, 'mail_smtp_host', ''),
(20, 'mail_smtp_port', ''),
(21, 'mail_smtp_user', 'admin'),
(22, 'mail_smtp_pass', 'knoppix1405'),
(23, 'mail_from_name', 'Audit Control System'),
(24, 'thumbnails_use_absolute', '0'),
(25, 'mail_copy_user_upload', '0'),
(26, 'mail_copy_client_upload', '0'),
(27, 'mail_copy_main_user', '0'),
(28, 'mail_copy_addresses', ''),
(29, 'version_last_check', '28-03-2018'),
(30, 'version_new_found', '0'),
(31, 'version_new_number', ''),
(32, 'version_new_url', ''),
(33, 'version_new_chlog', ''),
(34, 'version_new_security', ''),
(35, 'version_new_features', ''),
(36, 'version_new_important', ''),
(37, 'clients_auto_approve', '0'),
(38, 'clients_auto_group', '0'),
(39, 'clients_can_upload', '1'),
(40, 'clients_can_set_expiration_date', '0'),
(41, 'email_new_file_by_user_customize', '1'),
(42, 'email_new_file_by_client_customize', '1'),
(43, 'email_new_client_by_user_customize', '1'),
(44, 'email_new_client_by_self_customize', '1'),
(45, 'email_new_user_customize', '1'),
(46, 'email_new_file_by_user_text', '<p>%BODY1%</p>\r\n<div style=\"padding:15px 0; border:solid #ddd; border-width:1px 0; margin:0 0 20px;\">\r\n	<ul style=\"list-style:none; margin:0; padding:0;\">\r\n		%FILES%\r\n	</ul>\r\n</div>\r\n<p>%BODY2%</p>\r\n<p>%BODY3% <a href=\"%URI%\" target=\"_blank\">%BODY4%</a></p>'),
(47, 'email_new_file_by_client_text', '<p>%BODY1%</p>\r\n<div style=\"padding:15px 0; border:solid #ddd; border-width:1px 0; margin:0 0 20px;\">\r\n	<ul style=\"list-style:none; margin:0; padding:0;\">\r\n		%FILES%\r\n	</ul>\r\n</div>\r\n<p>%BODY2% <a href=\"%URI%\" target=\"_blank\">%BODY3%</a></p>'),
(48, 'email_new_client_by_user_text', '<p>%BODY1%</p>\r\n<p><strong>%LBLUSER%</strong>: %USERNAME%<br />\r\n<strong>%LBLPASS%</strong>: %PASSWORD%</p>\r\n<p>%BODY2%: <a href=\"%URI%\" target=\"_blank\">%URI%</a></p>\r\n<p>%BODY3%</p>'),
(49, 'email_new_client_by_self_text', '<p>%BODY1%</p>\r\n<p><strong>%LBLNAME%</strong>: %FULLNAME%<br />\r\n<strong>%LBLUSER%</strong>: %USERNAME%</p>\r\n<p>%LABEL_REQUESTS%</p>\r\n<p>%GROUPS_REQUESTS%</p>\r\n<p>%BODY2%: <a href=\"%URI%\" target=\"_blank\">%URI%</a></p>\r\n<p>%BODY3%</p>'),
(50, 'email_new_user_text', '<p>%BODY1%</p>\r\n<p><strong>%LBLUSER%</strong>: %USERNAME%<br />\r\n<strong>%LBLPASS%</strong>: %PASSWORD%</p>\r\n<p>%BODY2%: <a href=\"%URI%\" target=\"_blank\">%URI%</a></p>\r\n<p>%BODY3%</p>'),
(51, 'email_header_footer_customize', '1'),
(52, 'email_header_text', '<!doctype html>\r\n<html>\r\n	<head>\r\n		<meta name=\"viewport\" content=\"width=device-width\" />\r\n		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\r\n		<title>%SUBJECT%</title>\r\n	</head>\r\n\r\n	<body style=\"background:#f4f4f4; margin:40px 0; padding:40px 0;\" bgcolor=\"#f4f4f4\">\r\n	<table width=\"550\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"background:#fff; border:1px solid #ccc; -moz-border-radius:5px; -moz-box-shadow:3px 3px 5px #dedede; -webkit-border-radius:5px; -webkit-box-shadow:3px 3px 5px #dedede; border-radius:5px; box-shadow:3px 3px 5px #dedede;\" bgcolor=\"#FFFFFF\" align=\"center\">\r\n		<tr>\r\n			<td style=\"padding:20px; font-family:Arial, Helvetica, sans-serif; font-size:12px;\">\r\n				<h3 style=\"font-family:Arial, Helvetica, sans-serif; font-size:19px; font-weight:normal; margin-bottom:20px; margin-top:0; color:#333333;\">\r\n					<font face=\"Arial, Helvetica, sans-serif\" color=\"#333333\">\r\n						%SUBJECT%\r\n					</font>\r\n				</h3>'),
(53, 'email_footer_text', '				</td>\r\n			</tr>\r\n			<tr>\r\n				<td style=\"padding:20px; border-top:1px dotted #ccc;\">\r\n					<a href=\"%FOOTER_SYSTEM_URI%\" target=\"_blank\">\r\n						<img src=\"%FOOTER_URI%/img/icon-footer-email.jpg\" alt=\"\" style=\"display:block; margin:0; border:none;\">\r\n					</a>\r\n				</td>\r\n			</tr>\r\n		</table>\r\n	</body>\r\n</html>'),
(54, 'email_pass_reset_customize', '1'),
(55, 'email_pass_reset_text', '<p>%BODY1%</p>\r\n<p><strong>%LBLUSER%</strong>: %USERNAME%<br />\r\n<p>%BODY2%:</p>\r\n<p><a href=\"%URI%\" target=\"_blank\">%URI%</a></p>\r\n<p>%BODY3%</p>\r\n<p>%BODY4%</p>'),
(56, 'expired_files_hide', '0'),
(57, 'notifications_max_tries', '2'),
(58, 'notifications_max_days', '15'),
(59, 'file_types_limit_to', 'all'),
(60, 'pass_require_upper', '0'),
(61, 'pass_require_lower', '0'),
(62, 'pass_require_number', '0'),
(63, 'pass_require_special', '0'),
(64, 'mail_smtp_auth', 'none'),
(65, 'use_browser_lang', '0'),
(66, 'clients_can_delete_own_files', '0'),
(67, 'google_client_id', ''),
(68, 'google_client_secret', ''),
(69, 'google_signin_enabled', '0'),
(70, 'recaptcha_enabled', '0'),
(71, 'recaptcha_site_key', ''),
(72, 'recaptcha_secret_key', ''),
(73, 'clients_can_select_group', 'all'),
(74, 'files_descriptions_use_ckeditor', '1'),
(75, 'enable_landing_for_all_files', '0'),
(76, 'footer_custom_enable', '0'),
(77, 'footer_custom_content', 'Audit Control System'),
(78, 'email_new_file_by_user_subject_customize', '1'),
(79, 'email_new_file_by_client_subject_customize', '1'),
(80, 'email_new_client_by_user_subject_customize', '1'),
(81, 'email_new_client_by_self_subject_customize', '1'),
(82, 'email_new_user_subject_customize', '1'),
(83, 'email_pass_reset_subject_customize', '1'),
(84, 'email_new_file_by_user_subject', 'Сизга ҳабар'),
(85, 'email_new_file_by_client_subject', 'Аудит тамонидан'),
(86, 'email_new_client_by_user_subject', 'Ҳуш келибсиз'),
(87, 'email_new_client_by_self_subject', 'Рўйҳатдан ўтиш'),
(88, 'email_new_user_subject', 'Янги фойдаланувчилар'),
(89, 'email_pass_reset_subject', 'Паролни қайта олиш'),
(90, 'privacy_noindex_site', '0'),
(91, 'email_account_approve_subject_customize', '1'),
(92, 'email_account_deny_subject_customize', '1'),
(93, 'email_account_approve_customize', '1'),
(94, 'email_account_deny_customize', '1'),
(95, 'email_account_approve_subject', 'Тасдиқланган аудитлар'),
(96, 'email_account_deny_subject', 'Инкор қилинган аудитлар'),
(97, 'email_account_approve_text', '<p>%BODY1%</p>\r\n%REQUESTS_TITLE%\r\n%APPROVED_TITLE%\r\n%GROUPS_APPROVED%\r\n%DENIED_TITLE%\r\n%GROUPS_DENIED%\r\n<p>%BODY2%: <a href=\"%URI%\" target=\"_blank\">%URI%</a></p>\r\n<p>%BODY3%</p>'),
(98, 'email_account_deny_text', '<p>%BODY1%</p>\r\n<p>%BODY2%</p>'),
(99, 'email_client_edited_subject_customize', '1'),
(100, 'email_client_edited_customize', '1'),
(101, 'email_client_edited_subject', 'Аудитни янгилаш'),
(102, 'email_client_edited_text', '<p>%BODY1%</p>\r\n<p><strong>%LBLNAME%</strong>: %FULLNAME%<br />\r\n<strong>%LBLUSER%</strong>: %USERNAME%</p>\r\n<p>%LABEL_REQUESTS%</p>\r\n<p>%GROUPS_REQUESTS%</p>\r\n<p>%BODY2%: <a href=\"%URI%\" target=\"_blank\">%URI%</a></p>\r\n'),
(103, 'public_listing_page_enable', '1'),
(104, 'public_listing_logged_only', '0'),
(105, 'public_listing_show_all_files', '1'),
(106, 'public_listing_use_download_link', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_password_reset`
--

CREATE TABLE `tbl_password_reset` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `user` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(60) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address` text,
  `phone` varchar(32) DEFAULT NULL,
  `notify` tinyint(1) NOT NULL DEFAULT '0',
  `contact` text,
  `created_by` varchar(60) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `account_requested` tinyint(1) NOT NULL DEFAULT '0',
  `account_denied` tinyint(1) NOT NULL DEFAULT '0',
  `max_file_size` int(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `user`, `password`, `name`, `email`, `level`, `timestamp`, `address`, `phone`, `notify`, `contact`, `created_by`, `active`, `account_requested`, `account_denied`, `max_file_size`) VALUES
(1, 'admin', '$2a$08$QjQsaBRXpz9JrWQy4MqGmOXh0eSQbokZksooilTXdFn2T6./gVJa2', 'Bunyodbek Jumanazarov', 'worldalgorithms@gmail.com', 9, '2018-03-28 18:31:46', NULL, NULL, 0, NULL, NULL, 1, 0, 0, 0),
(5, 'audit1', '$2a$08$/HUrVUDo09msSx9GKTXHBOGrwPYdRrwbXFfTM0DQqrQzK24wKI3q.', 'Анваржон Абдуллаев', 'a.abbdullaev@mail.ru', 0, '2018-03-29 22:48:10', 'Хоразм вилояти, Ҳонқа тумани', '998-93-456-78-96', 1, 'Назоратчи аудитор', 'admin', 1, 0, 0, 5),
(6, 'audit2', '$2a$08$WfaCckFz0zzOIW7WU787ce12VAik9Qe5d02MG8Vut6Ou.9eBArLee', 'Зафаржон Каримов', 'z.karimov@mail.ru', 0, '2018-03-29 22:53:12', 'Хоразм вилояти, Ҳонқа тумани', '998-95-606-54-51', 1, 'Ектакчи аудитор', 'admin', 1, 0, 0, 5),
(7, 'audit3', '$2a$08$wQMmup18unOhm8yU4qxsOOs1mXBBRi15zLFJdZGYAH0kw3p0Nnib.', 'Наргиза Мирзаева', 'n.mirzayeva@mail.ru', 0, '2018-03-29 22:55:23', 'Хоразм вилояти, Ҳонқа тумани', '998-93-774-25-63', 1, 'Ектакчи аудитор', 'admin', 1, 0, 0, 5),
(8, 'manager', '$2a$08$i2dcX9P4iDePRtKec5raE.LuQA2KbfCTugERKyXIVCPTJCyrfkR1O', 'Қаландаров Темур Шарипович', 'manager@agrobank.uz', 8, '2018-03-29 22:56:36', NULL, NULL, 0, NULL, NULL, 1, 0, 0, 25);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tbl_actions_log`
--
ALTER TABLE `tbl_actions_log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`);

--
-- Индексы таблицы `tbl_categories_relations`
--
ALTER TABLE `tbl_categories_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Индексы таблицы `tbl_downloads`
--
ALTER TABLE `tbl_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Индексы таблицы `tbl_files`
--
ALTER TABLE `tbl_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_files_relations`
--
ALTER TABLE `tbl_files_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Индексы таблицы `tbl_folders`
--
ALTER TABLE `tbl_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `tbl_members_requests`
--
ALTER TABLE `tbl_members_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Индексы таблицы `tbl_options`
--
ALTER TABLE `tbl_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tbl_password_reset`
--
ALTER TABLE `tbl_password_reset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tbl_actions_log`
--
ALTER TABLE `tbl_actions_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT для таблицы `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `tbl_categories_relations`
--
ALTER TABLE `tbl_categories_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `tbl_downloads`
--
ALTER TABLE `tbl_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `tbl_files`
--
ALTER TABLE `tbl_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `tbl_files_relations`
--
ALTER TABLE `tbl_files_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `tbl_folders`
--
ALTER TABLE `tbl_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `tbl_members`
--
ALTER TABLE `tbl_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tbl_members_requests`
--
ALTER TABLE `tbl_members_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `tbl_options`
--
ALTER TABLE `tbl_options`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT для таблицы `tbl_password_reset`
--
ALTER TABLE `tbl_password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD CONSTRAINT `tbl_categories_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `tbl_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_categories_relations`
--
ALTER TABLE `tbl_categories_relations`
  ADD CONSTRAINT `tbl_categories_relations_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `tbl_files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_categories_relations_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `tbl_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_downloads`
--
ALTER TABLE `tbl_downloads`
  ADD CONSTRAINT `tbl_downloads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_downloads_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `tbl_files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_files_relations`
--
ALTER TABLE `tbl_files_relations`
  ADD CONSTRAINT `tbl_files_relations_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `tbl_files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_files_relations_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_files_relations_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `tbl_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_files_relations_ibfk_4` FOREIGN KEY (`folder_id`) REFERENCES `tbl_folders` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_folders`
--
ALTER TABLE `tbl_folders`
  ADD CONSTRAINT `tbl_folders_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `tbl_folders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_folders_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_folders_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `tbl_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD CONSTRAINT `tbl_members_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_members_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `tbl_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_members_requests`
--
ALTER TABLE `tbl_members_requests`
  ADD CONSTRAINT `tbl_members_requests_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_members_requests_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `tbl_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `tbl_notifications_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `tbl_files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_notifications_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tbl_password_reset`
--
ALTER TABLE `tbl_password_reset`
  ADD CONSTRAINT `tbl_password_reset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
