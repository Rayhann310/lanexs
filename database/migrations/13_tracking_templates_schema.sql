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

-- 2. Seed default templates (INSERT IGNORE prevents duplicates)
INSERT IGNORE INTO `tracking_templates` (`id`, `name`, `status`, `description`, `is_global`, `created_by`) VALUES
(1,  'Paket diterima di gudang asal',      'GUDANG_ASAL',   'Paket telah diterima dan disortir di gudang asal.',                       1, NULL),
(2,  'Paket sedang dimuat ke kendaraan',   'TRANSIT',       'Paket sedang dalam proses pemuatan ke kendaraan pengiriman antar kota.',   1, NULL),
(3,  'Paket dalam perjalanan transit',     'TRANSIT',       'Paket sedang dalam perjalanan menuju kota tujuan.',                        1, NULL),
(4,  'Paket tiba di gudang tujuan',        'GUDANG_TUJUAN', 'Paket telah tiba dan disortir di gudang tujuan.',                         1, NULL),
(5,  'Paket siap antar ke penerima',       'DELIVERY',      'Paket sudah diserahkan ke kurir dan dalam proses pengiriman ke penerima.', 1, NULL),
(6,  'Paket berhasil diterima penerima',   'SELESAI',       'Paket telah berhasil diterima oleh penerima. Pengiriman selesai.',         1, NULL),
(7,  'Percobaan pengiriman gagal',         'DELIVERY',      'Percobaan pengiriman gagal karena penerima tidak ada di tempat. Akan dicoba kembali.', 1, NULL),
(8,  'Paket dikembalikan ke pengirim',     'RETUR',         'Paket tidak dapat dikirimkan dan sedang dalam proses pengembalian ke pengirim.', 1, NULL),
(9,  'Paket dalam penjemputan kurir',      'PICKUP',        'Kurir sedang dalam perjalanan untuk menjemput paket dari pengirim.',       1, NULL),
(10, 'Paket menunggu proses',              'PENDING',       'Paket telah terdaftar dan menunggu proses penjemputan.',                   1, NULL);
