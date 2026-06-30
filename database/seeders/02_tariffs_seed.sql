-- Insert Dummy Tariffs for Branch to Branch and City to City
-- Using fixed IDs and INSERT IGNORE to prevent duplicate inserts during self-healing
INSERT IGNORE INTO `tariffs` (`id`, `type`, `origin_branch_id`, `destination_branch_id`, `origin_city`, `destination_city`, `price_per_kg`, `price_per_koli`, `price_per_volume`, `estimated_days`) VALUES
(1, 'CITY', NULL, NULL, 'Jakarta', 'Jakarta', 10000.00, 15000.00, 50000.00, 1),
(2, 'CITY', NULL, NULL, 'Jakarta', 'Bandung', 15000.00, 20000.00, 75000.00, 2),
(3, 'BRANCH', 1, 1, NULL, NULL, 9000.00, 12000.00, 40000.00, 1);
