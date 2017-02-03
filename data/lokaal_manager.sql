-- phpMyAdmin SQL Dump
-- version 4.6.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 03 feb 2017 om 18:10
-- Serverversie: 5.5.53-MariaDB
-- PHP-versie: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lokaal_manager`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Alarm`
--

CREATE TABLE `Alarm` (
  `id` int(11) NOT NULL,
  `omschrijving` text NOT NULL,
  `prioriteit` int(11) NOT NULL,
  `start_tijd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timeout` int(11) NOT NULL,
  `lokaal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Beweging`
--

CREATE TABLE `Beweging` (
  `id` int(11) NOT NULL,
  `lokaal_id` int(11) NOT NULL,
  `tijd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Lokaal`
--

CREATE TABLE `Lokaal` (
  `id` int(11) NOT NULL,
  `lokaalnummer` varchar(8) NOT NULL,
  `in_gebruik` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `Lokaal`
--

INSERT INTO `Lokaal` (`id`, `lokaalnummer`, `in_gebruik`) VALUES
(4, 'B3206', 1),
(5, 'B3210', 1),
(6, 'B3208', 1),
(7, 'B3212', 1),
(8, 'B3214', 1),
(9, 'B3216', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Lokaalrooster`
--

CREATE TABLE `Lokaalrooster` (
  `id` int(11) NOT NULL,
  `lokaal_id` int(11) NOT NULL,
  `start_tijd` datetime NOT NULL,
  `eind_tijd` datetime NOT NULL,
  `beschrijving` text,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `Temperatuur`
--

CREATE TABLE `Temperatuur` (
  `id` int(11) NOT NULL,
  `lokaal_id` int(11) NOT NULL,
  `temp` float NOT NULL,
  `tijd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `mail` varchar(128) NOT NULL,
  `pass_hash` varchar(256) NOT NULL,
  `date_created` datetime NOT NULL,
  `user_group` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `User_group`
--

CREATE TABLE `User_group` (
  `id` int(11) NOT NULL,
  `rol` varchar(32) NOT NULL DEFAULT 'DEFAULT',
  `lokalen_beheren` tinyint(1) NOT NULL,
  `gebruikers_beheren` tinyint(1) NOT NULL,
  `reserveren` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `Alarm`
--
ALTER TABLE `Alarm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lokaal_id` (`lokaal_id`);

--
-- Indexen voor tabel `Beweging`
--
ALTER TABLE `Beweging`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lokaal_id` (`lokaal_id`);

--
-- Indexen voor tabel `Lokaal`
--
ALTER TABLE `Lokaal`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `Lokaalrooster`
--
ALTER TABLE `Lokaalrooster`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lokaal_id` (`lokaal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `Temperatuur`
--
ALTER TABLE `Temperatuur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lokaal_id` (`lokaal_id`);

--
-- Indexen voor tabel `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `user_group` (`user_group`);

--
-- Indexen voor tabel `User_group`
--
ALTER TABLE `User_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `Alarm`
--
ALTER TABLE `Alarm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT voor een tabel `Beweging`
--
ALTER TABLE `Beweging`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT voor een tabel `Lokaal`
--
ALTER TABLE `Lokaal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT voor een tabel `Lokaalrooster`
--
ALTER TABLE `Lokaalrooster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT voor een tabel `Temperatuur`
--
ALTER TABLE `Temperatuur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4597;
--
-- AUTO_INCREMENT voor een tabel `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `Alarm`
--
ALTER TABLE `Alarm`
  ADD CONSTRAINT `Alarm_ibfk_1` FOREIGN KEY (`lokaal_id`) REFERENCES `Lokaal` (`id`);

--
-- Beperkingen voor tabel `Beweging`
--
ALTER TABLE `Beweging`
  ADD CONSTRAINT `Beweging_ibfk_1` FOREIGN KEY (`lokaal_id`) REFERENCES `Lokaal` (`id`);

--
-- Beperkingen voor tabel `Lokaalrooster`
--
ALTER TABLE `Lokaalrooster`
  ADD CONSTRAINT `Lokaalrooster_ibfk_1` FOREIGN KEY (`lokaal_id`) REFERENCES `Lokaal` (`id`),
  ADD CONSTRAINT `Lokaalrooster_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`);

--
-- Beperkingen voor tabel `Temperatuur`
--
ALTER TABLE `Temperatuur`
  ADD CONSTRAINT `Temperatuur_ibfk_1` FOREIGN KEY (`lokaal_id`) REFERENCES `Lokaal` (`id`);

--
-- Beperkingen voor tabel `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `User_ibfk_1` FOREIGN KEY (`user_group`) REFERENCES `User_group` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
