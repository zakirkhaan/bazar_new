-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2017 at 04:06 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `a_real_ecom_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `serial_numbers`
--

CREATE TABLE `serial_numbers` (
  `serial_id` int(10) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `serial_product` varchar(100) NOT NULL,
  `serial_date` text NOT NULL,
  `serial_status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `serial_numbers`
--

INSERT INTO `serial_numbers` (`serial_id`, `order_id`, `serial_number`, `serial_product`, `serial_date`, `serial_status`) VALUES
(2, '0', 'Z6N2-QCUU-HTBB', '4', '2017-02-23 05:22:01', 'Supplied'),
(3, '', 'SNU5-X7E8-5FNP\r', '1', '2017-02-23 21:48:20', 'Supplied'),
(4, '39', 'D3R8-QWNY-WRLQ', '1', '2017-02-23 21:48:21', 'Supplied'),
(6, '', 'B4S4-GSTF-85AC', '16', '2017-02-23 21:50:39', 'Supplied'),
(7, '', 'JCW8-HUP2-XH8J', '2', '2017-02-23 22:02:49', 'Supplied'),
(9, '', 'WNN2-F8JU-HT72\r', '17', '2017-02-26 13:15:54', 'Supplied'),
(10, '', 'ZS86-QY64-SDBB\r', '17', '2017-02-26 13:15:54', 'Supplied'),
(11, '', 'GS7O-XSU9-VDT2\r', '17', '2017-02-26 13:15:54', 'Not Supplied'),
(12, '', 'ZKS2-QH5U-N5LP', '17', '2017-02-26 13:15:54', 'Not Supplied'),
(13, '', 'AW6X-622K-X98N\r', '6', '2017-02-27 22:24:06', 'Supplied'),
(14, '43', 'PVNZ-T8PB-MN68', '6', '2017-02-27 22:24:06', 'Supplied'),
(15, '42', 'V38A-HFYC-2BRU\r', '7', '2017-03-08 03:26:43', 'Supplied'),
(16, '42', 'PVNZ-T8PB-MN68', '7', '2017-03-08 03:26:43', 'Supplied');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `serial_numbers`
--
ALTER TABLE `serial_numbers`
  ADD PRIMARY KEY (`serial_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `serial_numbers`
--
ALTER TABLE `serial_numbers`
  MODIFY `serial_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
