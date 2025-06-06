CREATE DATABASE IF NOT EXISTS meetingscreens CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci;

USE meetingscreens;

DROP TABLE IF EXISTS `meetingrooms`;
CREATE TABLE IF NOT EXISTS `meetingrooms` (
    `meroId` int(11) NOT NULL AUTO_INCREMENT,
    `meroNumber` int(11) NOT NULL,
    `meroName` varchar(150) COLLATE utf8mb4_danish_ci NOT NULL,
    `meroPersons` int(11) NOT NULL,
    `meroScreen` tinyint(1) NOT NULL,
    PRIMARY KEY (`meroId`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

INSERT INTO `meetingrooms` (`meroId`, `meroNumber`, `meroName`, `meroPersons`, `meroScreen`) VALUES
    (1, 100, 'Stavanger', 22, 1);

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

INSERT INTO `meetings` (`meetId`, `meetMeetingRoomsId`, `meetDateFrom`, `meetDateTo`, `meetNames`, `meetDepartment`, `meetImage`)
VALUES (1, 1, '2024-02-28 08:00:00', '2024-02-28 09:30:00', 'Adam, Bent, Carl', 'Salgsafdeling', 'uifaces1.jpg'),
       (2, 1, '2024-02-28 10:00:00', '2024-02-28 11:00:00', 'Adam, Bent, Carl', 'Finansafdeling', 'uifaces2.jpg'),
       (3, 1, '2024-02-28 11:00:00', '2024-02-28 12:00:00', 'Adam, Bent, Carl', 'IT-afdeling', 'uifaces3.jpg'),
       (4, 1, '2024-02-28 12:50:00', '2024-02-28 14:45:00', 'Adam, Bent, Carl', 'HR og Løn', 'uifaces4.jpg'),
       (5, 1, '2024-02-29 08:00:00', '2024-02-29 09:00:00', 'Adam, Bent, Carl', 'Salgsafdeling', 'uifaces5.jpg');
COMMIT;

CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON *.* TO 'user'@'%';
FLUSH PRIVILEGES;
