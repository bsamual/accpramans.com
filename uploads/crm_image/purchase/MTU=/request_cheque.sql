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
-- Table structure for table `request_cheque`
--

CREATE TABLE IF NOT EXISTS `request_cheque` (
  `cheque_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `bank_id` varchar(200) NOT NULL,
  `specific_number` text NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_cheque`
--

INSERT INTO `request_cheque` (`cheque_id`, `request_id`, `bank_id`, `specific_number`, `status`, `updatetime`) VALUES
(6, '4', '14', 'ABCDEFGH', 0, '2019-10-23 13:00:36'),
(7, '2', '9', '14000789654', 1, '2019-10-25 07:29:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_cheque`
--
ALTER TABLE `request_cheque`
  ADD PRIMARY KEY (`cheque_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_cheque`
--
ALTER TABLE `request_cheque`
  MODIFY `cheque_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
