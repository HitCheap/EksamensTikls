-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 05:08 AM
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
(3, 'Conversation with 27, 28'),
(4, 'Conversation with 27, 28'),
(5, 'Conversation with gan, sss'),
(6, 'Conversation with gan'),
(7, 'Conversation with gan'),
(8, 'Conversation with rikardo'),
(9, 'Conversation with rikardo'),
(10, 'Conversation with rikardo');

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
(19, 8, 44),
(20, 8, 46),
(21, 9, 44),
(22, 9, 46),
(23, 10, 44),
(24, 10, 46);

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
(44, 48);

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE `komentari` (
  `lietotaja_id` int(11) NOT NULL,
  `teksts` text NOT NULL,
  `datums` datetime NOT NULL DEFAULT current_timestamp(),
  `comment_id` int(11) NOT NULL,
  `media` varchar(255) DEFAULT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `is_edited` tinyint(1) DEFAULT 0,
  `edited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`lietotaja_id`, `teksts`, `datums`, `comment_id`, `media`, `parent_comment_id`, `is_edited`, `edited_at`) VALUES
(27, 'mana parole ir 123', '2024-03-06 10:50:15', 1, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'asd', '2024-03-06 11:05:10', 2, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'asdas', '2024-03-06 11:05:25', 3, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'asdasd', '2024-03-06 11:05:26', 4, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'asdada', '2024-03-06 11:30:00', 5, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, '5555', '2024-03-06 11:57:36', 6, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'qweq', '2024-03-06 12:10:09', 8, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'aaaa', '2024-03-06 12:14:30', 9, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'fdgs', '2024-03-06 12:18:45', 10, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'ggg', '2024-03-06 12:19:59', 11, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'll', '2024-03-06 12:43:21', 12, NULL, NULL, 0, '2024-06-05 22:46:10'),
(27, 'tjtj', '2024-03-06 12:44:22', 13, NULL, NULL, 0, '2024-06-05 22:46:10'),
(28, 'uuu', '2024-03-06 12:50:22', 14, NULL, NULL, 0, '2024-06-05 22:46:10'),
(28, 'eee', '2024-03-06 12:50:36', 15, NULL, NULL, 0, '2024-06-05 22:46:10'),
(29, 'asdae', '2024-03-19 08:56:14', 18, NULL, NULL, 0, '2024-06-05 22:46:10'),
(29, 'uoshdfowoef', '2024-03-19 09:16:49', 21, NULL, NULL, 0, '2024-06-05 22:46:10'),
(29, 'ertog', '2024-03-19 09:18:29', 23, NULL, NULL, 0, '2024-06-05 22:46:10'),
(38, 'eioasd', '2024-05-17 00:32:47', 66, NULL, NULL, 0, '2024-06-05 22:46:10'),
(38, 'sdfssdf', '2024-05-17 00:32:48', 67, NULL, NULL, 0, '2024-06-05 22:46:10'),
(38, 'ka tevi sauc', '2024-05-17 00:32:51', 68, NULL, NULL, 0, '2024-06-05 22:46:10'),
(41, 'ertert', '2024-05-20 01:40:07', 71, NULL, NULL, 0, '2024-06-05 22:46:10'),
(41, 'I am so cool', '2024-05-20 01:55:27', 72, NULL, NULL, 0, '2024-06-05 22:46:10'),
(43, 'qere', '2024-05-21 18:14:59', 73, NULL, NULL, 0, '2024-06-05 22:46:10'),
(44, 'rtetegdf', '2024-05-21 18:32:42', 74, NULL, NULL, 0, '2024-06-05 22:46:10'),
(45, 'ryrty', '2024-05-21 19:28:33', 76, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'qwe', '2024-05-22 20:23:04', 78, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'eee', '2024-05-22 20:23:10', 79, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'asd', '2024-05-22 20:50:49', 83, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'Amazinee', '2024-05-22 20:56:41', 84, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'eeer', '2024-05-22 21:06:51', 86, NULL, NULL, 1, '2024-06-05 22:46:10'),
(46, 'qweqw', '2024-05-31 23:32:48', 144, NULL, 135, 0, '2024-06-05 22:46:10'),
(46, 'eee', '2024-05-31 23:33:06', 145, NULL, 117, 0, '2024-06-05 22:46:10'),
(46, 'wer', '2024-05-31 23:56:57', 147, NULL, 117, 0, '2024-06-05 22:46:10'),
(46, 'qwe', '2024-06-02 01:53:30', 148, NULL, 117, 0, '2024-06-05 22:46:10'),
(44, 'I am so epic', '2024-06-02 21:03:34', 153, NULL, NULL, 0, '2024-06-05 22:46:10'),
(44, 'tttt', '2024-06-03 00:06:55', 156, NULL, 155, 0, '2024-06-05 22:46:10'),
(44, 'rer', '2024-06-03 00:07:20', 157, NULL, NULL, 0, '2024-06-05 22:46:10'),
(44, 'rer 2', '2024-06-03 00:07:26', 158, NULL, 157, 0, '2024-06-05 22:46:10'),
(46, 'qweqw', '2024-06-03 00:50:00', 160, NULL, 157, 0, '2024-06-05 22:46:10'),
(46, 'yee', '2024-06-03 00:50:07', 161, NULL, 157, 0, '2024-06-05 22:46:10'),
(46, 'qwe', '2024-06-03 03:21:20', 177, 'uploads/630283c191a2efc017ccdc7464f36d21.mp3', NULL, 1, '2024-06-09 22:25:52'),
(46, 'qweqer', '2024-06-03 03:21:31', 178, NULL, NULL, 1, '2024-06-05 22:46:10'),
(44, 'erer', '2024-06-03 03:58:09', 179, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'etrt', '2024-06-03 04:17:16', 180, NULL, NULL, 0, '2024-06-05 22:46:10'),
(48, 'qweq', '2024-06-03 19:41:02', 182, NULL, NULL, 0, '2024-06-05 22:46:10'),
(46, 'qwererdf', '2024-06-06 00:45:01', 183, NULL, NULL, 1, '2024-06-06 00:22:13'),
(46, 'a', '2024-06-06 01:46:45', 184, NULL, NULL, 1, '2024-06-07 00:07:00'),
(46, 'qwe', '2024-06-06 03:45:17', 185, NULL, 183, 0, '2024-06-06 00:45:17'),
(46, 'eqwe', '2024-06-06 04:02:41', 186, NULL, 178, 0, '2024-06-06 01:02:41'),
(46, 'qwe', '2024-06-08 00:50:49', 189, NULL, NULL, 0, '2024-06-07 21:50:49'),
(44, 'wow you so epic', '2024-06-09 02:02:44', 196, NULL, 189, 0, '2024-06-08 23:02:44'),
(46, 'qwe', '2024-06-10 05:52:08', 199, NULL, 198, 0, '2024-06-10 02:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `lietotaji`
--

CREATE TABLE `lietotaji` (
  `id` int(11) NOT NULL,
  `lietotājvārds` varchar(100) NOT NULL,
  `epasts` varchar(120) NOT NULL,
  `parole` varchar(255) NOT NULL,
  `statuss` enum('Lietotājs','Administrators','Deaktivizēts','') NOT NULL DEFAULT 'Lietotājs',
  `pg13_mode` tinyint(1) DEFAULT 0,
  `easter_egg_found` tinyint(1) DEFAULT 0,
  `profile_picture` varchar(255) DEFAULT 'bildes/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `lietotaji`
--

INSERT INTO `lietotaji` (`id`, `lietotājvārds`, `epasts`, `parole`, `statuss`, `pg13_mode`, `easter_egg_found`, `profile_picture`) VALUES
(27, 'gan', 'style@gmail.com', '$2y$10$GVr7RwobZWNjVhHf0JOpEOEQobAZ7HWFbFoKVvBV9tA/SRdjNBH0e', 'Lietotājs', 0, 0, 'bildes/default.png'),
(28, 'sss', 'epasts@gmail.com', '$2y$10$wTbVBALqKgV0fjhpbUzm1OZzcCW3spYwzct9hRR8VTpzbVxDia/W.', 'Lietotājs', 0, 0, 'bildes/default.png'),
(29, 'reee', 'ree@ree.com', '$2y$10$KtEM3tvEHnQmVxLkWDR8ROM2bElIVtClyNB/xbnYJfjwPPTzIE3ja', 'Lietotājs', 0, 0, 'bildes/default.png'),
(31, 'dfikjg', 'you@gmail.com', '$2y$10$JJ2B1hkN8KHxM7UMThyqTOEjkXGhoQDFCVH1Vor6LBkVjkZIsmUA2', 'Lietotājs', 0, 0, 'bildes/default.png'),
(34, 'onui', 'asums@as', '$2y$10$nEyRNsbacjB8NKX1qw9lv.8zzUj9hcVn2Gi.EAIcTg8yZY/VLFB66', 'Lietotājs', 0, 0, 'bildes/default.png'),
(38, 'asd', 'piemers1@gmail.com', '$2y$10$Grvfw8BUPw/Ow/FawbrPteAWpH4un7VZ1.sbhU5LlSJPyJcIyWAuG', 'Lietotājs', 0, 0, 'bildes/default.png'),
(39, 'ghert', 'joe1@gmail.com', '$2y$10$9ihbsspn3.bse3hNgEYlzuDcnC0StrOUfc9rMqaQ/sDxPgiOnV7wO', 'Lietotājs', 0, 0, 'bildes/default.png'),
(40, 'qwrter', 'mamot86742@fincainc.com', '$2y$10$hvl5aHfmGt4DuBvzegfK0uSZBdgaWzdoJ7JByRxsGr8CgSaJx2uiG', 'Lietotājs', 0, 0, 'bildes/default.png'),
(41, 'who', 'theone@gmail.com', '$2y$10$qHQ6g0X4YARGQewQT.zj9ufhSlrNv2gFLhg4lUvedn1Jr6u9uFPgi', 'Administrators', 0, 0, 'bildes/default.png'),
(42, 'es', 'esmu@gmail.com', '$2y$10$xL2RMkkmwqIia7Hi8RoqaugBT.vj2sxre26WQnLVECS.m/SWOSnrG', 'Lietotājs', 0, 0, 'bildes/default.png'),
(43, 'me', '81d1a7135b9722577fb4f094a2004296d6230512d37b68e64b73f050b919f7c4', '$2y$10$CEU5KEYxuJK9FspwJTHYruSLYWI.8uUK3cz.0nVJlZaIcxXqZJ/Sm', 'Deaktivizēts', 0, 0, 'bildes/default.png'),
(44, 'mo', 'df4f6562309d02e597715fa067a40e33e5f84787e53730c867c0c77b1826ccd5', '$2y$10$ifnDf9Fq6B7etdez68Umq.WSqmE5FC3jfp8FUDHYuEtOxb7Vms66u', 'Administrators', 0, 0, 'bildes/default.png'),
(45, 'hello', '1d945e4947da1a05bf393b67b2e0a1fe2be36965cd4f44da5069a1df505e0092', '$2y$10$ycgKX/2yoZVAMr/rdb5dYezapL1G5WzVtGJCHMn7QauIGydVRhcL.', 'Lietotājs', 0, 0, 'bildes/default.png'),
(46, 'rikardo', 'peWxznSyYSVQDVJ3hVd7Tkh7H8V++EO6I4g8iUiWtv8=', '$2y$10$0bXOQdSElBvPiBLObX5dZeaeG9c/3CjonEwR0.whxO4aVxEKw8yKm', 'Lietotājs', 0, 0, 'eb12edcb51fe378554861a90259efd01.png'),
(48, 'omg', 'rtoc6+kboPuCoL1NSX4X4w==', '$2y$10$QNjbWBe6MJRSutqZ692Va.nYOmsAHxErEeFNgSrj6vPwxfdwRTaI.', 'Lietotājs', 0, 0, 'default.png'),
(53, 'asdasd', 'HqU0nuRB+8Mu6gZnhPd4JQ==', '$2y$10$JT1vDhQuNfL1h2eNJWh6RePeshg2KARVafaagxQnb4rXjg/BbrCSa', 'Lietotājs', 0, 0, 'bildes/default.png'),
(54, 'tests', '7XTtjuxT/FkExS7mgvxRWlrWFye6LSOiYyjN6yrlbhQ=', '$2y$10$GBJSBX5.n119t7925YqJVuoRpPs7.ceJt.BEJLezE1AQMg1o5huMe', 'Lietotājs', 0, 0, 'bildes/default.png'),
(56, 'tests1', '+XcrHXSFwutUM5oDNFQ58g==', '$2y$10$MUjEnvry4vq2qCLl7Hxm5.oywqENprGmDrQk4v0Ty0izaTCDK6TOK', 'Lietotājs', 0, 0, 'bildes/default.png');

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
(36, 59),
(46, 72),
(46, 74),
(46, 0),
(46, 153),
(44, 186),
(46, 186),
(44, 157),
(46, 157);

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
(7, 2, 38, 'I am the best', '2024-05-21 01:10:15'),
(8, 3, 45, 'erhuoghoerg', '2024-05-21 16:39:05'),
(9, 3, 45, 'fsdfsjkf\r\n', '2024-05-21 16:39:08'),
(10, 3, 45, 'rizz', '2024-05-21 16:39:36'),
(11, 6, 46, 'qweqw', '2024-05-29 01:55:25'),
(12, 7, 44, 'qweqe', '2024-06-02 21:10:05'),
(13, 6, 46, 'qweq', '2024-06-02 21:51:59'),
(14, 6, 46, 'rer', '2024-06-02 21:52:01'),
(15, 6, 46, 'tt', '2024-06-02 21:52:04'),
(16, 4, 46, 'ffff', '2024-06-03 00:07:50'),
(18, 9, 46, 'qwe', '2024-06-09 01:52:08'),
(19, 8, 46, 'asdad', '2024-06-09 01:55:57'),
(20, 9, 46, 'qwe', '2024-06-09 01:58:26'),
(21, 8, 46, 'qwe\r\n', '2024-06-09 02:03:41'),
(22, 8, 46, 'qweq', '2024-06-09 02:04:26'),
(23, 8, 46, 'etertert', '2024-06-09 02:04:27'),
(24, 8, 46, 'asdsd', '2024-06-09 02:18:42'),
(25, 8, 46, 'qweqwe', '2024-06-09 02:18:50'),
(26, 8, 46, 'asdasd', '2024-06-09 02:18:51'),
(27, 8, 46, 'a', '2024-06-09 02:18:55'),
(31, 10, 44, 'qe', '2024-06-09 15:59:58'),
(32, 10, 44, 're', '2024-06-09 15:59:59'),
(34, 8, 44, 'qwe', '2024-06-09 16:21:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `created_at`, `type`) VALUES
(38, 48, 'Your comment was liked by user ID: 44', '2024-06-07 01:25:14', 'like'),
(52, 48, 'Your comment was liked by mo', '2024-06-07 01:59:02', 'like'),
(55, 44, 'Comment \'etrt\' was liked by mo', '2024-06-07 01:59:03', 'like'),
(56, 48, 'Your comment was liked by rikardo', '2024-06-07 01:59:30', 'like'),
(58, 44, 'Comment \'qweqer\' was liked by mo', '2024-06-07 02:00:01', 'like'),
(60, 44, 'Comment \'eqwe\' was liked by mo', '2024-06-07 02:00:02', 'like'),
(61, 44, 'Your comment was liked by rikardo', '2024-06-07 02:00:26', 'like'),
(63, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-07 02:02:14', 'like'),
(65, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-07 02:02:16', 'like'),
(67, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-07 02:02:18', 'like'),
(69, 44, 'Comment \'a\' was liked by mo', '2024-06-07 02:02:20', 'like'),
(70, 44, 'Comment \'rer\' was liked by mo', '2024-06-07 02:02:27', 'like'),
(72, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-07 02:02:38', 'like'),
(73, 44, 'Uz tavu komentāru tika nospiests patīk', '2024-06-08 01:09:12', 'like'),
(74, 48, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 00:06:09', 'like'),
(75, 48, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 00:06:18', 'like'),
(76, 44, 'Comment \'qweq\' was liked by mo', '2024-06-09 00:06:18', 'like'),
(78, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-09 00:06:20', 'like'),
(80, 44, 'Comment \'a\' was liked by mo', '2024-06-09 00:06:24', 'like'),
(83, 44, 'Comment \'qwe\' was liked by mo', '2024-06-09 01:24:20', 'like'),
(85, 44, 'Comment \'a\' was liked by mo', '2024-06-09 01:24:22', 'like'),
(87, 48, 'Uz tavu komentāru \'qweq\' tika nospiests patīk', '2024-06-09 01:24:23', 'like'),
(88, 44, 'Comment \'qweq\' was liked by mo', '2024-06-09 01:24:23', 'like'),
(90, 44, 'Comment \'etrt\' was liked by mo', '2024-06-09 01:24:25', 'like'),
(91, 44, 'Comment \'erer\' was liked by mo', '2024-06-09 01:24:27', 'like'),
(94, 48, 'Uz tavu komentāru \'qweq\' atcelta patīk no administratora mo', '2024-06-09 01:24:32', 'unlike_admin'),
(96, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-09 01:24:32', 'like'),
(99, 44, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 01:26:31', 'like'),
(101, 44, 'Comment \'a\' was liked by mo', '2024-06-09 01:29:03', 'like'),
(102, 44, 'Comment \'qwererdf\' was unliked by mo', '2024-06-09 01:29:04', 'unlike'),
(103, 48, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 01:29:05', 'like'),
(104, 44, 'Comment \'qweq\' was liked by mo', '2024-06-09 01:29:05', 'like'),
(105, 44, 'Comment \'qweq\' was unliked by mo', '2024-06-09 01:29:06', 'unlike'),
(106, 44, 'Comment \'a\' was unliked by mo', '2024-06-09 01:29:09', 'unlike'),
(108, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-09 01:29:11', 'like'),
(109, 44, 'Comment \'qwererdf\' was unliked by mo', '2024-06-09 01:29:11', 'unlike'),
(111, 44, 'Comment \'qwererdf\' was liked by mo', '2024-06-09 01:29:12', 'like'),
(112, 44, 'Comment \'qwererdf\' was unliked by mo', '2024-06-09 01:29:12', 'unlike'),
(114, 44, 'Comment \'a\' was liked by mo', '2024-06-09 01:29:13', 'like'),
(115, 44, 'Comment \'a\' was unliked by mo', '2024-06-09 01:29:14', 'unlike'),
(116, 48, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 01:29:41', 'like'),
(117, 48, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 01:29:48', 'like'),
(118, 44, 'Uz tavu komentāru tika nospiests patīk', '2024-06-09 01:29:53', 'like'),
(120, 44, 'Comment \'a\' was liked by mo', '2024-06-09 01:30:24', 'like'),
(121, 44, 'Comment \'a\' was unliked by mo', '2024-06-09 01:30:25', 'unlike');

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
(4, 'mamot86742@fincainc.com', 'b61660ab314a6845874dfe5c76cb31ea7be630851d02c55acef6317443b41b85ac2b3aa7ba21a59d9fd769c2edd7b56a3125', '2024-05-18 21:27:25'),
(5, 'mamot86742@fincainc.com', 'ae02d9ab0d92274f8de1c0c0d811c832c2379a3fb36e8222279dfa3db851ac69e761de8a5ecde1f7b6744094ff2428615347', '2024-06-02 18:57:34'),
(6, 'mamot86742@fincainc.com', '83a2151ab70b4e413c8b70c793d1c1f77f0ce016f9d796e7502ef5b3b026389d5ae1ab0c431dbaf7106d881495e273d7ede0', '2024-06-02 19:12:29'),
(7, 'mamot86742@fincainc.com', '0b69271b3377ef0bd559728cbebdad3c71f3f82e57efde2f519e7df94fa59afaefbc1af56ec17480e3091082de016f49a202', '2024-06-02 19:12:30'),
(8, 'mamot86742@fincainc.com', 'a898390bb356c806ec9923630c11d04bbd061adfb48c43d65441c2d5cbe0586266c4c9a6a2e12f82cacedf7ddc14c59c213f', '2024-06-02 19:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `reposts`
--

CREATE TABLE `reposts` (
  `repost_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `repost_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rezcuska`
--

CREATE TABLE `rezcuska` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `rezcuska`
--

INSERT INTO `rezcuska` (`id`, `user_id`, `score`, `timestamp`) VALUES
(1, 46, 2, '2024-05-22 19:59:55'),
(2, 46, 1, '2024-05-22 20:17:06'),
(3, 46, 7, '2024-06-02 22:00:01'),
(4, 46, 0, '2024-06-02 23:13:57'),
(5, 46, 0, '2024-06-02 23:41:14'),
(6, 46, 1, '2024-06-09 15:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `rezmerkatreneris`
--

CREATE TABLE `rezmerkatreneris` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `rezmerkatreneris`
--

INSERT INTO `rezmerkatreneris` (`id`, `user_id`, `score`, `timestamp`) VALUES
(1, 46, 2, '2024-05-22 20:34:27'),
(2, 46, 6, '2024-06-02 21:59:36'),
(3, 46, 0, '2024-06-02 23:13:52'),
(4, 46, 0, '2024-06-02 23:41:11'),
(5, 46, 6, '2024-06-02 23:59:28'),
(6, 46, 6, '2024-06-02 23:59:35'),
(7, 46, 5, '2024-06-09 14:16:20'),
(8, 46, 3, '2024-06-09 15:28:30');

-- --------------------------------------------------------

--
-- Table structure for table `reztet`
--

CREATE TABLE `reztet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `reztet`
--

INSERT INTO `reztet` (`id`, `user_id`, `score`, `timestamp`) VALUES
(1, 46, 180, '2024-06-09 00:43:34'),
(2, 46, 150, '2024-06-09 15:29:48');

-- --------------------------------------------------------

--
-- Table structure for table `reztrex`
--

CREATE TABLE `reztrex` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_latvian_ci;

--
-- Dumping data for table `reztrex`
--

INSERT INTO `reztrex` (`id`, `user_id`, `score`, `timestamp`) VALUES
(1, 46, 361, '2024-05-22 20:05:30'),
(2, 46, 368, '2024-05-22 20:18:07'),
(3, 46, 481, '2024-05-31 15:13:56'),
(4, 46, 361, '2024-05-31 15:13:59'),
(5, 46, 841, '2024-06-02 23:06:39'),
(6, 46, 361, '2024-06-02 23:14:14'),
(7, 46, 361, '2024-06-02 23:41:21'),
(8, 46, 368, '2024-06-09 14:16:34'),
(9, 46, 361, '2024-06-09 14:16:37'),
(10, 46, 1081, '2024-06-09 14:17:27'),
(11, 46, 842, '2024-06-09 14:18:25'),
(12, 46, 1201, '2024-06-09 14:18:59'),
(13, 46, 361, '2024-06-09 14:19:02'),
(14, 46, 421, '2024-06-09 14:19:07'),
(15, 46, 601, '2024-06-09 14:19:27'),
(16, 46, 361, '2024-06-09 14:19:32'),
(17, 46, 661, '2024-06-09 14:19:37'),
(18, 46, 361, '2024-06-09 14:19:40'),
(19, 46, 901, '2024-06-09 14:19:53'),
(20, 46, 361, '2024-06-09 15:30:05'),
(21, 46, 421, '2024-06-09 15:35:05'),
(22, 46, 1381, '2024-06-09 15:35:14'),
(23, 46, 2461, '2024-06-09 15:35:46'),
(24, 46, 361, '2024-06-09 15:35:50'),
(25, 46, 361, '2024-06-09 18:27:43'),
(26, 46, 601, '2024-06-09 18:27:49'),
(27, 46, 601, '2024-06-09 18:27:54'),
(28, 46, 361, '2024-06-09 18:29:18'),
(29, 46, 421, '2024-06-09 18:29:21'),
(30, 46, 661, '2024-06-09 18:29:26'),
(31, 46, 361, '2024-06-09 18:29:29'),
(32, 46, 481, '2024-06-09 18:29:32'),
(33, 46, 421, '2024-06-09 18:30:19'),
(34, 46, 361, '2024-06-09 18:30:22'),
(35, 46, 421, '2024-06-09 18:31:41'),
(36, 46, 1021, '2024-06-09 18:31:48'),
(37, 46, 421, '2024-06-09 18:31:51'),
(38, 46, 421, '2024-06-09 18:31:54'),
(39, 46, 361, '2024-06-09 18:31:57'),
(40, 46, 361, '2024-06-09 18:32:00'),
(41, 46, 361, '2024-06-09 18:32:03'),
(42, 46, 361, '2024-06-09 18:32:06'),
(43, 46, 361, '2024-06-09 18:32:08'),
(44, 46, 361, '2024-06-09 18:32:11'),
(45, 46, 421, '2024-06-09 18:32:17'),
(46, 46, 2581, '2024-06-09 18:32:33'),
(47, 46, 1141, '2024-06-09 18:33:41'),
(48, 46, 601, '2024-06-09 18:34:03'),
(49, 46, 361, '2024-06-09 18:34:31'),
(50, 46, 361, '2024-06-09 18:34:34'),
(51, 46, 361, '2024-06-09 18:34:37'),
(52, 46, 361, '2024-06-09 18:35:57'),
(53, 46, 421, '2024-06-09 18:36:00'),
(54, 46, 481, '2024-06-09 18:37:33'),
(55, 46, 1141, '2024-06-09 18:37:40'),
(56, 46, 361, '2024-06-09 18:37:43'),
(57, 46, 361, '2024-06-09 18:37:46'),
(58, 46, 481, '2024-06-09 18:37:50'),
(59, 46, 361, '2024-06-09 18:37:53'),
(60, 46, 361, '2024-06-09 18:38:29'),
(61, 46, 361, '2024-06-09 18:38:56'),
(62, 46, 421, '2024-06-09 18:38:59'),
(63, 46, 361, '2024-06-09 18:39:42'),
(64, 46, 361, '2024-06-09 18:40:13'),
(65, 46, 481, '2024-06-09 18:40:17'),
(66, 46, 362, '2024-06-09 18:40:20'),
(67, 46, 361, '2024-06-09 18:40:24'),
(68, 46, 361, '2024-06-09 18:40:27'),
(69, 46, 361, '2024-06-09 18:40:31'),
(70, 46, 661, '2024-06-09 18:40:53'),
(71, 46, 481, '2024-06-09 18:40:58'),
(72, 46, 421, '2024-06-09 18:41:01'),
(73, 46, 2101, '2024-06-09 18:41:38'),
(74, 46, 961, '2024-06-09 18:42:31'),
(75, 46, 423, '2024-06-09 18:42:34'),
(76, 46, 427, '2024-06-09 18:42:37'),
(77, 46, 362, '2024-06-09 18:42:40'),
(78, 46, 601, '2024-06-09 18:42:44'),
(79, 46, 428, '2024-06-09 18:42:48'),
(80, 46, 421, '2024-06-09 18:42:51'),
(81, 46, 661, '2024-06-09 18:42:56'),
(82, 46, 1081, '2024-06-09 18:43:03'),
(83, 46, 481, '2024-06-09 18:44:01'),
(84, 46, 541, '2024-06-09 18:44:05'),
(85, 46, 1261, '2024-06-09 18:44:14'),
(86, 46, 2281, '2024-06-09 18:44:28'),
(87, 46, 2641, '2024-06-09 18:46:11'),
(88, 46, 421, '2024-06-09 18:46:15'),
(89, 46, 361, '2024-06-09 18:46:20'),
(90, 46, 361, '2024-06-09 18:46:22'),
(91, 46, 361, '2024-06-09 18:46:25'),
(92, 46, 361, '2024-06-09 18:46:28'),
(93, 46, 361, '2024-06-09 18:46:34'),
(94, 46, 361, '2024-06-09 18:46:39');

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
  ADD UNIQUE KEY `lietotājvārds` (`lietotājvārds`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reposts`
--
ALTER TABLE `reposts`
  ADD PRIMARY KEY (`repost_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `rezcuska`
--
ALTER TABLE `rezcuska`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rezmerkatreneris`
--
ALTER TABLE `rezmerkatreneris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reztet`
--
ALTER TABLE `reztet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reztrex`
--
ALTER TABLE `reztrex`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `conversation_members`
--
ALTER TABLE `conversation_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `komentari`
--
ALTER TABLE `komentari`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `lietotaji`
--
ALTER TABLE `lietotaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reposts`
--
ALTER TABLE `reposts`
  MODIFY `repost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=421;

--
-- AUTO_INCREMENT for table `rezcuska`
--
ALTER TABLE `rezcuska`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rezmerkatreneris`
--
ALTER TABLE `rezmerkatreneris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reztet`
--
ALTER TABLE `reztet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reztrex`
--
ALTER TABLE `reztrex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `user_notes`
--
ALTER TABLE `user_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

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
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`);

--
-- Constraints for table `reposts`
--
ALTER TABLE `reposts`
  ADD CONSTRAINT `reposts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`),
  ADD CONSTRAINT `reposts_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `komentari` (`comment_id`);

--
-- Constraints for table `user_notes`
--
ALTER TABLE `user_notes`
  ADD CONSTRAINT `user_notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
