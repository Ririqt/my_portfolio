-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 10:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(100) NOT NULL,
  `degree` varchar(256) NOT NULL,
  `birthday` date NOT NULL,
  `experience` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `company` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL,
  `file_name` varchar(200) DEFAULT NULL,
  `time_create` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `user_id`, `name`, `email`, `degree`, `birthday`, `experience`, `address`, `company`, `phone`, `role`, `description`, `file_name`, `time_create`) VALUES
(14, 57, 'test three', 'test_3@gmail.com', 'BSPSCYH', '2004-04-05', 'None', '19 J. Bernal St. Sta. Mesa', 'FUGRO', '0915-451-4596', 'Cook', 'Let me cook', 'MicrosoftTeams-image.png', '2025-08-22 05:50:32'),
(15, 52, 'test two', 'test_2@gmail.com', 'BSCPE', '2004-10-18', 'Website Tester/QA', 'C. Santos St.', 'CITYSTYLE', '0915-451-4596', 'D', 'A passionate and dedicated individual with a strong interest in technology, programming, and problem-solving. Continuously learning and developing skills in software development, with a focus on building responsive, user-friendly websites and efficient sof', 'about_picture.JPG', '2025-08-29 08:34:19'),
(16, 58, 'test four', 'test_4@gmail.com', 'BSCPE', '2025-07-29', 'hello', '21', 'CITYSTYLE', '12341234', '', '', NULL, '2025-08-08 05:50:39'),
(17, 65, 'test six', 'test_6@gmail.com', 'try', '2025-07-28', 'try', 'tyr', 'try', '123412341234', '', '', NULL, '2025-08-11 01:28:20'),
(33, 78, 'test seven', 'test_7@gmail.com', 'BSPSCYH', '2025-07-29', 'Website Tester/QA', 'C. Santos St.', 'CITYSTYLE', '0915-451-4596', 'Future Prof', 'yiui', 'Screenshot 2025-08-15 121544.png', '2025-08-18 07:18:06'),
(34, 77, 'test eight', 'test_8@gmail.com', 'BSCPE', '2025-08-05', 'Website Tester/QA', 'C. Santos St.', 'CITYSTYLE', '0915-451-4596', 'Future Prof', '', 'MicrosoftTeams-image.png', '2025-08-22 05:54:07');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`) VALUES
(14, 'dadad', 'test_2@gmail.com', 'qw', 'qwer'),
(15, 'dadad', 'test_2@gmail.com', 'qw', 'qwer'),
(16, 'dadad', 'test_2@gmail.com', 'qw', 'asdasd');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `timestamp`) VALUES
(2, 52, 'Deleted a Skill', '2025-08-22 02:39:24'),
(3, 52, 'Created a Project', '2025-08-22 02:44:29'),
(4, 52, 'Created a Project', '2025-08-22 03:00:13'),
(5, 52, 'Deleted a Project', '2025-08-22 03:07:34'),
(6, 52, 'Deleted a Project', '2025-08-22 03:07:51'),
(9, 52, 'Logged In', '2025-08-22 03:54:52'),
(10, 57, 'Logged In', '2025-08-22 03:55:16'),
(11, 52, 'Logged In', '2025-08-22 03:57:57'),
(12, 52, 'Created a Skill', '2025-08-22 03:58:37'),
(14, 52, 'Edited a Skill', '2025-08-22 04:07:48'),
(15, 52, 'Logged Out', '2025-08-22 05:37:11'),
(16, 52, 'Logged In', '2025-08-22 05:37:21'),
(17, 52, 'Logged Out', '2025-08-22 05:40:44'),
(18, 57, 'Logged In', '2025-08-22 05:40:53'),
(19, 57, 'Uploaded a Picture', '2025-08-22 05:48:08'),
(21, 57, 'Edited about', '2025-08-22 05:50:32'),
(22, 57, 'Logged Out', '2025-08-22 05:52:14'),
(23, 78, 'Logged In', '2025-08-22 05:52:41'),
(24, 78, 'Logged Out', '2025-08-22 05:53:11'),
(25, 77, 'Logged In', '2025-08-22 05:53:23'),
(26, 77, 'Inserted about', '2025-08-22 05:54:07'),
(27, 77, 'Logged Out', '2025-08-22 05:59:58'),
(28, 52, 'Logged In', '2025-08-22 06:00:03'),
(32, 52, 'Edited a Project 2', '2025-08-22 06:04:41'),
(33, 52, 'Uploaded a Picture in Project', '2025-08-22 06:05:16'),
(34, 52, 'Uploaded a Picture in Project', '2025-08-22 06:05:34'),
(35, 52, '', '2025-08-22 06:22:35'),
(36, 52, '', '2025-08-22 06:23:20'),
(37, 52, 'Uploaded a Picture in Projects', '2025-08-22 06:28:58'),
(38, 52, 'Uploaded a Picture in Projects', '2025-08-22 06:34:27'),
(39, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:35:54'),
(40, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:38:07'),
(41, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:39:02'),
(42, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:43:30'),
(43, 52, 'Edited a Project', '2025-08-22 06:44:47'),
(44, 52, 'Edited a Project', '2025-08-22 06:46:34'),
(45, 52, 'Edited a Project', '2025-08-22 06:47:01'),
(46, 52, 'Deleted a Project', '2025-08-22 06:47:14'),
(47, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:47:57'),
(48, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:49:27'),
(49, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:50:11'),
(50, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:50:58'),
(51, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 06:52:22'),
(52, 52, 'Edited a Project', '2025-08-22 06:52:50'),
(53, 52, 'Edited a Project', '2025-08-22 06:52:57'),
(54, 52, 'Uploaded a Picture in Projects', '2025-08-22 06:53:59'),
(55, 52, 'Edited a Project', '2025-08-22 06:54:14'),
(56, 52, 'Edited a Project', '2025-08-22 06:54:23'),
(57, 52, 'Edited a Project', '2025-08-22 06:55:30'),
(58, 52, 'Edited a Project', '2025-08-22 06:56:18'),
(59, 52, 'Edited a Project', '2025-08-22 06:57:16'),
(60, 52, 'Uploaded a Picture in Projects', '2025-08-22 06:57:22'),
(61, 52, 'Edited a Project', '2025-08-22 07:00:21'),
(62, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:03:20'),
(63, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:06:05'),
(64, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:06:21'),
(65, 52, 'Edited a Project', '2025-08-22 07:07:23'),
(66, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:07:35'),
(67, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:07:51'),
(68, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:08:41'),
(69, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:09:52'),
(70, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:10:07'),
(71, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:10:27'),
(72, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:11:26'),
(73, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:11:34'),
(74, 52, 'Edited a Project', '2025-08-22 07:12:12'),
(75, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:12:26'),
(76, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:12:47'),
(77, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:12:59'),
(78, 52, 'Created a Project', '2025-08-22 07:18:42'),
(79, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:22:52'),
(80, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:23:35'),
(81, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:23:50'),
(82, 52, 'Created a Project', '2025-08-22 07:25:43'),
(83, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:26:09'),
(84, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:26:16'),
(85, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:32:53'),
(86, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:33:09'),
(87, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:33:18'),
(88, 52, 'Uploaded a Picture in Projects', '2025-08-22 07:33:57'),
(89, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:34:11'),
(90, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:43:24'),
(91, 52, 'Edited a Project', '2025-08-22 07:45:40'),
(92, 52, 'Edited a Project', '2025-08-22 07:45:47'),
(93, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:45:58'),
(94, 52, 'Edited and Uploaded a Picture in Project', '2025-08-22 07:46:20'),
(95, 52, 'Logged In', '2025-08-26 23:01:45'),
(96, 52, 'Edited a Project', '2025-08-27 00:03:05'),
(97, 52, 'Uploaded a Picture in Projects', '2025-08-27 00:04:47'),
(98, 52, 'Edited a Project', '2025-08-27 00:04:57'),
(99, 52, 'Uploaded a Picture in Projects', '2025-08-27 00:05:54'),
(100, 52, 'Uploaded a Picture in Projects', '2025-08-27 06:57:22'),
(101, 52, 'Uploaded a Picture in Projects', '2025-08-27 06:58:08'),
(102, 52, 'Logged Out', '2025-08-27 07:08:16'),
(103, 52, 'Logged In', '2025-08-27 07:25:25'),
(104, 52, 'Logged Out', '2025-08-27 07:39:44'),
(105, 52, 'Logged In', '2025-08-27 07:41:42'),
(106, 52, 'Deleted a Skill', '2025-08-27 07:47:25'),
(107, 52, 'Deleted a Skill', '2025-08-27 07:47:32'),
(108, 52, 'Created a Skill', '2025-08-27 07:52:06'),
(109, 52, 'Created a Skill', '2025-08-27 07:54:41'),
(110, 52, 'Created a Skill', '2025-08-27 08:10:08'),
(111, 52, 'Created a Skill', '2025-08-27 08:19:21'),
(112, 52, 'Created a Skill', '2025-08-27 08:20:10'),
(113, 52, 'Created a Skill', '2025-08-27 08:25:11'),
(114, 52, 'Created a Skill', '2025-08-27 08:25:31'),
(115, 52, 'Created a Skill', '2025-08-27 08:26:36'),
(116, 52, 'Created a Skill', '2025-08-27 08:33:48'),
(117, 52, 'Logged In', '2025-08-27 23:01:42'),
(118, 52, 'Created a Project', '2025-08-27 23:11:05'),
(119, 52, 'Deleted a Project', '2025-08-27 23:11:30'),
(120, 52, 'Created a Project', '2025-08-27 23:11:37'),
(121, 52, 'Deleted a Project', '2025-08-27 23:12:23'),
(122, 52, 'Created a Project', '2025-08-27 23:13:40'),
(123, 52, 'Deleted a Project', '2025-08-27 23:14:11'),
(124, 52, 'Created a Skill', '2025-08-27 23:37:29'),
(125, 52, 'Edited a Skill', '2025-08-27 23:37:36'),
(126, 52, 'Deleted a Skill', '2025-08-27 23:40:09'),
(127, 52, 'Created a Skill', '2025-08-27 23:40:14'),
(128, 52, 'Edited a Skill', '2025-08-27 23:40:21'),
(129, 52, 'Deleted a Skill', '2025-08-27 23:41:25'),
(130, 52, 'Created a Skill', '2025-08-27 23:41:28'),
(131, 52, 'Edited a Skill', '2025-08-27 23:41:35'),
(132, 52, 'Deleted a Skill', '2025-08-27 23:42:05'),
(133, 52, 'Created a Skill', '2025-08-27 23:42:10'),
(134, 52, 'Edited a Skill', '2025-08-27 23:42:19'),
(135, 52, 'Deleted a Skill', '2025-08-27 23:43:49'),
(136, 52, 'Created a Skill', '2025-08-27 23:43:54'),
(137, 52, 'Created a Skill', '2025-08-28 00:02:10'),
(138, 52, 'Edited a Skill', '2025-08-28 00:10:00'),
(139, 52, 'Edited a Skill', '2025-08-28 00:10:09'),
(140, 52, 'Edited a Skill', '2025-08-28 00:11:05'),
(141, 52, 'Deleted a Skill', '2025-08-28 00:12:51'),
(142, 52, 'Created a Skill', '2025-08-28 00:12:55'),
(143, 52, 'Edited a Skill', '2025-08-28 00:32:01'),
(144, 52, 'Edited a Skill', '2025-08-28 00:43:07'),
(145, 52, 'Edited a Skill', '2025-08-28 00:43:14'),
(146, 52, 'Edited a Skill', '2025-08-28 00:43:20'),
(147, 52, 'Edited a Skill', '2025-08-28 00:44:35'),
(148, 52, 'Edited a Skill', '2025-08-28 00:44:42'),
(149, 52, 'Created a Skill', '2025-08-28 00:50:19'),
(150, 52, 'Edited a Project', '2025-08-28 06:05:02'),
(151, 52, 'Created a Project', '2025-08-28 06:14:13'),
(152, 52, 'Edited a Project', '2025-08-28 06:15:13'),
(153, 52, 'Deleted a Project', '2025-08-28 06:15:45'),
(154, 52, 'Created a Project', '2025-08-28 06:15:50'),
(155, 52, 'Edited a Project', '2025-08-28 06:15:56'),
(156, 52, 'Deleted a Project', '2025-08-28 06:22:24'),
(157, 52, 'Edited a Project', '2025-08-28 06:22:31'),
(158, 52, 'Deleted a Project', '2025-08-28 06:24:12'),
(159, 52, 'Created a Project', '2025-08-28 06:24:24'),
(160, 52, 'Created a Project', '2025-08-28 06:24:30'),
(161, 52, 'Created a Project', '2025-08-28 06:24:36'),
(162, 52, 'Created a Project', '2025-08-28 06:29:32'),
(163, 52, 'Uploaded a Picture in Projects', '2025-08-28 06:30:50'),
(164, 52, 'Edited and Uploaded a Picture in Project', '2025-08-28 06:31:05'),
(165, 52, 'Edited and Uploaded a Picture in Project', '2025-08-28 06:31:21'),
(166, 52, 'Created a Project', '2025-08-28 06:37:31'),
(167, 52, 'Created a Project', '2025-08-28 06:37:39'),
(168, 52, 'Created a Project', '2025-08-28 06:37:45'),
(169, 52, 'Created a Project', '2025-08-28 06:38:10'),
(170, 52, 'Edited a Project', '2025-08-28 06:38:41'),
(171, 52, 'Edited a Project', '2025-08-28 06:38:45'),
(172, 52, 'Edited and Uploaded a Picture in Project', '2025-08-28 06:49:38'),
(173, 52, 'Uploaded a Picture in Projects', '2025-08-28 06:50:45'),
(174, 52, 'Uploaded a Picture in Projects', '2025-08-28 06:51:05'),
(175, 52, 'Edited a Project', '2025-08-28 06:51:32'),
(176, 52, 'Edited and Uploaded a Picture in Project', '2025-08-28 06:51:43'),
(177, 52, 'Logged In', '2025-08-28 23:45:09'),
(178, 52, 'Logged Out', '2025-08-28 23:55:55'),
(179, 52, 'Logged In', '2025-08-29 00:02:10'),
(180, 52, 'Uploaded a Picture', '2025-08-29 02:28:13'),
(181, 52, 'Uploaded a Picture', '2025-08-29 02:45:50'),
(182, 52, 'Uploaded a Picture', '2025-08-29 02:47:20'),
(183, 52, 'Logged Out', '2025-08-29 03:06:37'),
(184, 78, 'Logged In', '2025-08-29 03:06:44'),
(185, 78, 'Logged Out', '2025-08-29 03:06:48'),
(186, 77, 'Logged In', '2025-08-29 03:06:53'),
(187, 77, 'Logged Out', '2025-08-29 03:06:57'),
(188, 64, 'Logged In', '2025-08-29 03:07:29'),
(189, 64, 'Inserted About', '2025-08-29 03:26:02'),
(190, 64, 'Inserted About', '2025-08-29 03:29:17'),
(191, 64, 'Inserted About', '2025-08-29 03:31:56'),
(192, 64, 'Inserted About', '2025-08-29 03:33:35'),
(193, 64, 'Inserted About', '2025-08-29 03:41:51'),
(194, 64, 'Uploaded a Picture', '2025-08-29 03:43:56'),
(195, 64, 'Logged Out', '2025-08-29 03:50:46'),
(196, 52, 'Logged In', '2025-08-29 03:50:53'),
(197, 52, 'Uploaded a Picture', '2025-08-29 03:51:43'),
(198, 52, 'Logged Out', '2025-08-29 03:57:53'),
(199, 64, 'Logged In', '2025-08-29 03:58:13'),
(200, 64, 'Logged Out', '2025-08-29 05:32:37'),
(201, 52, 'Logged In', '2025-08-29 05:39:08'),
(202, 52, 'Uploaded a Picture', '2025-08-29 08:34:19'),
(203, 52, 'Logged Out', '2025-08-29 08:56:28'),
(204, 52, 'Logged In', '2025-08-29 08:59:07'),
(205, 52, 'Logged In', '2025-09-01 23:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `file_name` varchar(200) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `status`, `user_id`, `file_name`, `time`) VALUES
(169, 'A Simple Calculator', 'A working functional calculator with design.', 'In Progress', 57, NULL, '2025-08-07 08:10:35'),
(170, 'test 1', 'try', 'Start', 58, NULL, '2025-08-08 04:08:54'),
(173, 'test 1', 'asdfsdafasdfasdfsadfasdf', 'Start', 65, NULL, '2025-08-11 01:28:48'),
(185, 'weather system', '123', 'In Progress', 57, NULL, '2025-08-13 08:03:57'),
(305, '1', 'q', 'Start', 52, NULL, '2025-08-28 06:37:31'),
(306, '2', 'w', 'In Progress', 52, NULL, '2025-08-28 06:37:39'),
(307, '3', 'e', 'Done', 52, NULL, '2025-08-28 06:37:45'),
(308, '4', 'r23', 'In Progress', 52, 'images.jpg', '2025-08-28 06:38:10');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `rate` varchar(100) NOT NULL,
  `user_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `type`, `rate`, `user_id`) VALUES
(76, 'Problem Solving', 'Transferable', '3 - Satisfactory', 58),
(77, 'Teamwork', 'Hard', '4 - Good', 58),
(83, 'Programming', 'Soft', '3 - Satisfactory', 65),
(100, 'Teamwork', 'Knowledge-based', 'Satisfactory', 57),
(101, 'Programming', 'Knowledge-based', 'Very Good', 57),
(146, 'Programming', 'Hard', 'Good', 52),
(152, 'Communication', 'Soft', 'Satisfactory', 52),
(153, 'Math', 'Hard', 'Good', 52),
(154, 'Reading', 'Soft', 'Good', 52);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(51, 'test lang', 'test_1@gmail.com', '$2y$10$wlAu92Z/yeTeOms6dR9ZCehXT2xry9L27CyAZbnNP97yMG3hwVj8i', '2025-07-29 06:57:34'),
(52, 'test two', 'test_2@gmail.com', '$2y$10$FWQJNfq7Ak6XDFCExhZ1fOMz.88D.RkEl2nOOH4t3RNc9cSIKt7o.', '2025-07-29 06:58:59'),
(57, 'test three', 'test_3@gmail.com', '$2y$10$a98PgARaclrFX5cV9UGXkuS93/UN7lqEDjpt.ouqNleE/OJhZu12q', '2025-07-31 03:10:14'),
(58, 'test four', 'test_4@gmail.com', '$2y$10$xeCnDhBgcIHSDWKmHgWj0uSiPi8FLi43mPPs9hlNrPoKxpDR5QJb6', '2025-07-31 03:42:27'),
(64, 'test five', 'test_5@gmail.com', '$2y$10$Jl1qPIoKgErehvtEKhuod.Kdn8vzrtlW.0BGyGCmRFMvPFTRNxGM6', '2025-08-07 02:12:09'),
(65, 'test six', 'test_6@gmail.com', '$2y$10$sq3wnJdfF7ETvjy8n7d48OlPYId5gmj79JoTRWDRWdHaD.JzzxE5u', '2025-08-11 01:27:35'),
(77, 'test eight', 'test_8@gmail.com', '$2y$10$6hbHV4neKGToQ7uXkELTm.uX4yEb9YEi2PNCUL5GcvfHXBufP0kgK', '2025-08-15 02:38:46'),
(78, 'test seven', 'test_7@gmail.com', '$2y$10$okkA6UVFVKLuNQSJicr0aeyd0TNakCGYIY0tWr.iZvMRO656oUugC', '2025-08-18 06:18:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
