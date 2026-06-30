<?php

define('BASE_PATH', dirname(__DIR__));

$config = require BASE_PATH . '/config/database.php';
$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";

try {
    $pdo = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Read and execute migration
    $sql = file_get_contents(BASE_PATH . '/database/migrations/02_packages_schema.sql');
    if ($sql) {
        $pdo->exec($sql);
        echo "Migration 02_packages_schema.sql executed successfully.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
