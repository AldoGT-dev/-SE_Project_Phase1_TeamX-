SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(3) NOT NULL,
  `car_name` varchar(30) NOT NULL,
  `brand_id` int(3) NOT NULL,
  `type_id` int(3) NOT NULL,
  `color` varchar(20) NOT NULL,
  `model` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `weekly_rate` decimal(10,2) NOT NULL,
  `monthly_rate` decimal(10,2) NOT NULL,
  `mileage` int(11) DEFAULT NULL,
  `transmission` enum('Automatic','Manual') NOT NULL,
  `fuel_type` enum('Gasoline','Diesel','Electric','Hybrid') NOT NULL,
  `seats` int(2) NOT NULL,
  `doors` int(1) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `car_name`, `brand_id`, `type_id`, `color`, `model`, `description`, `daily_rate`, `weekly_rate`, `monthly_rate`, `mileage`, `transmission`, `fuel_type`, `seats`, `doors`, `available`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Porsche Boxster', 6, 4, 'Red', '2017', 'The Porsche Boxster is a mid-engine two-seater roadster. It has a perfect 50/50 weight distribution, delivering exceptional handling. Features include a 2.0L turbocharged flat-4 engine producing 300 hp, 7-speed PDK transmission, and a retractable soft top that opens in just 9 seconds at speeds up to 50 km/h. The interior boasts premium leather seats, a 7-inch touchscreen infotainment system with Apple CarPlay, and a 10-speaker audio system. Safety features include stability control, multiple airbags, and rear parking sensors.', 150.00, 850.00, 3000.00, 25000, 'Automatic', 'Gasoline', 2, 2, 1, 'porsche_boxster.jpg', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(2, 'Audi A5', 1, 4, 'Red', '2017', 'The Audi A5 Coupe combines elegant design with sporty performance. Powered by a 2.0L TFSI engine delivering 252 hp, it features quattro all-wheel drive and a 7-speed S tronic dual-clutch transmission. The luxurious interior includes leather upholstery, heated front seats, a panoramic sunroof, and Audi\'s Virtual Cockpit digital instrument cluster. The MMI infotainment system features a 8.3-inch display, navigation, and Bang & Olufsen 3D sound system. Advanced safety includes adaptive cruise control, lane assist, and pre sense front collision warning.', 120.00, 700.00, 2500.00, 32000, 'Automatic', 'Gasoline', 4, 2, 1, 'audi_a5.jpg', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(3, 'Mercedes-Benz CLS', 4, 4, 'Blue', '2019', 'The Mercedes-Benz CLS is a luxury four-door coupe with striking design and advanced technology. It features a 3.0L inline-6 turbocharged engine with 362 hp and EQ Boost mild hybrid system. The 9G-TRONIC automatic transmission delivers smooth shifts. The interior showcases premium materials with ambient lighting, heated/ventilated front seats with massage function, and dual 12.3-inch displays for the instrument cluster and MBUX infotainment system. Driver assistance features include DISTRONIC adaptive cruise control, active steering assist, and evasive maneuver assist.', 180.00, 1000.00, 3500.00, 15000, 'Automatic', 'Hybrid', 5, 4, 0, 'mercedes_cls.jpg', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
(4, 'Audi A7', 1, 6, 'Blue', '2019', 'The Audi A7 Sportback combines coupe-like styling with hatchback practicality. It features a 3.0L TFSI V6 engine producing 335 hp, paired with a 7-speed S tronic transmission and quattro all-wheel drive. The luxurious cabin includes Valcona leather seats, four-zone climate control, and a panoramic sunroof. The highlight is the dual touchscreen MMI system with haptic feedback and virtual cockpit. The rear hatch offers generous cargo space. Advanced tech includes night vision assist, head-up display, and traffic jam assist.', 160.00, 900.00, 3200.00, 18000, 'Automatic', 'Gasoline', 5, 5, 0, 'audi_a7.jpg', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `car_brands`
--

CREATE TABLE `car_brands` (
  `brand_id` int(3) NOT NULL,
  `brand_name` varchar(50) NOT NULL,
  `brand_image` varchar(255) NOT NULL,
  `brand_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `car_brands`
--

INSERT INTO `car_brands` (`brand_id`, `brand_name`, `brand_image`, `brand_description`) VALUES
(1, 'Audi', 'Audi-A4-Avant-1.jpg', 'A German automobile manufacturer that designs, engineers, produces, markets and distributes luxury vehicles. Audi is a member of the Volkswagen Group and has its roots at Ingolstadt, Bavaria, Germany. Audi-branded vehicles are produced in nine production facilities worldwide.'),
(2, 'BMW', 'bmw-3-series-sedan.jpg', 'Bayerische Motoren Werke AG, commonly referred to as BMW, is a German multinational company which produces luxury vehicles and motorcycles. The company was founded in 1916 as a manufacturer of aircraft engines, which it produced from 1917 until 1918 and again from 1933 to 1945.'),
(3, 'Lexus', '2016-Lexus-RX-350-BM-01.jpg', 'The luxury vehicle division of the Japanese automaker Toyota. The Lexus brand is marketed in more than 70 countries and territories worldwide and is Japan\'s largest-selling make of premium cars. Lexus headquarters are located in Nagoya, Japan. Operational centers are located in Brussels, Belgium, and Plano, Texas, United States.'),
(4, 'Mercedes Benz', 'Mercedes-C-Class-Estate-1.jpg', 'A German global automobile marque and a division of Daimler AG. Mercedes-Benz is known for luxury vehicles, vans, trucks, buses, coaches and ambulances. The headquarters is in Stuttgart, Baden-Württemberg. The name first appeared in 1926 under Daimler-Benz.'),
(5, 'MINI', '2016-MINI-Cooper-S-Clubman-ALL4.jpg', 'A British automotive marque founded in 1969, owned by German automotive company BMW since 2000. The original Mini was a line of small cars produced by the British Motor Corporation (BMC) and its successors from 1959 until 2000.'),
(6, 'Porsche', 'P14_0596_a4_rgb-1.jpg', 'A German automobile manufacturer specializing in high-performance sports cars, SUVs and sedans. Porsche AG is headquartered in Stuttgart, and is owned by Volkswagen AG, which is itself majority-owned by Porsche Automobil Holding SE. Porsche\'s current lineup includes the 718 Boxster/Cayman, 911, Panamera, Macan and Cayenne.');

-- --------------------------------------------------------

--
-- Table structure for table `car_features`
--

CREATE TABLE `car_features` (
  `feature_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `feature_name` varchar(100) NOT NULL,
  `feature_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `car_features`
--

INSERT INTO `car_features` (`feature_id`, `car_id`, `feature_name`, `feature_value`) VALUES
(1, 1, 'Engine', '2.0L Turbocharged Flat-4'),
(2, 1, 'Horsepower', '300 hp'),
(3, 1, 'Top Speed', '275 km/h'),
(4, 1, '0-100 km/h', '4.9 seconds'),
(5, 2, 'Engine', '2.0L TFSI'),
(6, 2, 'Horsepower', '252 hp'),
(7, 2, 'Top Speed', '250 km/h (limited)'),
(8, 2, '0-100 km/h', '5.8 seconds'),
(9, 3, 'Engine', '3.0L Inline-6 Turbo + EQ Boost'),
(10, 3, 'Horsepower', '362 hp'),
(11, 3, 'Top Speed', '250 km/h (limited)'),
(12, 3, '0-100 km/h', '4.8 seconds'),
(13, 4, 'Engine', '3.0L TFSI V6'),
(14, 4, 'Horsepower', '335 hp'),
(15, 4, 'Top Speed', '250 km/h (limited)'),
(16, 4, '0-100 km/h', '5.3 seconds');

-- --------------------------------------------------------

--
-- Table structure for table `car_images`
--

CREATE TABLE `car_images` (
  `image_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `car_images`
--

INSERT INTO `car_images` (`image_id`, `car_id`, `image_path`, `is_primary`) VALUES
(1, 1, 'porsche_boxster_1.jpg', 1),
(2, 1, 'porsche_boxster_2.jpg', 0),
(3, 1, 'porsche_boxster_3.jpg', 0),
(4, 2, 'audi_a5_1.jpg', 1),
(5, 2, 'audi_a5_2.jpg', 0),
(6, 2, 'audi_a5_3.jpg', 0),
(7, 3, 'mercedes_cls_1.jpg', 1),
(8, 3, 'mercedes_cls_2.jpg', 0),
(9, 3, 'mercedes_cls_3.jpg', 0),
(10, 4, 'audi_a7_1.jpg', 1),
(11, 4, 'audi_a7_2.jpg', 0),
(12, 4, 'audi_a7_3.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `car_types`
--

CREATE TABLE `car_types` (
  `type_id` int(3) NOT NULL,
  `type_label` varchar(50) NOT NULL,
  `type_description` varchar(250) NOT NULL,
  `icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `car_types`
--

INSERT INTO `car_types` (`type_id`, `type_label`, `type_description`, `icon`) VALUES
(1, 'Sedan', 'A sedan has four doors and a traditional trunk.', 'sedan-icon.png'),
(4, 'Coupe', 'A coupe has historically been considered a two-door car with a trunk and a solid roof.', 'coupe-icon.png'),
(6, 'HATCHBACK', 'Traditionally, the term \"hatchback\" has meant a compact or subcompact sedan with a squared-off roof and a rear flip-up hatch door that provides access to the vehicle\'s cargo area instead of a conventional trunk.', 'hatchback-icon.png');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(10) NOT NULL,
  `full_name` varchar(30) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_phone` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `driving_license_number` varchar(50) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `full_name`, `client_email`, `client_phone`, `address`, `city`, `country`, `driving_license_number`, `license_expiry`, `date_of_birth`, `registration_date`) VALUES
(5, 'John Doe', 'john_doe@gmail.com', '0123456789', '123 Main Street', 'New York', 'USA', 'DL12345678', '2025-12-31', '1985-06-15', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('Credit Card','Debit Card','Cash','Bank Transfer') NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Completed','Failed','Refunded') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `reservation_id`, `amount`, `payment_date`, `payment_method`, `transaction_id`, `status`) VALUES
(1, 5, 480.00, CURRENT_TIMESTAMP, 'Credit Card', 'PAY123456789', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `rental_history`
--

CREATE TABLE `rental_history` (
  `history_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `rental_start` date NOT NULL,
  `rental_end` date NOT NULL,
  `actual_return_date` date DEFAULT NULL,
  `total_charged` decimal(10,2) DEFAULT NULL,
  `mileage_before` int(11) DEFAULT NULL,
  `mileage_after` int(11) DEFAULT NULL,
  `fuel_level_before` enum('Empty','Quarter','Half','Three Quarters','Full') DEFAULT NULL,
  `fuel_level_after` enum('Empty','Quarter','Half','Three Quarters','Full') DEFAULT NULL,
  `damages` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rental_history`
--

INSERT INTO `rental_history` (`history_id`, `car_id`, `client_id`, `reservation_id`, `rental_start`, `rental_end`, `actual_return_date`, `total_charged`, `mileage_before`, `mileage_after`, `fuel_level_before`, `fuel_level_after`, `damages`, `notes`, `admin_id`, `recorded_at`) VALUES
(1, 4, 5, 5, '2024-03-02', '2024-03-05', '2024-03-05', 480.00, 17800, 17950, 'Full', 'Three Quarters', NULL, 'Client returned car on time with no issues', 1, CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(10) NOT NULL,
  `client_id` int(10) NOT NULL,
  `car_id` int(3) NOT NULL,
  `pickup_date` date NOT NULL,
  `return_date` date NOT NULL,
  `pickup_location` varchar(50) NOT NULL,
  `return_location` varchar(50) NOT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `cancellation_reason` varchar(250) DEFAULT NULL,
  `reservation_status` enum('Pending','Confirmed','Active','Completed','Canceled') NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `client_id`, `car_id`, `pickup_date`, `return_date`, `pickup_location`, `return_location`, `canceled`, `cancellation_reason`, `reservation_status`, `total_amount`, `created_at`, `updated_at`, `admin_notes`) VALUES
(1, 1, 1, '2021-05-11', '2021-05-17', 'Agadir', 'Agadir', 0, NULL, 'Completed', 850.00, '2021-05-01 10:00:00', '2021-05-18 09:30:00', 'Regular customer'),
(2, 2, 3, '2021-04-30', '2021-05-06', 'Agadir', 'Agadir', 0, NULL, 'Completed', 1000.00, '2021-04-20 14:30:00', '2021-05-07 11:15:00', 'Requested child seat'),
(3, 3, 4, '2021-04-30', '2021-05-06', 'Agadir', 'Agadir', 1, 'Changed my mind! Sorry', 'Canceled', 900.00, '2021-04-15 16:45:00', '2021-04-25 10:20:00', 'Client canceled 5 days before pickup'),
(4, 4, 4, '2021-04-29', '2021-05-02', 'Casablanca', 'Agadir', 0, NULL, 'Completed', 480.00, '2021-04-10 09:15:00', '2021-05-03 08:45:00', 'One-way rental with drop-off fee'),
(5, 5, 4, '2024-03-02', '2024-03-05', 'Paris', 'Paris', 0, NULL, 'Completed', 480.00, '2024-02-25 11:30:00', '2024-03-05 16:20:00', 'Corporate client');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `group_id` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `permissions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_email`, `full_name`, `password`, `group_id`, `last_login`, `is_active`, `permissions`) VALUES
(1, 'admin', 'admin@gmail.com', 'Admin Admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 0, CURRENT_TIMESTAMP, 1, '{"manage_cars":true,"manage_clients":true,"manage_reservations":true,"manage_payments":true,"view_reports":true,"system_settings":true}');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_activity_log`
--

INSERT INTO `user_activity_log` (`log_id`, `user_id`, `activity`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Login', 'User logged in successfully', '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', CURRENT_TIMESTAMP),
(2, 1, 'Car Update', 'Updated car details for Audi A7 (ID: 4)', '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', CURRENT_TIMESTAMP),
(3, 1, 'Rental Approval', 'Approved rental for John Doe (Reservation ID: 5)', '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36', CURRENT_TIMESTAMP);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `car_brands`
--
ALTER TABLE `car_brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `car_features`
--
ALTER TABLE `car_features`
  ADD PRIMARY KEY (`feature_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `car_types`
--
ALTER TABLE `car_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `rental_history`
--
ALTER TABLE `rental_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `car_brands`
--
ALTER TABLE `car_brands`
  MODIFY `brand_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `car_features`
--
ALTER TABLE `car_features`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `car_images`
--
ALTER TABLE `car_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `car_types`
--
ALTER TABLE `car_types`
  MODIFY `type_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rental_history`
--
ALTER TABLE `rental_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `car_brands` (`brand_id`),
  ADD CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `car_types` (`type_id`);

--
-- Constraints for table `car_features`
--
ALTER TABLE `car_features`
  ADD CONSTRAINT `car_features_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `rental_history`
--
ALTER TABLE `rental_history`
  ADD CONSTRAINT `rental_history_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`),
  ADD CONSTRAINT `rental_history_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `rental_history_ibfk_3` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`),
  ADD CONSTRAINT `rental_history_ibfk_4` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD CONSTRAINT `user_activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;