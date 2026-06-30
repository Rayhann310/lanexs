-- Alter manifests: add driver_id and vehicle_id if not already present
SET @dbname = DATABASE();

-- Add driver_id if missing
SET @colExists = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'manifests' AND COLUMN_NAME = 'driver_id'
);
SET @sql = IF(@colExists = 0,
    'ALTER TABLE `manifests` ADD COLUMN `driver_id` int DEFAULT NULL AFTER `manifest_code`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Add vehicle_id if missing
SET @colExists2 = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'manifests' AND COLUMN_NAME = 'vehicle_id'
);
SET @sql2 = IF(@colExists2 = 0,
    'ALTER TABLE `manifests` ADD COLUMN `vehicle_id` int DEFAULT NULL AFTER `driver_id`',
    'SELECT 1'
);
PREPARE stmt2 FROM @sql2; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;
