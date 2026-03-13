-- Struttura della tabella `contatti`
CREATE DATABASE IF NOT EXISTS `rubrica`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `rubrica`;

CREATE TABLE IF NOT EXISTS `contatti` (
  `id`              INT(11)      NOT NULL AUTO_INCREMENT,
  `nome`            VARCHAR(100) NOT NULL,
  `cognome`         VARCHAR(100) NOT NULL,
  `numero_telefono` VARCHAR(30)  NOT NULL,
  `indirizzo`       VARCHAR(255) DEFAULT NULL,
  `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dati di prova
INSERT INTO `contatti` (`nome`, `cognome`, `numero_telefono`, `indirizzo`) VALUES
('Mario',    'Rossi',    '+39 333 1234567', 'Via Roma 1, 00100 Roma RM'),
('Lucia',    'Bianchi',  '+39 347 9876543', 'Corso Italia 22, 20100 Milano MI'),
('Giovanni', 'Verdi',    '+39 320 5551234', 'Piazza Garibaldi 5, 40100 Bologna BO'),
('Anna',     'Ferrari',  '+39 366 4449876', 'Via Manzoni 8, 10100 Torino TO'),
('Marco',    'Esposito', '+39 391 7778899', 'Via Napoli 15, 80100 Napoli NA');
