-- Migrazione: aggiunta del campo `data_nascita` alla tabella `contatti`
-- Eseguire questo script su un database già esistente con la struttura precedente.

USE `rubrica`;

ALTER TABLE `contatti`
  ADD COLUMN `data_nascita` DATE DEFAULT NULL AFTER `indirizzo`;
