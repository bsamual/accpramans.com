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
-- Table structure for table `request_sales_attached`
--

CREATE TABLE IF NOT EXISTS `request_sales_attached` (
  `attached_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `sales_id` varchar(200) NOT NULL,
  `attachment` varchar(500) NOT NULL,
  `url` text NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_sales_attached`
--

INSERT INTO `request_sales_attached` (`attached_id`, `request_id`, `sales_id`, `attachment`, `url`, `status`, `updatetime`) VALUES
(2, '4', '5', 'no remove.jpg', 'no remove', 0, '2019-10-23 11:54:55'),
(3, '4', '5', 'jg2.jg', '', 0, '2019-10-23 13:03:01'),
(4, '2', '6', 'IGI0067787.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(5, '2', '6', 'IGI0068058.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(6, '2', '6', 'IGI0068079.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(7, '2', '6', 'IGI0068124.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(8, '2', '6', 'IGI0068151.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(9, '2', '6', 'IGI0068227.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(10, '2', '6', 'IGI0068311.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(11, '2', '6', 'IGI0068312.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(12, '2', '6', 'IGI0068457.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(13, '2', '6', 'IGI0068712.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37'),
(14, '2', '6', 'IGI0067699.PDF', 'uploads/crm_image/sales/Ng==', 1, '2019-10-25 07:27:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_sales_attached`
--
ALTER TABLE `request_sales_attached`
  ADD PRIMARY KEY (`attached_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_sales_attached`
--
ALTER TABLE `request_sales_attached`
  MODIFY `attached_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
