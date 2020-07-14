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
-- Table structure for table `request_client`
--

CREATE TABLE IF NOT EXISTS `request_client` (
  `request_id` int(11) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `category_id` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `request_date` date NOT NULL,
  `request_from` varchar(200) NOT NULL,
  `request_sent` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_client`
--

INSERT INTO `request_client` (`request_id`, `client_id`, `category_id`, `year`, `request_date`, `request_from`, `request_sent`, `status`, `updatetime`) VALUES
(1, 'GBS001', '1', '2020', '2019-10-23', '3', '0000-00-00 00:00:00', 1, '2019-10-23 07:46:07'),
(2, 'GBS001', '1', '2021', '2019-10-23', '2', '2019-10-25 14:36:59', 1, '2019-10-23 07:53:27'),
(4, 'GBS004', '1', '2022', '2019-10-23', '1', '0000-00-00 00:00:00', 0, '2019-10-23 12:59:31'),
(5, 'GBS472', '', '', '2019-10-24', '', '0000-00-00 00:00:00', 0, '2019-10-24 06:30:34'),
(6, 'GBS001', '1', '2020', '2019-10-24', '1', '0000-00-00 00:00:00', 0, '2019-10-24 06:30:48'),
(7, 'GBS001', '', '2009', '2019-10-24', '', '0000-00-00 00:00:00', 0, '2019-10-24 06:31:03'),
(8, 'GBS001', '1', '2009', '2019-10-25', '1', '0000-00-00 00:00:00', 0, '2019-10-25 14:00:41'),
(9, 'GBS002', '', '', '2019-10-25', '', '0000-00-00 00:00:00', 0, '2019-10-25 14:23:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_client`
--
ALTER TABLE `request_client`
  ADD PRIMARY KEY (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_client`
--
ALTER TABLE `request_client`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
