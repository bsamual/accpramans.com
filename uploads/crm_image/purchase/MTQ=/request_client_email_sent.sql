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
-- Table structure for table `request_client_email_sent`
--

CREATE TABLE IF NOT EXISTS `request_client_email_sent` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `email_sent` datetime NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_client_email_sent`
--

INSERT INTO `request_client_email_sent` (`id`, `request_id`, `email_sent`, `updatetime`) VALUES
(1, 2, '2019-10-25 13:48:35', '2019-10-25 12:48:35'),
(2, 2, '2019-10-25 14:30:28', '2019-10-25 13:30:28'),
(3, 2, '2019-10-25 14:36:59', '2019-10-25 13:36:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_client_email_sent`
--
ALTER TABLE `request_client_email_sent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_client_email_sent`
--
ALTER TABLE `request_client_email_sent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
