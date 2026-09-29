-- Ricrea il DB in minuscolo per coerenza con ambienti case-sensitive
DROP DATABASE IF EXISTS pabsbdec;
CREATE DATABASE pabsbdec CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pabsbdec;

-- UTENTI
CREATE TABLE Utenti (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cognome VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- PRODUTTORE
CREATE TABLE Produttore (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  website VARCHAR(255) NULL
) ENGINE=InnoDB;

-- TIPI PRODOTTI
CREATE TABLE TipoProdotto (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- CATEGORIE
CREATE TABLE Categoria (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  parent_id INT UNSIGNED NULL,
  CONSTRAINT fk_categoria_parent
    FOREIGN KEY (parent_id) REFERENCES Categoria(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- PRODOTTI
CREATE TABLE Prodotto (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  descrizione TEXT NULL,
  prezzo DECIMAL(10,2) NOT NULL,
  produttore_id INT UNSIGNED NULL,
  tipo_id INT UNSIGNED NULL,
  categoria_id INT UNSIGNED NULL,
  immagine_url VARCHAR(255) NULL,
  attivo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_prodotto_nome (nome),
  INDEX idx_prodotto_created (created_at),
  INDEX idx_prodotto_attivo (attivo),
  INDEX idx_prodotto_categoria (categoria_id),
  CONSTRAINT fk_prodotto_produttore
    FOREIGN KEY (produttore_id) REFERENCES Produttore(id) ON DELETE SET NULL,
  CONSTRAINT fk_prodotto_tipo
    FOREIGN KEY (tipo_id) REFERENCES TipoProdotto(id) ON DELETE SET NULL,
  CONSTRAINT fk_prodotto_categoria
    FOREIGN KEY (categoria_id) REFERENCES Categoria(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- CARRELLO
CREATE TABLE Carrello (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  utente_id INT UNSIGNED NOT NULL,
  prodotto_id INT UNSIGNED NOT NULL,
  quantita INT UNSIGNED NOT NULL DEFAULT 1,
  aggiunto_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_carrello_utente
    FOREIGN KEY (utente_id) REFERENCES Utenti(id) ON DELETE CASCADE,
  CONSTRAINT fk_carrello_prodotto
    FOREIGN KEY (prodotto_id) REFERENCES Prodotto(id) ON DELETE CASCADE,
  CONSTRAINT chk_carrello_quantita CHECK (quantita >= 1),
  UNIQUE KEY uq_carrello_utente_prodotto (utente_id, prodotto_id)
) ENGINE=InnoDB;

-- ORDINI
CREATE TABLE Ordine (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  utente_id INT UNSIGNED NOT NULL,
  totale DECIMAL(10,2) NOT NULL,
  stato ENUM('PENDENTE','COMPLETATO','ANNULLATO') NOT NULL DEFAULT 'PENDENTE',
  creato_il DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ordine_utente
    FOREIGN KEY (utente_id) REFERENCES Utenti(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE OrdineItem (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ordine_id INT UNSIGNED NOT NULL,
  prodotto_id INT UNSIGNED NOT NULL,
  quantita INT UNSIGNED NOT NULL,
  prezzo_unitario DECIMAL(10,2) NOT NULL,
  prezzo_totale DECIMAL(10,2) AS (quantita * prezzo_unitario) STORED,
  CONSTRAINT fk_item_ordine
    FOREIGN KEY (ordine_id) REFERENCES Ordine(id) ON DELETE CASCADE,
  CONSTRAINT fk_item_prodotto
    FOREIGN KEY (prodotto_id) REFERENCES Prodotto(id) ON DELETE RESTRICT,
  CONSTRAINT chk_item_quantita CHECK (quantita >= 1)
) ENGINE=InnoDB;

-- Dati minimi
INSERT INTO Produttore (nome) VALUES ('SereCosmetics');
INSERT INTO TipoProdotto (nome) VALUES ('Make-up');
INSERT INTO Categoria (nome) VALUES ('Labbra'), ('Occhi'), ('Viso');

INSERT INTO Prodotto (nome, prezzo, produttore_id, tipo_id, categoria_id, immagine_url) VALUES
('Lip Gloss', 19.90, 1, 1, 1, 'assets/img/sere1.jpg'),
('Mascara', 29.90, 1, 1, 2, 'assets/img/sere2.jpg'),
('Fondotinta', 49.90, 1, 1, 3, 'assets/img/sere3.jpg'),
('Blush liquido', 25.90, 1, 1, 3, 'assets/img/sere4.jpg'),
('Matita sopracciglia', 13.90, 1, 1, 2, 'assets/img/sere5.jpg'),
('Gel sopracciglia', 12.90, 1, 1, 2, 'assets/img/sere6.jpg'),
('Palette occhi', 59.90, 1, 1, 2, 'assets/img/sere7.jpg'),
('Illuminante', 29.90, 1, 1, 2, 'assets/img/sere8.jpg');


