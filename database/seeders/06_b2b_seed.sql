-- B2B Corporate Clients
INSERT IGNORE INTO `customers` (`id`, `company_name`, `pic_name`, `phone`, `email`, `credit_limit`, `status`) VALUES
(1, 'PT Maju Bersama', 'Bapak Hendra', '08112345678', 'hendra@majubersama.co.id', 50000000.00, 'ACTIVE'),
(2, 'CV Sinar Jaya', 'Ibu Dewi', '08227654321', 'dewi@sinarjaya.com', 20000000.00, 'ACTIVE');

-- B2B User Login (role_id=5 = Client)
-- Password: password (bcrypt hash)
INSERT IGNORE INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `customer_id`, `branch_id`) VALUES
(10, 'klien.majubersama', 'hendra@majubersama.co.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uFutrnXWK', 5, 1, 1),
(11, 'klien.sinarjaya', 'dewi@sinarjaya.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uFutrnXWK', 5, 2, 1);

-- Tag some packages as belonging to B2B client (if they exist)
UPDATE IGNORE `packages` SET `customer_id` = 1 WHERE `id` = 1;
