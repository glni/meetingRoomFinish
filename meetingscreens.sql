-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Vært: 127.0.0.1:3306
-- Genereringstid: 28. 02 2024 kl. 12:32:11
-- Serverversion: 10.6.5-MariaDB
-- PHP-version: 8.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meetingscreens`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `meetingrooms`
--

DROP TABLE IF EXISTS `meetingrooms`;
CREATE TABLE IF NOT EXISTS `meetingrooms` (
                                              `meroId` int(11) NOT NULL AUTO_INCREMENT,
    `meroNumber` int(11) NOT NULL,
    `meroName` varchar(150) COLLATE utf8mb4_danish_ci NOT NULL,
    `meroPersons` int(11) NOT NULL,
    `meroScreen` tinyint(1) NOT NULL,
    PRIMARY KEY (`meroId`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Data dump for tabellen `meetingrooms`
--

INSERT INTO `meetingrooms` (`meroId`, `meroNumber`, `meroName`, `meroPersons`, `meroScreen`) VALUES
    (1, 100, 'Stavanger', 22, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `meetings`
--

DROP TABLE IF EXISTS `meetings`;
CREATE TABLE IF NOT EXISTS `meetings` (
                                          `meetId` int(11) NOT NULL AUTO_INCREMENT,
    `meetMeetingRoomsId` int(11) DEFAULT NULL,
    `meetDateFrom` datetime NOT NULL,
    `meetDateTo` datetime NOT NULL,
    `meetNames` varchar(250) COLLATE utf8mb4_danish_ci NOT NULL,
    `meetDepartment` varchar(250) COLLATE utf8mb4_danish_ci NOT NULL,
    `meetImage` varchar(255) COLLATE utf8mb4_danish_ci DEFAULT NULL,
    PRIMARY KEY (`meetId`)
    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Data dump for tabellen `meetings`
--

INSERT INTO `meetings` (`meetId`, `meetMeetingRoomsId`, `meetDateFrom`, `meetDateTo`, `meetNames`, `meetDepartment`, `meetImage`) VALUES
                                                                                                                                      (1, 1, '2024-02-28 08:00:00', '2024-02-28 09:30:00', 'Adam, Bent, Carl', 'Salgsafdeling', 'uifaces1.jpg'),
                                                                                                                                      (2, 1, '2024-02-28 10:00:00', '2024-02-28 11:00:00', 'Adam, Bent, Carl', 'Finansafdeling', 'uifaces2.jpg'),
                                                                                                                                      (3, 1, '2024-02-28 11:00:00', '2024-02-28 12:00:00', 'Adam, Bent, Carl', 'IT-afdeling', 'uifaces3.jpg'),
                                                                                                                                      (4, 1, '2024-02-28 12:50:00', '2024-02-28 14:45:00', 'Adam, Bent, Carl', 'HR og Løn', 'uifaces4.jpg'),
                                                                                                                                      (5, 1, '2024-02-29 08:00:00', '2024-02-29 09:00:00', 'Adam, Bent, Carl', 'Salgsafdeling', 'uifaces5.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
