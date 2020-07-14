-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2019 at 05:02 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m561034_tasks`
--

-- --------------------------------------------------------

--
-- Table structure for table `request_purchase_invoice`
--

CREATE TABLE IF NOT EXISTS `request_purchase_invoice` (
  `invoice_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `specific_invoice` text NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_purchase_invoice`
--

INSERT INTO `request_purchase_invoice` (`invoice_id`, `request_id`, `specific_invoice`, `status`, `updatetime`) VALUES
(5, '4', 'welcome purchase', 0, '2019-10-23 12:59:48'),
(8, '2', 'Test Purchase', 1, '2019-10-25 07:20:31'),
(9, '2', 'Test Purchase 2', 1, '2019-10-25 07:22:40'),
(10, '8', 'Test Purchase', 0, '2019-10-25 14:01:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_purchase_invoice`
--
ALTER TABLE `request_purchase_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_purchase_invoice`
--
ALTER TABLE `request_purchase_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
