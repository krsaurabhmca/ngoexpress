<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require '../includes/db.php';
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'check') {
    // Fetch latest without merging
    exec("git fetch origin main 2>&1");
    
    // Read the version directly from the remote branch
    $output = [];
    exec("git show origin/main:includes/db.php 2>&1", $output);
    $output_str = implode("\n", $output);
    
    $remote_version = APP_VERSION;
    if (preg_match("/define\('APP_VERSION',\s*'([^']+)'\);/", $output_str, $matches)) {
        $remote_version = $matches[1];
    }
    
    $is_available = version_compare($remote_version, APP_VERSION, '>');
    
    echo json_encode([
        'status' => 'success',
        'current_version' => APP_VERSION,
        'remote_version' => $remote_version,
        'update_available' => $is_available
    ]);
    exit;
}

// Default Action (Perform Update)
$output = [];
$return_var = 0;
exec("git pull origin main 2>&1", $output, $return_var);

$output_str = implode("\n", $output);

if ($return_var === 0) {
    if (strpos($output_str, 'Already up to date.') !== false) {
        echo json_encode(['status' => 'success', 'message' => "System is already up to date.\n\nGit output:\n" . $output_str]);
    } else {
        echo json_encode(['status' => 'success', 'message' => "System updated successfully!\n\nGit output:\n" . $output_str]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => "Update failed. Please check permissions or git config.\n\nGit output:\n" . $output_str]);
}
?>
