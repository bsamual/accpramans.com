-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2019 at 05:35 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Table structure for table `request_cheque_attached`
--

CREATE TABLE `request_cheque_attached` (
  `attached_id` int(11) NOT NULL,
  `request_id` varchar(200) NOT NULL,
  `cheque_id` varchar(200) NOT NULL,
  `attachment` varchar(500) NOT NULL,
  `url` text NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_cheque_attached`
--

INSERT INTO `request_cheque_attached` (`attached_id`, `request_id`, `cheque_id`, `attachment`, `url`, `status`, `updatetime`) VALUES
(3, '4', '6', 'no remove', '', 0, '2019-10-23 11:54:28'),
(4, '4', '6', 'test2..jpg', '', 0, '2019-10-23 13:01:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request_cheque_attached`
--
ALTER TABLE `request_cheque_attached`
  ADD PRIMARY KEY (`attached_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request_cheque_attached`
--
ALTER TABLE `request_cheque_attached`
  MODIFY `attached_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
