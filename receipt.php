<?php
require_once('includes/db.php');

if (!isset($_GET['id'])) {
    die("Access Denied");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT * FROM donations WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    die("Donation record not found.");
}

$donation = mysqli_fetch_assoc($result);

// Site Info
$site_name = get_setting('site_name');
$site_address = get_setting('site_address');
$site_email = get_setting('site_email');
$primary_color = get_setting('primary_color');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt | <?php echo $donation['transaction_id']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Outfit', sans-serif; }
        body { background: #f0f0f0; padding: 50px; }
        .receipt-card {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .receipt-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 50px; }
        .receipt-header h1 { font-size: 2.2rem; margin-bottom: 10px; color: <?php echo $primary_color; ?>; }
        .receipt-header p { font-size: 0.9rem; color: #777; }

        .receipt-body { margin-bottom: 50px; }
        .receipt-body h2 { font-size: 1.5rem; margin-bottom: 20px; color: #333; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .info-table th { text-align: left; padding: 15px; border-bottom: 2px solid #eee; color: #777; }
        .info-table td { padding: 15px; border-bottom: 1px solid #f9f9f9; font-weight: 600; font-size: 1.1rem; }

        .total-section { text-align: right; margin-top: 30px; }
        .total-section h3 { font-size: 2.5rem; color: <?php echo $primary_color; ?>; }

        .footer-note { text-align: center; margin-top: 100px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.9rem; color: #aaa; }

        @media print {
            body { background: white; padding: 0; }
            .receipt-card { box-shadow: none; border: 1px solid #eee; width: 100%; }
            .print-btn { display: none; }
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: <?php echo $primary_color; ?>;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="receipt-header">
            <div>
                <h1><?php echo $site_name; ?></h1>
                <p><?php echo $site_address; ?></p>
                <p>Email: <?php echo $site_email; ?></p>
            </div>
            <div style="text-align: right;">
                <h2 style="color: #2ecc71;">OFFICIAL RECEIPT</h2>
                <p>Date: <?php echo date('d M, Y', strtotime($donation['date'])); ?></p>
                <p>Trans ID: <strong><?php echo $donation['transaction_id']; ?></strong></p>
            </div>
        </div>

        <div class="receipt-body">
            <h2>Donation Details</h2>
            <table class="info-table">
                <tr>
                    <th>Donor Name</th>
                    <td><?php echo $donation['name']; ?></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><?php echo $donation['email']; ?></td>
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td>Online Contribution</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td style="color: #2ecc71;">PAID</td>
                </tr>
            </table>

            <div class="total-section">
                <p style="font-size: 0.9rem; color: #777;">Amount Contributed</p>
                <h3><?php echo currency(); ?><?php echo number_format($donation['amount'], 2); ?></h3>
            </div>
        </div>

        <div style="margin-top: 50px;">
            <p>Thank you for your generous support! Your contribution will directly support our educational and community healthcare programs.</p>
        </div>

        <div class="footer-note">
            <p>This is a computer generated receipt and does not require a physical signature.</p>
            <p>&copy; 2026 Developed with Hope by NGO Platform.</p>
        </div>
    </div>

    <a href="javascript:window.print()" class="print-btn">Print / Save PDF <i class="fas fa-print"></i></a>

</body>
</html>
