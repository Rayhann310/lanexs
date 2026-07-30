ALTER TABLE landing_testimonials ADD COLUMN display_type ENUM('text', 'logo', 'both') DEFAULT 'text';
ALTER TABLE landing_testimonials ADD COLUMN logo VARCHAR(255) NULL;

ALTER TABLE landing_partners ADD COLUMN display_type ENUM('text', 'logo', 'both') DEFAULT 'logo';
ALTER TABLE landing_partners ADD COLUMN description TEXT NULL;
