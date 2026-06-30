CREATE TABLE IF NOT EXISTS `bags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bag_code` varchar(50) NOT NULL,
  `origin_branch_id` int NOT NULL,
  `destination_branch_id` int NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'PENDING',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_bag_code` (`bag_code`),
  KEY `fk_bags_origin` (`origin_branch_id`),
  KEY `fk_bags_dest` (`destination_branch_id`),
  KEY `fk_bags_creator` (`created_by`),
  CONSTRAINT `fk_bags_origin` FOREIGN KEY (`origin_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bags_dest` FOREIGN KEY (`destination_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bags_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `bag_items` (
  `bag_id` int NOT NULL,
  `package_id` int NOT NULL,
  PRIMARY KEY (`bag_id`, `package_id`),
  KEY `fk_bagitems_package` (`package_id`),
  CONSTRAINT `fk_bagitems_bag` FOREIGN KEY (`bag_id`) REFERENCES `bags` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bagitems_package` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `manifests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `manifest_code` varchar(50) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `vehicle_plate` varchar(20) NOT NULL,
  `origin_branch_id` int NOT NULL,
  `destination_branch_id` int NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'PENDING',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_manifest_code` (`manifest_code`),
  KEY `fk_manifests_origin` (`origin_branch_id`),
  KEY `fk_manifests_dest` (`destination_branch_id`),
  CONSTRAINT `fk_manifests_origin` FOREIGN KEY (`origin_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_manifests_dest` FOREIGN KEY (`destination_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `manifest_items` (
  `manifest_id` int NOT NULL,
  `item_type` enum('PACKAGE','BAG') NOT NULL,
  `item_id` int NOT NULL,
  PRIMARY KEY (`manifest_id`, `item_type`, `item_id`),
  CONSTRAINT `fk_manifestitems_manifest` FOREIGN KEY (`manifest_id`) REFERENCES `manifests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
