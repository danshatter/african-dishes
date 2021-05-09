-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 08, 2021 at 04:52 PM
-- Server version: 10.2.34-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chrystac_chrystacripsy`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'local dish', 'Nigeria meals/soup', '2020-10-17 13:33:05', '2020-10-17 13:33:05'),
(2, 'rice dish', 'different kind of rice dishes', '2020-10-17 13:34:03', '2020-10-17 13:34:03'),
(5, 'packed dishes', 'For packed food jollof and fried rice stew and rice with chicken semo,wheat,eba,fufu and soup', '2020-10-20 17:41:54', '2020-10-20 17:41:54'),
(6, 'stew dish', 'Special Stew dish for your enjoyment', '2020-10-25 14:50:15', '2020-10-25 14:50:15');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lga` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `lga`, `amount`, `created_at`, `updated_at`) VALUES
(4, 'yaba mainland', 'Yaba', 1000, '2020-12-30 10:50:24', '2020-12-30 10:50:24'),
(5, 'bariga', 'Shomolu', 1000, '2020-12-30 10:51:46', '2020-12-30 10:51:46'),
(6, 'oyingbo', 'Ebute meta', 1300, '2020-12-30 10:52:08', '2020-12-30 10:52:08'),
(7, 'victoria island', 'Eti osa', 2000, '2020-12-30 10:52:49', '2020-12-30 10:52:49'),
(8, 'ikoyi', 'Island', 2000, '2020-12-30 10:53:04', '2020-12-30 10:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_extension` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `quantity`, `price`, `description`, `image_extension`, `created_at`, `updated_at`) VALUES
(7, 1, 'egusi soup', 5, 8000, 'Richly prepared delicious dry melon soup enrished with goat meat, chicken and fish (Egwu leave) plus free coke', 'jpg', '2020-10-17 19:40:07', '2020-12-25 07:47:58'),
(8, 1, 'vegetable soup', 7, 7500, 'Richly prepared delicious vegetable soup enrished with goat meat,beef,chicken\r\nN.E (Vegetable:Egwu and spinach leave)', 'jpg', '2020-10-17 19:42:30', '2020-10-20 05:32:58'),
(9, 1, 'ogbono soup', 6, 7000, 'Nicely prepared ogbono Soup (draw soup) enriched with goat meat,cow leg and dry fish', 'jpeg', '2020-10-17 19:50:00', '2020-12-11 08:16:29'),
(12, 1, 'banga soup(deltan soup palm fruit soup)', 2, 8000, 'Nicely prepared deltan palm fruit soup(Banga Soup) fully packed delicious ingredients come with any protein of your choice (Catfish or Goat Meat or Chicken or Beef Meat)\r\nNote: free drink', 'jpg', '2020-10-20 03:46:54', '2020-10-20 03:46:54'),
(13, 6, 'chicken stew', 2, 6500, 'Delicious richly prepared stew enriched with chicken (full chicken) \r\nNote:free drink', 'jpeg', '2020-10-20 03:51:41', '2020-12-17 03:27:54'),
(14, 6, 'beef stew', 2, 5000, 'Richly prepared delicious beef stew packed with rich nutritional benefits', 'jpg', '2020-10-20 03:53:07', '2020-12-17 03:28:14'),
(15, 6, 'fish stew', 1, 5000, 'Fish stew deliciously packed with nutritional benefits nicely prepared Croaker fish', 'jpeg', '2020-10-20 03:57:16', '2020-12-25 21:49:37'),
(16, 1, 'afang soup', 0, 14000, 'Richly prepared Nigerian afang soup with fresh fish, goat meat and beef packed with healthy nutritional benefits\r\nNote: free drink', 'jpeg', '2020-10-20 04:00:55', '2020-12-25 07:59:05'),
(17, 6, 'ofe akwu(banga stew', 0, 7000, 'Deliciously prepared Banga stew enriched with fish and beef meat packed with nutritional benefits', 'jpeg', '2020-10-20 04:04:09', '2020-12-25 07:55:08'),
(18, 1, 'seafood okoro soup', 0, 13000, 'Really prepared seafood okra soup made with snail, prawns, crayfish,crabs, periwinkle and croaker fish packed with nutritional benefits', 'jpeg', '2020-10-20 04:15:41', '2020-12-25 07:55:08'),
(19, 6, 'pepper sauce(stew)', 2, 6000, 'Delicious spicy sauce prepared with assorted meat & kpomo packed with nutritional benefits', 'jpg', '2020-10-20 04:18:34', '2020-12-17 03:28:51'),
(20, 1, 'catfish pepper soup', 2, 10000, 'Deliciously prepared catfish pepper soup enriched with ripe plantain or unripe plantain as desired spicy and yummy', 'jpeg', '2020-10-20 04:26:38', '2020-10-20 04:26:38'),
(21, 1, 'pepper soup', 1, 7000, 'Freshly prepared pepper soup made with either chicken,beef or goat meat as desired delivered along with ripe or unripe plantain * if needed', 'jpeg', '2020-10-20 04:29:11', '2020-12-25 07:47:58'),
(22, 1, 'ewdu and gbediri', 2, 12000, 'Freshly prepared ewedu and gbediri soup with assorted meat,beef meat and fresh fish', 'jpeg', '2020-10-20 04:32:07', '2020-10-20 04:32:07'),
(23, 2, 'jollof rice', 1, 8000, 'Freshly prepared hot jollof rice with pepper sauce prepared with turkey \r\nPacked with nutritional benefits\r\nNote: free drink', 'jpeg', '2020-10-20 04:37:05', '2020-12-11 08:16:29'),
(24, 2, 'fried rice', 2, 8000, 'Deliciously prepared fried rice with fresh vegetables comes with fried spicy chicken\r\nNote: Free drinks', 'jpeg', '2020-10-20 04:39:02', '2020-10-20 04:39:02'),
(25, 2, 'coconut rice', 2, 8800, 'Lovely coconut rice richly prepared with fresh vegetable and pepper chicken\r\nNote: Free drink', 'jpeg', '2020-10-20 04:40:21', '2020-10-20 04:40:21'),
(26, 1, 'white soup', 2, 15000, 'Freshly prepared white soup with goat meat chicken and fresh fish pot with nutritional benefits', 'jpeg', '2020-10-20 04:45:50', '2020-10-20 04:45:50'),
(27, 1, 'melon pepper soup', 2, 7000, 'Freshly made Delicious delta Melon pepper soup made with stock fish, fresh fish, chicken and beef', 'jpeg', '2020-10-20 04:48:57', '2020-10-20 04:48:57'),
(28, 1, 'oha soup', 2, 8500, 'Freshly made Oha Soup packed with nutritional benefits prepared with goat meat, stock fish and chicken', 'jpeg', '2020-10-20 04:50:03', '2020-10-20 04:50:03'),
(29, 5, 'semo and vegetable soup', 97, 1500, 'Freshly prepared semo and vegetable soup with assorted meat and fresh fish\r\nNote: free drink', 'jpeg', '2020-10-20 17:43:41', '2020-12-30 20:12:31'),
(30, 5, 'semo and egusi soup', 92, 1500, 'Freshly prepared Egunsi Soup and Semo with assorted meat and fresh fish \r\nNote: free drink', 'jpeg', '2020-10-20 17:45:30', '2020-12-30 20:12:31'),
(31, 5, 'vegetable soup and pounded yam', 100, 2699, 'Nicely prepared vegetable soup with pounded yam prepared with goat\'s meat chicken and fresh fish\r\nNote: free drink', 'jpeg', '2020-10-20 17:47:03', '2020-10-20 17:47:03'),
(32, 5, 'jollof rice and chicken', 99, 1300, 'freshly prepared Delicious jollof rice and chicken with either plantain or salad as needed\r\nNote: Free drinks', 'jpeg', '2020-10-20 17:51:03', '2020-12-21 03:04:45'),
(33, 5, 'fried rice and chicken', 100, 1500, 'freshly prepared Delicious Fried rice and chicken with either plantain or salad as needed\r\nNote: Free drinks', 'jpg', '2020-10-20 17:52:03', '2020-10-20 17:52:03'),
(34, 5, 'noodles egg and turkey wings', 100, 1600, 'Freshly prepared vegetable noodles with turkey wings and fried egg\r\nNote: free drink', 'jpeg', '2020-10-20 17:58:06', '2020-10-20 17:58:06'),
(35, 5, 'yam and egg sauce', 96, 1300, 'Freshly prepared Delicious yam and Egg Sauce \r\nNote: Free drinks', 'jpeg', '2020-10-20 18:02:27', '2020-12-25 08:02:57'),
(36, 5, 'yam porridge', 100, 1800, 'freshly prepared Delicious Yam Porridge and fried spicy beef\r\nNote: Free drinks', 'jpeg', '2020-10-20 18:03:54', '2020-10-20 18:03:54'),
(37, 5, 'bean and plantain', 100, 1300, 'Freshly prepared Fried beans and plantain with spicy  chicken\r\nNote:Free drink', 'jpeg', '2020-10-20 18:05:09', '2020-10-20 18:05:09'),
(38, 5, 'pepper snail(10 pieces)', 1000, 5500, 'Freshly deliciously prepared spicy snail\r\nNote:Free drink', 'jpeg', '2020-10-20 18:07:36', '2020-10-20 18:07:36'),
(39, 5, 'nkwobi', 1000, 4500, 'Deliciously prepared nkwobi meal packed with nutritional benefits \r\nNote:free drink', 'jpeg', '2020-10-20 18:11:20', '2020-12-10 07:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`item`)),
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_fee` bigint(20) UNSIGNED NOT NULL,
  `address_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'standard user', '2020-10-04 22:45:37', '2020-10-04 22:45:37'),
(2, 'administratior', '2020-10-04 22:45:37', '2020-10-04 22:45:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(3) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `recovery` tinyint(1) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `phone`, `image`, `address_1`, `address_2`, `password`, `role`, `status`, `recovery`, `token`, `created_at`, `updated_at`) VALUES
(1, 'chrysta', 'cripsy', 'chrystacripsy', 'admin@chrystacripsy.com.ng', '09091836457', NULL, 'Lagos nigeria', 'Mainland', '$2y$10$uF1PuuZ1YvpL8KUE2imOE.4izxfNJ5DO7KfOhx.tYPzdCzR5AnSe2', 2, 1, 0, NULL, '2020-10-17 00:40:06', '2020-10-17 13:24:09'),
(3, 'Daniel', 'Isikaku', 'danshatter', 'isikakudaniel@yahoo.com', '08122692942', '3.jpg', 'FAAN Quarters. Ikeja', NULL, '$2y$10$opDsvdC0K1S/NwkruIc.x.f.bK/z/uaanM3fhvw6/coJ4gaoygD9G', 1, 1, 1, 'f044667a63d0560e4a3b2abba6f3c475', '2020-10-17 16:28:17', '2021-03-12 17:29:44'),
(4, 'Ugochukwu', 'Isikaku', 'godswillisikaku', 'godswillisikaku@gmail.com', '08064676061', NULL, 'Irebawa street, Oke-ira, Ogba', NULL, '$2y$10$FveS5QuaRY0RJifIqotVWeTYwEyAj5vmozuqM2volzxlJ5hf1AEce', 1, 1, 0, NULL, '2020-10-18 13:02:52', '2020-10-20 03:28:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `location` (`location`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_name_unique` (`name`),
  ADD KEY `menus_category_id_foreign` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_role_unique` (`role`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
