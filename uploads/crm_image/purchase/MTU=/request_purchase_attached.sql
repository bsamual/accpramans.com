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
-- Table structure for table `request_purchase_attached`
--

CREATE TABLE IF NOT EXISTS `request_purchase_attached` (
  `attached_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `purchase_id` varchar(200) NOT NULL,
  `attachment` varchar(500) NOT NULL,
  `url` text NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_purchase_attached`
--

INSERT INTO `request_purchase_attached` (`attached_id`, `request_id`, `purchase_id`, `attachment`, `url`, `status`, `updatetime`) VALUES
(1, '2', '9', 'IGI0067291.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(2, '2', '9', 'IGI0067411.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(3, '2', '9', 'IGI0067454.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(4, '2', '9', 'IGI0067455.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(5, '2', '9', 'IGI0067481.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(6, '2', '9', 'IGI0067517.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(7, '2', '9', 'IGI0067207.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(8, '2', '9', 'IGI0067236.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(9, '2', '9', 'IGI0067245.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(10, '2', '9', 'IGI0067257.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(11, '2', '9', 'IGI0067267.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(12, '2', '9', 'IGI0067283.PDF', 'uploads/crm_image/purchase/OQ==', 1, '2019-10-25 07:22:40'),
(13, '8', '10', 'IGI0067454.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(14, '8', '10', 'IGI0067455.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(15, '8', '10', 'IGI0067481.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(16, '8', '10', 'IGI0067517.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(17, '8', '10', 'IGI0067536.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(18, '8', '10', 'IGI0067562.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(19, '8', '10', 'IGI0067207.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(20, '8', '10', 'IGI0067236.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(21, '8', '10', 'IGI0067245.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(22, '8', '10', 'IGI0067257.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(23, '8', '10', 'IGI0067267.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(24, '8', '10', 'IGI0067283.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(25, '8', '10', 'IGI0067291.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28'),
(26, '8', '10', 'IGI0067411.PDF', 'uploads/crm_image/purchase/MTA=', 0, '2019-10-25 14:01:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_purchase_attached`
--
ALTER TABLE `request_purchase_attached`
  ADD PRIMARY KEY (`attached_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_purchase_attached`
--
ALTER TABLE `request_purchase_attached`
  MODIFY `attached_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
