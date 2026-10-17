-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2026 at 07:23 AM
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
-- Database: `promon`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('promon-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1792166043),
('promon-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1792166043;', 1792166043);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image_path`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Supplements', 'supplements', NULL, 1, 1, '2026-10-15 10:56:44', '2026-10-15 10:56:44'),
(2, 'Medicine', 'medicine', NULL, 2, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(3, 'Baby Food & Milk', 'baby-food-milk', NULL, 3, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(4, 'Grocery', 'grocery', NULL, 4, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(5, 'Nappies', 'nappies', NULL, 5, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(6, 'Chocolate', 'chocolate', NULL, 6, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(7, 'Gift Items', 'gifts', NULL, 7, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(8, 'Health Products', 'health-products', NULL, 8, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(9, 'Beauty Products', 'beauty', NULL, 9, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(10, 'Shoes & Bags', 'shoes-bags', NULL, 10, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(11, 'Vitamins & Minerals', 'vitamins', NULL, 11, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(12, 'Fish Oil & Omega', 'fish-oil-omega', NULL, 12, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(13, 'Sports Nutrition & Protein', 'sports-nutrition', NULL, 13, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(14, 'Herbal Remedies', 'herbal-remedies', NULL, 14, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(15, 'Cold & Flu', 'cold-flu', NULL, 15, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(16, 'Pain Relief', 'pain-relief', NULL, 16, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(17, 'First Aid', 'first-aid', NULL, 17, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(18, 'Digestive Health', 'digestive-health', NULL, 18, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(19, 'Eye & Ear Care', 'eye-ear-care', NULL, 19, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(20, 'Oral Care', 'oral-care', NULL, 20, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(21, 'Baby Formula', 'baby-formula', NULL, 21, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(22, 'Baby Toiletries', 'baby-toiletries', NULL, 22, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(23, 'Feeding & Weaning', 'baby-feeding', NULL, 23, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(24, 'Baby Clothes', 'baby-clothes', NULL, 24, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(25, 'Toys', 'toys', NULL, 25, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(26, 'Skincare', 'skincare', NULL, 26, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(27, 'Makeup & Cosmetics', 'makeup', NULL, 27, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(28, 'Hair Care', 'hair-care', NULL, 28, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(29, 'Body Care', 'body-care', NULL, 29, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(30, 'Sun & Holiday', 'sun-holiday', NULL, 30, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(31, 'Perfumes & Fragrance', 'fragrance', NULL, 31, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(32, 'Men\'s Grooming', 'mens-grooming', NULL, 32, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(33, 'Women\'s Health', 'womens-health', NULL, 33, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(34, 'Feminine Hygiene', 'feminine-hygiene', NULL, 34, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(35, 'Toiletries', 'toiletries', NULL, 35, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(36, 'Personal Care & Hygiene', 'personal-care', NULL, 36, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(37, 'Biscuits & Snacks', 'biscuits-snacks', NULL, 37, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(38, 'Tea & Coffee', 'tea-coffee', NULL, 38, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(39, 'Sweets & Confectionery', 'sweets', NULL, 39, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(40, 'Breakfast & Cereals', 'breakfast-cereals', NULL, 40, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(41, 'Sauces & Spices', 'sauces-spices', NULL, 41, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(42, 'Drinks & Squash', 'drinks', NULL, 42, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(43, 'Clothing', 'clothing', NULL, 43, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(44, 'Men\'s Clothing', 'mens-clothing', NULL, 44, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(45, 'Women\'s Clothing', 'womens-clothing', NULL, 45, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(46, 'Kids\' Clothing', 'kids-clothing', NULL, 46, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(47, 'Shoes', 'shoes', NULL, 47, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(48, 'Bags & Luggage', 'bags-luggage', NULL, 48, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(49, 'Watches & Jewellery', 'watches-jewellery', NULL, 49, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(50, 'Fashion Accessories', 'fashion-accessories', NULL, 50, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(51, 'Electricals', 'electricals', NULL, 51, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(52, 'Phones & Accessories', 'phones-accessories', NULL, 52, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(53, 'Small Appliances', 'small-appliances', NULL, 53, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(54, 'Shavers & Hair Tools', 'shavers-hair-tools', NULL, 54, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(55, 'Home & Household', 'home-household', NULL, 55, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(56, 'Kitchenware', 'kitchenware', NULL, 56, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(57, 'Cleaning Products', 'cleaning', NULL, 57, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(58, 'Laundry & Detergents', 'laundry', NULL, 58, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(59, 'Stationery', 'stationery', NULL, 59, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(60, 'Pet Care', 'pet-care', NULL, 60, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(61, 'Other UK Products', 'other-uk-products', NULL, 61, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `category_enquiry`
--

CREATE TABLE `category_enquiry` (
  `enquiry_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_enquiry`
--

INSERT INTO `category_enquiry` (`enquiry_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_name` varchar(160) DEFAULT NULL,
  `contact_name` varchar(120) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(80) DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'retailer',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `business_name`, `contact_name`, `email`, `phone`, `whatsapp`, `city`, `country`, `type`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Monarchy Solutions', 'ALAMIN HOSSAIN', 'contact@monarchysolutions.com', NULL, '+8801644941234', 'Dhaka', 'Bangladesh', 'retailer', NULL, '2026-10-15 11:25:21', '2026-10-15 11:25:21');

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(30) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `channel` varchar(20) NOT NULL DEFAULT 'form',
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `message` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enquiries`
--

INSERT INTO `enquiries` (`id`, `reference`, `customer_id`, `handled_by`, `channel`, `status`, `message`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'ENQ-261015-AJ2UD', 1, NULL, 'form', 'new', NULL, NULL, '2026-10-15 11:25:21', '2026-10-15 11:25:21');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'What currency are your prices in?', 'Every price is in pounds sterling (£ GBP).', 1, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(2, 'Who can order from PROMON LTD?', 'We welcome wholesalers and retailers.', 2, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(3, 'How do I find a product category?', 'Open the Products page and use the category buttons above the products. You can also choose a category from the home page.', 3, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(4, 'How do I place an order?', 'This website has no online checkout. Message us on WhatsApp or through the contact page with the products and quantities you need.', 4, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(5, 'Can I place an advance order?', 'Yes, advance orders are available. Message us with the products you need.', 5, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(6, 'How long does delivery take?', 'Products are sent from the UK by air cargo. Delivery time: 10 working days.', 6, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(7, 'What are the delivery charges, customs and import duty?', '[Add delivery charges and the customs and import duty details.]', 7, 0, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(8, 'What is your returns policy?', '[Add the returns window, condition requirements and how to start a return.]', 8, 0, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(9, 'Which payment methods do you accept?', '[Add the accepted payment methods.]', 9, 0, '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(10, 'How can I contact you?', 'Use the WhatsApp number on this site, or send an enquiry from the Enquiry list page.', 10, 1, '2026-10-15 10:56:45', '2026-10-15 10:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_15_000001_create_categories_table', 1),
(5, '2026_10_15_000006_create_customers_table', 1),
(6, '2026_10_15_000007_create_enquiries_table', 1),
(7, '2026_10_15_000009_create_settings_table', 1),
(8, '2026_10_15_000010_create_faqs_table', 1),
(9, '2026_10_15_000011_create_category_enquiry_table', 1),
(10, '2026_10_16_000001_create_reviews_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_name` varchar(120) NOT NULL,
  `reviewer_title` varchar(160) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `body` text NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewer_name`, `reviewer_title`, `rating`, `body`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Rahim Uddin', 'Retail shop owner, Dhaka', 5, 'We order baby food and groceries every month. The products are genuine and the parcel reached Dhaka in the time they promised.', 1, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(2, 'Nusrat Jahan', 'Online seller, Chattogram', 5, 'I sell UK skincare on my page and my customers trust the products because they are original. The team answers every message on WhatsApp.', 2, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(3, 'Md. Kamal Hossain', 'Wholesale buyer, Uttara, Dhaka', 4, 'Good wholesale support. They sent the product list and quantities clearly, and the packing was neat and safe.', 3, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(4, 'Farzana Akter', 'Personal customer, Sylhet', 5, 'I asked for a UK product that I could not find anywhere in Bangladesh. They sourced it for me and kept me updated until it arrived.', 4, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(5, 'Tanvir Ahmed', 'Pharmacy owner, Dhaka', 5, 'Fast replies and very clear about the import rules for healthcare products. I felt comfortable ordering with them.', 5, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(6, 'Sabrina Rahman', 'Gift buyer, Manchester, UK', 5, 'I send gifts to my family in Bangladesh every Eid. Ordering is simple and my family always receives everything in good condition.', 6, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(7, 'Imran Hasan', 'Reseller, Khulna', 4, 'Genuine products and honest communication. Delivery depends on customs, but they told me this from the start.', 7, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(8, 'Mahmuda Begum', 'Boutique owner, Rajshahi', 5, 'I bought bags and fashion accessories for my boutique. The quality matched the photos and my customers were happy.', 8, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(9, 'Shafiq Alam', 'Distributor, Cumilla', 4, 'The pre-order service is useful. I sent the product name and they arranged it from the UK without any confusion.', 9, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(10, 'Ayesha Siddiqua', 'Personal customer, Dhaka', 5, 'Friendly team and easy process. I send an enquiry, they reply with the details, and the order arrives well packed.', 10, 1, '2026-10-16 04:09:01', '2026-10-16 04:09:01'),
(11, 'ALAMIN HOSSAIN', 'Dhaka', 5, 'sefges ajfhgajh', 11, 0, '2026-10-16 09:44:27', '2026-10-16 09:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('alliLqAqCEpwW9dy7fNX1yqZrz55utiMdR0lbnBR', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 16; Pixel 10) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEhYSVhhc1RjSVFpUjFFYzNLWFZDeEowVDFtT0VFekVoT01nYkFWUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJzaXRlLmhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792166394),
('IO78SRAHQV1lWZIavPNl5nMfwajK8M5VzEPsRWFE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemd3dlBIaTI5MFF4VHRJdUJSTzFyMXY1WXk0a1NHbWtvd01LQWlmNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjEyOiJzaXRlLmNvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792213761);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(80) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'PROMON LTD', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(2, 'tagline', 'Bringing You the Best', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(3, 'whatsapp', '+447405124730', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(4, 'delivery_time', '10 working days', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(5, 'currency', 'GBP', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(6, 'email', 'promonltd@gmail.com', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(7, 'phone', '', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(8, 'address', '1A Wilmot Street, Bolton, Manchester, BL1 3LL, United Kingdom', '2026-10-15 10:56:45', '2026-10-15 10:56:45'),
(9, 'working_hours', '', '2026-10-15 10:56:45', '2026-10-15 10:56:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `is_active`, `email_verified_at`, `password`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'sadmin@promonltd.com', NULL, 'super_admin', 1, '2026-10-15 10:56:43', '$2y$12$xC77roDo/TVzrIjaV627GuyZWZML9Qaf4gEoy9tqCv0p2HsvG2qzi', '2026-10-16 04:18:15', NULL, '2026-10-15 10:56:43', '2026-10-16 04:18:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_is_active_sort_order_index` (`is_active`,`sort_order`);

--
-- Indexes for table `category_enquiry`
--
ALTER TABLE `category_enquiry`
  ADD PRIMARY KEY (`enquiry_id`,`category_id`),
  ADD KEY `category_enquiry_category_id_foreign` (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_email_index` (`email`),
  ADD KEY `customers_phone_index` (`phone`),
  ADD KEY `customers_whatsapp_index` (`whatsapp`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enquiries_reference_unique` (`reference`),
  ADD KEY `enquiries_customer_id_foreign` (`customer_id`),
  ADD KEY `enquiries_handled_by_foreign` (`handled_by`),
  ADD KEY `enquiries_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_is_active_sort_order_index` (`is_active`,`sort_order`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_is_active_sort_order_index` (`is_active`,`sort_order`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category_enquiry`
--
ALTER TABLE `category_enquiry`
  ADD CONSTRAINT `category_enquiry_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `category_enquiry_enquiry_id_foreign` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD CONSTRAINT `enquiries_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `enquiries_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
