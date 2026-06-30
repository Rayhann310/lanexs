CREATE TABLE IF NOT EXISTS `tariffs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('BRANCH','CITY') NOT NULL DEFAULT 'CITY',
  `origin_branch_id` int DEFAULT NULL,
  `destination_branch_id` int DEFAULT NULL,
  `origin_city` varchar(100) DEFAULT NULL,
  `destination_city` varchar(100) DEFAULT NULL,
  `price_per_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estimated_days` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tariff_type` (`type`),
  KEY `fk_tariffs_origin` (`origin_branch_id`),
  KEY `fk_tariffs_dest` (`destination_branch_id`),
  CONSTRAINT `fk_tariffs_origin` FOREIGN KEY (`origin_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tariffs_dest` FOREIGN KEY (`destination_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
