-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 22, 2025 at 08:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ims_nfc`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `created_at`) VALUES
(1, 'Little Caliphs SEL Seksyen 13 | LittleCaliphsJunior\r\n', 'No. 2-07-1 Blok 2 Presint Alami No. 2 Persiaran Akuatik, Seksyen 13, 40100 Shah Alam, Selangor', '2025-07-14 14:18:31'),
(2, 'Little Caliphs SEL Seksyen 19 | LittleCaliphsJunior\r\n', 'No 63-1, Jalan Nelayan A 19/A, Seksyen 19, 40400 Shah Alam, Selangor', '2025-07-14 14:18:31'),
(3, 'Little Caliphs SEL EC | LittleCaliphsJunior\r\n', 'No. 4, Block C, Jalan Platinum 7/58, Seksyen 7 40000 Shah Alam Selangor', '2025-07-14 14:21:12'),
(4, 'Little Caliphs SEL Seksyen 7 | LittleCaliphsJunior\r\n', 'No. 2, Jalan Pualam Dua 7/32B, Seksyen 7, 40000 Shah Alam, Selangor', '2025-07-14 14:21:12'),
(5, 'Little Caliphs SEL Seksyen 9 | LittleCaliphsJunior\r\n', 'No 1-B, Jalan Tengku Ampuan Zabedah F 9/F, Seksyen 9, 40100 Shah Alam, Selangor', '2025-07-14 14:22:48'),
(6, 'Little Caliphs SEL Sungai Buloh | LittleCaliphsJunior\r\n', 'Lot 73-1, Jalan Nautika A U20/A, Pusat Komesial TSB, 40160 Shah Alam, Selangor', '2025-07-14 14:22:48'),
(7, 'Little Caliphs SEL Pinggiran USJ | LittleCaliphsJunior', 'No 10, Jalan Pinggiran USJ 1/8, Taman Pinggiran USJ, 47600 Subang Jaya, Selangor.', '2025-07-14 14:25:47'),
(8, 'Little Caliphs SEL BBST | LittleCaliphsJunior', 'No. 47-1, Jalan Mawar 3B, Taman Mawar, Bandar Baru Salak Tinggi, 43900 Sepang, Selangor', '2025-07-14 14:25:47'),
(9, 'Little Caliphs SEL Rahman Putra | LittleCaliphsJunior', ' No 1, Jln BRP 7/1G, Bukit Rahman Putra, 47000 Sungai Buloh', '2025-07-14 14:26:51'),
(10, 'Little Caliphs SEL Dataran Abadi | LittleCaliphsJunior\r\n', 'No. 1, Jalan Dataran Abadi, Taman Dataran Abadi, 43900 Sepang, Selangor', '2025-07-14 14:26:51'),
(11, 'Little Caliphs SEL Salak Tinggi | LittleCaliphsJunior', 'No 7, Tingkat 1, Jalan Baiduri 1, 43400 Salak Tinggi Sepang, Selangor', '2025-07-14 14:28:07'),
(12, 'Little Caliphs SEL Denai Alam | LittleCaliphsJunior\r\n', 'No 5-2, First Floor, Jalan Elektron F U16/F, Seksyen U16, 40160 Shah Alam, Selangor', '2025-07-14 14:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('Ethernet Services','AC Services','Fire Extinguisher Services','CCTV Services') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`id`, `name`, `phone`, `email`, `created_at`, `type`) VALUES
(1, 'Ahmad Contractor', '0123456789', 'ahmad@example.com', '2025-07-14 14:38:02', 'AC Services'),
(2, 'Sulaiman Co', '01123456789', 'sulaiman@cctv.com', '2025-07-14 14:38:24', 'CCTV Services');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `address`) VALUES
(7, 'No 63-1, Jalan Nelayan A 19/A, Seksyen 19, 40400 Shah Alam, Selangor'),
(8, 'No. 2, Jalan Pualam Dua 7/32B, Seksyen 7, 40000 Shah Alam, Selangor'),
(9, 'No. 47-1, Jalan Mawar 3B, Taman Mawar, Bandar Baru Salak Tinggi, 43900 Sepang, Selangor');

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`id`, `name`, `location_id`, `location`) VALUES
(7, 'Little Caliphs SEL Seksyen 19 | LittleCaliphsJunior', 7, NULL),
(8, 'Little Caliphs SEL Seksyen 7 | LittleCaliphsJunior', 8, NULL),
(9, 'Little Caliphs SEL BBST | LittleCaliphsJunior', 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `unit_id` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reporter_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports_history`
--

CREATE TABLE `reports_history` (
  `id` int(11) NOT NULL,
  `unit_id` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `reporter_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports_history`
--

INSERT INTO `reports_history` (`id`, `unit_id`, `message`, `reporter_name`, `created_at`, `resolved_at`) VALUES
(3, 'A001', 'ABC', 'ABC', '2025-07-22 00:54:22', '2025-07-22 00:54:32'),
(4, 'W001', 'rosak', 'Teacher Jiha', '2025-07-22 02:27:56', '2025-07-22 02:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `office_id`, `name`) VALUES
(7, 7, 'Level G Teachers room'),
(8, 8, 'Level 1 Student room'),
(9, 9, 'Level G '),
(10, 7, 'Level G ');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `service_phone` varchar(50) DEFAULT NULL,
  `service_desc` text DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services_history`
--

CREATE TABLE `services_history` (
  `id` int(11) NOT NULL,
  `unit_id` varchar(10) NOT NULL,
  `service_date` date NOT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services_history`
--

INSERT INTO `services_history` (`id`, `unit_id`, `service_date`, `service_type`, `contractor`, `remarks`) VALUES
(7, 'A001', '2025-07-21', 'Testing123', 'Ahmad Contractor', 'Testing123'),
(8, 'W001', '2025-07-21', 'setel', 'Developer bro', 'setel');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `unit_id` varchar(50) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `item` varchar(50) DEFAULT NULL,
  `unit_type` varchar(50) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `capacity` varchar(50) DEFAULT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `location_desc` varchar(255) DEFAULT NULL,
  `install_date` date DEFAULT NULL,
  `warranty_date` date DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `last_service` date DEFAULT NULL,
  `next_service` date DEFAULT NULL,
  `service_type` enum('Minor','Major') DEFAULT NULL,
  `track_record` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_id`, `room_id`, `item`, `unit_type`, `brand`, `model`, `capacity`, `branch_name`, `branch_address`, `location_desc`, `install_date`, `warranty_date`, `contractor`, `last_service`, `next_service`, `service_type`, `track_record`) VALUES
(11, 'A001', 7, 'AC', 'Wall Mounted', 'Panasonic', 'CS-PN12WKH', '2HP', 'Little Caliphs SEL Seksyen 19 | LittleCaliphsJunior', 'No 63-1, Jalan Nelayan A 19/A, Seksyen 19, 40400 Shah Alam, Selangor', 'Level G Teachers room', '2025-07-15', '2025-08-15', '1', '2025-07-15', '2025-08-15', 'Minor', 0),
(12, 'W001', 8, 'WIFI', 'Router', 'TP-Link', 'Archer C6', 'AX1800', 'Little Caliphs SEL Seksyen 7 | LittleCaliphsJunior', 'No. 2, Jalan Pualam Dua 7/32B, Seksyen 7, 40000 Shah Alam, Selangor', 'Level 1 Student room', '2025-07-14', '2025-09-14', 'Sulaiman Co', '2025-07-16', '2025-07-17', 'Minor', 0),
(13, 'T001', 9, 'TV', 'LCD', 'Samsung', 'KD-43X7000G', '43 inch', 'Little Caliphs SEL BBST | LittleCaliphsJunior', 'No. 47-1, Jalan Mawar 3B, Taman Mawar, Bandar Baru Salak Tinggi, 43900 Sepang, Selangor', 'Level G ', '2025-07-18', '2025-07-18', 'Ahmad Contractor', '2025-07-27', '2025-07-31', 'Minor', 0),
(14, 'T002', 10, 'TV', 'LED', 'Sony', 'UA43T5300', '55 inch', 'Little Caliphs SEL Seksyen 19 | LittleCaliphsJunior', 'No 63-1, Jalan Nelayan A 19/A, Seksyen 19, 40400 Shah Alam, Selangor', 'Level G ', '2025-07-18', '2026-08-18', 'Ahmad Contractor', '2025-07-18', '2025-08-18', 'Minor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `unit_services`
--

CREATE TABLE `unit_services` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `technician_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('developer','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'developer1', '$2y$10$T74bqNmulo.H8kJKwb.iiefK776nNnXgIEyfCKh9AOkGkMzsSTZoC', 'developer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_ibfk_1` (`unit_id`);

--
-- Indexes for table `reports_history`
--
ALTER TABLE `reports_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `office_id` (`office_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services_history`
--
ALTER TABLE `services_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unit_id` (`unit_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `unit_services`
--
ALTER TABLE `unit_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reports_history`
--
ALTER TABLE `reports_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `services_history`
--
ALTER TABLE `services_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `unit_services`
--
ALTER TABLE `unit_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `offices`
--
ALTER TABLE `offices`
  ADD CONSTRAINT `offices_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services_history`
--
ALTER TABLE `services_history`
  ADD CONSTRAINT `services_history_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unit_services`
--
ALTER TABLE `unit_services`
  ADD CONSTRAINT `unit_services_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
