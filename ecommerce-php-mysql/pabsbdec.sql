-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 24, 2025 alle 13:12
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pabsbdec`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `carrello`
--

CREATE TABLE `carrello` (
  `id` int(11) NOT NULL,
  `utente_id` int(11) NOT NULL,
  `prodotto_id` int(11) NOT NULL,
  `quantita` int(11) NOT NULL DEFAULT 1,
  `aggiunto_il` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `parent_id`) VALUES
(1, 'Labbra', NULL),
(2, 'Occhi', NULL),
(3, 'Viso', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `id` int(11) NOT NULL,
  `utente_id` int(11) NOT NULL,
  `totale` decimal(10,2) NOT NULL,
  `stato` enum('PENDENTE','COMPLETATO','ANNULLATO') NOT NULL DEFAULT 'PENDENTE',
  `creato_il` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ordine`
--

INSERT INTO `ordine` (`id`, `utente_id`, `totale`, `stato`, `creato_il`) VALUES
(1, 1, 19.90, 'COMPLETATO', '2025-09-23 11:00:14'),
(2, 1, 19.90, 'COMPLETATO', '2025-09-23 11:01:41'),
(3, 1, 19.90, 'COMPLETATO', '2025-09-23 11:06:17'),
(4, 1, 19.90, 'COMPLETATO', '2025-09-23 11:12:28'),
(5, 1, 242.20, 'COMPLETATO', '2025-09-23 13:39:17'),
(6, 1, 39.80, 'COMPLETATO', '2025-09-23 14:01:42'),
(7, 1, 19.90, 'COMPLETATO', '2025-09-24 13:03:01');

-- --------------------------------------------------------

--
-- Struttura della tabella `ordineitem`
--

CREATE TABLE `ordineitem` (
  `id` int(11) NOT NULL,
  `ordine_id` int(11) NOT NULL,
  `prodotto_id` int(11) NOT NULL,
  `quantita` int(11) NOT NULL,
  `prezzo_unitario` decimal(10,2) NOT NULL,
  `prezzo_totale` decimal(10,2) GENERATED ALWAYS AS (`quantita` * `prezzo_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ordineitem`
--

INSERT INTO `ordineitem` (`id`, `ordine_id`, `prodotto_id`, `quantita`, `prezzo_unitario`) VALUES
(1, 1, 1, 1, 19.90),
(2, 2, 1, 1, 19.90),
(3, 3, 1, 1, 19.90),
(4, 4, 1, 1, 19.90),
(5, 5, 1, 1, 19.90),
(6, 5, 2, 1, 29.90),
(7, 5, 3, 1, 49.90),
(8, 5, 4, 1, 25.90),
(9, 5, 5, 1, 13.90),
(10, 5, 6, 1, 12.90),
(11, 5, 7, 1, 59.90),
(12, 5, 8, 1, 29.90),
(13, 6, 1, 2, 19.90),
(14, 7, 1, 1, 19.90);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotto`
--

CREATE TABLE `prodotto` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `produttore_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `immagine_url` varchar(255) DEFAULT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `prodotto`
--

INSERT INTO `prodotto` (`id`, `nome`, `descrizione`, `prezzo`, `produttore_id`, `tipo_id`, `categoria_id`, `immagine_url`, `attivo`, `created_at`) VALUES
(1, 'Lip Gloss', NULL, 19.90, 1, 1, 1, 'assets/img/sere1.jpg', 1, '2025-09-17 17:50:28'),
(2, 'Mascara', NULL, 29.90, 1, 1, 2, 'assets/img/sere2.jpg', 1, '2025-09-17 17:50:28'),
(3, 'Fondotinta', NULL, 49.90, 1, 1, 3, 'assets/img/sere3.jpg', 1, '2025-09-17 17:50:28'),
(4, 'Blush liquido', NULL, 25.90, 1, 1, 3, 'assets/img/sere4.jpg', 1, '2025-09-17 17:50:28'),
(5, 'Matita sopracciglia', NULL, 13.90, 1, 1, 2, 'assets/img/sere5.jpg', 1, '2025-09-17 17:50:28'),
(6, 'Gel sopracciglia', NULL, 12.90, 1, 1, 2, 'assets/img/sere6.jpg', 1, '2025-09-17 17:50:28'),
(7, 'Palette occhi', NULL, 59.90, 1, 1, 2, 'assets/img/sere7.jpg', 1, '2025-09-17 17:50:28'),
(8, 'Illuminante', NULL, 29.90, 1, 1, 2, 'assets/img/sere8.jpg', 1, '2025-09-17 17:50:28');

-- --------------------------------------------------------

--
-- Struttura della tabella `produttore`
--

CREATE TABLE `produttore` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `produttore`
--

INSERT INTO `produttore` (`id`, `nome`, `website`) VALUES
(1, 'SereCosmetics', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `id` int(11) NOT NULL,
  `prodotto_id` int(11) NOT NULL,
  `utente_id` int(11) NOT NULL,
  `voto` int(11) NOT NULL CHECK (`voto` between 1 and 5),
  `commento` text DEFAULT NULL,
  `creato_il` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `tipoprodotto`
--

CREATE TABLE `tipoprodotto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tipoprodotto`
--

INSERT INTO `tipoprodotto` (`id`, `nome`) VALUES
(1, 'Make-up');

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_prodotto`
--

CREATE TABLE `tipo_prodotto` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome`, `cognome`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Serena', 'Sammiceli', 'serena@gmail.com', '$2y$10$bHTxrc0W3hvU85MYE1CdN.BT53V.RIg6b54gaCwyULRNazTJjdmpm', '2025-09-22 13:58:15');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carrello_utente` (`utente_id`),
  ADD KEY `fk_carrello_prodotto` (`prodotto_id`);

--
-- Indici per le tabelle `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria_parent` (`parent_id`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ordine_utente` (`utente_id`);

--
-- Indici per le tabelle `ordineitem`
--
ALTER TABLE `ordineitem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_ordine` (`ordine_id`),
  ADD KEY `fk_item_prodotto` (`prodotto_id`);

--
-- Indici per le tabelle `prodotto`
--
ALTER TABLE `prodotto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prodotto_nome` (`nome`),
  ADD KEY `fk_prodotto_produttore` (`produttore_id`),
  ADD KEY `fk_prodotto_tipo` (`tipo_id`),
  ADD KEY `idx_prodotto_created` (`created_at`),
  ADD KEY `idx_prodotto_attivo` (`attivo`),
  ADD KEY `idx_prodotto_categoria` (`categoria_id`);

--
-- Indici per le tabelle `produttore`
--
ALTER TABLE `produttore`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `recensione`
--
ALTER TABLE `recensione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recensione_prodotto` (`prodotto_id`),
  ADD KEY `fk_recensione_utente` (`utente_id`),
  ADD KEY `idx_recensione_voto` (`voto`);

--
-- Indici per le tabelle `tipoprodotto`
--
ALTER TABLE `tipoprodotto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `tipo_prodotto`
--
ALTER TABLE `tipo_prodotto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `carrello`
--
ALTER TABLE `carrello`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `ordineitem`
--
ALTER TABLE `ordineitem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `produttore`
--
ALTER TABLE `produttore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `recensione`
--
ALTER TABLE `recensione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tipoprodotto`
--
ALTER TABLE `tipoprodotto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `tipo_prodotto`
--
ALTER TABLE `tipo_prodotto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `fk_carrello_prodotto` FOREIGN KEY (`prodotto_id`) REFERENCES `prodotto` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_carrello_utente` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `fk_categoria_parent` FOREIGN KEY (`parent_id`) REFERENCES `categoria` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `fk_ordine_utente` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `ordineitem`
--
ALTER TABLE `ordineitem`
  ADD CONSTRAINT `fk_item_ordine` FOREIGN KEY (`ordine_id`) REFERENCES `ordine` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_item_prodotto` FOREIGN KEY (`prodotto_id`) REFERENCES `prodotto` (`id`);

--
-- Limiti per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  ADD CONSTRAINT `fk_prodotto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_prodotto_produttore` FOREIGN KEY (`produttore_id`) REFERENCES `produttore` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_prodotto_tipo` FOREIGN KEY (`tipo_id`) REFERENCES `tipoprodotto` (`id`) ON DELETE SET NULL;

--
-- Limiti per la tabella `recensione`
--
ALTER TABLE `recensione`
  ADD CONSTRAINT `fk_recensione_prodotto` FOREIGN KEY (`prodotto_id`) REFERENCES `prodotto` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_recensione_utente` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
