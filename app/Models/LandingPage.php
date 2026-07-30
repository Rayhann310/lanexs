<?php

namespace App\Models;

class LandingPage extends BaseModel
{
    protected string $table = 'landing_pages';

    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    private function ensureTableExists()
    {
        try {
            self::$db->query("SELECT 1 FROM {$this->table} LIMIT 1");
        } catch (\PDOException $e) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `slug` VARCHAR(100) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `content` TEXT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `slug_unique` (`slug`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            self::$db->exec($sql);
            
            // Seed default data for sejarah perusahaan
            $sejarahContent = '<p>PT. Lintas Area Nusantara Express resmi didirikan pada tanggal 01 April 2025 di bawah kepemimpinan dan dukungan penuh dari seorang wanita sebagai komisaris utama sekaligus penyedia dana utama. Berlokasi di Bekasi, Jawa Barat, perusahaan ini lahir dengan visi besar untuk menjadi solusi ekspedisi terpercaya yang mampu menjangkau seluruh wilayah Nusantara, mulai dari perkotaan hingga daerah pelosok.</p>
<p>Keinginan untuk mendirikan perusahaan ekspedisi ini berakar dari kebutuhan masyarakat Indonesia akan layanan pengiriman barang yang cepat, aman, dan efisien. Melihat potensi besar yang dimiliki Indonesia sebagai negara kepulauan, PT. Lintas Area Nusantara Express hadir untuk menghubungkan setiap pulau, kota, dan daerah dengan menggunakan jaringan logistik yang kuat melalui jalur darat, laut, dan udara.</p>
<p>Sejak awal berdirinya, perusahaan ini mengedepankan komitmen untuk memberikan pelayanan terbaik bagi pelanggan. Dengan dukungan sumber daya manusia yang profesional, teknologi modern, serta armada pengiriman yang lengkap, PT. Lintas Area Nusantara Express berkembang pesat dan menjadi mitra logistik yang dapat diandalkan oleh banyak pihak, baik individu maupun perusahaan.</p>
<p>Sebagai perusahaan yang lahir di tengah pesatnya perkembangan sektor logistik, PT. Lintas Area Nusantara Express terus berinovasi untuk menjawab tantangan zaman dan kebutuhan pelanggan. Seiring perjalanan waktu, perusahaan ini tidak hanya berfokus pada pengiriman barang, tetapi juga berperan aktif dalam mendukung pemerataan ekonomi nasional melalui logistik yang efisien.</p>
<p>Dengan semangat inovasi dan dedikasi tinggi, PT. Lintas Area Nusantara Express siap melangkah ke masa depan, membawa nama Indonesia semakin maju di bidang logistik dan ekspedisi.</p>';
            
            $stmt = self::$db->prepare("INSERT IGNORE INTO {$this->table} (slug, title, content) VALUES (?, ?, ?)");
            $stmt->execute(['sejarah-perusahaan', 'Sejarah Perusahaan', $sejarahContent]);
        }
    }

    public function findBySlug($slug)
    {
        $stmt = self::$db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
}
