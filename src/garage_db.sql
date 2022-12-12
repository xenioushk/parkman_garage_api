-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Dec 12, 2022 at 12:09 AM
-- Server version: 5.7.40
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `garage_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `garage`
--

CREATE TABLE `garage` (
  `id` int(11) NOT NULL,
  `garage_id` bigint(20) NOT NULL,
  `garage_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `hourly_price` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `country` varchar(60) NOT NULL,
  `latitude` decimal(17,15) NOT NULL,
  `longitude` decimal(17,15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `garage`
--

INSERT INTO `garage` (`id`, `garage_id`, `garage_name`, `hourly_price`, `currency`, `country`, `latitude`, `longitude`) VALUES
(1, 50786421, 'Garage1', '2.00', '€', 'Finland', '60.168607847624095', '24.932371066131623'),
(2, 50786422, 'Garage2', '1.50', '€', 'Finland', '60.162562000000000', '24.939453000000000'),
(3, 50786423, 'Garage3', '3.00', '€', 'Finland', '60.164449966455110', '24.938178168200714'),
(4, 50786424, 'Garage4', '3.00', '€', 'Finland', '60.165219358852795', '24.935374259948730'),
(5, 50786425, 'Garage5', '3.00', '€', 'Finland', '60.171674294900680', '24.921585662024363'),
(6, 50786426, 'Garage6', '2.00', '€', 'Finland', '60.168673901487510', '24.930162952045407');

-- --------------------------------------------------------

--
-- Table structure for table `garage_info`
--

CREATE TABLE `garage_info` (
  `id` int(11) NOT NULL,
  `garage_id` bigint(20) NOT NULL,
  `owner_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `garage_info`
--

INSERT INTO `garage_info` (`id`, `garage_id`, `owner_id`) VALUES
(1, 50786421, 29190),
(2, 50786422, 29190),
(3, 50786423, 29190),
(4, 50786424, 29190),
(5, 50786425, 29190),
(6, 50786426, 29191);

-- --------------------------------------------------------

--
-- Table structure for table `garage_owner`
--

CREATE TABLE `garage_owner` (
  `id` int(11) NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `owner_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `owner_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `garage_owner`
--

INSERT INTO `garage_owner` (`id`, `owner_id`, `owner_name`, `owner_email`) VALUES
(1, 29190, 'AutoPark', 'testemail@testautopark.fi'),
(2, 29191, 'Parkkitalo OY', 'testemail@testgarage.fi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garage_info`
--
ALTER TABLE `garage_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `garage_owner`
--
ALTER TABLE `garage_owner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `garage`
--
ALTER TABLE `garage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `garage_info`
--
ALTER TABLE `garage_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `garage_owner`
--
ALTER TABLE `garage_owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
