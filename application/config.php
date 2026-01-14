<?php
/**
 * KONFIGURASI DATABASE
 * FIX BASE URL (RELATIVE)
 */

// Define Paths
define('BASEPATH', __DIR__ . '/');
define('APPPATH', __DIR__ . '/');

/**
 * AUTO-DETECT BASE URL
 */
function get_base_url() {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_path = dirname($_SERVER['PHP_SELF']);
    $script_path = str_replace('\\', '/', $script_path);
    $script_path = rtrim($script_path, '/');
    if ($script_path) { $script_path = '/' . $script_path . '/'; } else { $script_path = '/'; }
    return $protocol . '://' . $host . $script_path;
}

define('BASE_URL', get_base_url());

function base_url($path = '') { return BASE_URL . ltrim($path, '/'); }
function site_url($uri = '') { return base_url($uri); }

/**
 * DB CONNECT
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gudang_arsip');

try {
    global $pdo;
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

function view($view, $data = []) { extract($data); require APPPATH . 'views/' . $view . '.php'; }
function redirect($url) { header("Location: " . site_url($url)); exit; }
function esc($str) { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
?>
