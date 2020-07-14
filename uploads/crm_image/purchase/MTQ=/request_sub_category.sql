-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2019 at 05:03 PM
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
-- Table structure for table `request_sub_category`
--

CREATE TABLE IF NOT EXISTS `request_sub_category` (
  `sub_category_id` int(11) NOT NULL,
  `category_id` varchar(200) NOT NULL,
  `sub_category_name` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_sub_category`
--

INSERT INTO `request_sub_category` (`sub_category_id`, `category_id`, `sub_category_name`, `status`, `update_time`) VALUES
(1, '1', 'Purchase invoices', 0, '2019-10-16 17:01:53'),
(2, '1', 'Sales Invoices', 0, '2019-10-16 17:02:02'),
(3, '1', 'Bank statements', 0, '2019-10-16 17:02:13'),
(4, '1', 'Cheque Books', 0, '2019-10-16 17:02:20'),
(5, '1', 'Other Information', 0, '2019-10-16 17:02:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_sub_category`
--
ALTER TABLE `request_sub_category`
  ADD PRIMARY KEY (`sub_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_sub_category`
--
ALTER TABLE `request_sub_category`
  MODIFY `sub_category_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
