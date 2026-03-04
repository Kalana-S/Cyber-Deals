-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 04, 2026 at 08:36 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cyber_deals`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `product_name`, `product_price`, `user_id`, `quantity`) VALUES
(1, 2, 'Phone 02', '60000.00', 2, 4),
(2, 19, 'Desktop 01', '300000.00', 2, 2),
(3, 51, 'Board 03', '30000.00', 2, 1),
(4, 60, 'Processor 06', '240000.00', 2, 1),
(5, 77, 'Case 05', '30000.00', 3, 2),
(6, 4, 'Phone 04', '100000.00', 3, 1),
(7, 27, 'Earbud 03', '20000.00', 3, 2),
(8, 42, 'Charger 06', '7000.00', 3, 1),
(9, 46, 'Cable 04', '2500.00', 3, 3),
(10, 66, 'Memory 06', '80000.00', 3, 2),
(11, 28, 'Earbud 04', '25000.00', 2, 2),
(12, 75, 'Case 03', '20000.00', 2, 4),
(23, 26, 'Earbud 02', '15000.00', 3, 1),
(24, 58, 'Processor 04', '160000.00', 3, 1),
(25, 1, 'Phone 01', '40000.00', 11, 3),
(26, 37, 'Charger 01', '1000.00', 11, 3),
(27, 38, 'Charger 02', '1500.00', 11, 3),
(47, 89, 'Test Product 02', '400000.00', 6, 1),
(48, 10, 'Tab 04', '100000.00', 6, 2),
(49, 27, 'Earbud 03', '20000.00', 6, 1),
(50, 34, 'Headphone 04', '30000.00', 6, 1),
(51, 46, 'Cable 04', '2500.00', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `user_id`, `user_email`, `feedback`, `rating`, `created_at`) VALUES
(1, 2, 'nimal@gmail.com', 'Great website! Easy to navigate and find products.', 5, '2024-07-12 09:02:10'),
(2, 2, 'nimal@gmail.com', 'Overall, a good experience, but the search function needs improvement.', 2, '2024-07-12 09:02:31'),
(3, 2, 'nimal@gmail.com', 'Poor customer support and lack of response to inquiries.', 1, '2024-07-12 09:02:46'),
(4, 2, 'nimal@gmail.com', 'Impressive design and fast loading times.', 4, '2024-07-12 09:03:21'),
(6, 2, 'nimal@gmail.com', 'Too many broken links and error messages. Needs better maintenance.\r\n', 1, '2024-07-12 09:04:17'),
(7, 3, 'kamal@gmail.com', 'Love the user-friendly interface and quick checkout process.\r\n', 3, '2024-07-12 09:04:47'),
(8, 3, 'kamal@gmail.com', 'The website is decent but could use more detailed product descriptions.\r\n', 2, '2024-07-12 09:05:03'),
(9, 3, 'kamal@gmail.com', 'Had trouble registering my account. The process needs to be smoother.', 1, '2024-07-12 09:05:26'),
(10, 3, 'kamal@gmail.com', 'The website layout is confusing, and I struggled to find what I needed', 1, '2024-07-12 18:53:20'),
(12, 2, 'nimal@gmail.com', 'Great website! Easy to navigate and find products. Nice work.', 5, '2026-02-10 14:24:28'),
(13, 6, 'amal@gmail.com', 'Good work!', 4, '2026-02-10 14:27:24'),
(14, 6, 'amal@gmail.com', 'Works okay, need more improvement.', 3, '2026-02-10 14:28:23'),
(15, 11, 'sss@gmail.com', 'Works very well, nice UI.', 5, '2026-02-10 14:29:13'),
(16, 6, 'amal@gmail.com', 'Nice Website Design!', 5, '2026-02-21 14:12:01'),
(20, 23, 'manel@gmail.com', 'Very Futuristic Design!!!', 5, '2026-02-22 07:36:23'),
(21, 6, 'amal@gmail.com', 'Smooth UI', 4, '2026-03-03 19:20:37'),
(22, 32, 'testcustomer123@gmail.com', 'nice', 5, '2026-03-04 09:47:21'),
(23, 32, 'testcustomer123@gmail.com', 'Nice UI', 5, '2026-03-04 09:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `cart_id` (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `cart_id`, `user_name`, `user_email`, `product_name`, `product_price`, `quantity`, `total_price`) VALUES
(4, 3, 66, 10, 'kamal', 'kamal@gmail.com', 'Memory 06', '80000.00', 2, '160000.00'),
(5, 3, 46, 9, 'kamal', 'kamal@gmail.com', 'Cable 04', '2500.00', 3, '7500.00'),
(6, 3, 42, 8, 'kamal', 'kamal@gmail.com', 'Charger 06', '7000.00', 1, '7000.00'),
(10, 3, 26, 23, 'kamal', 'kamal@gmail.com', 'Earbud 02', '15000.00', 1, '15000.00'),
(11, 11, 37, 26, 'sss', 'sss@gmail.com', 'Charger 01', '1000.00', 3, '3000.00'),
(12, 11, 38, 27, 'sss', 'sss@gmail.com', 'Charger 02', '1500.00', 3, '4500.00'),
(51, 16, 24, 1770911149, 'malit', 'malit@gmail.com', 'Desktop 06', '800000.00', 1, '800000.00'),
(52, 16, 16, 1770911149, 'malit', 'malit@gmail.com', 'Lap 04', '250000.00', 2, '500000.00'),
(53, 16, 34, 1770911149, 'malit', 'malit@gmail.com', 'Headphone 04', '30000.00', 2, '60000.00'),
(54, 16, 30, 1770974027, 'malit', 'malit@gmail.com', 'Earbud 06', '35000.00', 1, '35000.00'),
(55, 16, 27, 1770974027, 'malit', 'malit@gmail.com', 'Earbud 03', '20000.00', 1, '20000.00'),
(56, 16, 21, 1770974027, 'malit', 'malit@gmail.com', 'Desktop 03', '500000.00', 1, '500000.00'),
(57, 16, 14, 1770974027, 'malit', 'malit@gmail.com', 'Lap 02', '150000.00', 3, '450000.00'),
(58, 16, 38, 1770974608, 'malit', 'malit@gmail.com', 'Charger 02', '1500.00', 1, '1500.00'),
(59, 16, 27, 1770974608, 'malit', 'malit@gmail.com', 'Earbud 03', '20000.00', 1, '20000.00'),
(60, 16, 20, 1770974608, 'malit', 'malit@gmail.com', 'Desktop 02', '400000.00', 3, '1200000.00'),
(61, 16, 14, 1770974608, 'malit', 'malit@gmail.com', 'Lap 02', '150000.00', 3, '450000.00'),
(62, 16, 2, 1770974608, 'malit', 'malit@gmail.com', 'Phone 02', '60000.00', 1, '60000.00'),
(92, 6, 24, 1771318737, 'amal', 'amal@gmail.com', 'Desktop 06', '800000.00', 2, '1600000.00'),
(93, 6, 5, 1771318737, 'amal', 'amal@gmail.com', 'Phone 05', '120000.00', 2, '240000.00'),
(94, 6, 75, 1771318737, 'amal', 'amal@gmail.com', 'Case 03', '20000.00', 1, '20000.00'),
(126, 6, 24, 1771573025, 'amal', 'amal@gmail.com', 'Desktop 06', '800000.00', 1, '800000.00'),
(127, 6, 16, 1771573025, 'amal', 'amal@gmail.com', 'Lap 04', '250000.00', 1, '250000.00'),
(128, 6, 3, 1771573025, 'amal', 'amal@gmail.com', 'Phone 03', '80000.00', 2, '160000.00'),
(152, 6, 66, 1771692425, 'amal', 'amal@gmail.com', 'Memory 06', '80000.00', 2, '160000.00'),
(153, 6, 60, 1771692425, 'amal', 'amal@gmail.com', 'Processor 06', '240000.00', 1, '240000.00'),
(155, 6, 8, 1771692507, 'amal', 'amal@gmail.com', 'Tab 02', '60000.00', 1, '60000.00'),
(156, 6, 2, 1771692507, 'amal', 'amal@gmail.com', 'Phone 02', '60000.00', 1, '60000.00'),
(162, 23, 65, 1771745446, 'Manel Perera', 'manel@gmail.com', 'Memory 05', '50000.00', 2, '100000.00'),
(164, 16, 3, 1771785771, 'malit', 'malit@gmail.com', 'Phone 03', '80000.00', 1, '80000.00'),
(165, 6, 90, 1772369058, 'amal', 'amal@gmail.com', 'Test Product 03', '40000.00', 3, '120000.00'),
(166, 6, 91, 1772369058, 'amal', 'amal@gmail.com', 'Test Product 04', '45000.00', 2, '90000.00'),
(167, 6, 15, 1772369058, 'amal', 'amal@gmail.com', 'Lap 03', '200000.00', 1, '200000.00'),
(168, 6, 14, 1772369058, 'amal', 'amal@gmail.com', 'Lap 02', '150000.00', 1, '150000.00'),
(169, 6, 27, 1772461317, 'amal', 'amal@gmail.com', 'Earbud 03', '20000.00', 2, '40000.00'),
(170, 6, 70, 1772461317, 'amal', 'amal@gmail.com', 'SSD 04', '35000.00', 2, '70000.00'),
(171, 6, 60, 1772564918, 'amal', 'amal@gmail.com', 'Processor 06', '240000.00', 2, '480000.00'),
(173, 6, 31, 1772564918, 'amal', 'amal@gmail.com', 'Headphone 01', '15000.00', 2, '30000.00'),
(174, 6, 10, 1772564918, 'amal', 'amal@gmail.com', 'Tab 04', '100000.00', 2, '200000.00'),
(175, 6, 2, 1772564918, 'amal', 'amal@gmail.com', 'Phone 02', '60000.00', 1, '60000.00'),
(176, 32, 5, 1772617364, 'Test Customer', 'testcustomer123@gmail.com', 'Phone 05', '120000.00', 1, '120000.00'),
(177, 32, 18, 1772617364, 'Test Customer', 'testcustomer123@gmail.com', 'Lap 06', '350000.00', 2, '700000.00'),
(178, 32, 96, 1772617364, 'Test Customer', 'testcustomer123@gmail.com', 'Test Product Motherboard', '20000.00', 3, '60000.00'),
(179, 32, 20, 1772617383, 'Test Customer', 'testcustomer123@gmail.com', 'Desktop 02', '400000.00', 1, '400000.00'),
(180, 32, 17, 1772617383, 'Test Customer', 'testcustomer123@gmail.com', 'Lap 05', '300000.00', 1, '300000.00'),
(181, 32, 11, 1772617383, 'Test Customer', 'testcustomer123@gmail.com', 'Tab 05', '120000.00', 1, '120000.00'),
(182, 32, 4, 1772617383, 'Test Customer', 'testcustomer123@gmail.com', 'Phone 04', '100000.00', 2, '200000.00'),
(183, 32, 68, 1772617403, 'Test Customer', 'testcustomer123@gmail.com', 'SSD 02', '60000.00', 1, '60000.00'),
(184, 32, 53, 1772617403, 'Test Customer', 'testcustomer123@gmail.com', 'Board 05', '40000.00', 1, '40000.00'),
(185, 32, 20, 1772617403, 'Test Customer', 'testcustomer123@gmail.com', 'Desktop 02', '400000.00', 2, '800000.00'),
(186, 32, 15, 1772617403, 'Test Customer', 'testcustomer123@gmail.com', 'Lap 03', '200000.00', 2, '400000.00'),
(187, 32, 7, 1772617403, 'Test Customer', 'testcustomer123@gmail.com', 'Tab 01', '40000.00', 2, '80000.00'),
(188, 32, 60, 1772617627, 'Test Customer', 'testcustomer123@gmail.com', 'Processor 06', '240000.00', 2, '480000.00'),
(189, 32, 24, 1772617627, 'Test Customer', 'testcustomer123@gmail.com', 'Desktop 06', '800000.00', 1, '800000.00'),
(190, 32, 11, 1772617627, 'Test Customer', 'testcustomer123@gmail.com', 'Tab 05', '120000.00', 1, '120000.00'),
(191, 32, 5, 1772617627, 'Test Customer', 'testcustomer123@gmail.com', 'Phone 05', '120000.00', 1, '120000.00'),
(192, 32, 21, 1772617737, 'Test Customer', 'testcustomer123@gmail.com', 'Desktop 03', '500000.00', 1, '500000.00'),
(193, 32, 14, 1772617737, 'Test Customer', 'testcustomer123@gmail.com', 'Lap 02', '150000.00', 2, '300000.00'),
(194, 32, 10, 1772617737, 'Test Customer', 'testcustomer123@gmail.com', 'Tab 04', '100000.00', 1, '100000.00'),
(195, 32, 74, 1772617737, 'Test Customer', 'testcustomer123@gmail.com', 'Case 02', '15000.00', 2, '30000.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `mainCategory` varchar(255) NOT NULL,
  `subCategory` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `mainCategory`, `subCategory`, `description`, `price`, `quantity`, `image`) VALUES
(1, 'Phone 01', 'mobile', 'phone', '4GB RAM, 64GB Storage, OLED Screen', '40000.00', 20, 'uploads/Redmi-Note-13-Pro-4G.png'),
(2, 'Phone 02', 'mobile', 'phone', '6GB RAM, 64GB Storage, OLED Screen \r\n', '60000.00', 10, 'uploads/OnePlus-12-5G-1.png'),
(3, 'Phone 03', 'mobile', 'phone', '8GB RAM, 64GB Storage, OLED Screen', '80000.00', 10, 'uploads/Google-Pixel-8a-1.png'),
(4, 'Phone 04', 'mobile', 'phone', '12GB RAM, 128GB Storage, OLED Screen\r\n', '100000.00', 20, 'uploads/Galaxy-M55-5G.png'),
(5, 'Phone 05', 'mobile', 'phone', '16GB RAM, 128GB Storage, OLED Screen', '120000.00', 10, 'uploads/Galaxy-A35-5G-1.png'),
(6, 'Phone 06', 'mobile', 'phone', '16GB RAM, 256GB Storage, OLED Screen ', '150000.00', 10, 'uploads/Galaxy-A15-5G-1.png'),
(7, 'Tab 01', 'mobile', 'tablet', '4GB RAM, 64GB Storage, OLED Screen \r\n', '40000.00', 20, 'uploads/Webp.net-resizeimage-14.png'),
(8, 'Tab 02', 'mobile', 'tablet', '6GB RAM, 64GB Storage, OLED Screen\r\n', '60000.00', 10, 'uploads/Samsung-Galaxy-Tab-S9-FE-Plus-5G-1.png'),
(9, 'Tab 03', 'mobile', 'tablet', '8GB RAM, 64GB Storage, OLED Screen\r\n', '80000.00', 10, 'uploads/Samsung-Galaxy-Tab-S6-Lite-2024.png'),
(10, 'Tab 04', 'mobile', 'tablet', '12GB RAM, 128GB Storage, OLED Screen \r\n', '100000.00', 20, 'uploads/Galaxy-Tab-S9-2.png'),
(11, 'Tab 05', 'mobile', 'tablet', '16GB RAM, 128GB Storage, OLED Screen\r\n', '120000.00', 10, 'uploads/Galaxy-Tab-A9.png'),
(12, 'Tab 06', 'mobile', 'tablet', '16GB RAM, 256GB Storage, OLED Screen', '150000.00', 10, 'uploads/02-7.png'),
(13, 'Lap 01', 'computer', 'laptop', '8GB RAM, 256GB Storage, OLED Screen\r\n', '100000.00', 20, 'uploads/Lenovo-IdeaPad-Slim-3-15IRU8-i3-02.png'),
(14, 'Lap 02', 'computer', 'laptop', '8GB RAM, 512GB Storage, OLED Screen \r\n', '150000.00', 10, 'uploads/Lenovo ThinkPad E15 Gen 4 – i5.png'),
(15, 'Lap 03', 'computer', 'laptop', '12GB RAM, 512GB Storage, OLED Screen\r\n', '200000.00', 10, 'uploads/Huawei-MateBook-D14-–-i5.png'),
(16, 'Lap 04', 'computer', 'laptop', '12GB RAM, 1TB Storage, OLED Screen\r\n', '250000.00', 20, 'uploads/HP-Victus-15-fa1247TX-Gaming-Laptop-i5-02-1.png'),
(17, 'Lap 05', 'computer', 'laptop', '16GB RAM, 1TB Storage, OLED Screen\r\n', '300000.00', 10, 'uploads/HP ProBook 450 G10 – i5.png'),
(18, 'Lap 06', 'computer', 'laptop', '16GB RAM, 2TB Storage, OLED Screen ', '350000.00', 10, 'uploads/Asus Vivobook 15 X513EA.png'),
(19, 'Desktop 01', 'computer', 'desktop', 'Ryzen 5700X, 16GB RAM, 1TBB Storage, RTX 4060 \r\n', '300000.00', 20, 'uploads/Hp-Pro-Tower-280-G9-2.png'),
(20, 'Desktop 02', 'computer', 'desktop', 'Ryzen 7900X, 16GB RAM, 1TBB Storage, RTX 4070 TI', '400000.00', 10, 'uploads/Desktop Hp Pro 280 G9.png'),
(21, 'Desktop 03', 'computer', 'desktop', 'Ryzen 7950X, 16GB RAM, 2TBB Storage, RTX 4080 \r\n', '500000.00', 20, 'uploads/Desktop Dell Vostro3910.png'),
(22, 'Desktop 04', 'computer', 'desktop', 'Intel 13700K, 32GB RAM, 2TBB Storage, RTX 4080   \r\n', '600000.00', 10, 'uploads/Dell-Vostro-3020-Desktop-with-Monitor-i5.png'),
(23, 'Desktop 05', 'computer', 'desktop', 'Intel 13900K, 32GB RAM, 4TBB Storage, RTX 4090   \r\n', '700000.00', 20, 'uploads/Aio Pc Lenovo .png'),
(24, 'Desktop 06', 'computer', 'desktop', 'Intel 14900K, 32GB RAM, 4TBB Storage, RTX 4090   \r\n', '800000.00', 10, 'uploads/Dell-Vostro-3020-Desktop-with-Monitor-i5.png'),
(25, 'Earbud 01', 'mobile', 'earbud', 'Bluetooth 5.0, 24 Hours Battery Life, Touch Control, Up to 15m wireless Range \r\n', '10000.00', 30, 'uploads/OnePlus-Buds-Pro-2.png'),
(26, 'Earbud 02', 'mobile', 'earbud', 'Bluetooth 5.0, 48 Hours Battery Life, Touch Control, Up to 20m wireless Range   \r\n', '15000.00', 20, 'uploads/Monster-N-Lite-203.png'),
(27, 'Earbud 03', 'mobile', 'earbud', 'Bluetooth 5.0, 72 Hours Battery Life, Touch Control, Up to 25m wireless Range   \r\n', '20000.00', 10, 'uploads/Monster-Mission-V1-Wireless-Earbuds.png'),
(28, 'Earbud 04', 'mobile', 'earbud', 'Bluetooth 6.0, 24 Hours Battery Life, Touch Control, Up to 30m wireless Range   \r\n', '25000.00', 30, 'uploads/Baseus-Bowie-MZ10.png'),
(29, 'Earbud 05', 'mobile', 'earbud', 'Bluetooth 6.0, 48 Hours Battery Life, Touch Control, Up to 40m wireless Range   \r\n', '30000.00', 20, 'uploads/Baseus-Bowie-E5x-True-Wireless-Earbuds.png'),
(30, 'Earbud 06', 'mobile', 'earbud', 'Bluetooth 6.0, 72 Hours Battery Life, Touch Control, Up to 50m wireless Range     ', '35000.00', 10, 'uploads/Anker-Soundcore-V30i-Open-Ear-Earbuds.png'),
(31, 'Headphone 01', 'mobile', 'headphone', 'Wired, 1m Cable Length, No Microphone \r\n', '15000.00', 20, 'uploads/Soundcore-by-Anker-Life-2-Neo-Wireless-Bluetooth-Over-Ear-Headphones.png'),
(32, 'Headphone 02', 'mobile', 'headphone', 'Wired, 1.5m Cable Length, Foldable Microphone  \r\n', '20000.00', 10, 'uploads/Logitech-G431-Headset-Leatherette-7.1-Surround-Sound-Wired-Gaming-Heads.png'),
(33, 'Headphone 03', 'mobile', 'headphone', 'Wired, 2.0m Cable Length, Foldable Microphone \r\n', '25000.00', 5, 'uploads/Joyroom-S-1224N2-Soft-Liquid-Silicone-2.4A-Type-C-Data-Cable-1.png'),
(34, 'Headphone 04', 'mobile', 'headphone', 'Bluetooth Wireless, 400mAh Battery Capacity, No Microphone \r\n', '30000.00', 20, 'uploads/JBL-Tune-570BT.png'),
(35, 'Headphone 05', 'mobile', 'headphone', 'Bluetooth Wireless, 500mAh Battery Capacity, Foldable Microphone \r\n', '35000.00', 10, 'uploads/Baseus-Bowie-H1i-Bluetooth-Headphone.png'),
(36, 'Headphone 06', 'mobile', 'headphone', 'Bluetooth Wireless, 600mAh Battery Capacity, Foldable Microphone \r\n', '40000.00', 5, 'uploads/Baseus-Bowie-D05-70Hr-Playtime-Headphones.png'),
(37, 'Charger 01', 'mobile', 'charger', 'No Fast Charging Support, 1 Port, 5V/2.0A Power Output \r\n', '1000.00', 30, 'uploads/samsung-15w-pd-power-adapter.png'),
(38, 'Charger 02', 'mobile', 'charger', 'No Fast Charging Support, 2 Ports, 5V/2.0A Power Output \r\n', '1500.00', 20, 'uploads/Baseus-Compact-charger-3x-USB-17W-UK-plug.png'),
(39, 'Charger 03', 'mobile', 'charger', 'No Fast Charging Support, 4 Ports, 5V/2.0A Power Output \r\n', '2000.00', 30, 'uploads/Anker-PowerPort-III-20W-Cube-USB-C-Charger.png'),
(40, 'Charger 04', 'mobile', 'charger', 'Fast Charging Support, 1 Port, 5V/2.0A Power Output \r\n', '4000.00', 30, 'uploads/Baseus-GaN5-20W-Type-C-Fast-Charger.png'),
(41, 'Charger 05', 'mobile', 'charger', 'Fast Charging Support, 4 Ports, 5V/2.0A Power Output \r\n', '5000.00', 20, 'uploads/WiWU-30W-Quick-Charge-Bundle-Type-C-to-Type-C.png'),
(42, 'Charger 06', 'mobile', 'charger', 'Fast Charging Support, 8 Ports, 5V/2.0A Power Output ', '7000.00', 30, 'uploads/Anker-PowerPort-III-3-Port-65w-Elite-Charger-A2034k11.png'),
(43, 'Cable 01', 'mobile', 'cable', 'TPE and Aluminum Alloy Interface, 1.0m Length \r\n', '1000.00', 30, 'uploads/Joyroom-S-2030N13-USB-Extension-Cable-2M.png'),
(44, 'Cable 02', 'mobile', 'cable', 'TPE and Aluminum Alloy Interface, 1.2m Length \r\n', '1500.00', 20, 'uploads/Interface_-USB-A-to-Type-C.png'),
(45, 'Cable 03', 'mobile', 'cable', 'TPE and Aluminum Alloy Interface, 1.5m Length \r\n', '2000.00', 10, 'uploads/Baseus-Explorer-Series-Auto-Power-Off-Fast-Charging-Data-Cable-Type-C-to-iPhone-20W.png'),
(46, 'Cable 04', 'mobile', 'cable', 'Nylon Wiring and Aluminum Alloy Interface, 1.0m Length \r\n', '2500.00', 30, 'uploads/11.png'),
(47, 'Cable 05', 'mobile', 'cable', 'Nylon Wiring and Aluminum Alloy Interface, 1.2m Length \r\n', '3000.00', 20, 'uploads/1_c4b5401d-ae5d-4f27-a142-7849de338cfb_700x.png'),
(48, 'Cable 06', 'mobile', 'cable', 'Nylon Wiring and Aluminum Alloy Interface, 1.5m Length ', '4000.00', 10, 'uploads/•_Total-40Gbps-bandwidth-•_8K-30Hz-ultra-clear-display-•_PD-100W-high-power-•_Gigabit-network-•_6-Months-Warranty.png'),
(49, 'Board 01', 'computer', 'motherboard', 'AMD AM4 CPU Socket Type, DDR4 RAM Type, Total Support 2 x M.2 Slots \r\n', '20000.00', 10, 'uploads/GIGABYTE H610M H DDR4 MOTHERBOARD.png'),
(50, 'Board 02', 'computer', 'motherboard', 'LGA 1200 CPU Socket Type, DDR4 RAM Type, Total Support 2 x M.2 Slots  \r\n', '25000.00', 5, 'uploads/ESONIC H510DA MOTHERBOARD.png'),
(51, 'Board 03', 'computer', 'motherboard', 'AMD AM4 CPU Socket Type, DDR5 RAM Type, Total Support 4 x M.2 Slots \r\n', '30000.00', 5, 'uploads/BIOSTAR-Z590GTA-DDR4-1.png'),
(52, 'Board 04', 'computer', 'motherboard', 'LGA 1700 CPU Socket Type, DDR5 RAM Type, Total Support 2 x M.2 Slots \r\n', '35000.00', 10, 'uploads/BIOSTAR-Z490A-SILVER-1.png'),
(53, 'Board 05', 'computer', 'motherboard', 'AMD AM5 CPU Socket Type, DDR5 RAM Type, Total Support 4 x M.2 Slots  \r\n', '40000.00', 10, 'uploads/BIOSTAR H510MHP 2.0 MOTHERBOARD (1).png'),
(54, 'Board 06', 'computer', 'motherboard', 'LGA 1700 CPU Socket Type, DDR5 RAM Type, Total Support 4 x M.2 Slots \r\n', '50000.00', 5, 'uploads/ASUS PROART B760-CREATOR D4 MOTHERBOARD.png'),
(55, 'Processor 01', 'computer', 'processor', '4 Cores, 8 Threads, 4.6GHz Boost Clock, 3.3 Base Clock \r\n', '80000.00', 20, 'uploads/INTEL-CELERON-G5920-3.50-GHz.png'),
(56, 'Processor 02', 'computer', 'processor', '8 Cores, 16 Threads, 5.1GHz Boost Clock, 4.1 Base Clock \r\n', '120000.00', 20, 'uploads/INTEL-CORE-i5-12400-PROCESSOR.png'),
(57, 'Processor 03', 'computer', 'processor', '12 Cores, 24 Threads, 5.1GHz Boost Clock, 4.1 Base Clock 150000 15\r\n', '150000.00', 15, 'uploads/I7-PROCESSOR-–-INTEL-9700-3.00GHZ.png'),
(58, 'Processor 04', 'computer', 'processor', '14 Cores, 20 Threads, 5.3GHz Boost Clock, 4.2 Base Clock 160000 20\r\n', '160000.00', 20, 'uploads/AMD RYZEN 5-5500 3.6GHZ PROCESSOR.png'),
(59, 'Processor 05', 'computer', 'processor', '16 Cores, 32 Threads, 5.7GHz Boost Clock, 4.4 Base Clock \r\n', '180000.00', 10, 'uploads/INTEL i7-14700K PROCESSOR.png'),
(60, 'Processor 06', 'computer', 'processor', '24 Cores, 32 Threads, 6.2GHz Boost Clock, 5.1 Base Clock \r\n', '240000.00', 5, 'uploads/INTEL-CORE-i9-11900K-PROCESSOR.png'),
(61, 'Memory 01', 'computer', 'ram', '8GB Memory Size, DDR4 Memory Type, 3200MHz Memory Speed \r\n', '10000.00', 10, 'uploads/ADATA-4GB-2666MHZ-DESKTOP.png'),
(62, 'Memory 02', 'computer', 'ram', '16GB Memory Size, DDR4 Memory Type, 3600MHz Memory Speed  \r\n', '15000.00', 15, 'uploads/NETAC-BASIC-4GB-DDR4-2666MHZ-C19-DESKTOP.png'),
(63, 'Memory 03', 'computer', 'ram', '32GB Memory Size, DDR4 Memory Type, 3600MHz Memory Speed \r\n', '25000.00', 10, 'uploads/DAHUA DHI-DDR-C160U4G16 4GB DDR3 1600MHZ DESKTOP.png'),
(64, 'Memory 04', 'computer', 'ram', '16GB Memory Size, DDR5 Memory Type, 5400MHz Memory Speed  \r\n', '35000.00', 20, 'uploads/DAHUA DHI-DDR-C300U4G26 4GB DDR4 2666MHZ DESKTOP.png'),
(65, 'Memory 05', 'computer', 'ram', '32GB Memory Size, DDR5 Memory Type, 5400MHz Memory Speed  \r\n', '50000.00', 10, 'uploads/DDR4 MEMORY - TEAM 4GB ELITE PLUS 2666MHZ.png'),
(66, 'Memory 06', 'computer', 'ram', '64GB Memory Size, DDR5 Memory Type, 6000MHz Memory Speed ', '80000.00', 5, 'uploads/THERMALTAKE-TOUGHRAM-RGB-3200MHZ-1-1.png'),
(67, 'SSD 01', 'computer', 'storage', 'M.2 Storage Type, 4TB Capacity, 7,000MB/s Read & Write Speed \r\n', '80000.00', 5, 'uploads/SAMSUNG-SSD980-250GB-M.2-NVMe.png'),
(68, 'SSD 02', 'computer', 'storage', 'M.2 Storage Type, 2TB Capacity, 6,000MB/s Read & Write Speed \r\n', '60000.00', 10, 'uploads/DAHUA DHI-SSD-C900N128G 128GB NVMe M.2.png'),
(69, 'SSD 03', 'computer', 'storage', 'M.2 Storage Type, 1TB Capacity, 6,000MB/s Read & Write Speed \r\n', '40000.00', 15, 'uploads/NETAC-N535N.png'),
(70, 'SSD 04', 'computer', 'storage', 'SATA Storage Type, 4TB Capacity, 4,000MB/s Read & Write Speed \r\n', '35000.00', 10, 'uploads/TRANSCEND SSD225S-1.png'),
(71, 'SSD 05', 'computer', 'storage', 'SATA Storage Type, 2TB Capacity, 3,600MB/s Read & Write Speed \r\n', '25000.00', 15, 'uploads/DAHUA DHI-SSD-C800AS480G 480GB 2.5 SSD.png'),
(72, 'SSD 06', 'computer', 'storage', 'SATA Storage Type, 1TB Capacity, 3,200MB/s Read & Write Speed \r\n', '15000.00', 20, 'uploads/NETAC SA500.png'),
(73, 'Case 01', 'computer', 'casing', 'Mid Tower Size, Non RGB Lighting, Glass Side Panels \r\n', '10000.00', 10, 'uploads/ATX CASING - COLORSIT CL-E20.png'),
(74, 'Case 02', 'computer', 'casing', 'Mid Tower Size, RGB Lighting, Glass Side Panels \r\n', '15000.00', 20, 'uploads/ATX CASING - COLORSIT CL-E08.png'),
(75, 'Case 03', 'computer', 'casing', 'Mid Tower Size, RGB Lighting, Tempered Glass Side Panels \r\n', '20000.00', 10, 'uploads/ATX CASING - COLORSIT CL-E02.png'),
(76, 'Case 04', 'computer', 'casing', 'Full Tower Size, Non RGB Lighting, Glass Side Panels \r\n', '25000.00', 5, 'uploads/ASUS TUF GT501CV GAMING CASING.png'),
(77, 'Case 05', 'computer', 'casing', 'Full Tower Size, RGB Lighting, Glass Side Panels \r\n', '30000.00', 15, 'uploads/ATX CASING - COLORSIT CL-G31.png'),
(78, 'Case 06', 'computer', 'casing', 'Full Tower Size, RGB Lighting, Tempered Glass Side Panels \r\n', '35000.00', 10, 'uploads/THERMALTAKE H200 TG SNOW RGB (CA-1M3-00M6WN-00)CHASSIS.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('admin','processing team','customer') NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`) VALUES
(1, 'kalana', 'kalana@gmail.com', 'admin', '123456'),
(3, 'kamal', 'kamal@gmail.com', 'customer', '123456'),
(4, 'shehan', 'shehan@gmail.com', 'processing team', '123456'),
(5, 'gimhana', 'gimhana@gmail.com', 'processing team', '123456'),
(6, 'amal', 'amal@gmail.com', 'customer', '123456'),
(7, 'madesh', 'madesh@gmail.com', 'admin', '123456'),
(9, 'namal', 'namal@gmail.com', 'customer', '123456'),
(11, 'sss', 'sss@gmail.com', 'customer', '123456'),
(13, 'sadun', 'sadun@gmail.com', 'processing team', '123456'),
(14, 'ccc', 'ccc@gmail.com', 'customer', 'ccc123'),
(15, 'nimal', 'nimal@gmail.com', 'admin', '123456'),
(16, 'malit', 'malit@gmail.com', 'customer', '123456'),
(21, 'Sunil Perera', 'sunil@gmail.com', 'customer', '123456'),
(22, 'samal', 'samal@gmail.com', 'customer', '123456'),
(23, 'Manel Perera', 'manel@gmail.com', 'customer', '654321'),
(28, 'Test User 01', 'testuser01@gmail.com', 'admin', '123456'),
(31, 'Test Customer Changed', 'testcustomerchanged@gmail.com', 'customer', '123456'),
(32, 'Test Customer', 'testcustomer123@gmail.com', 'customer', '123456'),
(33, 'Test Admin', 'testadmin@gmail.com', 'admin', '123456');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
