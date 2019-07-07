-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2019 at 12:17 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tb`
--

-- --------------------------------------------------------

--
-- Table structure for table `rectangles`
--

CREATE TABLE `rectangles` (
  `id` int(11) UNSIGNED NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `east` double(8,2) NOT NULL,
  `west` double(8,2) NOT NULL,
  `south` double(8,2) NOT NULL,
  `north` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rectangles`
--

INSERT INTO `rectangles` (`id`, `height`, `width`, `east`, `west`, `south`, `north`) VALUES
(1, 6, 13, 105.92, 105.46, 20.95, 21.16),
(2, 12, 27, 105.92, 105.46, 20.95, 21.16),
(3, 24, 54, 105.92, 105.46, 20.95, 21.16),
(4, 48, 108, 105.92, 105.46, 20.95, 21.16),
(5, 96, 216, 105.92, 105.46, 20.95, 21.16);

-- --------------------------------------------------------

--
-- Table structure for table `cells_detail`
--

CREATE TABLE `cells_detail` (
  `id` int(11) UNSIGNED NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `id_cell` int(11) UNSIGNED NOT NULL,
  `avg_speed` float(3,2) NOT NULL,
  `marker_count` int(11) UNSIGNED NOT NULL,
  `indicator` int(10) DEFAULT NULL,
  `color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cells_detail`
--

INSERT INTO `cells_detail` (`id`, `height`, `width`, `start_time`, `end_time`, `id_cell`, `avg_speed`, `marker_count`, `indicator`, `color`, `created_at`, `updated_at`) VALUES
(1, 10, 10, '2019-04-08 15:30:00', '2019-04-08 16:00:00', 3, 4.00, 0, 5, '#FF0000', NULL, '2019-04-08 07:40:13'),
(2, 11, 10, '2019-04-08 15:30:00', '2019-04-08 16:00:00', 2, 2.00, 0, 4, '#0000FF', NULL, '2019-04-08 07:40:13'),
(3, 10, 20, '2019-04-17 15:30:00', '2019-04-17 16:00:00', 3, 1.00, 0, 3, '#00FF00', NULL, '2019-04-08 20:55:42'),
(4, 4, 6, '2019-04-20 15:30:00', '2019-04-20 16:00:00', 1, 5.00, 0, 1, '#FFFF00', NULL, '2019-04-20 06:27:30'),
(5, 6, 13, '2019-04-20 15:30:00', '2019-04-20 16:00:00', 2, 3.00, 0, 2, '#00FF00', NULL, '2019-04-20 06:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `future`
--

CREATE TABLE `future` (
  `id` int(11) UNSIGNED NOT NULL,
  `height` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `destination_time` timestamp NULL DEFAULT NULL,
  `id_cell` int(11) UNSIGNED NOT NULL,
  `avg_speed` float(3,2) NOT NULL,
  `color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicator` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `future`
--

INSERT INTO `future` (`id`, `height`, `width`, `destination_time`, `id_cell`, `avg_speed`, `color`, `indicator`, `created_at`, `updated_at`) VALUES
(1, 10, 10, '2019-04-11 04:00:00', 2, 5.00, '#00FFFF', 1, NULL, NULL),
(2, 16, 16, '2019-04-11 06:00:00', 3, 4.00, '#FFFF00', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `markers`
--

CREATE TABLE `markers` (
  `id` int(11) UNSIGNED NOT NULL,
  `lat` double(8,2) NOT NULL,
  `lng` double(8,2) NOT NULL,
  `speed` float(3,2) NOT NULL,
  `vehicle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direct` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_user` int(11) UNSIGNED NOT NULL,
  `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `distance` double(10,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `markers`
--

INSERT INTO `markers` (`id`, `lat`, `lng`, `speed`, `vehicle`, `direct`, `record_user`, `record_time`, `distance`, `created_at`, `updated_at`) VALUES
(3, 10.20, 10.50, 2.00, '1', 'e', 1, '2019-04-08 15:30:00', 0.0000, '2019-04-17 20:11:43', '2019-04-17 20:11:43'),
(4, 112.20, 110.50, 3.00, '2', 's', 1, '2019-04-08 15:30:00', 0.0000, '2019-04-17 20:11:43', '2019-04-17 20:11:43'),
(5, 123.50, 222.20, 2.00, '2', 'n', 1, '2019-04-24 15:30:00', NULL, '2019-04-24 07:51:30', '2019-04-24 07:51:30'),
(6, 13.50, 222.20, 2.00, '2', 'n', 1, '2019-04-24 15:30:00', NULL, '2019-04-24 07:51:30', '2019-04-24 07:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(10) UNSIGNED NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `time_stamp` datetime NOT NULL,
  `idmsg` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `lat`, `lng`, `time_stamp`, `idmsg`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 10, 100, '2019-04-08 22:30:00', 1, 5, '2019-04-27 09:12:37', '2019-04-27 09:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('eqNFaa4hW56y9xvCfmPOtk9W9OeijOnby2MFs7kt', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZFI3UkpHOVUySkhabmtBQmsxZDdMbkRGOVdpcGMyMnNuMFpqY3dQNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb2xvci8yMDE5LTA0LTA4JTIwMjM6MDAvNC8xMiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1555768430),
('gIWBQDzR5InvymMRsEBCEWOWRpqw3Ha75LwMHv9j', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV24yM0xqRk1VamxRdHRUcE1kZldURG5XdGhvaWZYMG9RRGg5V1ZKSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jdXJyZW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1555765241),
('gnBStwQK3U1Z7zIKuH5gaeGSFLhwJBcTqUBIataT', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3J0b25Ia2xwUlNWaUVubG1vYzdEcEFhd2N6Nk9CWWRHazg5bGVlSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb2xvci9ub3cvMS8xMiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1556251933),
('GWmHnRz4gNtxbtNNpBjX9eZX2jT8puY1HGbropC1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiamx2UmwwNjhlYmJTTkRpS2h0Tlp2WUpUcXFYRmFNSnk4YTZKdG5iUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9oaXN0b3J5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1555768212),
('oM03dWSShfYBH4cxahhYc4Z4bTyPzig7WdgFXA0d', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:66.0) Gecko/20100101 Firefox/66.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiODV1NjBvbU8zRHN1aW1uSTBnSEMxdDBLbFRnaVJUT05ybTBDMFpNQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb2xvci9ub3cvMS8xMiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1556350121);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(1) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `vehicle`, `image`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$12$hXTKte8FsySF0o4GtdwNzeRNWK36swWDkI2HAwsSkSRqM35.Ekex.', '0123456789', NULL, NULL, 'wheatear_male_1200x675_1555646810.jpg', 'mAa5JZNRazxCctviUkMu8LWO1NfegdDQASTkWG31rmDcjx2mEAAkNzr1x2Op', 1, '2017-10-01 10:00:00', '2019-04-18 21:06:50'),
(2, 'ttt', 'toan@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, NULL, NULL, 3, '2019-04-25 20:31:43', '2019-04-25 20:31:43'),
(4, 'ttt', 'toan.ld@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'motorbike', NULL, 'b0hZKgimG9y6Ja8tNL7o3cHzursEPnxCkdBS54XURW12MefvTYqVwOjQplIA', 3, '2019-04-25 22:36:36', '2019-04-27 00:35:01'),
(5, 'tttt', 'ldtoan@gmail.com', 'c33367701511b4f6020ec61ded352059', '0123456789', NULL, 'plane', 'teotfw_1556352119.jpg', 'UF3gtNhfKuXCdrl8YJ74qZci5BHPsQ0Gm96zoeMIkRvDTajwxVSbn1Wy2OEp', 3, '2019-04-27 00:38:29', '2019-04-27 01:01:59');

--
-- Indexes for dumped tables
--
-- --------------------------------------------------------

--
-- Table structure for table `algorithm`
--

CREATE TABLE `algorithm` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_of_algorithm` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_team` int(10) UNSIGNED NOT NULL
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for table `rectangles`
--
ALTER TABLE `rectangles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cells_detail`
--
ALTER TABLE `cells_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cells_detail_id_cell_foreign` (`id_cell`);

--
-- Indexes for table `future`
--
ALTER TABLE `future`
  ADD PRIMARY KEY (`id`),
  ADD KEY `future_id_cell_foreign` (`id_cell`);

--
-- Indexes for table `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `markers_record_user_foreign` (`record_user`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- Indexes for table `algorithm`
--
ALTER TABLE `algorithm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `algorithm_id_team_foreign` (`id_team`);

--
-- AUTO_INCREMENT for table `rectangles`
--
ALTER TABLE `rectangles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cells_detail`
--
ALTER TABLE `cells_detail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `markers`
--
ALTER TABLE `markers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `algorithm`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for table `cells_detail`
--
ALTER TABLE `cells_detail`
  ADD CONSTRAINT `cells_detail_id_cell_foreign` FOREIGN KEY (`id_cell`) REFERENCES `rectangles` (`id`);

--
-- Constraints for table `future`
--
ALTER TABLE `future`
  ADD CONSTRAINT `future_id_cell_foreign` FOREIGN KEY (`id_cell`) REFERENCES `rectangles` (`id`);

--
-- Constraints for table `markers`
--
ALTER TABLE `markers`
  ADD CONSTRAINT `markers_record_user_foreign` FOREIGN KEY (`record_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
