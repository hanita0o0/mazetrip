-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2018 at 04:13 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payebash`
--

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE `bans` (
  `id` int(10) UNSIGNED NOT NULL,
  `banable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banable_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatgroupmembers`
--

CREATE TABLE `chatgroupmembers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `chatgroup_id` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatgroups`
--

CREATE TABLE `chatgroups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `information` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `chatable_id` int(11) NOT NULL,
  `chatable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `chatable_id`, `chatable_type`, `created_at`, `updated_at`) VALUES
(1, 2, 'App\\Event', '2018-11-10 05:44:01', '2018-11-10 05:44:01'),
(2, 3, 'App\\Event', '2018-11-10 05:54:54', '2018-11-10 05:54:54'),
(3, 4, 'App\\Event', '2018-11-10 10:26:45', '2018-11-10 10:26:45'),
(4, 5, 'App\\Event', '2018-11-13 05:33:41', '2018-11-13 05:33:41'),
(5, 5, 'App\\Event', '2018-11-13 05:40:21', '2018-11-13 05:40:21'),
(6, 5, 'App\\Event', '2018-11-13 05:42:40', '2018-11-13 05:42:40'),
(7, 5, 'App\\Event', '2018-11-13 05:43:09', '2018-11-13 05:43:09'),
(8, 5, 'App\\Event', '2018-11-13 05:44:19', '2018-11-13 05:44:19'),
(9, 5, 'App\\Event', '2018-11-13 05:45:07', '2018-11-13 05:45:07'),
(10, 6, 'App\\Event', '2018-11-13 05:50:07', '2018-11-13 05:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `checklists`
--

CREATE TABLE `checklists` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `requirement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'تهران', NULL, NULL),
(2, 2, 'کرج', NULL, NULL),
(3, 2, 'زز', '2018-11-10 05:54:54', '2018-11-10 05:54:54');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `body` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventphotos`
--

CREATE TABLE `eventphotos` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eventphotos`
--

INSERT INTO `eventphotos` (`id`, `path`, `created_at`, `updated_at`) VALUES
(2, '1541841894.Alaedin-Travel-Agency-Attraction-Palace-Shokat-Abad-Birjand-4.jpg', '2018-11-10 05:54:54', '2018-11-10 05:54:54'),
(5, '1542794807.alamir.png', '2018-11-21 06:36:47', '2018-11-21 06:36:47');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `header` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text COLLATE utf8mb4_unicode_ci,
  `about_team` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `activation_no` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `header`, `about`, `about_team`, `is_active`, `activation_no`, `avatar`, `state`, `city`, `created_at`, `updated_at`, `location_id`) VALUES
(3, 'زز', 'زز', 'زز', 'data.header', 1, 'hNFcpUwv6ngu9bW', 0, 2, 2, '2018-11-10 05:54:54', '2018-11-10 05:54:54', 0),
(5, 'goood', 'so cool', 'tour aliii', 'ddddd', 1, '5LZGTUjzDW90m3L', 0, 1, 1, '2018-11-13 05:45:07', '2018-11-17 07:46:42', 0),
(6, 'aaaaa', 'its a test', 'dddd', 'ddddd', 1, 'Ctey8oSD1o2Wukf', 5, 2, 2, '2018-11-13 05:50:07', '2018-11-21 06:39:28', 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_gang`
--

CREATE TABLE `event_gang` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `gang_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_gang`
--

INSERT INTO `event_gang` (`id`, `event_id`, `gang_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 3, 2, NULL, NULL),
(13, 4, 5, NULL, NULL),
(32, 6, 6, NULL, NULL),
(33, 6, 7, NULL, NULL),
(34, 6, 8, NULL, NULL),
(35, 4, 4, NULL, NULL),
(71, 5, 5, NULL, NULL),
(72, 5, 3, NULL, NULL),
(73, 5, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_type`
--

CREATE TABLE `event_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_type`
--

INSERT INTO `event_type` (`id`, `type_id`, `event_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, NULL),
(2, 1, 3, NULL, NULL),
(7, 3, 3, NULL, NULL),
(8, 1, 4, NULL, NULL),
(9, 4, 3, NULL, NULL),
(22, 1, 6, NULL, NULL),
(23, 2, 6, NULL, NULL),
(24, 3, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_user`
--

CREATE TABLE `event_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_vote`
--

CREATE TABLE `event_vote` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `vote_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `filters`
--

CREATE TABLE `filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `filterable_id` int(11) NOT NULL,
  `filterable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `limit` int(11) DEFAULT NULL,
  `request` tinyint(1) DEFAULT NULL,
  `hide_event` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filters`
--

INSERT INTO `filters` (`id`, `filterable_id`, `filterable_type`, `gender`, `limit`, `request`, `hide_event`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-13 03:27:45', '2018-11-13 03:27:45'),
(2, 2, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-13 03:40:09', '2018-11-13 03:40:09'),
(3, 5, 'App\\Event', NULL, NULL, NULL, NULL, '2018-11-13 05:33:40', '2018-11-13 05:33:40'),
(4, 5, 'App\\Event', NULL, NULL, NULL, NULL, '2018-11-13 05:40:21', '2018-11-13 05:40:21'),
(5, 5, 'App\\Event', NULL, NULL, NULL, NULL, '2018-11-13 05:42:40', '2018-11-13 05:42:40'),
(6, 5, 'App\\Event', NULL, NULL, NULL, NULL, '2018-11-13 05:44:18', '2018-11-13 05:44:18'),
(7, 5, 'App\\Event', NULL, NULL, NULL, NULL, '2018-11-13 05:45:07', '2018-11-13 05:45:07'),
(8, 3, 'App\\Ticket', NULL, NULL, NULL, NULL, '2018-11-13 06:21:09', '2018-11-13 06:21:09'),
(9, 8, 'App\\Ticket', NULL, NULL, NULL, NULL, '2018-11-14 12:00:12', '2018-11-14 12:00:12'),
(10, 1, 'App\\Ticket', NULL, NULL, NULL, NULL, '2018-11-21 08:22:11', '2018-11-21 08:22:11'),
(11, 2, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:16:50', '2018-11-26 07:16:50'),
(12, 3, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:36:36', '2018-11-26 07:36:36'),
(13, 4, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:36:42', '2018-11-26 07:36:42'),
(14, 5, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:36:46', '2018-11-26 07:36:46'),
(15, 6, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:36:54', '2018-11-26 07:36:54'),
(16, 7, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:37:13', '2018-11-26 07:37:13'),
(17, 8, 'App\\Ticket', 1, 10, 1, NULL, '2018-11-26 07:37:33', '2018-11-26 07:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`, `created_at`, `updated_at`) VALUES
(1, 4, 6, '2018-11-10 05:54:07', '2018-11-10 05:54:07');

-- --------------------------------------------------------

--
-- Table structure for table `gangs`
--

CREATE TABLE `gangs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gangs`
--

INSERT INTO `gangs` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'آفرود', '2018-11-10 05:44:00', '2018-11-10 05:44:00'),
(2, 'زز', '2018-11-10 05:54:54', '2018-11-10 05:54:54'),
(3, 'کوهنوردی', '2018-11-10 10:26:38', '2018-11-10 10:26:38'),
(4, 'گشت و گذار', '2018-11-12 09:36:35', '2018-11-12 09:36:35'),
(5, 'طبیعت', '2018-11-12 10:56:00', '2018-11-12 10:56:00'),
(6, 'دورهمی', '2018-11-13 05:33:40', '2018-11-13 05:33:40'),
(7, 'پیاده روی', '2018-11-13 05:33:41', '2018-11-13 05:33:41'),
(8, 'طبیعت گردی', '2018-11-13 05:33:41', '2018-11-13 05:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `gmaps_geocache`
--

CREATE TABLE `gmaps_geocache` (
  `id` int(10) UNSIGNED NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gmaps_geocache`
--

INSERT INTO `gmaps_geocache` (`id`, `address`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 'Fateh Garden, Jahanshahr, Karaj, Alborz Province, Iran\r\n', '35.831764', ' 50.976856', NULL, NULL),
(2, 'Persian Gulf Park, Tehran Province, Karaj, North Kuye Karmandan, Farvardin, Iran', '35.839424', '50.972173', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messagephotos`
--

CREATE TABLE `messagephotos` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `content` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `messagephoto_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_09_14_082441_create_cities_table', 1),
(4, '2017_09_14_082451_create_events_table', 1),
(5, '2017_09_14_082502_create_photos_table', 1),
(6, '2017_09_14_082518_create_roles_table', 1),
(7, '2017_09_14_082530_create_states_table', 1),
(8, '2017_09_14_082542_create_tickets_table', 1),
(9, '2017_09_14_082624_create_timelines_table', 1),
(10, '2017_09_14_082843_create_votes_table', 1),
(11, '2017_09_14_091629_create_event_user_pivot', 1),
(12, '2017_09_14_091738_create_ticket_user_pivot', 1),
(13, '2017_09_18_215335_create_types_table', 1),
(14, '2017_10_14_070442_create_checklists_table', 1),
(15, '2017_10_14_080133_create_eventphotos_table', 1),
(16, '2017_10_14_203308_event_managers_pivot_table', 1),
(17, '2017_10_15_062936_event_votes_pivot_table', 1),
(18, '2017_10_15_100451_create_posts_table', 1),
(19, '2017_10_15_165414_likes_for_posts', 1),
(20, '2017_10_15_180527_create_comments_table', 1),
(21, '2017_10_17_191946_create_event_type_pivot_table', 1),
(22, '2017_10_21_231541_subscribe_pivot', 1),
(23, '2017_10_21_233212_create_chats_table', 1),
(24, '2017_10_22_075724_create_messages_table', 1),
(25, '2017_10_22_080853_create_messagephotos_table', 1),
(26, '2017_10_22_192301_create_filters_table', 1),
(27, '2017_10_24_210132_create_requesthandelings_table', 1),
(28, '2017_11_10_011535_create_followers_pivot_table', 1),
(29, '2017_11_29_010434_create_chatgroups_table', 1),
(30, '2017_11_29_014744_create_chatgroup_members', 1),
(31, '2018_01_12_123457_create_bans_table', 1),
(32, '2018_02_05_163444_create_ticket_manager_table', 1),
(33, '2018_03_16_141557_create_timeline_photos_table', 1),
(34, '2018_04_06_145605_create_postmedia_table', 1),
(35, '2018_05_10_142345_create_ticketavatars_table', 1),
(36, '2018_05_30_000108_create_notifications_table', 1),
(37, '2018_07_30_133138_create_role_user_table', 1),
(38, '2018_10_24_093918_create_gangs_table', 1),
(39, '2018_10_24_094134_create_event_gang_pivot_table', 1),
(40, '2018_12_08_115034_create_gmaps_geocache_table', 2),
(41, '2018_12_08_124107_add_location_id_column_to_table_posts', 3),
(42, '2018_12_08_124511_add_location_id_to_tickets_table', 4),
(43, '2018_12_08_124927_add_location_id_to_events_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `info` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `path`, `created_at`, `updated_at`) VALUES
(1, '1541844238.walking.jpg', '2018-11-10 06:33:59', '2018-11-10 06:33:59'),
(2, '1541844495.mountaining.jpg', '2018-11-10 06:38:16', '2018-11-10 06:38:16'),
(3, '1541844772.desert.jpg', '2018-11-10 06:42:54', '2018-11-10 06:42:54'),
(4, '1541844975.desertification.jpg', '2018-11-10 06:46:16', '2018-11-10 06:46:16'),
(5, '1.jpg', '2018-11-10 07:11:09', '2018-11-10 07:11:09'),
(6, '1541847754.takhtejamshid.jpg', '2018-11-10 07:32:35', '2018-11-10 07:32:35'),
(7, '1541848115.ghayeghsavari.jpg', '2018-11-10 07:38:35', '2018-11-10 07:38:35'),
(8, '1541848238.adventure.jpg', '2018-11-10 07:40:38', '2018-11-10 07:40:38'),
(9, '1541848914.city.jpg', '2018-11-10 07:51:55', '2018-11-10 07:51:55'),
(10, '1541849309.nature.jpg', '2018-11-10 07:58:31', '2018-11-10 07:58:31'),
(11, '1541849668.nature (2).jpg', '2018-11-10 08:04:29', '2018-11-10 08:04:29'),
(12, '1541850117.nature3.jpg', '2018-11-10 08:11:58', '2018-11-10 08:11:58'),
(13, '1541850347.beach.jpg', '2018-11-10 08:15:48', '2018-11-10 08:15:48'),
(14, '1541850747.paraglider.jpg', '2018-11-10 08:22:28', '2018-11-10 08:22:28'),
(15, '1541850839.the beach.jpg', '2018-11-10 08:24:00', '2018-11-10 08:24:00'),
(16, '1541852259.ridebycicle.jpg', '2018-11-10 08:47:39', '2018-11-10 08:47:39'),
(17, '1541852323.afroud.jpg', '2018-11-10 08:48:43', '2018-11-10 08:48:43'),
(18, '1541852714.culture.jpg', '2018-11-10 08:55:15', '2018-11-10 08:55:15'),
(19, '1541852805.animal.jpg', '2018-11-10 08:56:46', '2018-11-10 08:56:46'),
(20, '1541853092.sport.jpg', '2018-11-10 09:01:33', '2018-11-10 09:01:33'),
(21, '1541853219.family.jpg', '2018-11-10 09:03:39', '2018-11-10 09:03:39'),
(22, '1541853335.sport.jpg', '2018-11-10 09:05:36', '2018-11-10 09:05:36'),
(23, '1541853718.rural.jpg', '2018-11-10 09:12:00', '2018-11-10 09:12:00'),
(24, '1541854465.Pilgrimage.png', '2018-11-10 09:24:25', '2018-11-10 09:24:25'),
(25, '1541854877.irangardi.png', '2018-11-10 09:31:18', '2018-11-10 09:31:18'),
(27, '1542012223.animal.jpg', '2018-11-12 05:13:45', '2018-11-12 05:13:45'),
(28, '1542012337.animal.jpg', '2018-11-12 05:15:38', '2018-11-12 05:15:38'),
(33, '1542795409.2.jpg', '2018-11-21 06:46:49', '2018-11-21 06:46:49'),
(35, '1542798829.alamir.png', '2018-11-21 07:43:49', '2018-11-21 07:43:49'),
(36, '1542800667.alamir.png', '2018-11-21 08:14:27', '2018-11-21 08:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `postmedia`
--

CREATE TABLE `postmedia` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `body` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `user_id`, `event_id`, `media_id`, `deleted_at`, `created_at`, `updated_at`, `location_id`) VALUES
(1, 'ssss', 2, NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `requesthandelings`
--

CREATE TABLE `requesthandelings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'full control', '2018-11-09 20:30:00', '2018-11-09 20:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 21, 1, NULL, NULL),
(2, 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'تهران', NULL, NULL),
(2, 'البرز', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `user_id`, `event_id`, `created_at`, `updated_at`) VALUES
(2, 6, 3, '2018-11-10 05:54:54', '2018-11-10 05:54:54'),
(5, 3, 5, '2018-11-13 05:33:41', '2018-11-13 05:33:41'),
(17, 3, 6, '2018-11-13 05:50:07', '2018-11-13 05:50:07'),
(18, 1, 6, '2018-11-13 05:50:07', '2018-11-13 05:50:07'),
(19, 2, 5, '2018-11-17 06:22:18', '2018-11-17 06:22:18'),
(21, 2, 5, '2018-11-17 06:28:25', '2018-11-17 06:28:25'),
(22, 5, 5, '2018-11-17 06:28:25', '2018-11-17 06:28:25'),
(24, 4, 5, '2018-11-17 06:30:53', '2018-11-17 06:30:53'),
(25, 7, 5, '2018-11-17 07:07:30', '2018-11-17 07:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `ticketavatars`
--

CREATE TABLE `ticketavatars` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticketavatars`
--

INSERT INTO `ticketavatars` (`id`, `path`, `created_at`, `updated_at`) VALUES
(1, '1543229209.1540639343.02.jpg', '2018-11-26 07:16:50', '2018-11-26 07:16:50'),
(2, '1543230396.1540639343.02.jpg', '2018-11-26 07:36:36', '2018-11-26 07:36:36'),
(3, '1543230402.1540639343.02.jpg', '2018-11-26 07:36:42', '2018-11-26 07:36:42'),
(4, '1543230406.1540639343.02.jpg', '2018-11-26 07:36:46', '2018-11-26 07:36:46'),
(5, '1543230414.1540639343.02.jpg', '2018-11-26 07:36:54', '2018-11-26 07:36:54'),
(6, '1543230433.1540639343.02.jpg', '2018-11-26 07:37:13', '2018-11-26 07:37:13'),
(7, '1543230453.1540639343.02.jpg', '2018-11-26 07:37:33', '2018-11-26 07:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `ticketmanager`
--

CREATE TABLE `ticketmanager` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticketmanager`
--

INSERT INTO `ticketmanager` (`id`, `user_id`, `ticket_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'admin', NULL, NULL),
(2, 3, 1, 'admin', NULL, NULL),
(5, 3, 3, 'admin', NULL, NULL),
(7, 1, 3, 'saghi', NULL, NULL),
(8, 3, 2, 'admin', NULL, NULL),
(9, 3, 3, 'admin', NULL, NULL),
(10, 3, 4, 'admin', NULL, NULL),
(11, 3, 5, 'admin', NULL, NULL),
(12, 3, 6, 'admin', NULL, NULL),
(13, 3, 7, 'admin', NULL, NULL),
(14, 3, 8, 'admin', NULL, NULL),
(15, 5, 1, 'saghi', NULL, NULL),
(16, 7, 1, 'checker', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `activation_num` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `name`, `body`, `avatar_id`, `date`, `end_date`, `event_id`, `activation_num`, `price`, `state`, `city`, `address`, `is_active`, `created_at`, `updated_at`, `location_id`) VALUES
(1, 'تور یکروزه مرنجاب3', 'dontknow', NULL, '2018-12-14 20:30:01', '2018-11-26 20:40:00', 3, '5bf546ebd3516', 349000, '1', '1', 'shandool abad', 1, '2018-11-21 08:22:11', '2018-11-21 08:22:11', 1),
(2, 'maranjab', 'dontknow', 1, '2018-12-26 20:30:00', '2018-11-29 20:30:00', 5, '5bfbcf19cc3a2', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:16:50', '2018-11-26 07:16:50', 2),
(3, 'maranjaba', 'dontknow', 2, '2018-11-26 20:30:00', '2018-11-29 20:30:00', 5, '5bfbd3bc12430', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:36:36', '2018-11-26 07:36:36', 0),
(4, 'maranjabd', 'dontknow', 3, '2018-11-26 20:30:00', '2018-11-29 20:30:00', 5, '5bfbd3c22b22c', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:36:42', '2018-11-26 07:36:42', 0),
(5, 'maranjabdf', 'dontknow', 4, '2018-11-26 20:30:00', '2018-11-29 20:30:00', 5, '5bfbd3c6a74e2', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:36:46', '2018-11-26 07:36:46', 0),
(6, 'maranjabds', 'dontknow', 5, '2018-11-26 20:30:00', '2018-11-29 20:30:00', 5, '5bfbd3ce47135', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:36:54', '2018-11-26 07:36:54', 0),
(7, 'maranjabdggg', 'dontknow', 6, '2018-11-26 20:30:00', '2018-11-24 20:30:00', 5, '5bfbd3e15aac1', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:37:13', '2018-11-26 07:37:13', 0),
(8, 'maranjabdggg', 'dontknow', 7, '2018-11-26 20:30:00', '2018-11-24 20:30:00', 5, '5bfbd3f575c86', 0, '2', '2', 'shandool abad', 1, '2018-11-26 07:37:33', '2018-11-26 07:37:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_user`
--

CREATE TABLE `ticket_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timelines`
--

CREATE TABLE `timelines` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  `timelineable_id` int(11) NOT NULL,
  `timelineable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeline_photos`
--

CREATE TABLE `timeline_photos` (
  `id` int(10) UNSIGNED NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timeline_photos`
--

INSERT INTO `timeline_photos` (`id`, `path`, `created_at`, `updated_at`) VALUES
(2, '1542097056.koushik-chowdavarapu-137783-unsplash.jpg', '2018-11-13 04:47:40', '2018-11-13 04:47:40');

-- --------------------------------------------------------

--
-- Table structure for table `touremanagers`
--

CREATE TABLE `touremanagers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `touremanagers`
--

INSERT INTO `touremanagers` (`id`, `user_id`, `event_id`, `created_at`, `updated_at`) VALUES
(2, 2, 3, '2018-11-10 05:54:54', '2018-11-10 05:54:54'),
(14, 3, 5, '2018-11-13 05:44:18', '2018-11-13 05:44:18'),
(18, 3, 6, '2018-11-13 05:50:07', '2018-11-13 05:50:07'),
(20, 5, 3, '2018-11-08 20:30:00', '2018-11-22 20:30:00'),
(24, 7, 3, '2018-11-17 07:07:30', '2018-11-17 07:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`, `description`, `photo_id`, `created_at`, `updated_at`) VALUES
(1, 'کوهنوردی', NULL, NULL, NULL, '2018-11-10 07:11:09'),
(2, 'پیاده روی', NULL, 35, '2018-11-10 06:33:59', '2018-11-21 07:45:59'),
(3, 'کویرشناسی', NULL, 4, '2018-11-10 06:42:54', '2018-11-10 06:46:16'),
(4, 'آثار باستانی', NULL, 6, '2018-11-10 07:32:35', '2018-11-10 07:32:35'),
(5, 'قایق رانی', NULL, 7, '2018-11-10 07:38:35', '2018-11-10 07:38:35'),
(6, 'ماجراجویی', NULL, 8, '2018-11-10 07:40:38', '2018-11-10 07:40:38'),
(7, 'شهرشناسی', NULL, 9, '2018-11-10 07:51:55', '2018-11-10 07:51:55'),
(8, 'طبیعت گردی', NULL, 12, '2018-11-10 07:58:31', '2018-11-10 08:11:58'),
(9, 'ساحلی', NULL, 15, '2018-11-10 08:15:48', '2018-11-10 08:24:00'),
(10, 'پاراگلایدر', NULL, 14, '2018-11-10 08:22:28', '2018-11-10 08:22:28'),
(11, 'دوچرخه سواری', NULL, 16, '2018-11-10 08:47:39', '2018-11-10 08:47:39'),
(12, 'آفرود', NULL, 17, '2018-11-10 08:48:43', '2018-11-10 08:48:43'),
(13, 'فرهنگ شناسی', NULL, 18, '2018-11-10 08:55:16', '2018-11-10 08:55:16'),
(14, 'جانورشناسی', NULL, 19, '2018-11-10 08:56:46', '2018-11-10 08:56:46'),
(15, 'ورزشی', NULL, 22, '2018-11-10 09:01:33', '2018-11-10 09:05:36'),
(16, 'خانوادگی', NULL, 21, '2018-11-10 09:03:39', '2018-11-10 09:03:39'),
(17, 'روستایی', NULL, 23, '2018-11-10 09:12:00', '2018-11-10 09:12:00'),
(18, 'زیارتی', NULL, 24, '2018-11-10 09:24:25', '2018-11-10 09:24:25'),
(19, 'ایران گردی', NULL, 25, '2018-11-10 09:31:18', '2018-11-10 09:31:18'),
(20, 'غذاشناسی', NULL, 36, '2018-11-10 09:38:43', '2018-11-21 08:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_header` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(1) NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar_id` int(11) DEFAULT NULL,
  `cover_id` int(11) DEFAULT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_clubs` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `name_header`, `email`, `password`, `activation_no`, `state_id`, `city_id`, `address`, `phone`, `gender`, `bio`, `avatar_id`, `cover_id`, `api_token`, `show_clubs`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'milad', 'milad', 'pastor_m1912@yahoo.com', '$2y$10$v1GRiNBYwNWYrdiEGfgCpOAgka3Jmrkk.E6toIHoELWwioEiHDnNa', NULL, 2, 2, NULL, NULL, 1, NULL, NULL, NULL, 'iGvP0upCbmxLMIJa8CYSpH7NhqSGNUr1TiC7FiTf76P5c4b7xMkdIif6KugD', 0, 'h0loAYy0BCj1QYUmh3orTwz9OqIzXID3TyzM887PtycEzcUICEhqs8y7uYvc', NULL, '2018-10-20 08:48:55', '2018-11-04 09:01:56'),
(3, 'tour_khodemoni', 'tour_khodemoni', 'tour_khodemoni@gmail.com', '$2y$10$LF.QtSdnq2/eiW9D2ILmIeOZv2.QTIbxeDGxK.8aODP7NT81Bzpfy', '5bd0657d031b7', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'OBZXdlpUVsrDSHo4eeQoc0AzGDQmDHCB9elOgeqp4D1JFBlazd5LKAggXQOR', 0, NULL, NULL, '2018-10-24 08:58:45', '2018-10-24 08:58:45'),
(4, 'tourhayeshad', 'tourhayeshad', 'tourhayeshad@gmail.com', '$2y$10$1/h1draX.VIT1HnxA3ZP2Ov91jeVom1E1rb6qA9EI1EQ9L2JRlo4m', '5bd0693e5a400', 1, 0, '1', NULL, 1, NULL, NULL, NULL, 'RhjZ1Azpos1tuMEg5jCDhKig5aYXHf4w5CnPaRUHZjIGXnsfWsLcK4HAZspa', 0, NULL, NULL, '2018-10-24 09:14:46', '2018-10-24 09:14:46'),
(5, 'eligasht', 'eligasht', 'eligasht@gmail.com', '$2y$10$G8LGQJGHGZK96P5dCeXrOeIpcjEROJ1WkRN1CLcppgYPGfLB3kvXm', '5bd59493a37ec', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'G2Bhf1s4Hsyz1IujpJOsDUWcFIYw77462pY2fe4MTGmv3fnfwjvZHUCKkTmn', 0, NULL, NULL, '2018-10-28 07:20:59', '2018-10-28 07:20:59'),
(7, 'mahmood', 'mahmood', 'mahmood@gmail.com', '$2y$10$fhh2DasNRSj2jZhHJf4U0OO.CDY4k/hFGmo4NZ282dRxfumwyEU9K', '5be40e9c632a6', 2, 2, NULL, NULL, 1, NULL, NULL, NULL, 'DfAHLJewBf5vKgxBfJomSpXqpElHsE4Ybhq4mqr80wqNp2myw1QLyl9nwR1K', 0, NULL, NULL, '2018-11-08 06:53:24', '2018-11-08 06:53:24'),
(8, 'afsane', 'afsane', 'afsane@gmail.com', '$2y$10$5mA6OAc1PjelO5GisTsYPe53VTWpJE6EhhVYW77SfQ0DT.fPZNXfe', '5be4100d69795', 2, 2, NULL, NULL, 0, NULL, NULL, NULL, 'uWmlAD5hO1NBNeOX5RXxOTfLWlWCbMS8l7xSUPCeNJpHYkaP5fHgShhqxeUi', 0, NULL, NULL, '2018-11-08 06:59:33', '2018-11-08 06:59:33'),
(9, 'mohammad', 'mohammad', 'Jalilvand.sb8789@gmail.com', '$2y$10$hs2.WjjCH6ts8nmEgmFU3.IoYAQP4MZ8Qr80dZ1woDo9Jl8EeX6k.', '5be413f7b4b29', 2, 2, NULL, NULL, 1, NULL, NULL, NULL, 'CaO8QqC4Q0ufwvFIcesD73Dh9nkfckLg1FDNYFmU2ctsbY32oypqH2Kn7UKy', 0, NULL, NULL, '2018-11-08 07:16:15', '2018-11-08 07:16:15'),
(10, 'marjan', 'marjan', 'marjan@gmail.com', '$2y$10$ceJqrGcVusMOeDVfgpyJaeV2h4PeT4hpgx6FIVRztUG7A9I3Ybl.K', '5be4176376b0b', 2, 2, NULL, NULL, 0, NULL, NULL, NULL, 'cnFfYp2832fnUXhZz4PYb9nqiFQjrqIlIZnO4eFk1BAlGHlWyxGjW2XB8lzQ', 0, NULL, NULL, '2018-11-08 07:30:51', '2018-11-08 07:30:51'),
(11, 'mahdi', 'mahdi', 'mahdi@gmail.com', '$2y$10$adeLtfJrBoH6OxK4TCA.hu8U/I0/hMibZQQ0HuvthE8LTe4HjBw6W', '5be6845c4b5e9', 2, 2, NULL, NULL, 1, NULL, NULL, NULL, 'HzdfbcDV13qkwe0p7PY7uqNH2KwCC9v5Cog1rSTFYGvE0xP0J3JyxV7dHjfk', 0, NULL, NULL, '2018-11-10 03:40:20', '2018-11-10 03:40:20'),
(12, 'gggg', 'gggg', 'h@t.com', '$2y$10$JeAu73FodE7jNbLEpHjhgulTy.b8OBGZPcplHtYSNgXUof8JHIdfe', '5be8102f8c7ac', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'mKFTkzVZBEc03QxZuyaQpEV0MR6uByQthVeDqhO4jCsTzn2OSfbwvB8JwOhA', 0, NULL, NULL, '2018-11-11 07:49:11', '2018-11-11 07:49:11'),
(13, 'darush', 'gggg', 'f@f.com', '$2y$10$3vBMxot2Vaaj/wn3F4nLH.ar7KoOa9o/jY8kCgRVszZrVO5h.DZAC', '5be93d3f40f01', 1, 1, NULL, NULL, 1, NULL, 27, NULL, 'ajpK0S1XhUYgfKaNbcR6Y623NPa4zR4GLENpbhp8LNjHsBiYcSK8PqgcXIUK', 0, NULL, NULL, '2018-11-12 05:13:43', '2018-11-12 05:13:45'),
(14, 'darushff', 'gggg', 'b@f.com', '$2y$10$RZs6r7/gZEwq.RLWqcd8VOlgDig4xFea9S1HReK3hJrtlEUqJeJ4C', '5be93db13d7f9', 1, 1, NULL, NULL, 1, NULL, 28, NULL, 'n8DkrcWQR52RjqdGUODLz71uJv5X5rFfSwhq7s3SvWCwEqamdMBMQu5wKKEk', 0, NULL, NULL, '2018-11-12 05:15:37', '2018-11-12 05:15:38'),
(15, 'saffff', 'gggg', 'cc@f.com', '$2y$10$ljysPb816OBq9PR60AiHRuqFohZEEUZ3Hb22JQNLGLPT5A.I7kRaK', '5be93f1c49571', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'qmWNnmY7drimqCz7BgGW2A5JComHSrw9BwnFLmPqryXsXQIKNOLJMOENYMeE', 0, NULL, NULL, '2018-11-12 05:21:40', '2018-11-12 05:21:40'),
(16, 'saffffggg', 'gggg', 'ccg@f.com', '$2y$10$FwVRYZkTl1nwZNJ.SNsiXe0nguaTvQiblFL06CMQL9RxnmkzioWBi', '5be93f78b72b5', 1, 1, NULL, NULL, 1, NULL, 29, NULL, 'JzG3LZZJhfUlMFlIxaiHWm41om110JwzB6SMAT9wTQXNLFp6CwGQgWaCJagc', 0, NULL, NULL, '2018-11-12 05:23:12', '2018-11-12 05:23:19'),
(17, 'saffffgggjjjj', 'gggg', 'cchhhg@f.com', '$2y$10$Se/KgzBwIpuVvPdwd3IUpOXMPeB6ud9U4WgLQz/ap6E7L68nYXedi', '5be93fb972343', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'xkxfyP8JbU1B5O974XLJRB6SzrDrOYsLVYAu0FkCySvtsMiNuHtyymfmUDU4', 0, NULL, NULL, '2018-11-12 05:24:17', '2018-11-12 05:24:17'),
(18, 'saffffgggjjjjttttt', 'gggg', 'cchhhttttg@f.com', '$2y$10$iuJgnsvzkyxPcPZcsY3rxuk5DBKSYa3gSgjogI07xSr1p91NNhmH6', '5be93ff66e69a', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, 'wM2qatiFIUCBQijTOrc3f4HKWy1wlj7cVa8ypfpwAYAlZFOeCDpJye3PZBfs', 0, NULL, NULL, '2018-11-12 05:25:18', '2018-11-12 05:25:18'),
(19, 'saffffgggjjjjtttttkkkk', 'gggg', 'cchhhtttkkkktg@f.com', '$2y$10$2MMDwVYWzYf1HGgAsISCTOpml.CWJ9oQR80YyNbxi0dzY/5dvwNUa', '5be9402f83b6b', 1, 1, NULL, NULL, 1, NULL, NULL, NULL, '5BvlWuVsTGD7JB2LK1EunKMtPzy01pNW5LAiuVfJkW1YrVGopYCHPenmhTVO', 0, NULL, NULL, '2018-11-12 05:26:15', '2018-11-12 05:26:15'),
(20, 'q', 'gggg', 'q@f.com', '$2y$10$OZZ5Bb9gPxhpKiVa.QoCserxWj/pSvBWTc3J0jmfH9LqU/LZBTUJO', '5be9404fc7679', 1, 1, NULL, NULL, 1, NULL, 30, NULL, 'UXnbPh0ViigVY3K73uDbdpTo80UYjeMfNOElM1WMBDKOSb7txYJvoWmiU79J', 0, NULL, NULL, '2018-11-12 05:26:47', '2018-11-12 05:26:49'),
(21, 'hanita', 'sssss', 'shahi.alamir@gmail.com', '$2y$10$mTVW6gYT7Ub.jR2nWOJ5n.Q3t4YwEV8Tce61Dtn.6/9kp41fwDbOu', '', 2, 2, NULL, NULL, 1, NULL, 33, NULL, '4Ti7R7IlPxjIqPyzBR7w8LXg9gaGFYtkIhr2BR1cEHubQ946gqhnfHFU93TH', 0, '967JZtlWf7RqwJYCEX94bKdoADKRDIpIfoQp22hMwyqTl1G3znyRa0qkqups', NULL, '2018-11-18 05:45:32', '2018-11-21 06:51:01');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vote` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatgroupmembers`
--
ALTER TABLE `chatgroupmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatgroups`
--
ALTER TABLE `chatgroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chatgroups_name_unique` (`name`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checklists`
--
ALTER TABLE `checklists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eventphotos`
--
ALTER TABLE `eventphotos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_activation_no_unique` (`activation_no`);

--
-- Indexes for table `event_gang`
--
ALTER TABLE `event_gang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_type`
--
ALTER TABLE `event_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_user`
--
ALTER TABLE `event_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_vote`
--
ALTER TABLE `event_vote`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gangs`
--
ALTER TABLE `gangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gangs_name_unique` (`name`);

--
-- Indexes for table `gmaps_geocache`
--
ALTER TABLE `gmaps_geocache`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messagephotos`
--
ALTER TABLE `messagephotos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postmedia`
--
ALTER TABLE `postmedia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requesthandelings`
--
ALTER TABLE `requesthandelings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketavatars`
--
ALTER TABLE `ticketavatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketmanager`
--
ALTER TABLE `ticketmanager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_activation_num_unique` (`activation_num`);

--
-- Indexes for table `ticket_user`
--
ALTER TABLE `ticket_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timelines`
--
ALTER TABLE `timelines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeline_photos`
--
ALTER TABLE `timeline_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `touremanagers`
--
ALTER TABLE `touremanagers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `types_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_name_unique` (`name`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`),
  ADD UNIQUE KEY `users_activation_no_unique` (`activation_no`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bans`
--
ALTER TABLE `bans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatgroupmembers`
--
ALTER TABLE `chatgroupmembers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatgroups`
--
ALTER TABLE `chatgroups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `checklists`
--
ALTER TABLE `checklists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventphotos`
--
ALTER TABLE `eventphotos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `event_gang`
--
ALTER TABLE `event_gang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `event_type`
--
ALTER TABLE `event_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `event_user`
--
ALTER TABLE `event_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_vote`
--
ALTER TABLE `event_vote`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gangs`
--
ALTER TABLE `gangs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gmaps_geocache`
--
ALTER TABLE `gmaps_geocache`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messagephotos`
--
ALTER TABLE `messagephotos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `postmedia`
--
ALTER TABLE `postmedia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requesthandelings`
--
ALTER TABLE `requesthandelings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ticketavatars`
--
ALTER TABLE `ticketavatars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ticketmanager`
--
ALTER TABLE `ticketmanager`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ticket_user`
--
ALTER TABLE `ticket_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timelines`
--
ALTER TABLE `timelines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeline_photos`
--
ALTER TABLE `timeline_photos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `touremanagers`
--
ALTER TABLE `touremanagers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
