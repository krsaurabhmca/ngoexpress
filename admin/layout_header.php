<?php
session_start();
require_once('../includes/db.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Management Portal</title>
    <!-- Google Fonts & icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- Deep Dynamic Theme Injection (Admin) -->
    <style>
        :root {
            --primary-color: <?php echo get_setting('primary_color') ?: '#1e293b'; ?>;
            --secondary-color: <?php echo get_setting('secondary_color') ?: '#3b82f6'; ?>;
            --font-family: <?php echo get_setting('typography') ?: 'Outfit, sans-serif'; ?>;
            --sidebar-width: 240px;
            --header-height: 60px;
        }
        * { font-family: var(--font-family) !important; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
    <style>
        body {
            background-color: #f8fafc;
            display: flex;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #1e293b;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1001;
            padding: 20px 10px;
            overflow-y: auto;
            color: #cbd5e1;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }

        .sidebar-logo {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: #f8fafc;
            margin-bottom: 30px;
            display: block;
            letter-spacing: 1px;
            padding: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .nav-menu {
            margin-bottom: 25px;
        }

        .nav-menu-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 10px;
            padding-left: 12px;
            letter-spacing: 1px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 6px;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 2px;
            font-size: 0.85rem;
            transition: 0.2s;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: #f8fafc;
        }

        .nav-item.active {
            background: var(--secondary-color);
            color: white;
            box-shadow: none;
        }

        .nav-item i { width: 18px; font-size: 1rem; }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.85rem;
        }

        th { text-align: left; padding: 12px; color: #64748b; border-bottom: 2px solid #f1f5f9; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }

        .badge {
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success { background: #e6f9f1; color: #2ecc71; }
        .badge-warning { background: #fff8e6; color: #f39c12; }

        /* Rich Text Editor Placeholder Styles */
        .editor-container {
            border: 1.5px solid #eee;
            border-radius: 12px;
            padding: 20px;
            min-height: 300px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.php" class="sidebar-logo" style="display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none;">
            <?php 
                $logo = get_setting('site_logo');
                $site_name = get_setting('site_name');
                $mode = get_setting('logo_display_mode') ?: 'both';
                
                if ($logo && ($mode == 'both' || $mode == 'logo')) {
                    echo '<img src="../' . $logo . '" style="height: 40px;" alt="' . $site_name . '">';
                } elseif (!$logo && ($mode == 'both' || $mode == 'logo')) {
                    echo '<i class="bi bi-heart-fill"></i>';
                }
                
                if ($mode == 'both' || $mode == 'name') {
                    echo '<span>' . ($site_name ?: 'NGOEXPRESS') . '</span>';
                }
            ?>
        </a>

        <div class="nav-menu">
            <h3 class="nav-menu-title">Main Menu</h3>
            <a href="index.php" class="nav-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="donations.php" class="nav-item <?php echo $current_page == 'donations.php' ? 'active' : ''; ?>">
                <i class="bi bi-cash-coin"></i> Donations
            </a>
            <a href="members.php" class="nav-item <?php echo $current_page == 'members.php' ? 'active' : ''; ?>">
                <i class="bi bi-person-gear"></i> Members
            </a>
        </div>

        <div class="nav-menu">
            <h3 class="nav-menu-title">CMS Management</h3>
                <a href="sliders.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sliders.php') ? 'active' : ''; ?>">
                    <i class="bi bi-images"></i> Home Sliders
                </a>
                <a href="notices.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'notices.php') ? 'active' : ''; ?>">
                    <i class="bi bi-megaphone"></i> What's New
                </a>
                <a href="services.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php') ? 'active' : ''; ?>">
                    <i class="bi bi-laptop"></i> Impact Areas
                </a>
            <a href="gallery.php" class="nav-item <?php echo $current_page == 'gallery.php' ? 'active' : ''; ?>">
                <i class="bi bi-camera-reels"></i> Media Gallery
            </a>
            <a href="pages.php" class="nav-item <?php echo $current_page == 'pages.php' ? 'active' : ''; ?>">
                <i class="bi bi-file-text"></i> Page Content
            </a>
        </div>

        <div class="nav-menu">
            <h3 class="nav-menu-title">System Settings</h3>
            <a href="settings.php" class="nav-item <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                <i class="bi bi-gear"></i> Site Settings
            </a>
            <a href="theme.php" class="nav-item <?php echo $current_page == 'theme.php' ? 'active' : ''; ?>">
                <i class="bi bi-palette"></i> Theme Config
            </a>
            <a href="logout.php" class="nav-item" style="color: #e74c3c;">
                <i class="bi bi-box-arrow-right"></i> Logout Portal
            </a>
        </div>

        <!-- Git/Auto Update Special Section -->
        <div style="margin-top: 30px; padding: 20px; background: #1e293b; border-radius: 15px; text-align: center; border: 1px solid rgba(255,255,255,0.05);">
            <p id="system-version-text" style="font-size: 0.8rem; color: #94a3b8; margin: 0;">NgoExpress Ver. <?php echo defined('APP_VERSION') ? APP_VERSION : '0.00'; ?></p>
            <button id="system-update-btn" class="btn btn-primary" style="margin-top: 10px; width: 100%; border-radius: 8px; padding: 10px; background: #333;" onclick="checkUpdate()">Check for Updates</button>
        </div>
    </div>

    <div class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: white; padding: 15px 20px; border-radius: 10px; border: 1px solid #e2e8f0;">
            <div>
                <h1 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Dashboard Overview</h1>
                <p style="color: #64748b; font-size: 0.75rem; margin: 0;">System Status: <span style="color: #22c55e;">Online</span> • Version: <?php echo defined('APP_VERSION') ? APP_VERSION : '0.00'; ?> • Admin</p>
            </div>
            <div style="display: flex; gap: 15px; align-items: center;">
                <a href="../index.php" target="_blank" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.75rem; border: 1px solid #e2e8f0;">Live Site <i class="bi bi-box-arrow-up-right" style="margin-left: 5px;"></i></a>
                <div style="height: 30px; width: 1px; background: #e2e8f0;"></div>
                <div style="position: relative; display: flex; align-items: center; gap: 10px; cursor: pointer;" onclick="toggleProfileMenu()">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1e293b&color=fff&size=32" style="width: 32px; height: 32px; border-radius: 6px;" alt="Admin Profile">
                    <div style="display: none; md:block;">
                        <span style="font-weight: 600; font-size: 0.85rem; color: #1e293b; display: block;">Super Admin <i class="bi bi-chevron-down" style="font-size: 0.6rem; margin-left: 3px;"></i></span>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown" style="display: none; position: absolute; top: 45px; right: 0; width: 160px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 1002; padding: 5px;">
                        <a href="settings.php#security" style="display: flex; align-items: center; gap: 8px; padding: 10px; font-size: 0.8rem; border-radius: 6px; color: #475569;" class="dropdown-item">
                            <i class="bi bi-key"></i> Security
                        </a>
                        <a href="javascript:void(0)" onclick="confirmLogout()" style="display: flex; align-items: center; gap: 8px; padding: 10px; font-size: 0.8rem; border-radius: 6px; color: #ef4444;" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <script>
            function toggleProfileMenu() {
                var menu = document.getElementById('profile-dropdown');
                menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
            }

            function confirmLogout() {
                if (confirm("Are you sure you want to log out from the portal?")) {
                    window.location.href = 'logout.php';
                }
            }

            // Close dropdown when clicking outside
            window.onclick = function(event) {
                if (!event.target.closest('#navbar') && !event.target.closest('.main-content')) {
                    // Logic to close
                }
            }
        </script>
        
        <style>
            .dropdown-item:hover { background: #f8fafc; color: var(--secondary-color) !important; }
        </style>
