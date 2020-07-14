-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2019 at 05:01 PM
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
-- Table structure for table `request_bank_statement`
--

CREATE TABLE IF NOT EXISTS `request_bank_statement` (
  `statement_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `bank_id` varchar(200) NOT NULL,
  `statment_number` varchar(500) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` int(11) NOT NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_bank_statement`
--

INSERT INTO `request_bank_statement` (`statement_id`, `request_id`, `bank_id`, `statment_number`, `from_date`, `to_date`, `status`, `update_time`) VALUES
(4, '4', '13', '123456', '0000-00-00', '0000-00-00', 0, '2019-10-23 13:00:14'),
(5, '4', '14', '123456', '2019-10-01', '2019-10-23', 0, '2019-10-23 13:00:27'),
(6, '2', '9', '123456', '0000-00-00', '0000-00-00', 1, '2019-10-25 07:34:50'),
(7, '2', '2', '987654', '2019-10-01', '2019-10-26', 1, '2019-10-25 07:35:54'),
(9, '2', '', '121212121', '0000-00-00', '0000-00-00', 1, '2019-10-25 07:50:40'),
(10, '2', '', '', '2019-10-25', '2019-10-30', 1, '2019-10-25 07:51:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_bank_statement`
--
ALTER TABLE `request_bank_statement`
  ADD PRIMARY KEY (`statement_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_bank_statement`
--
ALTER TABLE `request_bank_statement`
  MODIFY `statement_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
