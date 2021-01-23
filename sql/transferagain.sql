-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2021 at 08:18 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transferagain`
--
CREATE DATABASE IF NOT EXISTS `transferagain` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `transferagain`;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `event_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `event_status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `events`:
--

--
-- Triggers `events`
--
DROP TRIGGER IF EXISTS `Add creator to joined list`;
DELIMITER $$
CREATE TRIGGER `Add creator to joined list` AFTER INSERT ON `events` FOR EACH ROW INSERT INTO events_members(`user_id`, `event_id`) VALUES (NEW.creator_id, NEW.id)
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Create transactions when locked event`;
DELIMITER $$
CREATE TRIGGER `Create transactions when locked event` AFTER UPDATE ON `events` FOR EACH ROW IF old.event_status = 0 AND new.event_status = 1 THEN
	CALL `Create transaction report`(NEW.id);
ELSEIF old.event_status = 1 AND new.event_status = 0 THEN
	DELETE FROM transaction WHERE event_id = NEW.id;
END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `events_expense_history`
--

DROP TABLE IF EXISTS `events_expense_history`;
CREATE TABLE `events_expense_history` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `events_expense_history`:
--   `event_id`
--       `events` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `events_expense_total`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `events_expense_total`;
CREATE TABLE `events_expense_total` (
`event_id` int(11)
,`display_name` text
,`amount` decimal(32,0)
,`notes` text
);

-- --------------------------------------------------------

--
-- Table structure for table `events_members`
--

DROP TABLE IF EXISTS `events_members`;
CREATE TABLE `events_members` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `events_members`:
--   `event_id`
--       `events` -> `id`
--   `user_id`
--       `users` -> `id`
--

--
-- Triggers `events_members`
--
DROP TRIGGER IF EXISTS `Remove events_expenses`;
DELIMITER $$
CREATE TRIGGER `Remove events_expenses` AFTER DELETE ON `events_members` FOR EACH ROW DELETE FROM events_expense_history WHERE event_id = OLD.event_id AND user_id = OLD.user_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `owe_amount` int(11) NOT NULL,
  `transaction_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `transaction`:
--   `event_id`
--       `events` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `student_id` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` text COLLATE utf8_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

-- --------------------------------------------------------

--
-- Structure for view `events_expense_total`
--
DROP TABLE IF EXISTS `events_expense_total`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `events_expense_total`  AS  select `e`.`id` AS `event_id`,`u`.`display_name` AS `display_name`,sum(`eh`.`amount`) AS `amount`,group_concat(`eh`.`notes` separator ', ') AS `notes` from (((`events` `e` join `events_members` `em`) join `events_expense_history` `eh`) join `users` `u`) where ((`e`.`id` = `em`.`event_id`) and (`e`.`id` = `eh`.`event_id`) and (`em`.`user_id` = `u`.`id`) and (`eh`.`user_id` = `u`.`id`)) group by `e`.`id`,`u`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_expense_history`
--
ALTER TABLE `events_expense_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events_members`
--
ALTER TABLE `events_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events_expense_history`
--
ALTER TABLE `events_expense_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events_members`
--
ALTER TABLE `events_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events_expense_history`
--
ALTER TABLE `events_expense_history`
  ADD CONSTRAINT `events_expense_history_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `events_expense_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `events_members`
--
ALTER TABLE `events_members`
  ADD CONSTRAINT `events_members_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `events_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
