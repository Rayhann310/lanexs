-- Migration: Create services table
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(20) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `estimated_days` INT DEFAULT 1,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add service_id column to packages table
ALTER TABLE `packages` 
    ADD COLUMN IF NOT EXISTS `service_id` INT NULL,
    ADD COLUMN IF NOT EXISTS `service_name` VARCHAR(100) NULL;

-- Seed default services
INSERT IGNORE INTO `services` (`code`, `name`, `description`, `estimated_days`) VALUES
    ('REG', 'Reguler', 'Pengiriman reguler standar', 3),
    ('EXP', 'Express', 'Pengiriman ekspres lebih cepat', 1),
    ('KARGO', 'Kargo', 'Pengiriman kargo/barang besar', 5),
    ('FROZEN', 'Frozen', 'Pengiriman barang beku/dingin', 2),
    ('MOTOR', 'Motor', 'Pengiriman via motor untuk jarak dekat', 1);
