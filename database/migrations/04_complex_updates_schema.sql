-- Migration: 04_complex_updates_schema
-- Adds advanced fields for packages and tariffs

-- 1. Tariffs Table: Add Koli and Volume pricing
ALTER TABLE `tariffs`
  ADD COLUMN `price_per_koli` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `price_per_kg`,
  ADD COLUMN `price_per_volume` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `price_per_koli`;

-- 2. Packages Table: Add physical properties and volume
ALTER TABLE `packages`
  ADD COLUMN `item_type` varchar(150) DEFAULT 'UMUM' AFTER `destination_branch_id`,
  ADD COLUMN `koli` int(11) NOT NULL DEFAULT '1' AFTER `item_type`,
  ADD COLUMN `length` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `koli`,
  ADD COLUMN `width` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `length`,
  ADD COLUMN `height` decimal(10,2) NOT NULL DEFAULT '0.00' AFTER `width`,
  ADD COLUMN `volume` decimal(10,4) NOT NULL DEFAULT '0.0000' AFTER `height`;
