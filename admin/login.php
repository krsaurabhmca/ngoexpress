<?php
session_start();
require_once('../includes/db.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT id, password FROM users WHERE username = '$username' LIMIT 1");
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | NGO Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <!-- Dynamic Theme Injection (Login) -->
    <style>
        :root {
            --primary-color: <?php echo get_setting('primary_color') ?: '#1e293b'; ?>;
            --secondary-color: <?php echo get_setting('secondary_color') ?: '#3b82f6'; ?>;
            --font-family: <?php echo get_setting('typography') ?: 'Outfit, sans-serif'; ?>;
        }
        * { font-family: var(--font-family) !important; }
    </style>
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            color: var(--text-color);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .login-card h2 { text-align: center; margin-bottom: 8px; color: var(--primary-color); font-weight: 700; }
        .login-card p.subtitle { text-align: center; margin-bottom: 30px; color: var(--text-muted); font-size: 0.85rem; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.85rem; color: #475569; }

        .form-group input {
            width: 100%;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #1e293b;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .error-msg {
            background: #fef2f2;
            color: #ef4444;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 20px;
            font-size: 0.8rem;
            border: 1px solid #fee2e2;
        }
    </style>
</head>
<body>

    <div class="login-card animate-up">
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <i class="bi bi-person-lock" style="font-size: 1.5rem; color: var(--primary-color);"></i>
            </div>
        </div>
        <h2>Portal Login</h2>
        <p class="subtitle">Enter your credentials to access the management panel.</p>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px; font-weight: 600;">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 30px; font-size: 0.85rem; opacity: 0.7;">
            <p>&copy; 2026 Powerful NGO Management.</p>
        </div>
    </div>

</body>
</html>
