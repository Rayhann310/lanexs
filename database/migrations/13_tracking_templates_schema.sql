-- ============================================================
-- 13_tracking_templates_schema.sql
-- Self-healing: safe to run multiple times
-- ============================================================

-- 1. Create tracking_templates table if not exists
CREATE TABLE IF NOT EXISTS `tracking_templates` (
  `id`          int          NOT NULL AUTO_INCREMENT,
  `name`        varchar(100) NOT NULL COMMENT 'Nama template (tampil di dropdown)',
  `status`      varchar(30)  NOT NULL COMMENT 'Status paket terkait, misal PICKUP',
  `description` text         NOT NULL COMMENT 'Teks deskripsi tracking',
  `is_global`   tinyint(1)   NOT NULL DEFAULT '1' COMMENT '1=global semua user, 0=private',
  `created_by`  int          DEFAULT NULL,
  `created_at`  timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_tt_user` (`created_by`),
  CONSTRAINT `fk_tt_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Seed default templates (Removed to prevent self-healing re-insertion)
