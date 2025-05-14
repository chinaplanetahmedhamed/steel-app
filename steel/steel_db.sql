-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 10, 2025 at 10:38 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `steel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `pending_customers`
--

CREATE TABLE `pending_customers` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `invite_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_customers`
--

INSERT INTO `pending_customers` (`id`, `name`, `email`, `company`, `country`, `phone`, `notes`, `created_at`, `status`, `invite_code`) VALUES
(18, 'ahmedhamed', 'hamed@hamed.com', 'kjhkjh', 'kjhkjh', '808908098', 'lkhjlkj', '2025-05-08 09:22:38', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `invite_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','held') COLLATE utf8mb4_general_ci DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `company`, `country`, `phone`, `notes`, `invite_code`, `role`, `created_at`, `status`) VALUES
(1, 'Ahmed', 'ahmed@example.com', 'ChinaPlanet', 'China', '12345678', 'Test note', '801e4dae', 'customer', '2025-05-05 19:34:01', 'active'),
(2, '2323', '3232', '4343', '3434', '34334', '343434', 'e215621a', 'customer', '2025-05-05 19:34:01', 'active'),
(3, 'jhgj', 'iuyiuy', 'lkjlkjlk', 'lklklklk', '8768768768', '', '392f7d50', 'customer', '2025-05-05 19:34:01', 'active'),
(4, 'sarah', 'sarah@hh.com', 'company', 'china', '132333', 'sarah', '20397c8a', 'customer', '2025-05-06 15:47:16', 'active'),
(5, 'jane', 'jane@example.com', 'test company', 'china', '987987987', 'new email server test', '97499ead', 'customer', '2025-05-06 16:03:34', 'active'),
(6, 'safia', 'soso', 'lala', 'china', '01212', 'safia', '490cbbf5', 'customer', '2025-05-06 16:11:53', 'active'),
(7, 'ahmedhamed2', 'nana', 'lkjl', 'lkjlkj', '234234', 'note 4 with controllers', '88e90f44', 'customer', '2025-05-06 16:12:48', 'active'),
(23, 'rina', 'Rina@imporster.com', 'Hamed  Trade', NULL, NULL, NULL, 'b9f4a145', 'customer', '2025-05-07 10:28:10', 'active'),
(24, 'ahmedhamed', 'ahmed@hamed.com', 'hhhh', NULL, NULL, NULL, '204f478e', 'customer', '2025-05-08 09:32:39', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pending_customers`
--
ALTER TABLE `pending_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pending_customers`
--
ALTER TABLE `pending_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
