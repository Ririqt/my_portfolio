-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2025 at 05:27 AM
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
  `phone` int(20) NOT NULL,
  `time_create` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `user_id`, `name`, `email`, `degree`, `birthday`, `experience`, `address`, `company`, `phone`, `time_create`) VALUES
(14, 57, 'test three', 'test_3@gmail.com', '3', '2025-08-07', '3', '3', '3', 3, '2025-08-06 08:51:37'),
(15, 52, 'test two', 'test_2@gmail.com', '2', '2025-08-06', '3123', '3123', '33344', 31232, '2025-08-06 23:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(100) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `status`, `time`) VALUES
(151, 'test 1', 'hello', 'Done', '2025-08-01 01:47:19'),
(152, 'test 3', 'This project works 222', 'Done', '2025-08-01 02:00:11'),
(154, 'test 2', 'hello', 'In Progress', '2025-08-01 02:32:56'),
(159, 'test 6', 'yehey it works', 'In Progress', '2025-08-04 02:37:58'),
(166, 'test 5', 'trying again and again', 'Start', '2025-08-06 00:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `rate` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `type`, `rate`) VALUES
(49, 'Programming', 'Hard', '5 - Very Good'),
(50, 'Math', 'Knowledge-based', '4 - Good'),
(51, 'Reading', 'Personal', '4 - Good'),
(52, 'dada', 'Hard', '3 - Satisfactory');

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
(64, 'test five', 'test_5@gmail.com', '$2y$10$Jl1qPIoKgErehvtEKhuod.Kdn8vzrtlW.0BGyGCmRFMvPFTRNxGM6', '2025-08-07 02:12:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
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
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
