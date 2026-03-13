-- Migration: aggiunta tabella tags e relazione molti-a-molti con contatti
USE `rubrica`;

-- Crea la tabella tags con i valori predefiniti
CREATE TABLE IF NOT EXISTS `tags` (
  `id`   INT(11)      NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tags_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserisce i tag predefiniti
INSERT IGNORE INTO `tags` (`nome`) VALUES
  ('lavoro'),
  ('scuola'),
  ('tempo libero'),
  ('bambini');

-- Crea la tabella pivot contatti_tags per la relazione molti-a-molti
CREATE TABLE IF NOT EXISTS `contatti_tags` (
  `id_contatto` INT(11) NOT NULL,
  `id_tag`      INT(11) NOT NULL,
  PRIMARY KEY (`id_contatto`, `id_tag`),
  CONSTRAINT `fk_ct_contatto` FOREIGN KEY (`id_contatto`) REFERENCES `contatti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ct_tag`      FOREIGN KEY (`id_tag`)      REFERENCES `tags` (`id`)     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
