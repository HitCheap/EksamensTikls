-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2024 at 08:20 PM
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
(8, 36, 38),
(9, 45, 44),
(10, 45, 41),
(11, 45, 33);

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `name`) VALUES
(1, 'Conversation with 41'),
(2, 'Conversation with 36, 38'),
(3, 'Conversation with 27, 28');

-- --------------------------------------------------------

--
-- Table structure for table `conversation_members`
--

CREATE TABLE `conversation_members` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `conversation_members`
--

INSERT INTO `conversation_members` (`id`, `conversation_id`, `user_id`) VALUES
(1, 1, 36),
(2, 1, 41),
(3, 2, 41),
(4, 2, 36),
(5, 2, 38),
(6, 3, 45),
(7, 3, 27),
(8, 3, 28);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`follower_id`, `followed_id`) VALUES
(45, 33);

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE `komentari` (
  `lietotaja_id` int(11) NOT NULL,
  `teksts` varchar(255) NOT NULL,
  `datums` datetime NOT NULL DEFAULT current_timestamp(),
  `comment_id` int(11) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`lietotaja_id`, `teksts`, `datums`, `comment_id`, `photo`) VALUES
(27, 'mana parole ir 123', '2024-03-06 10:50:15', 1, NULL),
(27, 'asd', '2024-03-06 11:05:10', 2, NULL),
(27, 'asdas', '2024-03-06 11:05:25', 3, NULL),
(27, 'asdasd', '2024-03-06 11:05:26', 4, NULL),
(27, 'asdada', '2024-03-06 11:30:00', 5, NULL),
(27, '5555', '2024-03-06 11:57:36', 6, NULL),
(27, 'qweq', '2024-03-06 12:10:09', 8, NULL),
(27, 'aaaa', '2024-03-06 12:14:30', 9, NULL),
(27, 'fdgs', '2024-03-06 12:18:45', 10, NULL),
(27, 'ggg', '2024-03-06 12:19:59', 11, NULL),
(27, 'll', '2024-03-06 12:43:21', 12, NULL),
(27, 'tjtj', '2024-03-06 12:44:22', 13, NULL),
(28, 'uuu', '2024-03-06 12:50:22', 14, NULL),
(28, 'eee', '2024-03-06 12:50:36', 15, NULL),
(29, 'asdae', '2024-03-19 08:56:14', 18, NULL),
(29, 'uoshdfowoef', '2024-03-19 09:16:49', 21, NULL),
(29, 'ertog', '2024-03-19 09:18:29', 23, NULL),
(36, 'asdasd', '2024-05-15 03:46:53', 55, NULL),
(38, 'eioasd', '2024-05-17 00:32:47', 66, NULL),
(38, 'sdfssdf', '2024-05-17 00:32:48', 67, NULL),
(38, 'ka tevi sauc', '2024-05-17 00:32:51', 68, NULL),
(36, 'asd', '2024-05-17 19:06:42', 69, NULL),
(36, 'qweqwe', '2024-05-17 19:12:20', 70, NULL),
(41, 'ertert', '2024-05-20 01:40:07', 71, NULL),
(41, 'I am so cool', '2024-05-20 01:55:27', 72, NULL),
(43, 'qere', '2024-05-21 18:14:59', 73, NULL),
(44, 'rtetegdf', '2024-05-21 18:32:42', 74, NULL),
(45, 'ryrty', '2024-05-21 19:28:33', 76, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lietotaji`
--

CREATE TABLE `lietotaji` (
  `id` int(11) NOT NULL,
  `lietotājvārds` varchar(255) NOT NULL,
  `epasts` varchar(255) NOT NULL,
  `parole` varchar(255) NOT NULL,
  `statuss` enum('Lietotājs','Administrators','Deaktivizēts','') NOT NULL DEFAULT 'Lietotājs'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotaji`
--

INSERT INTO `lietotaji` (`id`, `lietotājvārds`, `epasts`, `parole`, `statuss`) VALUES
(27, 'gan', 'style@gmail.com', '$2y$10$GVr7RwobZWNjVhHf0JOpEOEQobAZ7HWFbFoKVvBV9tA/SRdjNBH0e', 'Lietotājs'),
(28, 'sss', 'epasts@gmail.com', '$2y$10$wTbVBALqKgV0fjhpbUzm1OZzcCW3spYwzct9hRR8VTpzbVxDia/W.', 'Lietotājs'),
(29, 'reee', 'ree@ree.com', '$2y$10$KtEM3tvEHnQmVxLkWDR8ROM2bElIVtClyNB/xbnYJfjwPPTzIE3ja', 'Lietotājs'),
(31, 'dfikjg', 'you@gmail.com', '$2y$10$JJ2B1hkN8KHxM7UMThyqTOEjkXGhoQDFCVH1Vor6LBkVjkZIsmUA2', 'Lietotājs'),
(33, 'asd', 'asd@asd.com', '$2y$10$M4Dei9eEJqZup4DM4kPVD.TyJDr4Ceh7SUQUe.UxlwaUmc2niDoqq', 'Lietotājs'),
(34, 'onui', 'asums@as', '$2y$10$nEyRNsbacjB8NKX1qw9lv.8zzUj9hcVn2Gi.EAIcTg8yZY/VLFB66', 'Lietotājs'),
(36, 'asd', 'joe@gmail.com', '$2y$10$JxvFxRgAjStiQEQ9Nloc4u0VrfoIGrmds2EmUZIhGUpIigZsmYE02', 'Lietotājs'),
(38, 'asd', 'piemers1@gmail.com', '$2y$10$Grvfw8BUPw/Ow/FawbrPteAWpH4un7VZ1.sbhU5LlSJPyJcIyWAuG', 'Lietotājs'),
(39, 'ghert', 'joe1@gmail.com', '$2y$10$9ihbsspn3.bse3hNgEYlzuDcnC0StrOUfc9rMqaQ/sDxPgiOnV7wO', 'Lietotājs'),
(40, 'qwrter', 'mamot86742@fincainc.com', '$2y$10$hvl5aHfmGt4DuBvzegfK0uSZBdgaWzdoJ7JByRxsGr8CgSaJx2uiG', 'Lietotājs'),
(41, 'who', 'theone@gmail.com', '$2y$10$qHQ6g0X4YARGQewQT.zj9ufhSlrNv2gFLhg4lUvedn1Jr6u9uFPgi', 'Administrators'),
(42, 'es', 'esmu@gmail.com', '$2y$10$xL2RMkkmwqIia7Hi8RoqaugBT.vj2sxre26WQnLVECS.m/SWOSnrG', 'Lietotājs'),
(43, 'me', '81d1a7135b9722577fb4f094a2004296d6230512d37b68e64b73f050b919f7c4', '$2y$10$CEU5KEYxuJK9FspwJTHYruSLYWI.8uUK3cz.0nVJlZaIcxXqZJ/Sm', 'Deaktivizēts'),
(44, 'mo', 'df4f6562309d02e597715fa067a40e33e5f84787e53730c867c0c77b1826ccd5', '$2y$10$ifnDf9Fq6B7etdez68Umq.WSqmE5FC3jfp8FUDHYuEtOxb7Vms66u', 'Lietotājs'),
(45, 'hello', '1d945e4947da1a05bf393b67b2e0a1fe2be36965cd4f44da5069a1df505e0092', '$2y$10$ycgKX/2yoZVAMr/rdb5dYezapL1G5WzVtGJCHMn7QauIGydVRhcL.', 'Lietotājs'),
(46, 'rikardo', 'peWxznSyYSVQDVJ3hVd7Tkh7H8V++EO6I4g8iUiWtv8=', '$2y$10$jJfoPqoSQzfdPaZqhSVkZuRxWq3d566AI0iI0BNaAJ8sglka9f52W', 'Lietotājs');

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
(36, 15),
(36, 59);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `content`, `created_at`) VALUES
(1, 1, 36, 'yoo', '2024-05-21 01:07:56'),
(2, 1, 36, 'what is good?', '2024-05-21 01:08:05'),
(3, 1, 41, 'nothing much wbu?\r\n', '2024-05-21 01:08:27'),
(4, 1, 41, 'that is good to hear', '2024-05-21 01:08:45'),
(5, 2, 41, 'what is poppin?', '2024-05-21 01:09:44'),
(6, 2, 36, 'nothing much wbu?', '2024-05-21 01:09:57'),
(7, 2, 38, 'I am the best', '2024-05-21 01:10:15'),
(8, 3, 45, 'erhuoghoerg', '2024-05-21 16:39:05'),
(9, 3, 45, 'fsdfsjkf\r\n', '2024-05-21 16:39:08'),
(10, 3, 45, 'rizz', '2024-05-21 16:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`) VALUES
(1, 'mamot86742@fincainc.com', '529b58eb6840af22001677f11e590599baa86f486a029cb1a76d022946f565553b903c2a0516a4050b234c79ba3b9b63c1c3', '2024-05-18 21:13:13'),
(2, 'mamot86742@fincainc.com', 'b6aa6acbc6407a512546215c1a4a518ee2c3ab5bf69872228a403b5922357caa2a395c99051177ad51c35e585feb16ca03d9', '2024-05-18 21:19:33'),
(3, 'mamot86742@fincainc.com', '76c7527d85006559c5fe13ac4b582537a800d31fb24a15d49b67dd91a219a78fbe84d24eacc8e8617c9e7db0f5646b18574d', '2024-05-18 21:20:07'),
(4, 'mamot86742@fincainc.com', 'b61660ab314a6845874dfe5c76cb31ea7be630851d02c55acef6317443b41b85ac2b3aa7ba21a59d9fd769c2edd7b56a3125', '2024-05-18 21:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reposts`
--

CREATE TABLE `reposts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `score`, `timestamp`) VALUES
(7, 42, 6, '2024-05-21 01:53:34'),
(8, 42, 7, '2024-05-21 01:53:47'),
(9, 45, 7, '2024-05-21 16:37:21'),
(10, 45, 7, '2024-05-21 17:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_notes`
--

CREATE TABLE `user_notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `user_notes`
--

INSERT INTO `user_notes` (`id`, `user_id`, `note_text`, `created_at`, `updated_at`) VALUES
(28, 36, '', '2024-05-17 12:27:45', '2024-05-17 12:27:45'),
(41, 45, 'qweqerer', '2024-05-21 16:27:50', '2024-05-21 16:27:53');

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
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversation_members`
--
ALTER TABLE `conversation_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `followed_id` (`followed_id`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversation_id` (`conversation_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reposts`
--
ALTER TABLE `reposts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_notes`
--
ALTER TABLE `user_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocked_users`
--
ALTER TABLE `blocked_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `conversation_members`
--
ALTER TABLE `conversation_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `komentari`
--
ALTER TABLE `komentari`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `lietotaji`
--
ALTER TABLE `lietotaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reposts`
--
ALTER TABLE `reposts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_notes`
--
ALTER TABLE `user_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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
-- Constraints for table `conversation_members`
--
ALTER TABLE `conversation_members`
  ADD CONSTRAINT `conversation_members_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `conversation_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `lietotaji` (`id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`lietotaja_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `komentari` (`comment_id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `reposts`
--
ALTER TABLE `reposts`
  ADD CONSTRAINT `reposts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`),
  ADD CONSTRAINT `reposts_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `komentari` (`comment_id`);

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `user_notes`
--
ALTER TABLE `user_notes`
  ADD CONSTRAINT `user_notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
