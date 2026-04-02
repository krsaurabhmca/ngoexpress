<?php
require_once('includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$amount = (float)$_POST['amount'];
$transaction_id = 'DON-' . strtoupper(substr(md5(time()), 0, 8));

$query = "INSERT INTO donations (name, email, amount, transaction_id, status) VALUES ('$name', '$email', '$amount', '$transaction_id', 'paid')";

if (mysqli_query($conn, $query)) {
    $last_id = mysqli_insert_id($conn);
    
    // In a real scenario, this is where you'd trigger Email Send with branding
    // send_donation_email($email, $name, $amount, $transaction_id);

    echo json_encode(['status' => 'success', 'id' => $last_id]);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
?>
