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
-- Table structure for table `request_client`
--

CREATE TABLE `request_client` (
  `request_id` int(11) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `category_id` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `request_date` date NOT NULL,
  `request_from` varchar(200) NOT NULL,
  `request_sent` date NOT NULL,
  `status` int(11) NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_client`
--

INSERT INTO `request_client` (`request_id`, `client_id`, `category_id`, `year`, `request_date`, `request_from`, `request_sent`, `status`, `updatetime`) VALUES
(1, 'GBS001', '1', '2020', '2019-10-23', '3', '0000-00-00', 1, '2019-10-23 07:46:07'),
(2, 'GBS001', '1', '', '2019-10-23', '2', '0000-00-00', 0, '2019-10-23 07:53:27'),
(4, 'GBS004', '1', '2022', '2019-10-23', '1', '0000-00-00', 0, '2019-10-23 12:59:31');

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
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
