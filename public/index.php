<?php

/**
 * Sistem Ekspedisi Multi Branch Enterprise
 * Entry Point (Front Controller)
 */

// Start Output Buffering & Session
ob_start();
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define Base Path and Global constants
define('BASE_PATH', dirname(__DIR__));
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = str_ends_with($scriptName, '/public') ? substr($scriptName, 0, -7) : $scriptName;
$baseUrl = rtrim($baseUrl, '/');
define('BASE_URL', $baseUrl);
define('APP_NAME', 'LANEXS Logistics');
define('APP_LOGO', BASE_URL . '/assets/images/logo.png');

// Load Environment variables if .env exists
require_once BASE_PATH . '/app/Libraries/Env.php';
\App\Libraries\Env::load(BASE_PATH . '/.env');

// Ensure storage paths exist
$storagePaths = [
    BASE_PATH . '/storage',
    BASE_PATH . '/storage/logs',
    BASE_PATH . '/storage/uploads',
    BASE_PATH . '/public/assets/images'
];

// Require Composer Autoloader
require_once BASE_PATH . '/vendor/autoload.php';

use App\Libraries\Router;
use App\Libraries\Request;

// Initialize Router
$router = new Router();

// Load Web Routes
require_once BASE_PATH . '/routes/web.php';

// Dispatch Request
try {
    $request = new Request();
    $router->dispatch($request);
} catch (\Exception $e) {
    $msg = $e->getMessage();

    // Detect if this is an AJAX / JSON request
    $isAjax = (
        (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
        (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
        (isset($_SERVER['HTTP_CONTENT_TYPE']) && strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') !== false)
    );

    // Global Self-Healing for missing tables/columns
    $needsHeal = (
        strpos($msg, '42S02') !== false ||
        strpos($msg, "doesn't exist") !== false ||
        strpos($msg, 'Unknown column') !== false
    );

    if ($needsHeal) {
        $isSelfHealAttempt = isset($_SESSION['_self_heal_attempt']) && $_SESSION['_self_heal_attempt'] === $_SERVER['REQUEST_URI'];
        if (!$isSelfHealAttempt) {
            \App\Helpers\DatabaseHelper::repair();
            $_SESSION['_self_heal_attempt'] = $_SERVER['REQUEST_URI'];
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Database diperbaiki otomatis. Silakan scan ulang.']);
            } else {
                $_SESSION['error'] = "Sistem baru saja melakukan perbaikan database otomatis. Silakan ulangi aksi Anda.";
                header("Location: " . $_SERVER['REQUEST_URI']);
            }
            exit;
        }
    }

    // Clear heal flag
    unset($_SESSION['_self_heal_attempt']);

    // Return error
    $code = $e->getCode();
    if (!is_int($code) || $code < 100 || $code > 599) {
        $code = 500;
    }
    http_response_code($code);

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $msg]);
    } else {
        echo "<h1>Error " . $code . "</h1>";
        echo "<p>" . htmlspecialchars($msg) . "</p>";
    }
}
