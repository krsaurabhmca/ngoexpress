<?php
// includes/auth.php
// Ensures that only logged-in administrators can access certain pages.

session_start();

function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}
?>
