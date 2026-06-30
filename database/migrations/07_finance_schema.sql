-- Create transactions table if not exists
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int NOT NULL,
  `type` enum('INCOME','EXPENSE') NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reference_type` enum('PACKAGE','COD_DEPOSIT','OPERATIONAL','OTHER') NOT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `description` text,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_branch_trans` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add payment_type to packages if missing
SET @dbname = DATABASE();

SET @c1 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@dbname AND TABLE_NAME='packages' AND COLUMN_NAME='payment_type');
SET @s1 = IF(@c1=0, "ALTER TABLE `packages` ADD COLUMN `payment_type` enum('CASH','TRANSFER','INVOICE') NOT NULL DEFAULT 'CASH' AFTER `price`", 'SELECT 1');
PREPARE p1 FROM @s1; EXECUTE p1; DEALLOCATE PREPARE p1;

-- Add payment_status to packages if missing
SET @c2 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@dbname AND TABLE_NAME='packages' AND COLUMN_NAME='payment_status');
SET @s2 = IF(@c2=0, "ALTER TABLE `packages` ADD COLUMN `payment_status` enum('PAID','UNPAID','COD') NOT NULL DEFAULT 'UNPAID' AFTER `payment_type`", 'SELECT 1');
PREPARE p2 FROM @s2; EXECUTE p2; DEALLOCATE PREPARE p2;
