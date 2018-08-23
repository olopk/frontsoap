-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 03 Sie 2018, 13:48
-- Wersja serwera: 10.1.32-MariaDB
-- Wersja PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `aplikacja`
--
CREATE DATABASE IF NOT EXISTS `aplikacja` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `aplikacja`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontrahent_status`
--

CREATE TABLE `kontrahent_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `nip` bigint(10) NOT NULL,
  `kod` varchar(5) COLLATE utf8_polish_ci NOT NULL,
  `komunikat` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `kontrahent_status`
--

INSERT INTO `kontrahent_status` (`id`, `nazwa`, `nip`, `kod`, `komunikat`, `data_utworzenia`, `data_aktualizacji`) VALUES
(12, 'OpenIT', 8431614194, 'C', 'Podmiot o podanym identyfikatorze podatkowym NIP jest zarejestrowany jako podatnik VAT czynny', '2018-08-02 15:43:12', '0000-00-00 00:00:00'),
(13, 'Firma Krzak', 843000000, 'I', 'Niepoprawny Numer Identyfikacji Podatkowej', '2018-08-02 15:43:12', '0000-00-00 00:00:00'),
(14, 'Przedsiebiorstwo komunalne', 8430003548, 'C', 'Podmiot o podanym identyfikatorze podatkowym NIP jest zarejestrowany jako podatnik VAT czynny', '2018-08-02 15:43:12', '0000-00-00 00:00:00'),
(15, 'Test', 999111666, 'I', 'Niepoprawny Numer Identyfikacji Podatkowej', '2018-08-02 17:52:06', '0000-00-00 00:00:00'),
(16, 'Costam ktostam', 222444555, 'I', 'Niepoprawny Numer Identyfikacji Podatkowej', '2018-08-02 17:53:55', '2018-08-02 17:57:52');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `create_time`, `update_time`) VALUES
(1, 'Olek', 'Olek', '2018-08-03 11:01:20', '2018-08-03 11:01:20');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kontrahent_status`
--
ALTER TABLE `kontrahent_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `kontrahent_status`
--
ALTER TABLE `kontrahent_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Baza danych: `subiekt`
--
CREATE DATABASE IF NOT EXISTS `subiekt` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `subiekt`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontrahenci`
--

CREATE TABLE `kontrahenci` (
  `id` int(10) UNSIGNED NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `nip` bigint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `kontrahenci`
--

INSERT INTO `kontrahenci` (`id`, `nazwa`, `nip`) VALUES
(1, 'OpenIT', 8431614194),
(2, 'Firma Krzak', 843000000),
(3, 'Przedsiebiorstwo komunalne', 8430003548),
(4, 'Test', 999111666),
(5, 'Costam ktostam', 222444555);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kontrahenci`
--
ALTER TABLE `kontrahenci`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `kontrahenci`
--
ALTER TABLE `kontrahenci`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
