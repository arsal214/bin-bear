-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 31, 2025 at 06:56 PM
-- Server version: 8.0.41-32
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbhzm0dkh0fyoz`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint UNSIGNED NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','DeActive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `body`, `image`, `is_featured`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Blog 1', '<p>Hey World</p>', '202506030920ChatGPT Image May 31, 2025, 03_27_13 PM.png', 'Yes', 'Active', '2025-06-03 04:20:48', '2025-06-03 04:21:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `service_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_option` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_pickup_truck_load` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `half_pickup_truck_load` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `units` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dumpster_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_popular` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `price`, `is_popular`, `status`, `image`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Cat 1', '12', 'Yes', 1, '202506030914ChatGPT Image May 31, 2025, 03_42_40 PM.png', NULL, '2025-06-03 04:14:40', '2025-06-03 04:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `company_jobber_tokens`
--

CREATE TABLE `company_jobber_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BinBear Junk Removal',
  `company_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contact@binbears.com',
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('Fixed','Percentage') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_value` int DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_till` date DEFAULT NULL,
  `maximum_usage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','DeActive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobber_clients`
--

CREATE TABLE `jobber_clients` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jobber_client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobber_clients`
--

INSERT INTO `jobber_clients` (`id`, `user_id`, `jobber_client_id`, `first_name`, `last_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(3, 3, 'Z2lkOi8vSm9iYmVyL0NsaWVudC8xMDk3NDAwNjg=', 'Test', 'Client', 'test.client@example.com', NULL, '2025-06-03 11:29:14', '2025-06-03 11:29:14'),
(4, 4, 'Z2lkOi8vSm9iYmVyL0NsaWVudC8xMTA4NzE1MjU=', 'Laravel', '4', 'laravelpractice4@gmail.com', NULL, '2025-06-17 00:26:24', '2025-06-17 00:26:24'),
(10, 7, 'Z2lkOi8vSm9iYmVyL0NsaWVudC8xMTM3NzIxMTk=', 'John', 'Smith', 'john@example.com', NULL, '2025-07-18 00:56:34', '2025-07-18 00:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `jobber_invoices`
--

CREATE TABLE `jobber_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jobber_invoice_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jobber_job_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` int NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `payment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `public_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_amount` int DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobber_jobs`
--

CREATE TABLE `jobber_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jobber_job_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jobber_client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobber_o_auth_tokens`
--

CREATE TABLE `jobber_o_auth_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobber_o_auth_tokens`
--

INSERT INTO `jobber_o_auth_tokens` (`id`, `user_id`, `access_token`, `refresh_token`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 3, 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMxODYzNjAsImlzcyI6Imh0dHBzOi8vYXBpLmdldGpvYmJlci5jb20iLCJjbGllbnRfaWQiOiIyZGMzMDc5Yy04YTNkLTQ4MTAtYTk1Yy00Njc4Y2M2N2U0ODYiLCJzY29wZSI6InJlYWRfY2xpZW50cyB3cml0ZV9jbGllbnRzIHJlYWRfcmVxdWVzdHMgd3JpdGVfcmVxdWVzdHMgcmVhZF9xdW90ZXMgd3JpdGVfcXVvdGVzIHJlYWRfam9icyB3cml0ZV9qb2JzIHJlYWRfc2NoZWR1bGVkX2l0ZW1zIHdyaXRlX3NjaGVkdWxlZF9pdGVtcyByZWFkX2ludm9pY2VzIHdyaXRlX2ludm9pY2VzIHJlYWRfam9iYmVyX3BheW1lbnRzIHJlYWRfdXNlcnMgd3JpdGVfdXNlcnMgd3JpdGVfdGF4X3JhdGVzIHJlYWRfZXhwZW5zZXMgd3JpdGVfZXhwZW5zZXMgcmVhZF9jdXN0b21fZmllbGRfY29uZmlndXJhdGlvbnMgd3JpdGVfY3VzdG9tX2ZpZWxkX2NvbmZpZ3VyYXRpb25zIHJlYWRfdGltZV9zaGVldHMgcmVhZF9lcXVpcG1lbnQgd3JpdGVfZXF1aXBtZW50IiwiYXBwX2lkIjoiMmRjMzA3OWMtOGEzZC00ODEwLWE5NWMtNDY3OGNjNjdlNDg2IiwidXNlcl9pZCI6MzE4NjM2MCwiYWNjb3VudF9pZCI6MTgzNTQzMSwiZXhwIjoxNzUwMTQxMzI1fQ.IdHWrbL2uR6fOqEhuU3E44oHWjZOfl0If9v-KqxkvBc', '027fada62cbb647f2ed2918f831e72cc', '2025-06-17 00:22:03', '2025-06-03 12:29:08', '2025-06-17 00:22:03'),
(2, 4, 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMxODYzNjAsImlzcyI6Imh0dHBzOi8vYXBpLmdldGpvYmJlci5jb20iLCJjbGllbnRfaWQiOiIyZGMzMDc5Yy04YTNkLTQ4MTAtYTk1Yy00Njc4Y2M2N2U0ODYiLCJzY29wZSI6InJlYWRfY2xpZW50cyB3cml0ZV9jbGllbnRzIHJlYWRfcmVxdWVzdHMgd3JpdGVfcmVxdWVzdHMgcmVhZF9xdW90ZXMgd3JpdGVfcXVvdGVzIHJlYWRfam9icyB3cml0ZV9qb2JzIHJlYWRfc2NoZWR1bGVkX2l0ZW1zIHdyaXRlX3NjaGVkdWxlZF9pdGVtcyByZWFkX2ludm9pY2VzIHdyaXRlX2ludm9pY2VzIHJlYWRfam9iYmVyX3BheW1lbnRzIHJlYWRfdXNlcnMgd3JpdGVfdXNlcnMgd3JpdGVfdGF4X3JhdGVzIHJlYWRfZXhwZW5zZXMgd3JpdGVfZXhwZW5zZXMgcmVhZF9jdXN0b21fZmllbGRfY29uZmlndXJhdGlvbnMgd3JpdGVfY3VzdG9tX2ZpZWxkX2NvbmZpZ3VyYXRpb25zIHJlYWRfdGltZV9zaGVldHMgcmVhZF9lcXVpcG1lbnQgd3JpdGVfZXF1aXBtZW50IiwiYXBwX2lkIjoiMmRjMzA3OWMtOGEzZC00ODEwLWE5NWMtNDY3OGNjNjdlNDg2IiwidXNlcl9pZCI6MzE4NjM2MCwiYWNjb3VudF9pZCI6MTgzNTQzMSwiZXhwIjoxNzUxMTA4NzU1fQ.Dxxw3KFs6ePTcl7We0UtMrq77eywI1Uc1rXDYqLdZEo', '0106bab23ddcbb9c0a77a3a2c366003e', '2025-06-28 05:05:55', '2025-06-17 00:26:12', '2025-06-28 05:05:55'),
(3, 5, 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMyNjIzNjksImlzcyI6Imh0dHBzOi8vYXBpLmdldGpvYmJlci5jb20iLCJjbGllbnRfaWQiOiI0YmJmOTJjNC0xNWZlLTQwODUtYTJjNi1jYWU1NDM0NzdiZjUiLCJzY29wZSI6InJlYWRfY2xpZW50cyB3cml0ZV9jbGllbnRzIHJlYWRfcmVxdWVzdHMgd3JpdGVfcmVxdWVzdHMgcmVhZF9xdW90ZXMgd3JpdGVfcXVvdGVzIHJlYWRfam9icyB3cml0ZV9qb2JzIHJlYWRfc2NoZWR1bGVkX2l0ZW1zIHdyaXRlX3NjaGVkdWxlZF9pdGVtcyByZWFkX2ludm9pY2VzIHdyaXRlX2ludm9pY2VzIHJlYWRfam9iYmVyX3BheW1lbnRzIHJlYWRfdXNlcnMgd3JpdGVfdXNlcnMgd3JpdGVfdGF4X3JhdGVzIHJlYWRfZXhwZW5zZXMgd3JpdGVfZXhwZW5zZXMgcmVhZF9jdXN0b21fZmllbGRfY29uZmlndXJhdGlvbnMgd3JpdGVfY3VzdG9tX2ZpZWxkX2NvbmZpZ3VyYXRpb25zIHJlYWRfdGltZV9zaGVldHMgcmVhZF9lcXVpcG1lbnQgd3JpdGVfZXF1aXBtZW50IiwiYXBwX2lkIjoiNGJiZjkyYzQtMTVmZS00MDg1LWEyYzYtY2FlNTQzNDc3YmY1IiwidXNlcl9pZCI6MzI2MjM2OSwiYWNjb3VudF9pZCI6MTg4NzI1NiwiZXhwIjoxNzUyNzg0MzM0fQ.x4JVZWuR9mG1rezSg6QOYieMYlQMFM6TEUmBWi2x_vY', 'b4c127a8db07d1c9451204092db858ba', '2025-07-17 14:32:13', '2025-07-17 14:17:19', '2025-07-17 14:32:13'),
(5, 7, 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMyNjIzNjksImlzcyI6Imh0dHBzOi8vYXBpLmdldGpvYmJlci5jb20iLCJjbGllbnRfaWQiOiI0YmJmOTJjNC0xNWZlLTQwODUtYTJjNi1jYWU1NDM0NzdiZjUiLCJzY29wZSI6InJlYWRfY2xpZW50cyB3cml0ZV9jbGllbnRzIHJlYWRfcmVxdWVzdHMgd3JpdGVfcmVxdWVzdHMgcmVhZF9xdW90ZXMgd3JpdGVfcXVvdGVzIHJlYWRfam9icyB3cml0ZV9qb2JzIHJlYWRfc2NoZWR1bGVkX2l0ZW1zIHdyaXRlX3NjaGVkdWxlZF9pdGVtcyByZWFkX2ludm9pY2VzIHdyaXRlX2ludm9pY2VzIHJlYWRfam9iYmVyX3BheW1lbnRzIHJlYWRfdXNlcnMgd3JpdGVfdXNlcnMgd3JpdGVfdGF4X3JhdGVzIHJlYWRfZXhwZW5zZXMgd3JpdGVfZXhwZW5zZXMgcmVhZF9jdXN0b21fZmllbGRfY29uZmlndXJhdGlvbnMgd3JpdGVfY3VzdG9tX2ZpZWxkX2NvbmZpZ3VyYXRpb25zIHJlYWRfdGltZV9zaGVldHMgcmVhZF9lcXVpcG1lbnQgd3JpdGVfZXF1aXBtZW50IiwiYXBwX2lkIjoiNGJiZjkyYzQtMTVmZS00MDg1LWEyYzYtY2FlNTQzNDc3YmY1IiwidXNlcl9pZCI6MzI2MjM2OSwiYWNjb3VudF9pZCI6MTg4NzI1NiwiZXhwIjoxNzUyODIxOTczfQ.NLy2s1S05JeHXG_k5vOWWiX4lW5jZLNvvolOCvUEFPA', '4b7c6609efbbb5739f5894decd8c16e7', '2025-07-18 00:59:31', '2025-07-17 15:07:07', '2025-07-18 00:59:31');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_01_03_001_create_audits_table', 1),
(6, '2024_02_15_110014_create_settings_table', 1),
(7, '2024_08_19_104243_create_permission_tables', 1),
(8, '2024_08_28_111048_create_categories_table', 1),
(9, '2024_09_23_100541_create_blogs_table', 1),
(10, '2024_12_10_194122_create_jobs_table', 1),
(11, '2024_12_27_111216_create_password_resets_table', 1),
(12, '2025_02_25_111116_create_coupons_table', 1),
(13, '2025_02_26_101056_create_stripe_payments_table', 1),
(14, '2025_04_11_131934_create_zip_codes_table', 1),
(15, '2025_05_03_133129_create_bookings_table', 1),
(16, '2025_05_03_134624_create_booking_details_table', 1),
(17, '2025_06_03_151025_create_jobber_clients_table', 2),
(18, '2025_06_03_164020_create_jobber_o_auth_tokens_table', 3),
(19, '2025_06_03_164021_create_jobber_jobs_table', 4),
(22, '2025_06_18_194639_create_jobber_invoices_table', 5),
(23, '2025_07_18_000001_create_company_jobber_tokens_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'roles-list', 'web', 'Admin Role List', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(2, 'roles-create', 'web', 'Admin Role Create', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(3, 'roles-view', 'web', 'Admin Role View', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(4, 'roles-edit', 'web', 'Admin Role Edit', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(5, 'roles-delete', 'web', 'Admin Role Delete', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(6, 'permissions-list', 'web', 'Admin Permission List', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(7, 'permissions-edit', 'web', 'Admin Permission Edit', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(8, 'dashboard-view', 'web', 'Dashboard View', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(9, 'users-list', 'web', 'User List', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(10, 'users-view', 'web', 'User View', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(11, 'users-create', 'web', 'User Create', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(12, 'users-edit', 'web', 'User Edit', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(13, 'users-delete', 'web', 'User Delete', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(14, 'users-profile', 'web', 'User Profile', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(15, 'blogs-list', 'web', 'blogs List', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(16, 'blogs-create', 'web', 'blogs Create', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(17, 'blogs-edit', 'web', 'blogs Edit', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(18, 'blogs-delete', 'web', 'blogs Delete', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(19, 'coupons-list', 'web', 'coupons List', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(20, 'coupons-create', 'web', 'coupons  Create', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(21, 'coupons-edit', 'web', 'coupons Edit', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(22, 'coupons-delete', 'web', 'coupons Delete', '2025-06-03 04:09:18', '2025-06-03 04:09:18');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(17, 'App\\Models\\User', 7, 'API Token of contact@binbears.com', 'cb2a471eb6252dd9ccf5beae178be1e33260ad9fb0a16fdb88697fcd8fd02da4', '[\"*\"]', '2025-07-18 01:15:42', NULL, '2025-07-17 15:30:46', '2025-07-18 01:15:42'),
(18, 'App\\Models\\User', 4, 'API Token of laravelpractice4@gmail.com', 'c9b3bef077005766d2e14169fe67651321f6b9b1e5facdd2bdff7b7dd8116309', '[\"*\"]', '2025-07-18 01:19:19', NULL, '2025-07-18 01:18:23', '2025-07-18 01:19:19'),
(19, 'App\\Models\\User', 4, 'API Token of laravelpractice4@gmail.com', '0c74113228110bf6244d5f8c815ab8774335586bef32c017f51886d8e94e4fd1', '[\"*\"]', '2025-07-18 12:43:18', NULL, '2025-07-18 12:42:04', '2025-07-18 12:43:18'),
(20, 'App\\Models\\User', 7, 'API Token of contact@binbears.com', '982b2946bbb8c55451c478761bd6bcf3fb014840f3c5f1f765601928bf3343ee', '[\"*\"]', '2025-07-18 12:49:33', NULL, '2025-07-18 12:49:10', '2025-07-18 12:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2025-06-03 04:09:18', '2025-06-03 04:09:18'),
(2, 'Admin', 'web', '2025-06-03 04:09:18', '2025-06-03 04:09:18');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_payments`
--

CREATE TABLE `stripe_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` bigint UNSIGNED DEFAULT NULL,
  `customer_email` bigint UNSIGNED DEFAULT NULL,
  `stripe_customer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_4_digit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_exp_month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_exp_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` int NOT NULL DEFAULT '0',
  `type` enum('Web','Admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` enum('Pending','Accepted','Approved','DeActive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Approved',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `image`, `email`, `email_verified_at`, `password`, `is_admin`, `type`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'Admin', '202506030913modified_background.png', 'superadmin@gmail.com', NULL, '$2y$12$9UBa42XdaihUepfuv/rUc.IRIiL.EW1/hyf6Q1HgRotkTjvwRT/gC', 1, 'Admin', 'Approved', NULL, '2025-06-03 04:09:19', '2025-06-03 04:13:39'),
(2, 'Admin', 'Admin', NULL, 'admin@gmail.com', NULL, '$2y$12$G4Dpe/Bbd3dE2UuaBGEYm.gp0jGj19ixbiXc6MxFulH3mEXSUD6ii', 1, 'Admin', 'Approved', NULL, '2025-06-03 04:09:19', '2025-06-03 04:09:19'),
(3, 'User1', 'normal', NULL, 'user@gmail.com', NULL, '$2y$12$G4Dpe/Bbd3dE2UuaBGEYm.gp0jGj19ixbiXc6MxFulH3mEXSUD6ii', 0, 'Web', 'Approved', NULL, NULL, NULL),
(4, 'Laravel', 'Practice', NULL, 'laravelpractice4@gmail.com', NULL, '$2y$12$G4Dpe/Bbd3dE2UuaBGEYm.gp0jGj19ixbiXc6MxFulH3mEXSUD6ii', 0, NULL, 'Approved', NULL, NULL, NULL),
(5, 'Ara', 'Solutions', NULL, 'aracustomsolutions@gmail.com', NULL, '$2y$12$G4Dpe/Bbd3dE2UuaBGEYm.gp0jGj19ixbiXc6MxFulH3mEXSUD6ii', 0, 'Web', 'Approved', NULL, NULL, NULL),
(7, 'BinBear', 'Admin', NULL, 'contact@binbears.com', NULL, '$2y$12$wDwemcwQA01mPHiqNQEGWON2ztwmIMKfF4lI8lubIN1c0KXqHfNLG', 0, 'Web', 'Approved', NULL, '2025-07-17 15:04:00', '2025-07-17 15:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `zip_codes`
--

CREATE TABLE `zip_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `city_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','DeActive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audits_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_details_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_details_category_id_foreign` (`category_id`),
  ADD KEY `booking_details_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `company_jobber_tokens`
--
ALTER TABLE `company_jobber_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_jobber_tokens_is_active_expires_at_index` (`is_active`,`expires_at`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobber_clients`
--
ALTER TABLE `jobber_clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobber_clients_jobber_client_id_unique` (`jobber_client_id`),
  ADD KEY `jobber_clients_user_id_index` (`user_id`),
  ADD KEY `jobber_clients_jobber_client_id_index` (`jobber_client_id`);

--
-- Indexes for table `jobber_invoices`
--
ALTER TABLE `jobber_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobber_invoices_jobber_invoice_id_unique` (`jobber_invoice_id`),
  ADD KEY `jobber_invoices_user_id_status_index` (`user_id`,`status`),
  ADD KEY `jobber_invoices_jobber_invoice_id_index` (`jobber_invoice_id`);

--
-- Indexes for table `jobber_jobs`
--
ALTER TABLE `jobber_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobber_jobs_jobber_job_id_unique` (`jobber_job_id`),
  ADD KEY `jobber_jobs_user_id_index` (`user_id`),
  ADD KEY `jobber_jobs_jobber_job_id_index` (`jobber_job_id`),
  ADD KEY `jobber_jobs_jobber_client_id_index` (`jobber_client_id`),
  ADD KEY `jobber_jobs_status_index` (`status`);

--
-- Indexes for table `jobber_o_auth_tokens`
--
ALTER TABLE `jobber_o_auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobber_o_auth_tokens_user_id_index` (`user_id`),
  ADD KEY `jobber_o_auth_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stripe_payments_user_id_foreign` (`user_id`),
  ADD KEY `stripe_payments_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `zip_codes`
--
ALTER TABLE `zip_codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_jobber_tokens`
--
ALTER TABLE `company_jobber_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobber_clients`
--
ALTER TABLE `jobber_clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobber_invoices`
--
ALTER TABLE `jobber_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobber_jobs`
--
ALTER TABLE `jobber_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobber_o_auth_tokens`
--
ALTER TABLE `jobber_o_auth_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `zip_codes`
--
ALTER TABLE `zip_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_details_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_details_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobber_clients`
--
ALTER TABLE `jobber_clients`
  ADD CONSTRAINT `jobber_clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobber_invoices`
--
ALTER TABLE `jobber_invoices`
  ADD CONSTRAINT `jobber_invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobber_jobs`
--
ALTER TABLE `jobber_jobs`
  ADD CONSTRAINT `jobber_jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobber_o_auth_tokens`
--
ALTER TABLE `jobber_o_auth_tokens`
  ADD CONSTRAINT `jobber_o_auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  ADD CONSTRAINT `stripe_payments_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stripe_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
