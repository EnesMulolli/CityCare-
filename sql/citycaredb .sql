-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 11:08 PM
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
-- Database: `citycaredb`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved','In Review') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `title`, `description`, `location`, `image`, `status`, `created_at`) VALUES
(1, 1, 'Street Lights', 'broken trafic lights', 'Prishtina/Dardani', '6924d9e3599aa.jpg', 'Resolved', '2025-11-24 22:19:15'),
(2, 3, 'Parks & Trees', 'fallen trees all over the neighbourhood', 'Prishtina/Germi', '6926220ee8be4.jpg', 'In Review', '2025-11-25 21:39:26'),
(3, 2, 'Other', 'test desc', 'Test/Test', '692623778ac55.jpg', 'Pending', '2025-11-25 21:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.webp',
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `username`, `location`, `profile_image`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin', 'admin123', 'Prishtina', 'default.webp', 'admin@example.com', '$2y$10$b/WaLRb3yGxwcCJjM9hHaeFWJ4G29JXx4zI9Esac7IJzizcX1/GoW', 'admin', '2025-11-24 20:11:29'),
(2, 'Enes', 'Mulolli', 'enesmulolli', 'Prishtina', 'default.webp', 'enesmulolli59@gmail.com', '$2y$10$HT7GkBB/f1eg7NcZwhw3TuyyUfUP6mXgXW1ClIQykJLyAA8cmmAA.', 'admin', '2025-11-24 20:11:29'),
(3, 'User', 'Normal', 'normaluser', 'Skenderaj', 'default.webp', 'efgfwaocklrsdgjkda@nespj.com', '$2y$10$Xu6h2JZIhGe4PwB9eOps6OvtQTdcgjf0QCe87EqoM3PCthlLKN7NG', 'user', '2025-11-24 21:40:00'),
(4, 'Test', 'Acc', 'testacc', 'Test', 'default.webp', 'efgfwabrgejuydgjkda@nespj.com', '$2y$10$Anq7w5YeiSX6RruKrKOKzO12FaLx4YjU369pgDacIirN0WQNWoCRa', 'user', '2025-11-25 21:58:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
