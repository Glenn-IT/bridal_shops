-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 07:04 PM
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
-- Database: `bridal_event_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_datetime` datetime NOT NULL,
  `location` text NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash',
  `payment_screenshot` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `firstname`, `middlename`, `lastname`, `email`, `phone_number`, `service_type`, `package_name`, `event_name`, `event_datetime`, `location`, `payment_method`, `payment_screenshot`, `status`, `created_at`) VALUES
(6, 'Glenard', 'U', 'Pagurayan', 'glenard2308@gmail.com', '09557997409', 'Wedding', 'Basic Package', 'Sample', '2025-10-31 00:27:00', 'Sample', 'Cash', NULL, 'Declined', '2025-10-28 16:27:42'),
(7, 'Lhei', 'B', 'Pagurayan', 'lbariuangasmen@gmail.com', '09797978978', 'Anniversary', 'Silver Package', 'qwe', '2025-10-30 00:28:00', 'qewe12eq', 'GCash', 'uploads/payment_references/payment_1761668916_6900ef34db627.png', 'Approved', '2025-10-28 16:28:36'),
(8, 'Glenard', 'U', 'Pagurayan', 'glenard2308@gmail.com', '09557997409', 'Wedding', 'Basic Package', 'Samkwe', '2025-11-02 10:58:00', 'qweqweqw', 'GCash', 'uploads/payment_references/payment_1761674389_69010495df29e.jpg', 'Approved', '2025-10-28 17:59:49');

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_message_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','closed') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_conversations`
--

INSERT INTO `chat_conversations` (`id`, `user_id`, `admin_id`, `created_at`, `last_message_at`, `status`) VALUES
(2, 8, NULL, '2025-10-28 16:06:09', '2025-10-28 16:08:31', 'active'),
(3, 7, NULL, '2025-10-28 16:14:37', '2025-10-28 18:00:59', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `sender_id`, `message`, `is_read`, `created_at`) VALUES
(3, 2, 8, 'qwe', 1, '2025-10-28 16:06:12'),
(4, 2, 1, 'qwe', 1, '2025-10-28 16:06:24'),
(5, 2, 1, 'qwe', 1, '2025-10-28 16:08:29'),
(6, 2, 1, 'rrrr', 1, '2025-10-28 16:08:31'),
(7, 3, 7, 'qwedas', 1, '2025-10-28 16:15:24'),
(8, 3, 7, 'qwrqew', 1, '2025-10-28 16:16:29'),
(9, 3, 1, 'qweasd', 1, '2025-10-28 16:22:22'),
(10, 3, 7, 'adqwe', 1, '2025-10-28 16:22:31'),
(11, 3, 1, 'qweasd', 1, '2025-10-28 16:22:35'),
(12, 3, 7, 'qwewqeasdawd', 1, '2025-10-28 18:00:33'),
(13, 3, 1, 'heloo', 1, '2025-10-28 18:00:59');

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
(35, 'Brian A Ilac', 'Hello Brian A Ilac, your booking reservation has been approved.', 0, '2025-09-30 10:03:07'),
(36, 'Lhei B Pagurayan', 'Hello Lhei B Pagurayan, your booking reservation has been approved.', 0, '2025-10-29 00:29:12'),
(37, 'Glenard U Pagurayan', 'Hello Glenard U Pagurayan, your booking reservation has been declined.', 0, '2025-10-29 00:29:22'),
(38, 'Glenard U Pagurayan', 'Hello Glenard U Pagurayan, your booking reservation has been approved.', 0, '2025-10-29 02:01:55');

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
(13, 'Birthday', 'Basic Package', 'Venue decoration / Gowns / Themes', 10000.00),
(14, 'Birthday', 'Silver Package', 'Customized theme decoration / Gowns / Themes', 20000.00),
(15, 'Birthday', 'Gold Package', 'Full event planning including all services', 30000.00),
(16, 'Wedding', 'Basic Package', 'Venue decoration / Gowns / Themes', 10000.00),
(17, 'Wedding', 'Silver Package', 'Customized theme decoration / Gowns / Themes', 20000.00),
(18, 'Wedding', 'Gold Package', 'Full event planning including all services', 30000.00),
(19, 'Anniversary', 'Basic Package', 'Venue decoration / Gowns / Themes', 10000.00),
(20, 'Anniversary', 'Silver Package', 'Customized theme decoration / Gowns / Themes', 20000.00),
(21, 'Anniversary', 'Gold Package', 'Full event planning including all services', 30000.00),
(22, 'Corporate', 'Basic Package', 'Venue decoration / Gowns / Themes', 10000.00),
(23, 'Corporate', 'Silver Package', 'Customized theme decoration / Gowns / Themes', 20000.00),
(24, 'Corporate', 'Gold Package', 'Full event planning including all services', 30000.00);

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

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `phone_number`, `username`, `password`, `role`, `email`, `security_question`, `security_answer_hash`, `status`) VALUES
(1, '', '', '', NULL, 'admin', '$2y$10$gvCmf1zwWBABdIk/L4hdNOHdOvCBwuTcFogGsJcQ60qyL5MEqxWIy', 'admin', 'admin@gmail.com', 'What is your favorite color?', 'a67a41c8bc79d5da917b5051f1f0d3f5aeb4b63ba246b3546a961ef7a3c7d931', 'active'),
(2, 'angelie', 'quin', 'pangadua', NULL, 'Angeeeee', '$2y$10$CSyB6F39LPeV2MU/rNBBMO72qpVMAUHShbEtQsjsHcI9ch9X/o1tm', 'client', 'angelie@gmail.com.com', 'What is your mother’s maiden name?', '155aaee0403c7f9ed1383f4bb9ec75b25d4f158af21acef43a029ee658ffd99b', 'active'),
(6, 'admin', '', '', NULL, 'admin1', '$2y$10$w6QwQwQwQwQwQwQwQwQwQeQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQwQw', 'admin', 'admin@gmail.com', 'What is you favorite color?', '9b1a4e9a1f2b3c3e7b8e7e8e7e8e7e8e7e8e7e8e7e8e7e8e7e8e7e8e7e8e7e8e', 'active'),
(7, 'Glenard', 'U', 'Pagurayan', '09557997409', 'Glenn', '$2y$10$1At2/EEHCMrt00i8N3K9MOmNf9Zu2Ae3nBeHp2fApHYKP6gqJ6aAq', 'client', 'glenard2308@gmail.com', 'What is your favorite color?', '16477688c0e00699c6cfa4497a3612d7e83c532062b64b250fed8908128ed548', 'active'),
(8, 'Lhei', 'B', 'Pagurayan', '09797978978', 'Lhei', '$2y$10$Abl1nsfUTWzBrsAKBYU43.5sbsRt89K8l0Wm.n7xQg7HxnrH.0M6K', 'client', 'lbariuangasmen@gmail.com', 'What is your favorite color?', '16477688c0e00699c6cfa4497a3612d7e83c532062b64b250fed8908128ed548', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_datetime` (`event_datetime`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation_id` (`conversation_id`),
  ADD KEY `idx_sender_id` (`sender_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `chat_conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_conversations_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
