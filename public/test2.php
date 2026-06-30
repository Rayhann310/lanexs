<?php
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://localhost/lanex');
require_once BASE_PATH . '/app/Helpers/View.php';
$warehouses = [];
$branches = [];
$totalWarehouses = 0;
$totalActive = 0;
ob_start();
require BASE_PATH . '/app/Views/warehouses/index.php';
$content = ob_get_clean();
echo strlen($content) . " bytes captured\n";
echo "Layout set to: " . \App\Helpers\View::$layout . "\n";
