<?php
// processes the installation request

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$db_host = $_POST['db_host'] ?? 'localhost';
$db_user = $_POST['db_user'] ?? 'root';
$db_pass = $_POST['db_pass'] ?? '';
$db_name = $_POST['db_name'] ?? 'ngo_db';

$admin_user = $_POST['admin_user'] ?? 'admin';
$admin_email = $_POST['admin_email'] ?? '';
$admin_pass = password_hash($_POST['admin_pass'] ?? 'admin123', PASSWORD_DEFAULT);

// 1. Connect to MySQL server
$conn = mysqli_connect($db_host, $db_user, $db_pass);
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// 2. Create database
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
if (!mysqli_query($conn, $sql)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create database: ' . mysqli_error($conn)]);
    exit;
}

// 3. Select database
mysqli_select_db($conn, $db_name);

// 4. Run schema.sql
$schema = file_get_contents('schema.sql');
$queries = explode(';', $schema);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if (!mysqli_query($conn, $query)) {
            echo json_encode(['status' => 'error', 'message' => 'Schema error: ' . mysqli_error($conn)]);
            exit;
        }
    }
}

// 5. Insert Admin User
$sql_admin = "INSERT INTO `users` (`username`, `password`, `email`, `role`) VALUES ('$admin_user', '$admin_pass', '$admin_email', 'admin')";
if (!mysqli_query($conn, $sql_admin)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create admin user: ' . mysqli_error($conn)]);
    exit;
}

// 6. Write to includes/db.php
$db_config_content = "<?php
// NGO Platform Database Configuration
// Generated on: " . date('Y-m-d H:i:s') . "

define('DB_HOST', '$db_host');
define('DB_USER', '$db_user');
define('DB_PASS', '$db_pass');
define('DB_NAME', '$db_name');

\$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!\$conn) {
    die(\"Database Connection Error: \" . mysqli_connect_error());
}

// Global Site Settings Helper
function get_setting(\$key) {
    global \$conn;
    \$key = mysqli_real_escape_string(\$conn, \$key);
    \$query = \"SELECT value FROM settings WHERE setting_key = '\$key' LIMIT 1\";
    \$result = mysqli_query(\$conn, \$query);
    if (\$result && mysqli_num_rows(\$result) > 0) {
        \$row = mysqli_fetch_assoc(\$result);
        return \$row['value'];
    }
    return null;
}

// Global Site Settings Update Helper
function set_setting(\$key, \$value) {
    global \$conn;
    \$key = mysqli_real_escape_string(\$conn, \$key);
    \$value = mysqli_real_escape_string(\$conn, \$value);
    \$query = \"UPDATE settings SET value = '\$value' WHERE setting_key = '\$key' LIMIT 1\";
    return mysqli_query(\$conn, \$query);
}
?>";

if (!file_put_contents('../includes/db.php', $db_config_content)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to write db.php file check permissions.']);
    exit;
}

// Installation successful
echo json_encode(['status' => 'success']);
?>
