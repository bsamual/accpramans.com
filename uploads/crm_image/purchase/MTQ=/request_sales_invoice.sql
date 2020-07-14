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
-- Table structure for table `request_sales_invoice`
--

CREATE TABLE IF NOT EXISTS `request_sales_invoice` (
  `invoice_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `specific_invoice` text NOT NULL,
  `sales_invoices` text NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_sales_invoice`
--

INSERT INTO `request_sales_invoice` (`invoice_id`, `request_id`, `specific_invoice`, `sales_invoices`, `status`, `updatetime`) VALUES
(5, '4', 'specific welcome', 'welcome sales', 0, '2019-10-23 13:00:06'),
(6, '2', 'Test Sales', 'Test Specific customer', 1, '2019-10-25 07:27:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_sales_invoice`
--
ALTER TABLE `request_sales_invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_sales_invoice`
--
ALTER TABLE `request_sales_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
