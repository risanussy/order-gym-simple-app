-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
-- Tabel untuk users/admins
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk memberships
CREATE TABLE memberships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    member_number VARCHAR(50) NOT NULL UNIQUE,
    join_date DATE NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
    
-- Dumping data for table order_gym.memberships: ~2 rows (approximately)
INSERT INTO `memberships` (`id`, `name`, `member_number`, `join_date`, `phone_number`, `amount`, `created_at`) VALUES
	(1, 'Risa nussy ', '123', '2024-08-15', '08888', 1000.00, '2024-08-19 22:01:34'),
	(2, 'user ', '124', '2024-08-22', '08888', 1000.00, '2024-08-19 22:02:01');

-- Dumping data for table order_gym.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
	(1, 'admin', 'admin@admin.com', '$2y$10$gceYk/LPAbxb/S8OxuZXoegOTERO.JSZLeyAp7Uaht6KZBP2zEmPW', '2024-08-19 21:55:32');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
