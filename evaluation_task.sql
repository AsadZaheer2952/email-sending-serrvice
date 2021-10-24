-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2021 at 11:21 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation_task`
--

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `receiver` varchar(255) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `cc_name` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `bcc_name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `merchant_id`, `receiver`, `receiver_name`, `cc`, `cc_name`, `bcc`, `bcc_name`, `subject`, `body`, `status`) VALUES
(1, 4, 'admin@abc.com', 'admin', '', '', '', '', 'Test', 'Testing', 'pending'),
(2, 4, 'admin@abc.com', 'admin', 'abc@abc.com', 'asad', '', '', 'Test', 'Testing', 'pending'),
(3, 4, 'admin@abc.com', 'admin', 'abc@abc.com', 'asad', '', '', 'Test', 'Testing', 'pending'),
(4, 4, 'admin@abc.com', 'admin', 'abc@abc.com', 'asad', '', '', 'Test', 'Testing', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

CREATE TABLE `merchants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `balance` varchar(255) DEFAULT NULL,
  `parent_id` int(255) NOT NULL,
  `token` varchar(500) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `name`, `email`, `password`, `image`, `balance`, `parent_id`, `token`, `type`, `created_at`) VALUES
(1, 'Arsalan', 'admin@admin.com', '12345678', NULL, '0', 0, 'ac1d66e6ef7739a4381e03890aa0ee70845667', 'admin', '2021-10-23 19:11:17'),
(2, 'Arsalan', 'arsalan@gmail.com', '123456', NULL, '0', 0, '', '', '2021-10-23 19:13:16'),
(4, 'asad', 'arsalan@abc.com', '123456', 'uploads/1635016932.jpeg', '205.9022', 0, 'ac1d66e6ef7739a4381e03890aa0ee70', '', '2021-10-23 19:52:28'),
(6, 'asad', 'asad@gmail.com', '123456', NULL, '0', 4, '9cbaa7f083226cd8bf9e65bd0b2da6cf', 'user', '2021-10-23 20:19:06'),
(7, 'asad', 'asad@abc.com', '123456', NULL, '0', 4, 'bedf0582c58abef8d12c70de7cf6298d', 'user', '2021-10-23 20:39:33'),
(8, 'Arsalan', 'arsalan@abc23.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, '0', 0, '5ac321252fb083b133da890ba5363d4a', 'merchant', '2021-10-24 10:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL,
  `exp_month` varchar(255) NOT NULL,
  `exp_year` varchar(255) NOT NULL,
  `cvc` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `merchant_id`, `number`, `exp_month`, `exp_year`, `cvc`, `amount`) VALUES
(1, 4, '4242424242424242', '10', '2022', '123', '200'),
(2, 4, '4242424242424242', '10', '2022', '123', '200'),
(3, 4, '4242424242424242', '10', '2022', '123', '200'),
(4, 4, '4242424242424242', '10', '2022', '123', '200');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`) VALUES
(2, 'createSecondoryUser', '2021-10-23 22:07:53'),
(3, 'assignPermission', '2021-10-23 22:35:54');

-- --------------------------------------------------------

--
-- Table structure for table `secondory_user_permissions`
--

CREATE TABLE `secondory_user_permissions` (
  `id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `secondory_user_permissions`
--

INSERT INTO `secondory_user_permissions` (`id`, `merchant_id`, `permission_id`) VALUES
(2, 6, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `secondory_user_permissions`
--
ALTER TABLE `secondory_user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `secondory_user_permissions`
--
ALTER TABLE `secondory_user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
