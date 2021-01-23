-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2021 at 11:33 AM
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

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `Get total expense`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get total expense` (IN `event_id` INT)  NO SQL
BEGIN 
    DROP TEMPORARY TABLE IF EXISTS `tmp`;
    DROP TEMPORARY TABLE IF EXISTS `tmp2`;

    CREATE TEMPORARY TABLE `tmp` ( `display_name` VARCHAR(255) NOT NULL, `amount` INT NOT NULL, `notes` TEXT);
    CREATE TEMPORARY TABLE `tmp2` ( `display_name` VARCHAR(255) NOT NULL, `amount` INT NOT NULL, `notes` TEXT);

    INSERT INTO `tmp` (display_name, amount, notes) select u.display_name, SUM(eh.amount), GROUP_CONCAT(notes SEPARATOR ', ') from events e, events_members em, events_expense_history eh, users u where e.id = em.event_id and e.id = eh.event_id and em.user_id = u.id and e.id = event_id and eh.user_id = u.id GROUP BY u.id;

    INSERT INTO `tmp2` (display_name, amount, notes) select u.display_name, 0, null from users u where not u.display_name in (select display_name from `tmp`) and u.id in (select em.user_id from events_members em where em.event_id = event_id);

    SELECT * FROM `tmp` UNION SELECT * FROM `tmp2`;

    DROP TEMPORARY TABLE IF EXISTS `tmp`;
    DROP TEMPORARY TABLE IF EXISTS `tmp2`;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bankslips`
--

DROP TABLE IF EXISTS `bankslips`;
CREATE TABLE `bankslips` (
  `seller_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `slip_link` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `bankslips`:
--

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
  `owe_amount` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `transaction_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `transaction`:
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
