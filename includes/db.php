<?php
// NGO Platform Database Configuration
// Generated on: 2026-04-02 18:59:36

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ngo_test_db');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Connection Error: " . mysqli_connect_error());
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