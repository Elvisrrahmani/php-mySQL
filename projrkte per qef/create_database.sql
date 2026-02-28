-- SQL schema 
CREATE DATABASE IF NOT EXISTS `moduli8_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `moduli8_db`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example insert
INSERT INTO `users` (`name`, `email`) VALUES
('Alice Example', 'alice@example.test'),
('Bob Example', 'bob@example.test')
ON DUPLICATE KEY UPDATE `email` = `email`;
