<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

// Attempt to run git pull
$output = [];
$return_var = 0;
exec("git pull 2>&1", $output, $return_var);

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
