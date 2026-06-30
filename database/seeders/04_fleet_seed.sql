-- Dummy Data for Fleet (Vehicles and Drivers)
INSERT IGNORE INTO `vehicles` (`id`, `plate_number`, `vehicle_type`, `capacity_kg`, `status`) VALUES
(1, 'B 1234 CD', 'Truk Box Engkel', 2000, 'AVAILABLE'),
(2, 'D 5678 EF', 'Truk Fuso', 5000, 'AVAILABLE'),
(3, 'B 9999 XX', 'Mobil GrandMax', 800, 'AVAILABLE');

INSERT IGNORE INTO `drivers` (`id`, `name`, `phone`, `license_number`, `status`) VALUES
(1, 'Budi Santoso', '081234567890', 'SIM-B1-12345', 'AVAILABLE'),
(2, 'Ahmad Suherman', '081987654321', 'SIM-A-98765', 'AVAILABLE');

-- Update the existing manifest (ID 1) to use these seeded driver and vehicle instead of the old strings
-- (We use UPDATE IGNORE just in case the records don't exist yet)
UPDATE IGNORE `manifests` SET `driver_id` = 1, `vehicle_id` = 1 WHERE `id` = 1;
