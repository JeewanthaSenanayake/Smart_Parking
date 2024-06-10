-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 10:44 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_parking`
--

-- --------------------------------------------------------

--
-- Table structure for table `parking_history`
--

CREATE TABLE `parking_history` (
  `pid` int(11) NOT NULL,
  `vehical_number` varchar(10) NOT NULL,
  `vehical_type` varchar(10) NOT NULL,
  `in_date` timestamp NULL DEFAULT NULL,
  `out_date` timestamp NULL DEFAULT NULL,
  `your_nic` varchar(13) NOT NULL,
  `your_name` varchar(50) NOT NULL,
  `your_mobile` varchar(12) NOT NULL,
  `slotId` int(10) DEFAULT NULL,
  `uid` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parking_history`
--

INSERT INTO `parking_history` (`pid`, `vehical_number`, `vehical_type`, `in_date`, `out_date`, `your_nic`, `your_name`, `your_mobile`, `slotId`, `uid`) VALUES
(1, 'ABB-7859', 'Car', '2024-02-14 02:30:00', '2024-02-14 04:30:00', '19999270128', 'Hirushini', '0758941289', 5, '13'),
(2, 'UA-5896', 'Bike', '2024-02-14 00:30:00', '2024-02-14 07:30:00', '19999270128', 'Hirushini', '0758941289', 8, '13'),
(3, 'BCO-7896', 'Other', '2024-02-13 16:08:00', '2024-02-13 16:10:00', '992275846V', 'Jeewantha', '0778954785', 10, '10');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payid` int(11) NOT NULL,
  `acc_id` varchar(10) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payid`, `acc_id`, `user_name`, `amount`) VALUES
(1, '13', 'Hirushini', '450.00'),
(2, '10', 'Jeewantha', '175.00');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `uid` int(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobileNumber` varchar(12) NOT NULL,
  `nic` varchar(12) NOT NULL,
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`uid`, `name`, `email`, `password`, `mobileNumber`, `nic`, `role`) VALUES
(11, 'Lakshika Hirushini Wanninayake', 'lakshikahirushini89@gmail.com', 'Abc&1234', '0723014903', '199925789632', 'user'),
(13, 'Lakshika user', 'lakshikauser@gmail.com', 'Abc&1234', '0784596852', '199925789633', 'user'),
(14, 'Lakshika admin', 'lakshikaadmin@gmail.com', 'Abc&1234', '0784596858', '199925789634', 'admin'),
(10, 'Jeewantha Senanayake', 'ssbjms123@gmail.com', 'Abc&1234', '0787314241', '992270129v', 'user'),
(15, 'Sanduni', 'sandu@gmail.com', 'Abc&1234', '0723014903', '999999999v', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `solts_table`
--

CREATE TABLE `solts_table` (
  `slotId` int(11) NOT NULL,
  `slot_name` varchar(50) NOT NULL,
  `bookedCar` varchar(10) DEFAULT NULL,
  `inTime` timestamp NULL DEFAULT NULL,
  `outTime` timestamp NULL DEFAULT NULL,
  `isAvailable` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `solts_table`
--

INSERT INTO `solts_table` (`slotId`, `slot_name`, `bookedCar`, `inTime`, `outTime`, `isAvailable`) VALUES
(5, 'Slot 1', 'ABB-7859', '2024-02-14 02:30:00', '2024-02-14 04:30:00', 0),
(6, 'Slot 2', NULL, NULL, NULL, 1),
(7, 'Slot 3', NULL, NULL, NULL, 1),
(8, 'Slot 4', 'UA-5896', '2024-02-14 00:30:00', '2024-02-14 07:30:00', 0),
(9, 'Slot 5', NULL, NULL, NULL, 1),
(10, 'Slot 6', 'ABB-0084', '2024-02-13 17:41:00', '2024-02-13 18:16:00', 0),
(11, 'Slot 7', NULL, NULL, NULL, 1),
(12, 'Slot 8', NULL, NULL, NULL, 1),
(14, 'Slot 9', 'UA-0001', '2024-02-13 17:41:00', '2024-02-13 18:41:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parking_history`
--
ALTER TABLE `parking_history`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payid`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`nic`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `solts_table`
--
ALTER TABLE `solts_table`
  ADD PRIMARY KEY (`slotId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parking_history`
--
ALTER TABLE `parking_history`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `uid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `solts_table`
--
ALTER TABLE `solts_table`
  MODIFY `slotId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
