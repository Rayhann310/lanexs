<?php
$config = require __DIR__ . '/../config/database.php';
$dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
$db = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
try {
    $stmt = $db->query("
        SELECT w.*, b.name as branch_name, b.code as branch_code
        FROM warehouses w 
        LEFT JOIN branches b ON w.branch_id = b.id 
        ORDER BY w.id DESC
    ");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
