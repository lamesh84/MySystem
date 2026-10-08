<?php
/**
 * Configuration & Database Setup
 * KSRTC Online Bus Ticket Reservation System
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application Constants
define('APP_NAME', 'Kerala State Road Transport Corporation');
define('APP_SHORT_NAME', 'KSRTC');
define('BASE_URL', './');
define('CURRENCY', '₹');

// Database setup: SQLite database file located in data directory
$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'data';
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0777, true);
}
$dbFile = $dataDir . DIRECTORY_SEPARATOR . 'ksrtc_ticket.sqlite';

$pdo = null;
$useSqlite = false;

try {
    if (extension_loaded('pdo_sqlite')) {
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $useSqlite = true;
    }
} catch (Exception $e) {
    $pdo = null;
    $useSqlite = false;
}

// JSON fallback file path
$jsonStorageFile = $dataDir . DIRECTORY_SEPARATOR . 'storage.json';

// Initialize Database / Storage
require_once __DIR__ . '/db_init.php';

// Helper functions
function get_current_user_data() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    return null;
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function generate_pnr() {
    return 'KL' . rand(1000000, 9999999);
}

