-- Schema completo del database rubrica
CREATE DATABASE IF NOT EXISTS `rubrica`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `rubrica`;

-- Struttura della tabella `aziende`
CREATE TABLE IF NOT EXISTS `aziende` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(255) NOT NULL,
  `settore`     VARCHAR(100) DEFAULT NULL,
  `indirizzo`   VARCHAR(255) DEFAULT NULL,
  `telefono`    VARCHAR(30)  DEFAULT NULL,
  `email`       VARCHAR(100) DEFAULT NULL,
  `sito_web`    VARCHAR(255) DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Struttura della tabella `contatti`
CREATE TABLE IF NOT EXISTS `contatti` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`            VARCHAR(100) NOT NULL,
  `cognome`         VARCHAR(100) NOT NULL,
  `numero_telefono` VARCHAR(30)  NOT NULL,
  `indirizzo`       VARCHAR(255) DEFAULT NULL,
  `data_nascita`    DATE         DEFAULT NULL,
  `id_azienda`      INT(11)      DEFAULT NULL,
  `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_contatti_azienda`
    FOREIGN KEY (`id_azienda`) REFERENCES `aziende` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dati di prova per le aziende
INSERT INTO `aziende` (`nome`, `settore`, `indirizzo`, `telefono`, `email`, `sito_web`) VALUES
('Acme S.p.A.',      'Tecnologia',    'Via del Lavoro 10, 20100 Milano MI',    '+39 02 1234567',  'info@acme.it',           'www.acme.it'),
('Beta Consulting',  'Consulenza',    'Corso Europa 5, 10100 Torino TO',       '+39 011 9876543', 'info@betaconsulting.it', 'www.betaconsulting.it'),
('Gamma Foods',      'Alimentare',    'Via Roma 100, 40100 Bologna BO',        '+39 051 5556789', 'info@gammafoods.it',     'www.gammafoods.it'),
('Delta Finance',    'Finanza',       'Via della Borsa 3, 00100 Roma RM',      '+39 06 4443210',  'info@deltafinance.it',   'www.deltafinance.it'),
('Epsilon Media',    'Comunicazione', 'Via Partenope 50, 80100 Napoli NA',     '+39 081 7778899', 'info@epsilonmedia.it',   'www.epsilonmedia.it');

-- Dati di prova per i contatti (con associazione alle aziende)
INSERT INTO `contatti` (`nome`, `cognome`, `numero_telefono`, `indirizzo`, `data_nascita`, `id_azienda`) VALUES
('Mario',    'Rossi',    '+39 333 1234567', 'Via Roma 1, 00100 Roma RM',             '1985-03-15', 1),
('Lucia',    'Bianchi',  '+39 347 9876543', 'Corso Italia 22, 20100 Milano MI',      '1990-07-22', 2),
('Giovanni', 'Verdi',    '+39 320 5551234', 'Piazza Garibaldi 5, 40100 Bologna BO',  '1978-11-08', 3),
('Anna',     'Ferrari',  '+39 366 4449876', 'Via Manzoni 8, 10100 Torino TO',        '1995-01-30', NULL),
('Marco',    'Esposito', '+39 391 7778899', 'Via Napoli 15, 80100 Napoli NA',        '1982-06-05', NULL);
