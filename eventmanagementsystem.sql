-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 12:20 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventmanagementsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `contactus_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`contactus_id`, `user_id`, `username`, `email`, `subject`, `message`, `status`, `time_created`) VALUES
(2, 0, 'Lim ', 'lim@gmail.com', 'Test', 'testing', 'Unread', '2024-10-23 02:05:21'),
(3, 0, 'test', 'test@gmail.com', 'test', 'test', 'Read', '2024-10-24 16:57:14'),
(4, 7, 'Lim Jia De', 'test@gmail.com', 'test', 'test', 'Unread', '2024-10-27 17:30:31'),
(5, 9, 'Lim Jia De', 'limjiadede@hotmail.com', '123', '123', 'Unread', '2024-11-06 10:18:07'),
(6, 9, 'Lim Jia De', 'limjiadede@hotmail.com', '123', '123', 'Read', '2024-11-06 10:20:07');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text NOT NULL,
  `slots` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_type`, `image`, `location`, `date`, `start_time`, `end_time`, `description`, `slots`, `created_by`, `status`, `time_created`) VALUES
(1, 'Science Fair ', 'Workshop', 'science.jpg', 'LT', '2024-10-25', '08:00:00', '14:30:00', 'Hi, Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.', 38, 7, 'Approved', '2024-10-09 14:00:28'),
(6, 'FootBall Competition FootBall Competition ', 'Sport', 'slider1.jpg', 'Inti Car Park', '2024-11-13', '08:00:00', '10:00:00', 'Welcome to Football Competition Event!', 17, 7, 'Approved', '2024-10-13 11:45:41'),
(15, 'Chinese Workshop', 'Workshop', 'chinese.jpg', 'Hall 1', '2024-11-05', '13:00:00', '15:00:00', 'Chinese Workshop !', 2, 7, 'Pending', '2024-10-20 05:10:47'),
(18, 'Event 1 ', 'Career', 'account.jpg', 'Event Hall 1', '2024-11-06', '09:00:00', '12:00:00', 'Event 1 !', 10, 9, 'Pending', '2024-11-06 12:41:44');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `registration_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`registration_id`, `event_id`, `user_id`, `registration_date`) VALUES
(7, 1, 7, '2024-10-20 17:37:42'),
(9, 15, 7, '2024-10-23 01:57:39'),
(15, 6, 9, '2024-11-06 08:55:35'),
(16, 15, 9, '2024-11-06 08:56:59'),
(22, 6, 7, '2024-11-07 11:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `rating` int(3) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `event_id`, `user_id`, `feedback`, `rating`, `time_created`) VALUES
(20, 1, 7, 'Hi, Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Hi, Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Hi, Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.Welcome to Science Fair 2024, I Hope you enjoy the Science and Technology.', 5, '2024-09-22 05:28:53'),
(21, 15, 9, 'Good', 5, '2024-11-06 09:06:56'),
(23, 15, 7, '123', 2, '2024-11-07 11:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telno` varchar(15) NOT NULL,
  `role` varchar(20) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `telno`, `role`, `time_created`) VALUES
(7, 'Lim Jia De', 'test@gmail.com', '8e585e7956626554430d9aee08b4866e', '0164760632', 'Organizer', '2024-10-09 13:06:28'),
(8, 'Admin1', 'admin@gmail.com', '8e585e7956626554430d9aee08b4866e', '0164760632', 'Admin', '2024-10-20 07:26:07'),
(9, 'Lim Jia De', 'limjiadede@hotmail.com', '8e585e7956626554430d9aee08b4866e', '0123456789', 'User', '2024-10-21 15:08:29'),
(10, 'Testing', 'testing@gmail.com', '8e585e7956626554430d9aee08b4866e', '0164760632', 'Organizer', '2024-10-21 16:35:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`contactus_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`registration_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `contactus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
