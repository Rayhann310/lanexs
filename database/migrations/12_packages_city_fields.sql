-- Add origin_city and destination_city to packages table
SET @dbname = DATABASE();

SET @col1 = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'packages' AND COLUMN_NAME = 'origin_city'
);
SET @sql1 = IF(@col1 = 0,
    'ALTER TABLE `packages` ADD COLUMN `origin_city` varchar(100) DEFAULT NULL AFTER `origin_branch_id`',
    'SELECT 1'
);
PREPARE stmt1 FROM @sql1; EXECUTE stmt1; DEALLOCATE PREPARE stmt1;

SET @col2 = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = 'packages' AND COLUMN_NAME = 'destination_city'
);
SET @sql2 = IF(@col2 = 0,
    'ALTER TABLE `packages` ADD COLUMN `destination_city` varchar(100) DEFAULT NULL AFTER `destination_branch_id`',
    'SELECT 1'
);
PREPARE stmt2 FROM @sql2; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

-- Make origin_branch_id nullable (for city-to-city mode)
ALTER TABLE `packages` MODIFY COLUMN `origin_branch_id` int DEFAULT NULL;
