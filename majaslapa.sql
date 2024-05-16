-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2024 at 11:50 PM
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
-- Database: `majaslapa`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocked_users`
--

CREATE TABLE `blocked_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `blocked_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `blocked_users`
--

INSERT INTO `blocked_users` (`id`, `user_id`, `blocked_user_id`) VALUES
(8, 36, 38);

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
(28, 'eee', '2024-03-06 12:50:36', 15),
(29, 'asdae', '2024-03-19 08:56:14', 18),
(29, 'uoshdfowoef', '2024-03-19 09:16:49', 21),
(29, 'ertog', '2024-03-19 09:18:29', 23),
(36, 'asdasd', '2024-05-15 03:46:53', 55),
(36, 'qwerty', '2024-05-16 17:22:19', 59),
(38, 'eioasd', '2024-05-17 00:32:47', 66),
(38, 'sdfssdf', '2024-05-17 00:32:48', 67),
(38, 'ka tevi sauc', '2024-05-17 00:32:51', 68);

-- --------------------------------------------------------

--
-- Table structure for table `lietotaji`
--

CREATE TABLE `lietotaji` (
  `id` int(11) NOT NULL,
  `vards` varchar(255) NOT NULL,
  `uzvards` varchar(255) NOT NULL,
  `epasts` varchar(255) NOT NULL,
  `parole` varchar(255) NOT NULL,
  `status` enum('Lietotājs','Administrators','','') NOT NULL DEFAULT 'Lietotājs'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotaji`
--

INSERT INTO `lietotaji` (`id`, `vards`, `uzvards`, `epasts`, `parole`, `status`) VALUES
(27, 'gan', 'dam', 'style@gmail.com', '$2y$10$GVr7RwobZWNjVhHf0JOpEOEQobAZ7HWFbFoKVvBV9tA/SRdjNBH0e', 'Lietotājs'),
(28, 'sss', 'sss2', 'epasts@gmail.com', '$2y$10$wTbVBALqKgV0fjhpbUzm1OZzcCW3spYwzct9hRR8VTpzbVxDia/W.', 'Lietotājs'),
(29, 'reee', 'buuu', 'ree@ree.com', '$2y$10$KtEM3tvEHnQmVxLkWDR8ROM2bElIVtClyNB/xbnYJfjwPPTzIE3ja', 'Lietotājs'),
(31, 'dfikjg', 'gpjan', 'you@gmail.com', '$2y$10$JJ2B1hkN8KHxM7UMThyqTOEjkXGhoQDFCVH1Vor6LBkVjkZIsmUA2', 'Lietotājs'),
(33, 'asd', 'asd', 'asd@asd.com', '$2y$10$M4Dei9eEJqZup4DM4kPVD.TyJDr4Ceh7SUQUe.UxlwaUmc2niDoqq', 'Lietotājs'),
(34, 'onui', 'xzhkxc', 'asums@as', '$2y$10$nEyRNsbacjB8NKX1qw9lv.8zzUj9hcVn2Gi.EAIcTg8yZY/VLFB66', 'Lietotājs'),
(36, 'asd', 'erglk', 'joe@gmail.com', '$2y$10$JxvFxRgAjStiQEQ9Nloc4u0VrfoIGrmds2EmUZIhGUpIigZsmYE02', 'Lietotājs'),
(38, 'asd', 'wdgerg', 'piemers1@gmail.com', '$2y$10$Grvfw8BUPw/Ow/FawbrPteAWpH4un7VZ1.sbhU5LlSJPyJcIyWAuG', 'Lietotājs'),
(39, 'ghert', 'gdfdfg', 'joe1@gmail.com', '$2y$10$9ihbsspn3.bse3hNgEYlzuDcnC0StrOUfc9rMqaQ/sDxPgiOnV7wO', 'Lietotājs');

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
(27, 1),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(36, 23),
(36, 56),
(36, 55),
(36, 15);

-- --------------------------------------------------------

--
-- Table structure for table `reposts`
--

CREATE TABLE `reposts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `repost_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `lietotaja_id` int(11) NOT NULL,
  `rezultats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `blocked_user_id` (`blocked_user_id`);

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
-- Indexes for table `reposts`
--
ALTER TABLE `reposts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocked_users`
--
ALTER TABLE `blocked_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `komentari`
--
ALTER TABLE `komentari`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `lietotaji`
--
ALTER TABLE `lietotaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `reposts`
--
ALTER TABLE `reposts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD CONSTRAINT `blocked_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`),
  ADD CONSTRAINT `blocked_users_ibfk_2` FOREIGN KEY (`blocked_user_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`lietotaja_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reposts`
--
ALTER TABLE `reposts`
  ADD CONSTRAINT `reposts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`),
  ADD CONSTRAINT `reposts_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `komentari` (`comment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
