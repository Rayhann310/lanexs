-- Insert Dummy Data for Bag and Manifest (using INSERT IGNORE for self-healing safety)
INSERT IGNORE INTO `bags` (`id`, `bag_code`, `origin_branch_id`, `destination_branch_id`, `status`, `created_by`) VALUES
(1, 'BAG-JKT-BDO-001', 1, 1, 'PENDING', 1);

INSERT IGNORE INTO `manifests` (`id`, `manifest_code`, `driver_name`, `vehicle_plate`, `origin_branch_id`, `destination_branch_id`, `status`, `created_by`) VALUES
(1, 'MNF-JKT-BDO-001', 'Budi Santoso', 'B 1234 CD', 1, 1, 'PENDING', 1);
