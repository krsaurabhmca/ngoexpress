<?php
require_once('../includes/db.php');

if (!isset($_GET['id'])) die("Access Denied");
$id = mysqli_real_escape_string($conn, $_GET['id']);
$member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE md5(id) = '$id' LIMIT 1"));
if (!$member) die("Member not found.");

$site_name = get_setting('site_name');
$primary_color = get_setting('primary_color');
$secondary_color = get_setting('secondary_color');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member ID Card | <?php echo $member['name']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Outfit', sans-serif; }
        body { background: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        
        .id-card {
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            text-align: center;
            border-top: 20px solid <?php echo $primary_color; ?>;
        }

        .card-header {
            padding: 30px 20px;
            background: linear-gradient(135deg, <?php echo $primary_color; ?>, <?php echo $secondary_color; ?>);
            color: white;
            margin-top: -20px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .card-header h2 { font-size: 1.2rem; }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            margin: -60px auto 20px;
            background: #eee url('<?php echo !empty($member['photo']) ? "../" . $member['photo'] : "https://ui-avatars.com/api/?name=" . urlencode($member['name']); ?>') center/cover;
            position: relative;
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .member-info h3 { font-size: 1.5rem; color: #333; margin-bottom: 5px; }
        .member-info p { font-size: 0.9rem; color: #777; margin-bottom: 20px; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            padding: 20px;
            text-align: left;
        }

        .info-item label { display: block; font-size: 0.7rem; color: #999; text-transform: uppercase; letter-spacing: 1px; }
        .info-item span { font-weight: 600; color: #333; font-size: 0.95rem; }

        .card-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #f8fbff;
            padding: 15px;
            font-size: 0.75rem;
            color: #777;
            border-top: 1px solid #eee;
        }

        @media print {
            body { background: white; }
            .print-btn { display: none; }
        }
        
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="id-card">
        <div class="card-header">
            <h2>OFFICIAL MEMBER</h2>
            <p style="font-size: 0.7rem; opacity: 0.8;"><?php echo $site_name; ?></p>
        </div>
        
        <div class="profile-img"></div>
        
        <div class="member-info">
            <h3><?php echo $member['name']; ?></h3>
            <p>Member Since: <?php echo date('Y', strtotime($member['joined_date'])); ?></p>
            
            <div class="info-grid">
                <div class="info-item">
                    <label>Member ID</label>
                    <span>#NGO-M00<?php echo $member['id']; ?></span>
                </div>
                <div class="info-item">
                    <label>Email Address</label>
                    <span><?php echo $member['email']; ?></span>
                </div>
                <div class="info-item">
                    <label>Contact Phone</label>
                    <span><?php echo $member['phone']; ?></span>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <p>Authorized by <?php echo $site_name; ?> Management</p>
        </div>
    </div>

    <a href="javascript:window.print()" class="print-btn">Print ID Card <i class="bi bi-printer"></i></a>

</body>
</html>
