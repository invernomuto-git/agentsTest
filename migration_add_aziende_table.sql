-- Migration: aggiunta tabella aziende e relazione con contatti
USE `rubrica`;

-- Crea la tabella aziende
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

-- Aggiunge la colonna id_azienda alla tabella contatti
ALTER TABLE `contatti`
  ADD COLUMN `id_azienda` INT(11) DEFAULT NULL AFTER `data_nascita`,
  ADD CONSTRAINT `fk_contatti_azienda`
    FOREIGN KEY (`id_azienda`) REFERENCES `aziende` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Dati di prova per le aziende
INSERT INTO `aziende` (`nome`, `settore`, `indirizzo`, `telefono`, `email`, `sito_web`) VALUES
('Acme S.p.A.',      'Tecnologia',    'Via del Lavoro 10, 20100 Milano MI',    '+39 02 1234567',  'info@acme.it',              'www.acme.it'),
('Beta Consulting',  'Consulenza',    'Corso Europa 5, 10100 Torino TO',       '+39 011 9876543', 'info@betaconsulting.it',    'www.betaconsulting.it'),
('Gamma Foods',      'Alimentare',    'Via Roma 100, 40100 Bologna BO',        '+39 051 5556789', 'info@gammafoods.it',        'www.gammafoods.it'),
('Delta Finance',    'Finanza',       'Via della Borsa 3, 00100 Roma RM',      '+39 06 4443210',  'info@deltafinance.it',      'www.deltafinance.it'),
('Epsilon Media',    'Comunicazione', 'Via Partenope 50, 80100 Napoli NA',     '+39 081 7778899', 'info@epsilonmedia.it',      'www.epsilonmedia.it');

-- Associa i contatti di prova alle aziende (gli ultimi due rimangono senza azienda)
UPDATE `contatti` SET `id_azienda` = 1 WHERE `cognome` = 'Rossi'    AND `nome` = 'Mario';
UPDATE `contatti` SET `id_azienda` = 2 WHERE `cognome` = 'Bianchi'  AND `nome` = 'Lucia';
UPDATE `contatti` SET `id_azienda` = 3 WHERE `cognome` = 'Verdi'    AND `nome` = 'Giovanni';
