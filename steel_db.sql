-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 12, 2025 at 08:41 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

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
-- Table structure for table `base_materials`
--

CREATE TABLE `base_materials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price_per_ton` decimal(10,2) NOT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `base_materials`
--

INSERT INTO `base_materials` (`id`, `name`, `price_per_ton`, `updated_at`) VALUES
(1, 'HRC', '3300.00', '2025-05-11 19:28:39'),
(2, 'Stainless 304', '15500.00', '2025-05-11 19:28:39'),
(3, 'Aluminum', '18000.00', '2025-05-11 19:28:39');

-- --------------------------------------------------------

--
-- Table structure for table `base_price`
--

CREATE TABLE `base_price` (
  `id` int(11) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `price_per_ton` decimal(10,2) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `base_price`
--

INSERT INTO `base_price` (`id`, `label`, `price_per_ton`, `updated_at`) VALUES
(1, 'HRC', '3300.00', '2025-05-10 19:11:55'),
(3, 'test', '600.00', '2025-05-10 19:13:55'),
(5, 'jhg', '765.00', '2025-05-10 19:18:51');

-- --------------------------------------------------------

--
-- Table structure for table `coatings`
--

CREATE TABLE `coatings` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('standard','fingerprint','wrinkled','PVDF') NOT NULL,
  `price_per_m2` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `destination_ports`
--

CREATE TABLE `destination_ports` (
  `id` int(11) NOT NULL,
  `port_name` varchar(100) NOT NULL,
  `shipping_method` enum('bulk','container') NOT NULL,
  `cif_cost_per_ton` decimal(10,2) NOT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rate`
--

CREATE TABLE `exchange_rate` (
  `id` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `rate` decimal(10,4) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `unit` varchar(20) DEFAULT 'ton',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `base_material_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `unit`, `status`, `created_at`, `base_material_id`) VALUES
(3, 'Galvanized Steel (镀锌)', 'ton', 'active', '2025-05-10 14:49:47', 1),
(4, 'Aluzinc Steel (Galvalume) (镀铝锌)', 'ton', 'active', '2025-05-10 14:50:00', 1),
(5, 'Aluzinc + Anti-Fingerprint (镀铝锌 耐指纹)', 'ton', 'active', '2025-05-10 14:50:11', 1),
(6, 'Aluzinc Color-Coated (镀铝锌 彩涂)', 'ton', 'active', '2025-05-10 14:50:32', 1),
(7, 'Aluzinc Wrinkled Finish (镀铝锌 网纹彩涂)', 'ton', 'active', '2025-05-10 14:50:42', 1),
(8, 'Aluzinc High-Durability (PVDF) (镀铝锌 高耐候)', 'ton', 'active', '2025-05-10 14:50:54', 1),
(9, 'Galvanized Color-Coated (镀锌 彩涂)', 'ton', 'active', '2025-05-10 14:51:06', 1),
(10, 'Heavy Zinc Coated Galvanized (120g 镀锌 or 高锌层)', 'ton', 'active', '2025-05-10 14:51:22', 1),
(11, 'Color-Coated on Heavy Zinc', 'ton', 'active', '2025-05-10 14:51:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `packing_options`
--

CREATE TABLE `packing_options` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_type` enum('per_ton','per_pack','per_sheet') NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `packing_options`
--

INSERT INTO `packing_options` (`id`, `name`, `unit_type`, `cost`, `created_at`) VALUES
(1, 'coil-<3tons', 'per_ton', '100.00', '2025-05-10 18:08:46');

-- --------------------------------------------------------

--
-- Table structure for table `pending_customers`
--

CREATE TABLE `pending_customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `invite_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `processing_costs`
--

CREATE TABLE `processing_costs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cost_type` enum('per_ton','per_m2') NOT NULL,
  `cost_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_ports`
--

CREATE TABLE `shipping_ports` (
  `id` int(11) NOT NULL,
  `port_name` varchar(100) NOT NULL,
  `shipping_method` enum('bulk','container') NOT NULL,
  `fob_cost_per_ton` decimal(10,2) NOT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `thickness_costs`
--

CREATE TABLE `thickness_costs` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `thickness_mm` decimal(4,2) NOT NULL,
  `cold_rolling_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thickness_costs`
--

INSERT INTO `thickness_costs` (`id`, `material_id`, `thickness_mm`, `cold_rolling_cost`, `created_at`) VALUES
(1, 3, '0.20', '500.00', '2025-05-10 16:03:18'),
(2, 5, '0.15', '480.00', '2025-05-10 16:14:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `notes` text,
  `invite_code` varchar(50) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','held') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `company`, `country`, `phone`, `notes`, `invite_code`, `role`, `created_at`, `status`) VALUES
(1, 'Ahmed', 'ahmed@example.com', 'ChinaPlanet', 'China', '12345678', 'Test note', 'admin', 'customer', '2025-05-05 19:34:01', 'active'),
(2, '2323', '3232', '4343', '3434', '34334', '343434', 'e215621a', 'customer', '2025-05-05 19:34:01', 'active'),
(3, 'jhgj', 'iuyiuy', 'lkjlkjlk', 'lklklklk', '8768768768', '', '392f7d50', 'customer', '2025-05-05 19:34:01', 'active'),
(4, 'sarah', 'sarah@hh.com', 'company', 'china', '132333', 'sarah', '20397c8a', 'customer', '2025-05-06 15:47:16', 'active'),
(5, 'jane', 'jane@example.com', 'test company', 'china', '987987987', 'new email server test', '97499ead', 'customer', '2025-05-06 16:03:34', 'active'),
(6, 'safia', 'soso', 'lala', 'china', '01212', 'safia', '490cbbf5', 'customer', '2025-05-06 16:11:53', 'active'),
(7, 'ahmedhamed2', 'nana', 'lkjl', 'lkjlkj', '234234', 'note 4 with controllers', '88e90f44', 'customer', '2025-05-06 16:12:48', 'active'),
(23, 'rina', 'Rina@imporster.com', 'Hamed  Trade', NULL, NULL, NULL, 'b9f4a145', 'customer', '2025-05-07 10:28:10', 'active'),
(24, 'ahmedhamed', 'ahmed@hamed.com', 'hhhh', NULL, NULL, NULL, '204f478e', 'customer', '2025-05-08 09:32:39', 'active'),
(25, 'fares', 'fares@safia.com', 'visionfares', NULL, NULL, NULL, '362f8869', 'customer', '2025-05-10 12:52:14', 'active'),
(26, 'sfdsd', 'sdasd', 'sdasd', NULL, NULL, NULL, 'e8c1e780', 'customer', '2025-05-11 14:10:53', 'active'),
(27, 'ahmedhamed', 'hamed@hamed.com', 'kjhkjh', NULL, NULL, NULL, 'd574fd6d', 'customer', '2025-05-12 07:35:50', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `widths`
--

CREATE TABLE `widths` (
  `id` int(11) NOT NULL,
  `width_mm` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `widths`
--

INSERT INTO `widths` (`id`, `width_mm`, `status`, `created_at`) VALUES
(1, 1000, 'active', '2025-05-11 12:45:59'),
(2, 1250, 'active', '2025-05-11 12:46:45'),
(3, 1500, 'active', '2025-05-11 12:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `zinc_costs`
--

CREATE TABLE `zinc_costs` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `base_zinc_g` int(11) DEFAULT '30',
  `extra_10g_cost_per_m2` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `zinc_costs`
--

INSERT INTO `zinc_costs` (`id`, `material_id`, `base_zinc_g`, `extra_10g_cost_per_m2`, `created_at`) VALUES
(1, 5, 30, '0.27', '2025-05-10 17:43:33'),
(3, 3, 30, '0.27', '2025-05-11 12:19:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `base_materials`
--
ALTER TABLE `base_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `base_price`
--
ALTER TABLE `base_price`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Indexes for table `coatings`
--
ALTER TABLE `coatings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destination_ports`
--
ALTER TABLE `destination_ports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exchange_rate`
--
ALTER TABLE `exchange_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_base_material` (`base_material_id`);

--
-- Indexes for table `packing_options`
--
ALTER TABLE `packing_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_customers`
--
ALTER TABLE `pending_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `processing_costs`
--
ALTER TABLE `processing_costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_ports`
--
ALTER TABLE `shipping_ports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thickness_costs`
--
ALTER TABLE `thickness_costs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `widths`
--
ALTER TABLE `widths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zinc_costs`
--
ALTER TABLE `zinc_costs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_id` (`material_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `base_materials`
--
ALTER TABLE `base_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `base_price`
--
ALTER TABLE `base_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coatings`
--
ALTER TABLE `coatings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destination_ports`
--
ALTER TABLE `destination_ports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exchange_rate`
--
ALTER TABLE `exchange_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `packing_options`
--
ALTER TABLE `packing_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pending_customers`
--
ALTER TABLE `pending_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `processing_costs`
--
ALTER TABLE `processing_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_ports`
--
ALTER TABLE `shipping_ports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thickness_costs`
--
ALTER TABLE `thickness_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `widths`
--
ALTER TABLE `widths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `zinc_costs`
--
ALTER TABLE `zinc_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `fk_base_material` FOREIGN KEY (`base_material_id`) REFERENCES `base_materials` (`id`);

--
-- Constraints for table `thickness_costs`
--
ALTER TABLE `thickness_costs`
  ADD CONSTRAINT `thickness_costs_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zinc_costs`
--
ALTER TABLE `zinc_costs`
  ADD CONSTRAINT `zinc_costs_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
