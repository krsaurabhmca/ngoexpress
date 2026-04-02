<?php
// NGO Platform Database Configuration
// Generated on: 2026-04-02 18:59:36

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ngo_test_db');

// System Version Control
require_once __DIR__ . '/version.php';

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    // Determine relative path to installer based on current caller script depth
    $installer_path = 'installer/index.php';
    if (file_exists('../installer/index.php')) {
        $installer_path = '../installer/index.php';
    } elseif (file_exists('../../installer/index.php')) {
        $installer_path = '../../installer/index.php';
    }
    
    // Prevent redirect loop if already on installer page
    if (strpos($_SERVER['PHP_SELF'], 'installer/') === false) {
        header("Location: " . $installer_path);
        exit;
    }
}

// Global Site Settings Helper
function get_setting($key)
{
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $query = "SELECT value FROM settings WHERE setting_key = '$key' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['value'];
    }
    return null;
}

// Global Site Settings Update Helper (Resilient version)
function set_setting($key, $value)
{
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);
    // Use INSERT ... ON DUPLICATE KEY UPDATE for maximum reliability
    $query = "INSERT INTO settings (setting_key, value) VALUES ('$key', '$value') 
              ON DUPLICATE KEY UPDATE value = '$value'";
    return mysqli_query($conn, $query);
}

// Global Currency Symbol Helper
function currency() {
    return get_setting('currency_symbol') ?: '₹';
}
?>