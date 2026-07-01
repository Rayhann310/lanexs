-- 15_landing_partners_and_testimonials.sql

CREATE TABLE IF NOT EXISTS landing_partners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    logo_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS landing_testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    position VARCHAR(150) DEFAULT NULL,
    content TEXT NOT NULL,
    rating INT DEFAULT 5,
    avatar_initials VARCHAR(5) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data for landing_partners
INSERT IGNORE INTO landing_partners (id, name, logo_path) VALUES
(1, 'Partner A', NULL),
(2, 'Partner B', NULL),
(3, 'Partner C', NULL);

-- Insert sample data for landing_testimonials
INSERT IGNORE INTO landing_testimonials (id, name, position, content, rating, avatar_initials) VALUES
(1, 'John Doe', 'CEO of TechCorp', 'Pelayanan sangat memuaskan, barang sampai tepat waktu.', 5, 'JD'),
(2, 'Jane Smith', 'Logistics Manager', 'Harga bersaing dan customer service responsif.', 5, 'JS');
