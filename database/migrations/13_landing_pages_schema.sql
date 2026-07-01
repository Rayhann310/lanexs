-- 13_landing_pages_schema.sql

CREATE TABLE IF NOT EXISTS landing_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(150) NOT NULL,
    content LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default pages
INSERT IGNORE INTO landing_pages (slug, title, content) VALUES
('sejarah-perusahaan', 'Sejarah Perusahaan', '<p>Sejarah panjang perusahaan kami dimulai sejak...</p>'),
('visi-misi', 'Visi & Misi', '<p><strong>Visi:</strong> Menjadi yang terdepan...</p>'),
('struktur-organisasi', 'Struktur Organisasi', '<p>Berikut adalah jajaran manajemen kami...</p>'),
('layanan-pengiriman', 'Layanan Pengiriman', '<p>Kami menyediakan berbagai opsi pengiriman darat, laut, dan udara.</p>'),
('layanan-pengemasan', 'Layanan Pengemasan', '<p>Layanan packing kayu, bubble wrap, dan asuransi.</p>'),
('layanan-tracking', 'Layanan Tracking & Questions', '<p>Lacak paket Anda secara real-time dan baca FAQ kami.</p>'),
('experience', 'Experience', '<p>Pengalaman kami melayani ribuan klien B2B.</p>'),
('kontak-kami', 'Kontak Kami', '<p>Hubungi kami melalui form atau email berikut.</p>');
