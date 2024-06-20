-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2024 at 07:09 AM
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
(10, 'Conversation with rikardo'),
(11, 'Saruna ar gangsteris');

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
(25, 11, 65),
(26, 11, 57);

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
(65, 57),
(65, 66);

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
(57, 'Mani sauc eduards', '2024-06-20 03:57:51', 212, NULL, NULL, 0, '2024-06-20 00:57:51'),
(57, 'cik tev daudz naudas?', '2024-06-20 04:05:32', 221, NULL, NULL, 0, '2024-06-20 01:05:32'),
(57, 'Kā iet?', '2024-06-20 04:05:35', 222, NULL, NULL, 0, '2024-06-20 01:05:35'),
(57, 'wow tik forša bilde', '2024-06-20 04:36:30', 226, NULL, 224, 0, '2024-06-20 01:36:30'),
(66, 'Es esmu klāt', '2024-06-20 05:11:33', 227, NULL, NULL, 0, '2024-06-20 02:11:33'),
(66, 'Man suns beidzot atnāca mājās!', '2024-06-20 05:12:00', 228, NULL, NULL, 0, '2024-06-20 02:12:00'),
(65, 'Šī majaslapa ir tik forša', '2024-06-20 05:33:18', 231, NULL, NULL, 0, '2024-06-20 02:33:18'),
(57, 'Šis ir vislabakais', '2024-06-20 06:00:04', 232, 'uploads/2498751a821bb7ea04ba4927d3088bc3.gif', NULL, 0, '2024-06-20 03:00:04'),
(57, 'Es nevaru beigt skatīties grieztos!', '2024-06-20 06:00:16', 233, NULL, NULL, 1, '2024-06-20 03:03:28'),
(57, 'Tas ir tik relaksejoši', '2024-06-20 06:00:33', 234, NULL, NULL, 0, '2024-06-20 03:00:33'),
(57, 'Kur visi palika?', '2024-06-20 06:04:01', 235, NULL, NULL, 0, '2024-06-20 03:04:01'),
(65, 'Tiko redzēju lielu zvaigzni', '2024-06-20 06:04:25', 236, NULL, NULL, 0, '2024-06-20 03:04:25'),
(57, 'NEVAR BŪT!', '2024-06-20 06:13:51', 237, NULL, 236, 0, '2024-06-20 03:13:51'),
(57, 'ES ARĪ REDZĒJU TO PAŠU', '2024-06-20 06:14:07', 238, NULL, 236, 0, '2024-06-20 03:14:07'),
(65, 'Manuprāt būs vēlviena pēc 5 gadiem', '2024-06-20 06:15:42', 240, NULL, 238, 0, '2024-06-20 03:15:42');

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
(57, 'gangsteris', 'I3tc1UeW+jyL6YtOyOu6aehkxT3qUBE64g/fK8esZ1E=', '$2y$10$MQ2NyhS6nwkN6lUrdQ5VSO7H9TTOWe2mu1.KKlGoE7yrVMVcw1Xne', 'Administrators', 0, 0, 'e6504e51268a7d1a41ab635e29fdaac7.jpg'),
(58, 'Tomelis', 'yjS2GYL8v7wV8OdaPWnd1HXmHH8lcZxen4DAW8cNL4Q=', '$2y$10$c.MNZAzovEZubuf044ixr./wkZNy54DDUEKebQTmu20vecOeO86Mq', 'Lietotājs', 0, 0, 'bildes/default.png'),
(59, 'Bobs', '4iFYhcWXmmFWi6Fhjx7eig==', '$2y$10$e94SIvFz8D6x379ol3qrye5bTzHwnkffTN/PJJ701P0P96q0nYI9a', 'Deaktivizēts', 0, 0, 'bildes/default.png'),
(60, 'Alfreds', 'a6Pgjc+hckyQlV9OX+lkCL6/qOFHv9dn6MNwPrLWhW8=', '$2y$10$6k8UPBYqPWXDPLqU9Zq6Wu5x4HR91NkIzCPMhPgqgyUFPm3kBYhRK', 'Lietotājs', 0, 0, 'bildes/default.png'),
(61, 'Īstais Pēteris', 'YiaCzNZYe7b7gR3xtVvVxX8dgVCPO2DVXZaHoluVAGI=', '$2y$10$MeKZB7WwnuNAvDfCp8FfC.y48DO43seYUer.ZgrS100jCtSHZvtei', 'Lietotājs', 0, 0, 'bildes/default.png'),
(62, 'Parasts Lietotajs', 'q0k4Gp34i/Q6jDKlE+53FhdfSOZ4L1pBWmGH9eEhXkw=', '$2y$10$g1xaXRS4TruNn6cjabLMWepfveyEt9FtgS9JXg5uZXti4xf4.qgH2', 'Deaktivizēts', 0, 0, 'bildes/default.png'),
(63, 'Svētā Marija', 'xK+EUfT9SisI34hApfImRQDnob4HR3XWchCrakDyLM4=', '$2y$10$e/WfQw4qCve5DP0Ojt5RvOMrwLA.NeilQnQkpAOq.LDfH6y6OjYYi', 'Deaktivizēts', 0, 0, 'bildes/default.png'),
(64, 'Lielais Janka', 'OeAJ6za3PfK3RUd2JLdy43eLDUAtQ6AHDHXOCfpj6gw=', '$2y$10$Gd2hc6jfaRbTBAx3P2S3pe72N8hsJ7WO.bOkNBDretyeQu/wMCOMu', 'Lietotājs', 0, 0, 'bildes/default.png'),
(65, 'Evelīna', 'EE9KD7H56wShOviKzKQMdvoMPlXnqP9jZAyz3YGMG38=', '$2y$10$9she.LId5ISDwzSJHwJuY.pAAgHYSCW1LCP16tzykRAWqB4FJ563y', 'Lietotājs', 0, 0, 'bildes/default.png'),
(66, 'Roberto', 'wps5v4CuhovFddmH+q7gNemn1KfGeCdumEVwN/PMJpg=', '$2y$10$eaA.GCWXoUXF93Ogtgkzh.MT16e76vaVQ7P1IgG3xDZ95wynNlmwK', 'Lietotājs', 0, 0, 'bildes/default.png'),
(68, 'Mazais Mārcis', 'unJhxWVDS4fOy8RQtHAXAl1piEdsgtbSM3UYbUn8u+s=', '$2y$10$7F8kQCEFd4zLUUlXXkp00uDeUnl4it/FQbD9MN9sDDiJorQkVltBK', 'Deaktivizēts', 0, 0, 'bildes/default.png'),
(69, 'Pēteris', 'ZlolMrIj+GOBckRvFAyumg==', '$2y$10$wjgcT2Ek6FKm5wyPgaNVwOgrBpTp2rEvqjbGj97lx7zosZ650AxAC', 'Deaktivizēts', 0, 0, 'bildes/default.png'),
(70, 'Tas Janka ', 'qrZEUj9keB4Nv0Uh6V3yVsaac0rGoR5OThWeEUN1InY=', '$2y$10$/HDYa6v7qmGRtaXlkrJR6OHFDxJcIg5Yn3rzeRCKXTfMesZ8Wx.9u', 'Lietotājs', 0, 0, 'bildes/default.png'),
(71, 'Marija', '72OCSGv+A5rcYJ9d2Xv9cgOs1vev4DWPkWVjHDjEGRg=', '$2y$10$fY2hdWFc3DZT.lt2A9fJ.eBztlmOXXmM3MOVGyuBXXAulenNuHE9e', 'Lietotājs', 0, 0, 'bildes/default.png'),
(72, 'Harijs', 'PTWUfb8o/WFSfyBAfSoCEiQzJSpATcdPbgSOmBUliJE=', '$2y$10$aegdSPFjs..AxvzzjimhPu2JW.vRGYRzyXG3NSPut4VqzwA6Y8aD.', 'Lietotājs', 0, 0, 'bildes/default.png');

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
(46, 157),
(57, 212),
(57, 221),
(57, 222),
(57, 228),
(57, 231),
(65, 237),
(65, 238),
(65, 236);

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
(128, 66, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:39:30', 'like'),
(129, 66, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:39:31', 'like'),
(130, 65, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:23', 'like'),
(131, 57, 'Comment \'Šī majaslapa ir tik forša\' was liked by gangsteris', '2024-06-20 02:56:23', 'like'),
(132, 57, 'Comment \'Šī majaslapa ir tik forša\' was unliked by gangsteris', '2024-06-20 02:56:24', 'unlike'),
(133, 65, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:26', 'like'),
(134, 57, 'Comment \'Šī majaslapa ir tik forša\' was liked by gangsteris', '2024-06-20 02:56:26', 'like'),
(135, 57, 'Comment \'Šī majaslapa ir tik forša\' was unliked by gangsteris', '2024-06-20 02:56:28', 'unlike'),
(136, 65, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:31', 'like'),
(137, 57, 'Comment \'Šī majaslapa ir tik forša\' was liked by gangsteris', '2024-06-20 02:56:31', 'like'),
(138, 57, 'Comment \'Šī majaslapa ir tik forša\' was unliked by gangsteris', '2024-06-20 02:56:31', 'unlike'),
(139, 65, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:32', 'like'),
(140, 57, 'Comment \'Šī majaslapa ir tik forša\' was liked by gangsteris', '2024-06-20 02:56:32', 'like'),
(141, 57, 'Comment \'Šī majaslapa ir tik forša\' was unliked by gangsteris', '2024-06-20 02:56:32', 'unlike'),
(142, 57, 'Comment \'Kā iet?\' was liked by gangsteris', '2024-06-20 02:56:40', 'like'),
(143, 57, 'Comment \'Kā iet?\' was unliked by gangsteris', '2024-06-20 02:56:40', 'unlike'),
(144, 57, 'Comment \'Mani sauc eduards\' was liked by gangsteris', '2024-06-20 02:56:48', 'like'),
(145, 57, 'Comment \'cik tev daudz naudas?\' was liked by gangsteris', '2024-06-20 02:56:49', 'like'),
(146, 57, 'Comment \'Kā iet?\' was liked by gangsteris', '2024-06-20 02:56:50', 'like'),
(147, 66, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:51', 'like'),
(148, 57, 'Comment \'Es esmu klāt\' was liked by gangsteris', '2024-06-20 02:56:51', 'like'),
(149, 66, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:52', 'like'),
(150, 57, 'Comment \'Man suns beidzot atnāca mājās!\' was liked by gangsteris', '2024-06-20 02:56:52', 'like'),
(151, 65, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 02:56:53', 'like'),
(152, 57, 'Comment \'Šī majaslapa ir tik forša\' was liked by gangsteris', '2024-06-20 02:56:53', 'like'),
(153, 57, 'Comment \'Es esmu klāt\' was unliked by gangsteris', '2024-06-20 02:56:55', 'unlike'),
(154, 57, 'Comment \'Šī majaslapa ir tik forša\' was unliked by gangsteris', '2024-06-20 03:01:35', 'unlike'),
(155, 65, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 03:01:37', 'like'),
(156, 57, 'Comment \'Šī majaslapa ir tik forša\' was liked by gangsteris', '2024-06-20 03:01:37', 'like'),
(157, 57, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 03:15:49', 'like'),
(158, 57, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 03:15:50', 'like'),
(159, 57, 'Uz tavu komentāru tika nospiests patīk', '2024-06-20 03:15:50', 'like');

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
(8, 'mamot86742@fincainc.com', 'a898390bb356c806ec9923630c11d04bbd061adfb48c43d65441c2d5cbe0586266c4c9a6a2e12f82cacedf7ddc14c59c213f', '2024-06-02 19:12:31'),
(9, 'peWxznSyYSVQDVJ3hVd7Tkh7H8V++EO6I4g8iUiWtv8=', 'd94c553b079fe1a6529b038399c2ea5d4e7f2a291cd16b92b6221894aeb726bbaed65ce013e4bc086adf98156d6bb4176d43', '2024-06-14 09:12:41'),
(10, 'peWxznSyYSVQDVJ3hVd7Tkh7H8V++EO6I4g8iUiWtv8=', 'b73d64bba75d77f04b3270c9ec71812c683d3c571d97b50fd64c6c25e1b29042fa91544eb0fef1c9cd3a63c2cbd28a6b2c37', '2024-06-14 09:13:13'),
(11, 'peWxznSyYSVQDVJ3hVd7Tkh7H8V++EO6I4g8iUiWtv8=', '82bb9e5eed4e77d6148c5a8b66013b279b57b899d1f14c0a29e237ff76c33cbba551173eb8ab3ffecb843f6fa9f12d6bb037', '2024-06-14 09:16:49'),
(12, 'YiaCzNZYe7b7gR3xtVvVxX8dgVCPO2DVXZaHoluVAGI=', '022b7786ac1aff0c96637600c45ffd7b7beb07d3f9704cfc15e4f6f73221cfa94921548755dfb0684104b54abb078ce6fd70', '2024-06-20 01:43:18'),
(13, 'I3tc1UeW+jyL6YtOyOu6aehkxT3qUBE64g/fK8esZ1E=', 'dd87344e6150578aa2ce6134969497b8788836d3d139b33a83418b13d5869c17c98501ce6a5ff967fcdd7e04aa62ca9129e8', '2024-06-20 06:18:07'),
(14, 'I3tc1UeW+jyL6YtOyOu6aehkxT3qUBE64g/fK8esZ1E=', '022a167790fe2ff0dc556fc4dc2b9737ab8d980c4208761a422db5b7b75e8fa7ab2edbbbbbb60cad989d3a07016659ebeff5', '2024-06-20 06:18:23');

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
(7, 61, 1, '2024-06-19 22:22:23');

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
(9, 46, 9, '2024-06-13 04:05:51'),
(10, 61, 12, '2024-06-19 22:22:16');

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
(95, 46, 421, '2024-06-13 03:50:04'),
(96, 61, 1088, '2024-06-19 22:22:50');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blocked_users_ibfk_1` (`user_id`),
  ADD KEY `blocked_users_ibfk_2` (`blocked_user_id`);

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
  ADD KEY `conversation_members_ibfk_1` (`conversation_id`),
  ADD KEY `conversation_members_ibfk_2` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `follows_ibfk_2` (`followed_id`);

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
  ADD KEY `messages_ibfk_1` (`conversation_id`),
  ADD KEY `messages_ibfk_2` (`sender_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_ibfk_1` (`user_id`);

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
  ADD KEY `reposts_ibfk_1` (`user_id`),
  ADD KEY `reposts_ibfk_2` (`content_id`);

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
  ADD KEY `user_notes_ibfk_1` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blocked_users`
--
ALTER TABLE `blocked_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `conversation_members`
--
ALTER TABLE `conversation_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `komentari`
--
ALTER TABLE `komentari`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `lietotaji`
--
ALTER TABLE `lietotaji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reposts`
--
ALTER TABLE `reposts`
  MODIFY `repost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=424;

--
-- AUTO_INCREMENT for table `rezcuska`
--
ALTER TABLE `rezcuska`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rezmerkatreneris`
--
ALTER TABLE `rezmerkatreneris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reztet`
--
ALTER TABLE `reztet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reztrex`
--
ALTER TABLE `reztrex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `user_notes`
--
ALTER TABLE `user_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD CONSTRAINT `blocked_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blocked_users_ibfk_2` FOREIGN KEY (`blocked_user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conversation_members`
--
ALTER TABLE `conversation_members`
  ADD CONSTRAINT `conversation_members_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conversation_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`lietotaja_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reposts`
--
ALTER TABLE `reposts`
  ADD CONSTRAINT `reposts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reposts_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `komentari` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_notes`
--
ALTER TABLE `user_notes`
  ADD CONSTRAINT `user_notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lietotaji` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
