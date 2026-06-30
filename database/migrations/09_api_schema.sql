-- Add api_token to users for Mobile API Auth
SET @dbname = DATABASE();

SET @c1 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@dbname AND TABLE_NAME='users' AND COLUMN_NAME='api_token');
SET @s1 = IF(@c1=0, 'ALTER TABLE `users` ADD COLUMN `api_token` varchar(100) DEFAULT NULL AFTER `password`, ADD UNIQUE KEY `idx_api_token` (`api_token`)', 'SELECT 1');
PREPARE p1 FROM @s1; EXECUTE p1; DEALLOCATE PREPARE p1;
