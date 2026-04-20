<?php
// ============================================================
// DATABASE CONFIGURATION — AUTO ENVIRONMENT DETECTION
//
// ✅ LOCAL  → uses localhost / root / no password (XAMPP default)
// ✅ LIVE   → uses InfinityFree credentials automatically
//
// No need to change anything when switching — just upload!
// ============================================================

// Detect environment by checking the server hostname
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', ''])
        || (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] === '127.0.0.1');

if ($isLocal) {
    // ── LOCAL (XAMPP) ──────────────────────────────────────
    $host = 'localhost';
    $db   = 'bridal_event_system';
    $user = 'root';
    $pass = '';
} else {
    // ── LIVE (InfinityFree) ────────────────────────────────
    $host = 'sql100.infinityfree.com';
    $db   = 'if0_41683721_maesbridal';
    $user = 'if0_41683721';
    $pass = '0AQ8onZJ11wx4Q3';
}

$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Generic error — never expose real DB details to users in production
    die("Database connection failed. Please contact support.");
}

// Also create mysqli connection for backward compatibility
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed. Please contact support.");
}
$conn->set_charset($charset);
// Make $mysqli available globally (alias for legacy files)
$mysqli = $conn;
?>
