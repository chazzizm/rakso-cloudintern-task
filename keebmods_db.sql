-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 08:11 PM
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
-- Database: `keebmods_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `shipping_address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image_file` varchar(255) NOT NULL DEFAULT 'placeholder.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image_file`) VALUES
(1, 'Gazzew Boba U4T Tactile (35pcs)', 1150.00, 'The king of tactile thock. Features a large, \"D\" shaped tactile bump with no pre-travel.', 'boba_u4t.jpg'),
(2, 'Cherry MX Rubber O-Rings (120pcs)', 250.00, 'Reduces bottom-out distance and dampens the high-pitched clack of your keystrokes. 40A Shore hardness.', 'o_rings.jpg'),
(3, 'KBDfans Modular Case Foam', 450.00, 'High-density EVA memory foam. Fills the empty space in your keyboard case to eliminate hollowness and metallic ping.', 'case_foam.jpg'),
(4, 'KTT Kang White Linear Switches', 15.00, 'Extremely smooth budget linear switches with a classic poppy sound signature.', 'ktt-kang.jpg'),
(5, 'Gateron Milky Yellow Pro (35pcs)', 450.00, 'The budget linear king. Factory lubed and incredibly smooth out of the box with a deep, thocky sound profile.', 'gateron_yellow.jpg'),
(6, 'Akko V3 Cream Blue Pro (45pcs)', 550.00, 'A snappy and highly tactile switch. Perfect for typists who want a pronounced bump without the heavy spring weight.', 'akko_blue.jpg'),
(7, 'PBT Olivia Clone Keycaps (129-key)', 1250.00, 'Cherry profile, double-shot PBT keycaps. Features the classic pink, black, and white aesthetic. Highly durable and won\'t shine over time.', 'olivia_keycaps.jpg'),
(8, 'Krytox GPL 205g0 Lube (5g)', 350.00, 'The industry standard for switch and stabilizer lubrication. Enough to cover a full-sized keyboard.', 'krytox_205g0.jpg'),
(9, 'Durock V2 Screw-in Stabilizers', 850.00, 'Eliminate rattle completely. Pre-clipped, gold-plated wires, and includes washers to prevent PCB shorting.', 'durock_v2.jpg'),
(10, 'Poron Switch Pads (120pcs)', 150.00, 'Adhesive dampening pads that sit between the switch and the PCB. Reduces ping and creates a more marbled sound.', 'poron_pads.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `username`, `review_text`, `created_at`) VALUES
(1, 4, 'test_user', 'I bought this recently, and it\'s a good switch for those people who are on a budget keyboard looking to upgrade their stock switches!', '2026-04-03 11:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(20) DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'keeb_admin', 'admin@keebmods.local', 'SuperSecretAdmin123!', 'admin'),
(2, 'test_user', 'test@gmail.com', 'password123', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
