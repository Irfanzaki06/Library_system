-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 04:06 PM
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
-- Database: `dbs_5`
--

-- --------------------------------------------------------

--
-- Table structure for table `booklist`
--

CREATE TABLE `booklist` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `published_date` date DEFAULT NULL,
  `genre1` varchar(100) DEFAULT NULL,
  `genre2` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `synopsis` text DEFAULT NULL,
  `book_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booklist`
--

INSERT INTO `booklist` (`id`, `title`, `author`, `published_date`, `genre1`, `genre2`, `type`, `synopsis`, `book_image`) VALUES
(12, 'Harry Potter', 'JK Rowling', '2025-10-18', 'Mystery', 'Action', 'Novel', 'harry yang sedang potter', 'uploads/68f2765922f75_1760720473.jpeg'),
(14, 'Upin ipin keris siamang tunggal', 'Md radzi', '2025-10-02', 'Comedy', 'Action', 'Comic', 'upin ipin mengembara bersama rakan-rakan', 'uploads/69003c32c8499_1761623090.jpg'),
(15, 'Boboiboy Sori', 'Nizam Razak', '2025-10-05', 'Drama', 'Motivational', 'Magazine', 'Boboiboy bertukar kepada boboiboy soriiii', 'uploads/69003d095e947_1761623305.jpg'),
(16, 'Spy x Family', 'irfan zaki', '2025-10-31', 'Motivational', 'Religion', 'Newspaper', 'kisah anak muda bernama anya yang dikurniakan power semula jadi', 'uploads/69003d6b3aae4_1761623403.jpg'),
(17, 'Jujutsu Kaisen', 'Irfan Hakeem', '2025-10-24', 'Action', 'Drama', 'Comic', 'Yuji secara tiba-tiba terlibat dalam dunia Jujutsu', 'uploads/69003db73f900_1761623479.jpg'),
(18, 'Dark Gathering', 'Lutfil Hadi', '2025-10-11', 'Horror', 'Mystery', 'Novel', 'Kisah hantuuuu yang gempaksss', 'uploads/69003e3625e2d_1761623606.jpg'),
(19, 'Gotoubun no hanayome', 'Zaki Zakaria', '2025-10-27', 'Drama', 'Education', 'Magazine', 'kisah lima beradik yang ingin berjaya dalam hidup', 'uploads/69003eb2d7fbc_1761623730.jpg'),
(20, 'Attack on Titan', 'Ammar rashid', '2025-10-03', 'Action', 'Mystery', 'Newspaper', 'Kisah seorang eren Yeager yang ingin merasai kebebasan', 'uploads/69003f1556656_1761623829.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_return`
--

CREATE TABLE `borrow_return` (
  `id` int(11) NOT NULL,
  `reader_id` varchar(20) NOT NULL,
  `book_title` varchar(200) NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `status` enum('borrowed','returned','overdue') DEFAULT 'borrowed',
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `readers`
--

CREATE TABLE `readers` (
  `id` int(11) NOT NULL,
  `reader_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `membership_date` date DEFAULT curdate(),
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `readers`
--

INSERT INTO `readers` (`id`, `reader_id`, `full_name`, `email`, `phone`, `address`, `date_of_birth`, `membership_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'R28417897', 'ipan', 'gg@gmail.com', '018 777b0099', 'jalan tepi sekali', '2025-10-18', '2025-10-18', 'active', '2025-10-17 16:08:26', '2025-10-17 16:08:26'),
(2, 'R27021596', 'rashdan', 'r@gmail.com', '011 666 7890', 'jalan mati', '2025-10-06', '2025-10-18', 'active', '2025-10-17 16:58:45', '2025-10-17 17:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `is_active`) VALUES
(1, 'irfanhakeem', 'pan@gmail.com', 'Irf123', '2025-09-29 09:34:39', 1),
(2, 'Admin1', 'Admin1@gmail.com', 'Abc123', '2025-09-29 11:19:01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booklist`
--
ALTER TABLE `booklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow_return`
--
ALTER TABLE `borrow_return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_borrow_return_reader_id` (`reader_id`),
  ADD KEY `idx_borrow_return_status` (`status`);

--
-- Indexes for table `readers`
--
ALTER TABLE `readers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reader_id` (`reader_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_readers_reader_id` (`reader_id`),
  ADD KEY `idx_readers_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booklist`
--
ALTER TABLE `booklist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `borrow_return`
--
ALTER TABLE `borrow_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `readers`
--
ALTER TABLE `readers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrow_return`
--
ALTER TABLE `borrow_return`
  ADD CONSTRAINT `borrow_return_ibfk_1` FOREIGN KEY (`reader_id`) REFERENCES `readers` (`reader_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
