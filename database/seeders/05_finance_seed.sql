-- Dummy Data for Initial Cash Balance
INSERT IGNORE INTO `transactions` (`id`, `branch_id`, `type`, `amount`, `reference_type`, `reference_id`, `description`, `created_by`) VALUES
(1, 1, 'INCOME', 5000000.00, 'OTHER', NULL, 'Saldo Kas Awal Cabang Pusat', 1),
(2, 2, 'INCOME', 2000000.00, 'OTHER', NULL, 'Saldo Kas Awal Cabang Jakarta', 1);

-- Update dummy package to be PAID via CASH so it matches business logic
UPDATE IGNORE `packages` SET `payment_type` = 'CASH', `payment_status` = 'PAID' WHERE `id` = 1;
