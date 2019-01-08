-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2019 at 08:41 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `horacekv_web`
--

-- --------------------------------------------------------

--
-- Drop tables
--

DROP TABLE IF EXISTS `horacekv_articles`;
DROP TABLE IF EXISTS `horacekv_reviews`;
DROP TABLE IF EXISTS `horacekv_users`;

-- --------------------------------------------------------

--
-- Table structure for table `horacekv_articles`
--

CREATE TABLE IF NOT EXISTS `horacekv_articles` (
  `ID_ARTICLE` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(80) COLLATE utf8_czech_ci NOT NULL,
  `FILE_NAME` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `FILE_DATA` mediumblob NOT NULL,
  `ABSTRACT` mediumtext COLLATE utf8_czech_ci NOT NULL,
  `REVIEWS` int(10) NOT NULL,
  `STATE` varchar(16) COLLATE utf8_czech_ci NOT NULL,
  `ID_AUTHOR` int(11) NOT NULL,
  PRIMARY KEY (`ID_ARTICLE`),
  UNIQUE KEY `NAME` (`TITLE`),
  KEY `ID_USER` (`ID_AUTHOR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `horacekv_reviews`
--

CREATE TABLE IF NOT EXISTS `horacekv_reviews` (
  `ID_REVIEW` int(11) NOT NULL AUTO_INCREMENT,
  `ID_REVIEWER` int(11) NOT NULL,
  `ID_ARTICLE` int(11) NOT NULL,
  `SCORE_1` int(11) NOT NULL,
  `SCORE_2` int(11) NOT NULL,
  `SCORE_3` int(11) NOT NULL,
  PRIMARY KEY (`ID_REVIEW`),
  KEY `ID_REVIEWER` (`ID_REVIEWER`),
  KEY `ID_ARTICLE` (`ID_ARTICLE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `horacekv_users`
--

CREATE TABLE IF NOT EXISTS `horacekv_users` (
  `ID_USER` int(11) NOT NULL AUTO_INCREMENT,
  `FULL_NAME` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `LOGIN` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `PASSWORD` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  `EMAIL` varchar(35) COLLATE utf8_czech_ci NOT NULL,
  `ROLE` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`ID_USER`),
  UNIQUE KEY `LOGIN` (`LOGIN`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `horacekv_articles`
--
ALTER TABLE `horacekv_articles`
  ADD CONSTRAINT `horacekv_articles_ibfk_1` FOREIGN KEY (`ID_AUTHOR`) REFERENCES `horacekv_users` (`ID_USER`);

--
-- Constraints for table `horacekv_reviews`
--
ALTER TABLE `horacekv_reviews`
  ADD CONSTRAINT `horacekv_reviews_ibfk_1` FOREIGN KEY (`ID_ARTICLE`) REFERENCES `horacekv_articles` (`ID_ARTICLE`),
  ADD CONSTRAINT `horacekv_reviews_ibfk_2` FOREIGN KEY (`ID_REVIEWER`) REFERENCES `horacekv_users` (`ID_USER`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
