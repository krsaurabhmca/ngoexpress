<?php
require_once('../includes/db.php');

if (!isset($_GET['id'])) die("Access Denied");
$id = mysqli_real_escape_string($conn, $_GET['id']);
$member = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM members WHERE id = '$id' LIMIT 1"));
if (!$member) die("Member not found.");

$site_name = get_setting('site_name');
$primary_color = get_setting('primary_color');
$secondary_color = get_setting('secondary_color');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Certificate | <?php echo $member['name']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 100vh; overflow: hidden; }
        
        .certificate-container {
            width: 1100px;
            height: 770px;
            background: white;
            padding: 50px;
            position: relative;
            border: 20px solid <?php echo $primary_color; ?>;
            box-shadow: 0 40px 100px rgba(0,0,0,0.2);
            font-family: 'Outfit', sans-serif;
            text-align: center;
        }

        .inner-border {
            height: 100%;
            border: 5px solid <?php echo $secondary_color; ?>;
            position: relative;
            padding: 80px;
        }

        .certificate-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            color: <?php echo $primary_color; ?>;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 20px;
        }

        .certificate-sub {
            font-size: 1.2rem;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 50px;
        }

        .member-name {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            color: <?php echo $secondary_color; ?>;
            border-bottom: 2px solid #eee;
            display: inline-block;
            margin-bottom: 30px;
            padding: 0 50px;
        }

        .certificate-text {
            font-size: 1.3rem;
            line-height: 1.6;
            color: #555;
            max-width: 800px;
            margin: 0 auto 100px;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-top: 50px;
        }

        .sig-item { text-align: center; width: 250px; }
        .sig-line { border-top: 2px solid #333; margin-top: 40px; padding-top: 10px; font-weight: 600; color: #333; }

        .corner-decoration {
            position: absolute;
            font-size: 5rem;
            color: <?php echo $secondary_color; ?>;
            opacity: 0.1;
        }

        @media print {
            body { background: white; }
            .print-btn { display: none; }
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: <?php echo $primary_color; ?>;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="certificate-container">
        <div class="inner-border">
            <div class="certificate-header">
                <h1>Certificate</h1>
                <div class="certificate-sub">Of Official Membership</div>
            </div>

            <div class="certificate-body">
                <p style="font-size: 1.1rem; color: #999; margin-bottom: 10px;">This certificate is proudly awarded to:</p>
                <div class="member-name"><?php echo $member['name']; ?></div>
                
                <div class="certificate-text">
                    In recognition of their commitment and dedication to the social welfare and community development missions of <strong><?php echo $site_name; ?></strong>. We hereby acknowledge your inclusion as an official member under registration ID #NGO-M00<?php echo $member['id']; ?>.
                </div>
            </div>

            <div class="signatures">
                <div class="sig-item">
                    <p style="font-style: italic; color: #777;">Digital Token Signature</p>
                    <div class="sig-line">Director of Trust</div>
                </div>
                <div class="sig-item">
                    <p style="font-weight: 700; font-size: 1.2rem;"><?php echo date('d M, Y'); ?></p>
                    <div class="sig-line">Date of Issuance</div>
                </div>
                <div class="sig-item">
                    <p style="font-style: italic; color: #777;">Official NGO Seal</p>
                    <div class="sig-line">Executive Chairman</div>
                </div>
            </div>
            
            <i class="fas fa-award corner-decoration" style="top: 20px; left: 20px;"></i>
            <i class="fas fa-award corner-decoration" style="bottom: 20px; right: 20px;"></i>
        </div>
    </div>

    <a href="javascript:window.print()" class="print-btn">Generate & Print Certificate <i class="fas fa-print"></i></a>

</body>
</html>
