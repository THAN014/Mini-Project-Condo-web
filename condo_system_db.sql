-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 06:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `condo_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `Purchase_id` int(5) NOT NULL,
  `Room_id` int(5) NOT NULL,
  `User_id` int(5) NOT NULL,
  `Room_price` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reserve`
--

CREATE TABLE `reserve` (
  `Reserve_id` int(5) NOT NULL,
  `Room_id` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `User_id` int(5) NOT NULL,
  `fullname` varchar(20) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `Email` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `reserve`
--

INSERT INTO `reserve` (`Reserve_id`, `Room_id`, `username`, `User_id`, `fullname`, `Phone`, `Email`) VALUES
(1, 1, 'User1', 1, 'TEST Rent 1', '6426', 'hihwpo@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `room_db`
--

CREATE TABLE `room_db` (
  `Room_id` int(5) NOT NULL,
  `Room_number` varchar(5) NOT NULL,
  `Room_owner` int(5) NOT NULL,
  `Room_price` int(10) NOT NULL,
  `Status` enum('Sold','Empty','reserve') NOT NULL,
  `Description` varchar(400) NOT NULL,
  `Room_floor` int(3) NOT NULL,
  `Room_size` int(3) NOT NULL,
  `Seller_id` int(5) NOT NULL,
  `Picture` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `room_db`
--

INSERT INTO `room_db` (`Room_id`, `Room_number`, `Room_owner`, `Room_price`, `Status`, `Description`, `Room_floor`, `Room_size`, `Seller_id`, `Picture`) VALUES
(1, '', 0, 2890000, 'Sold', 'TEST ROOMAS ASKLDP  DSOFJLDSFO', 10, 54, 1, 'test1.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_id` int(5) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `Role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `email` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_id`, `Username`, `Password`, `phone`, `Role`, `email`) VALUES
(1, 'User1', '123456', '0641591046', 'User', '68319010014@tatc.ac.'),
(20001, 'admin', '123456', '8268', 'Admin', '289@gmail.com'),
(20005, 'Mo', '12', '28767867', 'User', 'hihwpo@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`Purchase_id`);

--
-- Indexes for table `reserve`
--
ALTER TABLE `reserve`
  ADD PRIMARY KEY (`Reserve_id`);

--
-- Indexes for table `room_db`
--
ALTER TABLE `room_db`
  ADD PRIMARY KEY (`Room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `Purchase_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reserve`
--
ALTER TABLE `reserve`
  MODIFY `Reserve_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `room_db`
--
ALTER TABLE `room_db`
  MODIFY `Room_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20006;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
