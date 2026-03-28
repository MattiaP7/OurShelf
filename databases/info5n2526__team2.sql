-- phpMyAdmin SQL Dump
-- version 5.2.2-1.el10_0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mar 28, 2026 alle 08:29
-- Versione del server: 10.11.11-MariaDB
-- Versione PHP: 8.3.19
-- LANDI E IONUT 

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `info5n2526__team2`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `Annunci`
--

CREATE TABLE `Annunci` (
  `id_annuncio` int(11) NOT NULL,
  `prezzo` int(11) NOT NULL,
  `data_pubblicazione` varchar(50) NOT NULL,
  `data_acquisto` varchar(50) DEFAULT NULL,
  `descrizione` varchar(250) DEFAULT NULL,
  `ora_scambio` varchar(250) DEFAULT NULL,
  `data_scambio` varchar(50) DEFAULT NULL,
  `id_venditore` int(11) NOT NULL,
  `id_compratore` int(11) DEFAULT NULL,
  `id_stato` int(11) NOT NULL,
  `id_condizione` int(11) NOT NULL,
  `id_luogo` int(11) DEFAULT NULL,
  `id_libro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Classi`
--

CREATE TABLE `Classi` (
  `id_classe` int(11) NOT NULL,
  `anno` varchar(200) NOT NULL,
  `sezione` varchar(200) NOT NULL,
  `indirizzo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Classi_Libri`
--

CREATE TABLE `Classi_Libri` (
  `id_libro` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Condizioni`
--

CREATE TABLE `Condizioni` (
  `id_condizione` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Libri`
--

CREATE TABLE `Libri` (
  `id_libro` int(11) NOT NULL,
  `isbn` varchar(14) NOT NULL,
  `titolo` varchar(200) NOT NULL,
  `autore` varchar(200) NOT NULL,
  `materia` varchar(200) NOT NULL,
  `editore` varchar(200) NOT NULL,
  `volume` varchar(200) NOT NULL,
  `anno_scolastico` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Luoghi_Scambi`
--

CREATE TABLE `Luoghi_Scambi` (
  `id_luogo` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Stati`
--

CREATE TABLE `Stati` (
  `id_stato` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Studenti`
--

CREATE TABLE `Studenti` (
  `id_studente` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `data_nascita` varchar(50) NOT NULL,
  `data_iscrizione` varchar(50) NOT NULL,
  `sesso` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `id_classe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Annunci`
--
ALTER TABLE `Annunci`
  ADD PRIMARY KEY (`id_annuncio`),
  ADD KEY `id_venditore` (`id_venditore`),
  ADD KEY `id_compratore` (`id_compratore`),
  ADD KEY `id_stato` (`id_stato`),
  ADD KEY `id_condizione` (`id_condizione`),
  ADD KEY `id_luogo` (`id_luogo`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indici per le tabelle `Classi`
--
ALTER TABLE `Classi`
  ADD PRIMARY KEY (`id_classe`);

--
-- Indici per le tabelle `Classi_Libri`
--
ALTER TABLE `Classi_Libri`
  ADD PRIMARY KEY (`id_libro`,`id_classe`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Indici per le tabelle `Condizioni`
--
ALTER TABLE `Condizioni`
  ADD PRIMARY KEY (`id_condizione`);

--
-- Indici per le tabelle `Libri`
--
ALTER TABLE `Libri`
  ADD PRIMARY KEY (`id_libro`);

--
-- Indici per le tabelle `Luoghi_Scambi`
--
ALTER TABLE `Luoghi_Scambi`
  ADD PRIMARY KEY (`id_luogo`);

--
-- Indici per le tabelle `Stati`
--
ALTER TABLE `Stati`
  ADD PRIMARY KEY (`id_stato`);

--
-- Indici per le tabelle `Studenti`
--
ALTER TABLE `Studenti`
  ADD PRIMARY KEY (`id_studente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_classe` (`id_classe`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Annunci`
--
ALTER TABLE `Annunci`
  MODIFY `id_annuncio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Classi`
--
ALTER TABLE `Classi`
  MODIFY `id_classe` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Condizioni`
--
ALTER TABLE `Condizioni`
  MODIFY `id_condizione` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Libri`
--
ALTER TABLE `Libri`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Luoghi_Scambi`
--
ALTER TABLE `Luoghi_Scambi`
  MODIFY `id_luogo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Stati`
--
ALTER TABLE `Stati`
  MODIFY `id_stato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `Studenti`
--
ALTER TABLE `Studenti`
  MODIFY `id_studente` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Annunci`
--
ALTER TABLE `Annunci`
  ADD CONSTRAINT `Annunci_ibfk_1` FOREIGN KEY (`id_venditore`) REFERENCES `Studenti` (`id_studente`),
  ADD CONSTRAINT `Annunci_ibfk_2` FOREIGN KEY (`id_compratore`) REFERENCES `Studenti` (`id_studente`),
  ADD CONSTRAINT `Annunci_ibfk_3` FOREIGN KEY (`id_stato`) REFERENCES `Stati` (`id_stato`),
  ADD CONSTRAINT `Annunci_ibfk_4` FOREIGN KEY (`id_condizione`) REFERENCES `Condizioni` (`id_condizione`),
  ADD CONSTRAINT `Annunci_ibfk_5` FOREIGN KEY (`id_luogo`) REFERENCES `Luoghi_Scambi` (`id_luogo`),
  ADD CONSTRAINT `Annunci_ibfk_6` FOREIGN KEY (`id_libro`) REFERENCES `Libri` (`id_libro`);

--
-- Limiti per la tabella `Classi_Libri`
--
ALTER TABLE `Classi_Libri`
  ADD CONSTRAINT `Classi_Libri_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `Libri` (`id_libro`),
  ADD CONSTRAINT `Classi_Libri_ibfk_2` FOREIGN KEY (`id_classe`) REFERENCES `Classi` (`id_classe`);

--
-- Limiti per la tabella `Studenti`
--
ALTER TABLE `Studenti`
  ADD CONSTRAINT `Studenti_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `Classi` (`id_classe`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
