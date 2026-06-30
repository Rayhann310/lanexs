-- B2B Schema: customers table + alter packages & users

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) NOT NULL,
  `pic_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `email` varchar(100) DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add customer_id to packages if missing
SET @dbname = DATABASE();

SET @c1 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@dbname AND TABLE_NAME='packages' AND COLUMN_NAME='customer_id');
SET @s1 = IF(@c1=0, 'ALTER TABLE `packages` ADD COLUMN `customer_id` int DEFAULT NULL AFTER `created_by`', 'SELECT 1');
PREPARE p1 FROM @s1; EXECUTE p1; DEALLOCATE PREPARE p1;

-- Add customer_id to users if missing
SET @c2 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@dbname AND TABLE_NAME='users' AND COLUMN_NAME='customer_id');
SET @s2 = IF(@c2=0, 'ALTER TABLE `users` ADD COLUMN `customer_id` int DEFAULT NULL AFTER `role_id`', 'SELECT 1');
PREPARE p2 FROM @s2; EXECUTE p2; DEALLOCATE PREPARE p2;
