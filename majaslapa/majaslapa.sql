-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2024 at 11:52 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `majaslapa`
--

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE `komentari` (
  `lietotaja_id` int(11) NOT NULL,
  `teksts` varchar(255) NOT NULL,
  `datums` datetime NOT NULL DEFAULT current_timestamp(),
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`lietotaja_id`, `teksts`, `datums`, `comment_id`) VALUES
(27, 'mana parole ir 123', '2024-03-06 10:50:15', 1),
(27, 'asd', '2024-03-06 11:05:10', 2),
(27, 'asdas', '2024-03-06 11:05:25', 3),
(27, 'asdasd', '2024-03-06 11:05:26', 4),
(27, 'asdada', '2024-03-06 11:30:00', 5),
(27, '5555', '2024-03-06 11:57:36', 6),
(27, 'qweq', '2024-03-06 12:10:09', 8),
(27, 'aaaa', '2024-03-06 12:14:30', 9),
(27, 'fdgs', '2024-03-06 12:18:45', 10),
(27, 'ggg', '2024-03-06 12:19:59', 11),
(27, 'll', '2024-03-06 12:43:21', 12),
(27, 'tjtj', '2024-03-06 12:44:22', 13),
(28, 'uuu', '2024-03-06 12:50:22', 14),
(28, 'eee', '2024-03-06 12:50:36', 15);

-- --------------------------------------------------------

--
-- Table structure for table `lietotaji`
--

CREATE TABLE `lietotaji` (
  `id` int(11) NOT NULL,
  `vards` varchar(255) NOT NULL,
  `uzvards` varchar(255) NOT NULL,
  `epasts` varchar(255) NOT NULL,
  `parole` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotaji`
--

INSERT INTO `lietotaji` (`id`, `vards`, `uzvards`, `epasts`, `parole`) VALUES
(27, 'gan', 'dam', 'style@gmail.com', '$2y$10$GVr7RwobZWNjVhHf0JOpEOEQobAZ7HWFbFoKVvBV9tA/SRdjNBH0e'),
(28, 'sss', 'sss2', 'epasts@gmail.com', '$2y$10$wTbVBALqKgV0fjhpbUzm1OZzcCW3spYwzct9hRR8VTpzbVxDia/W.');

-- --------------------------------------------------------

--
-- Table structure for table `likes_table`
--

CREATE TABLE `likes_table` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `likes_table`
--

INSERT INTO `likes_table` (`user_id`, `post_id`) VALUES
(27, 2),
(27, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `komentari`
--
ALTER TABLE `komentari`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `lietotaja_id` (`lietotaja_id`);

--
-- Indexes for table `lietotaji`
--
ALTER TABLE `lietotaji`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `epasts` (`epasts`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `komentari`
--
ALTER TABLE `komentari`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `lietotaji`
--
ALTER TABLE `lietotaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`lietotaja_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
