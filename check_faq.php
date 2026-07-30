<?php
$db = new PDO('mysql:host=localhost;dbname=lanex_db', 'root', '');
$stmt = $db->query("SELECT * FROM pages WHERE slug='faq'");
if (!$stmt->fetch()) {
    $db->exec("INSERT INTO pages (slug, title, content) VALUES ('faq', 'Pusat Bantuan & FAQ', '')");
    echo "FAQ inserted\n";
} else {
    echo "FAQ exists\n";
}
