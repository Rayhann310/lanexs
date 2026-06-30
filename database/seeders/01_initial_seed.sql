-- Insert Default Company
INSERT INTO `companies` (`id`, `name`, `address`, `phone`) VALUES
(1, 'LANEX Logistics', 'Gedung LANEX, Jl. Jend. Sudirman No. 1, Jakarta', '021-1234567');

-- Insert Default Branch (HQ)
INSERT INTO `branches` (`id`, `company_id`, `code`, `name`, `type`, `city`) VALUES
(1, 1, 'JKT-HQ', 'Jakarta Head Office', 'HQ', 'Jakarta');

-- Insert Default Roles
INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Super Admin', 'Full system access'),
(2, 'Owner', 'Business Owner'),
(3, 'Manager', 'Branch Manager'),
(4, 'Admin Cabang', 'Branch Admin');

-- Insert Super Admin User (password is 'password' hashed with BCRYPT)
INSERT INTO `users` (`company_id`, `branch_id`, `role_id`, `username`, `password`, `fullname`, `email`) VALUES
(1, 1, 1, 'superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'admin@lanex.co.id');
