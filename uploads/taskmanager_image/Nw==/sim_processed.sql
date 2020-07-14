-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2020 at 11:09 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comco-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `sim_processed`
--

CREATE TABLE IF NOT EXISTS `sim_processed` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `import_date` datetime NOT NULL,
  `uploaded_date` datetime NOT NULL,
  `month_year` varchar(200) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `network_id` varchar(100) NOT NULL,
  `first_connection` int(11) NOT NULL,
  `topups` int(11) NOT NULL,
  `commission` varchar(200) NOT NULL,
  `bonus` varchar(200) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sim_processed`
--
ALTER TABLE `sim_processed`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sim_processed`
--
ALTER TABLE `sim_processed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
