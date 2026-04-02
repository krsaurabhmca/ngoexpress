<?php
require_once('includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$dob = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
$gender = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
$blood_group = mysqli_real_escape_string($conn, $_POST['blood_group'] ?? '');
$occupation = mysqli_real_escape_string($conn, $_POST['occupation'] ?? '');
$id_proof = mysqli_real_escape_string($conn, $_POST['id_proof'] ?? '');

// Handle Photo Upload
$photo_path = null;
if (isset($_FILES['member_photo']) && $_FILES['member_photo']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/members/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $file_ext = pathinfo($_FILES['member_photo']['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . md5($email) . '.' . $file_ext;
    $photo_path = $upload_dir . $filename;
    
    if (!move_uploaded_file($_FILES['member_photo']['tmp_name'], $photo_path)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload photo.']);
        exit;
    }
}

// Check if email already exists
$check_user = mysqli_query($conn, "SELECT id FROM members WHERE email = '$email' LIMIT 1");
if (mysqli_num_rows($check_user) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email address already registered as a member.']);
    exit;
}

// Auto Upgrade Database Structure for New Fields if not exists
try {
    @mysqli_query($conn, "ALTER TABLE members ADD COLUMN dob DATE DEFAULT NULL");
    @mysqli_query($conn, "ALTER TABLE members ADD COLUMN gender VARCHAR(10) DEFAULT NULL");
    @mysqli_query($conn, "ALTER TABLE members ADD COLUMN blood_group VARCHAR(5) DEFAULT NULL");
    @mysqli_query($conn, "ALTER TABLE members ADD COLUMN occupation VARCHAR(100) DEFAULT NULL");
    @mysqli_query($conn, "ALTER TABLE members ADD COLUMN id_proof VARCHAR(100) DEFAULT NULL");
} catch (mysqli_sql_exception $e) {
    // Ignore duplicate column errors 
}
$query = "INSERT INTO members (name, email, phone, address, photo, dob, gender, blood_group, occupation, id_proof, status) VALUES ('$name', '$email', '$phone', '$address', '$photo_path', '$dob', '$gender', '$blood_group', '$occupation', '$id_proof', 'pending')";

if (mysqli_query($conn, $query)) {
    echo json_encode(['status' => 'success', 'message' => 'Application received successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
?>
