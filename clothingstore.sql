-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2026 at 09:06 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

DROP TABLE IF EXISTS `tbladmin`;
CREATE TABLE IF NOT EXISTS `tbladmin` (
  `AdminID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AdminID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`AdminID`, `Name`, `Email`, `Username`, `Password`, `CreatedAt`) VALUES
(1, 'Admin User', 'admin@pastimes.local', 'admin', '0192023a7bbd73250516f069df18b500', '2026-05-03 16:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

DROP TABLE IF EXISTS `tblclothes`;
CREATE TABLE IF NOT EXISTS `tblclothes` (
  `ClothesID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Brand` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `ConditionType` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ImageURL` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'images/homephoto.png',
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ClothesID`),
  KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`ClothesID`, `Name`, `Brand`, `Price`, `ConditionType`, `Username`, `ImageURL`, `CreatedAt`) VALUES
(1, 'Nike Hoodie', 'Nike', 1290.00, 'Good', 'jdoe', 'images/nike_hoodie.png', '2026-05-03 16:58:54'),
(2, 'Adidas Shirt', 'Adidas', 890.00, 'Excellent', 'jsmith', 'images/adidas_shirt.png', '2026-05-03 16:58:54'),
(3, 'Zara Jacket', 'Zara', 2450.00, 'Good', 'mbrown', 'images/zara_jacket.png', '2026-05-03 16:58:54'),
(4, 'Puma Sneakers', 'Puma', 1590.00, 'Like New', 'slee', 'images/puma_sneakers.png', '2026-05-03 16:58:54'),
(5, 'H&M Jeans', 'H&M', 950.00, 'Good', 'cwhite', 'images/hm_jeans.png', '2026-05-03 16:58:54'),
(6, 'Red dress', 'Shein', 230.00, 'Good', 'kea2026', 'images/red_dress.png', '2026-05-04 10:57:08');

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

DROP TABLE IF EXISTS `tblorder`;
CREATE TABLE IF NOT EXISTS `tblorder` (
  `OrderID` int NOT NULL AUTO_INCREMENT,
  `UserID` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `OrderDate` date NOT NULL,
  `Status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`OrderID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

DROP TABLE IF EXISTS `tbluser`;
CREATE TABLE IF NOT EXISTS `tbluser` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Role` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `IsVerified` tinyint(1) NOT NULL DEFAULT '0',
  `CreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`UserID`, `Name`, `Email`, `Username`, `Password`, `Role`, `IsVerified`, `CreatedAt`) VALUES
(1, 'John Doe', 'j.doe@abc.co.za', 'jdoe', '482c811da5d5b4bc6d497ffa98491e38', 'customer', 1, '2026-05-03 16:58:54'),
(2, 'Jane Smith', 'j.smith@abc.co.za', 'jsmith', '96b33694c4bb7dbd07391e0be54745fb', 'customer', 1, '2026-05-03 16:58:54'),
(3, 'Mike Brown', 'm.brown@abc.co.za', 'mbrown', '7d347cf0ee68174a3588f6cba31b8a67', 'customer', 1, '2026-05-03 16:58:54'),
(4, 'Sarah Lee', 's.lee@abc.co.za', 'slee', 'db6ae64dfa9e78039db6df5b8edbc38c', 'customer', 1, '2026-05-03 16:58:54'),
(5, 'Chris White', 'c.white@abc.co.za', 'cwhite', 'b3009649b95347794283e38005637a7d', 'customer', 1, '2026-05-03 16:58:54'),
(6, 'mutonda', 'mutondamuofhe04@gmail.com', 'mutonda', '7fa3639f6b5fa70da0c1032d2ad1941c', 'customer', 1, '2026-05-04 10:38:38'),
(7, 'kea', 'kea@gmail.com', 'kea2026', '79c579baf5883b23729eab47b7278324', 'customer', 1, '2026-05-04 10:51:55');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD CONSTRAINT `tblclothes_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `tbluser` (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
