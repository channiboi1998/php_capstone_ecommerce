-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 18, 2022 at 04:11 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_capstone_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing_information`
--

CREATE TABLE `billing_information` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `address` text,
  `address_2` text,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(54, 'Bundle', '2022-08-17 04:25:15', '2022-08-17 04:25:15'),
(79, 'Sidearms', '2022-08-17 09:04:58', '2022-08-17 09:04:58'),
(80, 'Smg\'s', '2022-08-17 09:05:04', '2022-08-17 09:05:04'),
(81, 'Shotguns', '2022-08-17 09:05:08', '2022-08-17 09:05:08'),
(82, 'Rifles', '2022-08-17 09:05:13', '2022-08-17 09:05:13'),
(83, 'Snipers', '2022-08-17 09:05:21', '2022-08-17 09:05:21'),
(84, 'Heavy', '2022-08-17 09:05:26', '2022-08-17 09:05:26'),
(85, 'Melee', '2022-08-17 09:05:32', '2022-08-17 09:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `product_description` text,
  `product_images` json NOT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_description`, `product_images`, `product_price`, `quantity_sold`, `created_at`, `updated_at`) VALUES
(34, 'Valorant Bundles', 'One of the coolest things about Valorant is the range of beautiful and expertly crafted skins that you can equip to make your weapons stand out. Here’s a rundown of every skin in Riot Games’ FPS, as well as everything you need to know about them.', '[{\"is_main\": 0, \"file_path\": \"http://localhost/uploads/ares.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/bucky.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/bulldog.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/classic.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/frenzy.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/ghost.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/guardian.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/judge.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/marshal.png\"}, {\"is_main\": 1, \"file_path\": \"http://localhost/uploads/odin.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/operator.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/phantom.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/sheriff.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/shorty.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/spectre.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/stinger.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/tactical-knife.png\"}, {\"is_main\": 0, \"file_path\": \"http://localhost/uploads/vandal.png\"}]', '2000', 0, '2022-08-17 08:57:20', '2022-08-18 23:54:04'),
(35, 'Classic', 'Primary fire lands precise shots when still, and is equipped with an alternate burst-firing mode for close encounters.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/classic1.png\"}]', '150', 0, '2022-08-17 09:06:28', '2022-08-18 14:02:07'),
(36, 'Shorty', 'A nimble, short barrel shotgun that is deadly at close range but can only fire twice before needing to reload. Pairs well with long range weapons.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/shorty1.png\"}]', '150', 0, '2022-08-17 09:07:12', '2022-08-18 14:02:12'),
(37, 'Frenzy', 'Lightweight machine pistol that excels at firing on the move. It’s high rate-of-fire can be difficult to control, so try short bursts at medium ranges.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/frenzy1.png\"}]', '200', 0, '2022-08-17 09:07:42', '2022-08-18 14:02:37'),
(38, 'Ghost', 'The Ghost is accurate and carries a large magazine if you miss. Distant targets require a controlled fire rate. Quickly tap the trigger when you can see the whites of their eyes.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/ghost1.png\"}]', '220', 0, '2022-08-17 09:08:51', '2022-08-18 14:02:46'),
(39, 'Sheriff', 'It’s high-impact rounds pack a ton of recoil and require true grit to master. Wield the Sheriff right, and your enemies will know they were expendable.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/sheriff1.png\"}]', '230', 0, '2022-08-17 09:09:19', '2022-08-18 14:02:53'),
(40, 'Bucky', 'Heavy but stable, Bucky’s primary fire is for holding tight corners or charging close quarters. Alternate fire strikes targets at medium range.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/bucky1.png\"}]', '400', 0, '2022-08-17 09:10:49', '2022-08-18 14:02:59'),
(41, 'Judge', 'The Judge is stable at the stand but volatile when fired rapidly.  Primary fire hammers short range targets and you’ll need to be steady to nail anything beyond an arm\'s-length.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/judge1.png\"}]', '450', 0, '2022-08-17 09:11:12', '2022-08-18 14:03:03'),
(42, 'Stinger', 'This SMG is more potent at medium to long range than its counterparts, but at the cost of firing rate and mobility. The 20-round mag gets wasted in recoil-filled sprays, but lands lethal blows at medium distances with ADS and controlled fire.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/stinger1.png\"}]', '500', 0, '2022-08-17 09:13:09', '2022-08-18 14:03:08'),
(43, 'Spectre', 'A jack-of-all-trades weapon with a great balance of damage, fire rate, and accuracy—at both short and mid range. It haunts the corners of every map and requires only steady aim to drop foes at long distance.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/spectre2.png\"}]', '550', 0, '2022-08-17 09:14:06', '2022-08-17 09:14:06'),
(44, 'Bulldog', 'A surefire beast when you can pick your shots. Alt. fire let’s you ADS and spew accurate, short bursts at anyone who tries you from medium to long-range.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/bulldog1.png\"}]', '600', 0, '2022-08-17 09:14:45', '2022-08-18 14:03:16'),
(45, 'Guardian', 'The designated marksman rifle. Heavier and less mobile relative to other rifles but precise and powerful. Headhunt when enemies appear at medium to long distances.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/guardian1.png\"}]', '620', 0, '2022-08-17 09:15:14', '2022-08-18 14:03:21'),
(46, 'Phantom', 'Go full auto for anyone who tests you up close and short controlled bursts scramble enemies from anywhere. Best when fired while stationary.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/phantom1.png\"}]', '700', 0, '2022-08-17 09:15:45', '2022-08-18 14:03:27'),
(47, 'Vandal', 'Extended fire results in less stability, however. The Vandal retains high damage over distance and rewards those who focus single shots at a target’s head.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/vandal1.png\"}]', '750', 0, '2022-08-17 09:16:09', '2022-08-18 14:03:30'),
(48, 'Marshal', 'A nimble lever-action sniper rifle with a single zoom that can keep bullish enemies at bay. A slow rate of fire means you have to either hit the mark or leave yourself open to attacks.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/marshal1.png\"}]', '550', 0, '2022-08-17 09:16:51', '2022-08-18 14:03:34'),
(49, 'Operator', 'A fierce bolt-action sniper rifle with high-powered dual zoom. Extremely immobile but fires an incredibly powerful round that can devastate a team with one shot.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/operator1.png\"}]', '1200', 0, '2022-08-17 09:17:19', '2022-08-18 14:03:37'),
(50, 'Ares', 'The Ares’ large magazine means it excels at suppressive fire or dealing heavy damage to clustered groups.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/ares1.png\"}]', '1000', 0, '2022-08-17 09:17:49', '2022-08-18 14:03:47'),
(51, 'Odin', 'Suppressive, high damage fire with surprising stability. Spray enemies at short range and use alt. fire to make yourself a living turret.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/odin1.png\"}]', '2200', 0, '2022-08-17 09:18:21', '2022-08-18 14:03:43'),
(52, 'Knife', 'The Tactical Knife is a base melee weapon is provided to every player, and cannot be dropped. It has a two modes of fire: one being a fast slashing motion, and the other being a sharp jab.', '[{\"is_main\": 1, \"file_path\": \"http://localhost/uploads/tactical-knife1.png\"}]', '20', 0, '2022-08-18 01:44:57', '2022-08-18 22:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(109, 43, 80, '2022-08-17 09:14:06', '2022-08-17 09:14:06'),
(417, 35, 79, '2022-08-18 06:02:07', '2022-08-18 06:02:07'),
(418, 36, 79, '2022-08-18 06:02:12', '2022-08-18 06:02:12'),
(420, 37, 79, '2022-08-18 06:02:37', '2022-08-18 06:02:37'),
(421, 38, 79, '2022-08-18 06:02:46', '2022-08-18 06:02:46'),
(422, 39, 79, '2022-08-18 06:02:53', '2022-08-18 06:02:53'),
(423, 40, 79, '2022-08-18 06:02:59', '2022-08-18 06:02:59'),
(424, 41, 81, '2022-08-18 06:03:03', '2022-08-18 06:03:03'),
(425, 42, 80, '2022-08-18 06:03:08', '2022-08-18 06:03:08'),
(426, 44, 82, '2022-08-18 06:03:16', '2022-08-18 06:03:16'),
(427, 45, 82, '2022-08-18 06:03:21', '2022-08-18 06:03:21'),
(428, 46, 82, '2022-08-18 06:03:27', '2022-08-18 06:03:27'),
(429, 47, 82, '2022-08-18 06:03:30', '2022-08-18 06:03:30'),
(430, 48, 82, '2022-08-18 06:03:34', '2022-08-18 06:03:34'),
(431, 48, 83, '2022-08-18 06:03:34', '2022-08-18 06:03:34'),
(432, 49, 82, '2022-08-18 06:03:37', '2022-08-18 06:03:37'),
(433, 49, 83, '2022-08-18 06:03:37', '2022-08-18 06:03:37'),
(434, 51, 84, '2022-08-18 06:03:43', '2022-08-18 06:03:43'),
(435, 50, 84, '2022-08-18 06:03:47', '2022-08-18 06:03:47'),
(522, 52, 85, '2022-08-18 14:05:37', '2022-08-18 14:05:37'),
(523, 34, 54, '2022-08-18 15:54:04', '2022-08-18 15:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_information`
--

CREATE TABLE `shipping_information` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `address` text,
  `address_2` text,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `password`, `first_name`, `last_name`, `is_admin`, `created_at`, `updated_at`) VALUES
(6, 'chan@gmail.com', '$2y$10$pvDyEwd3TXH3NEl3eRq3nuAG1Gs2WC7nX.Ay/3sMHCZNXU8jFa8uq', 'Chan', 'Verzosa', 1, '2022-08-12 14:15:11', '2022-08-12 14:15:11'),
(7, 'tester3n1@gmail.com', '$2y$10$S7jU86.8n7x34A7GrpFLo.jKjHONOthW2CUMDkRbkvq6e3E8qgZ3e', 'Jane', 'Doe', 0, '2022-08-12 14:37:15', '2022-08-12 14:37:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing_information`
--
ALTER TABLE `billing_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_billing_information_orders1_idx` (`order_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_users1_idx` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_details_orders1_idx` (`order_id`),
  ADD KEY `fk_order_details_products1_idx` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categories_products1_idx` (`product_id`),
  ADD KEY `fk_product_categories_categories1_idx` (`category_id`);

--
-- Indexes for table `shipping_information`
--
ALTER TABLE `shipping_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_shipping_information_orders1_idx` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing_information`
--
ALTER TABLE `billing_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=524;

--
-- AUTO_INCREMENT for table `shipping_information`
--
ALTER TABLE `shipping_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_information`
--
ALTER TABLE `billing_information`
  ADD CONSTRAINT `fk_billing_information_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_order_details_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `fk_categories_products1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_categories_categories1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `shipping_information`
--
ALTER TABLE `shipping_information`
  ADD CONSTRAINT `fk_shipping_information_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
