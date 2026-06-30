<?php

define('BASE_PATH', dirname(__DIR__));

$config = require BASE_PATH . '/config/database.php';
$dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";

try {
    // Connect without DB first to create it
    $pdo = new PDO($dsn, $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Create DB if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` COLLATE '{$config['collation']}'");
    echo "Database {$config['database']} checked/created.\n";
    
    // Connect to the DB
    $pdo->exec("USE `{$config['database']}`");
    
    // Read and execute migration
    $sql = file_get_contents(BASE_PATH . '/database/migrations/01_initial_schema.sql');
    if ($sql) {
        $pdo->exec($sql);
        echo "Migration 01_initial_schema.sql executed successfully.\n";
    }

    // Run Seeder
    $seeder = file_get_contents(BASE_PATH . '/database/seeders/01_initial_seed.sql');
    if ($seeder) {
        $pdo->exec($seeder);
        echo "Seeder 01_initial_seed.sql executed successfully.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
