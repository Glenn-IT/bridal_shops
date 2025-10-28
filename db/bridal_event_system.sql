-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 01:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bridal_event_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `event_name` varchar(255) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `event_datetime` datetime NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `event` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `venue_package` varchar(50) NOT NULL,
  `gowns_package` varchar(50) NOT NULL,
  `themes_package` varchar(50) NOT NULL,
  `event_date` datetime NOT NULL,
  `location` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `firstname`, `middlename`, `lastname`, `username`, `phone_number`, `event_name`, `service_type`, `event_datetime`, `event_type`, `fullname`, `phone`, `event`, `contact`, `email`, `venue_package`, `gowns_package`, `themes_package`, `event_date`, `location`, `total_price`, `created_at`, `status`) VALUES
(58, 'Nicole', 'Daguio', 'Acojedo', '', '', 'Wedding', 'Wedding Gown', '2025-03-10 10:00:00', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', 'campo', 0.00, '2025-09-29 04:23:53', 'Pending'),
(59, 'Roderic', 'Pacion', 'Casauay', '', 'abc', 'wedding', 'Wedding Gown', '2025-09-29 10:32:00', '', '', '', '', '', 'roderic.casauaygmail.com', '', '', '', '0000-00-00 00:00:00', 'piat', 0.00, '2025-09-30 01:33:43', 'Pending'),
(60, 'Brian', 'A', 'Ilac', '', '09971737060', 'wedding', 'Wedding Gown', '2025-10-05 09:40:00', '', '', '', '', '', 'roderic.casauay@gmail.com', '', '', '', '0000-00-00 00:00:00', 'piat', 0.00, '2025-09-30 01:39:54', 'Approved'),
(61, 'Angelie', 'Lagoc', 'Pagutalan', '', '09971737060', 'Wedding', 'Wedding Gown', '2025-10-06 10:50:00', '', '', '', '', '', 'delacruzmelody847@gmail.com', '', '', '', '0000-00-00 00:00:00', 'piat', 0.00, '2025-09-30 01:49:53', 'Approved'),
(62, 'Roderic', 'Pacion', 'Casauay', '', '09971737060', 'Wedding', 'Wedding Gown', '2025-10-05 10:51:00', '', '', '', '', '', 'roderic.casauay@gmail.com', '', '', '', '0000-00-00 00:00:00', 'piat', 0.00, '2025-09-30 01:51:53', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `client_name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `username`, `login_time`, `ip_address`) VALUES
(1, 'angelie', '2025-05-28 12:51:49', '::1'),
(2, 'angelie', '2025-05-28 12:52:25', '::1'),
(3, 'angelie', '2025-05-28 12:53:24', '::1'),
(4, 'angelie', '2025-05-28 12:54:19', '::1'),
(5, 'angelie', '2025-05-28 12:54:55', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `username`, `message`, `is_read`, `created_at`) VALUES
(15, 'laika queroda', 'Your booking reservation is approved.', 0, '2025-09-18 20:44:20'),
(16, 'Angelie Pagutalan', 'Your booking reservation is approved.', 0, '2025-09-24 10:18:38'),
(17, 'Melody Dela cruz', 'Your booking reservation is approved.', 0, '2025-09-24 10:21:06'),
(18, 'Melody Dela cruz', 'Your booking reservation is approved.', 0, '2025-09-24 15:28:02'),
(19, 'Melody Dela cruz', 'Your booking reservation is approved.', 0, '2025-09-24 18:53:57'),
(20, '', 'Your booking reservation is approved.', 0, '2025-09-27 16:37:58'),
(21, '', 'Hello , your booking reservation has been approved.', 0, '2025-09-27 20:26:29'),
(22, 'Nicole Daguio Acojedo', 'Hello Nicole Daguio Acojedo, your booking reservation has been approved.', 0, '2025-09-27 21:00:41'),
(23, 'Angelie Padua Pagutalan', 'Hello Angelie Padua Pagutalan, your booking reservation has been approved.', 0, '2025-09-27 21:17:44'),
(24, 'Angelie Padua Pagutalan', 'Hello Angelie Padua Pagutalan, your booking reservation has been approved.', 0, '2025-09-27 21:34:52'),
(25, 'Nicole Daguio Acojedo', 'Hello Nicole Daguio Acojedo, your booking reservation has been approved.', 0, '2025-09-29 11:41:27'),
(26, 'Nicole Daguio Acojedo', 'Hello Nicole Daguio Acojedo, your booking reservation has been approved.', 0, '2025-09-29 11:41:34'),
(27, 'Nicole Daguio Acojedo', 'Hello Nicole Daguio Acojedo, your booking reservation has been approved.', 0, '2025-09-29 11:41:40'),
(28, 'Nicole Daguio Acojedo', 'Hello Nicole Daguio Acojedo, your booking reservation has been approved.', 0, '2025-09-29 11:41:46'),
(29, 'Roderic Pacion Casauay', 'Hello Roderic Pacion Casauay, your booking reservation has been approved.', 0, '2025-09-30 09:54:21'),
(30, 'Angelie Lagoc Pagutalan', 'Hello Angelie Lagoc Pagutalan, your booking reservation has been approved.', 0, '2025-09-30 10:02:23'),
(31, 'Angelie Lagoc Pagutalan', 'Hello Angelie Lagoc Pagutalan, your booking reservation has been approved.', 0, '2025-09-30 10:02:30'),
(32, 'Angelie Lagoc Pagutalan', 'Hello Angelie Lagoc Pagutalan, your booking reservation has been approved.', 0, '2025-09-30 10:02:35'),
(33, 'Angelie Lagoc Pagutalan', 'Hello Angelie Lagoc Pagutalan, your booking reservation has been approved.', 0, '2025-09-30 10:02:41'),
(34, 'Angelie Lagoc Pagutalan', 'Hello Angelie Lagoc Pagutalan, your booking reservation has been approved.', 0, '2025-09-30 10:02:48'),
(35, 'Brian A Ilac', 'Hello Brian A Ilac, your booking reservation has been approved.', 0, '2025-09-30 10:03:07');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `event_name` varchar(100) DEFAULT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `event_name`, `package_name`, `description`, `price`) VALUES
(1, 'Birthday', 'Basic Package', 'Venue decoration, standard birthday cake, balloon setup, basic gown rental', 10000.00),
(2, 'Birthday', 'Silver Package', 'Customized theme decoration, upgraded birthday cake, themed photo booth, designer gown rental', 20000.00),
(3, 'Birthday', 'Gold Package', 'Full event planning, live hosting, premium catering, full costume package, photo and video coverage', 35000.00),
(4, 'Wedding', 'Basic Package', 'Venue decoration, basic bridal gown rental, standard bouquet, basic lighting setup', 15000.00),
(5, 'Wedding', 'Silver Package', 'Customized venue theme, designer bridal gown rental, floral arrangements, professional photographer', 25000.00),
(6, 'Wedding', 'Gold Package', 'Full wedding planning, bridal and entourage gowns, luxury floral design, 5-course catering, full media team', 40000.00),
(7, 'Anniversary', 'Basic Package', 'Simple venue decor, music setup, anniversary cake', 8000.00),
(8, 'Anniversary', 'Silver Package', 'Theme-based decoration, wine setup, catering for 50 guests, couple attire', 18000.00),
(9, 'Anniversary', 'Gold Package', 'Full event management, live music band, luxury dining, video documentary, designer dress/suit rental', 30000.00),
(10, 'Corporate', 'Basic Package', 'Stage setup, basic sound system, banner printing, 50 chairs and tables', 12000.00),
(11, 'Corporate', 'Silver Package', 'Themed stage design, wireless microphones, buffet catering, event coordination', 22000.00),
(12, 'Corporate', 'Gold Package', 'Full event execution, LED wall, media coverage, conference kits, branding materials', 35000.00);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `service` varchar(100) NOT NULL,
  `event` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `client_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `email` varchar(100) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer_hash` char(64) NOT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `role`, `email`, `security_question`, `security_answer_hash`, `status`) VALUES
(1, 'melody', 'dela', 'cruz', 'admin', '$2y$10$ixwZy1X0LIrUvA8AfatvgOVHZ5rGDi9Gl0t.J9Lw/MX5MNiWsgZiS', 'admin', 'admin@gmail.com', 'What is your favorite color?', 'a67a41c8bc79d5da917b5051f1f0d3f5aeb4b63ba246b3546a961ef7a3c7d931', 'active'),
(2, 'angelie', 'quin', 'pangadua', 'angelie', '$2y$10$CSyB6F39LPeV2MU/rNBBMO72qpVMAUHShbEtQsjsHcI9ch9X/o1tm', 'client', 'angelie@gmail.com.com', 'What is your mother’s maiden name?', '155aaee0403c7f9ed1383f4bb9ec75b25d4f158af21acef43a029ee658ffd99b', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
