<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Our Cause | Hope & Help NGO</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    
    <!-- Dynamic Theme Injection (Global) -->
    <style>
        :root {
            --primary-color: <?php echo get_setting('primary_color') ?: '#1e293b'; ?>;
            --secondary-color: <?php echo get_setting('secondary_color') ?: '#3b82f6'; ?>;
            --font-family: <?php echo get_setting('typography') ?: 'Outfit, sans-serif'; ?>;
        }
        * { font-family: var(--font-family) !important; }
    </style>
    <style>
        /* Modern Floating Header */
        nav {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 95%;
            max-width: 1200px;
            padding: 12px 30px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 100px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: 0.3s;
        }

        .logo { font-size: 1.25rem; font-weight: 800; color: var(--primary-color); display: flex; align-items: center; gap: 10px; }
        .nav-links { display: flex; gap: 25px; align-items: center; font-weight: 600; font-size: 0.85rem; color: #475569; }

        .donate-container {
            max-width: 800px;
            margin: 150px auto 100px;
            padding: 40px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }

        .donation-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .amount-btn {
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 12px;
            text-align: center;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .amount-btn:hover { border-color: var(--secondary-color); color: var(--secondary-color); }
        .amount-btn.active { background: var(--secondary-color); color: white; border-color: var(--secondary-color); }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 10px; font-weight: 600; color: var(--primary-color); }
        .form-group input { width: 100%; padding: 15px; border-radius: 12px; border: 1.5px solid #eee; background: #fafafa; font-size: 1rem; }
    </style>
</head>
<body style="background: #f4f7f6;">

    <nav id="navbar" style="background: white; border-bottom: 1px solid #f1f5f9;">
        <a href="index.php" class="logo" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
            <?php 
                $logo = get_setting('site_logo');
                $site_name = get_setting('site_name'); 
                $mode = get_setting('logo_display_mode') ?: 'both';
                
                if ($logo && ($mode == 'both' || $mode == 'logo')) {
                    echo '<img src="' . $logo . '" style="height: 40px;" alt="' . $site_name . '">';
                } elseif (!$logo && ($mode == 'both' || $mode == 'logo')) {
                    echo '<i class="bi bi-heart-fill"></i>';
                }
                
                if ($mode == 'both' || $mode == 'name') {
                    echo '<span>' . ($site_name ?: 'NGOEXPRESS') . '</span>';
                }
            ?>
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            <li><a href="membership.php">Membership</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <a href="donate.php" class="btn btn-primary">Donate Now</a>
        </div>
        <button class="mobile-menu-btn" onclick="document.querySelector('nav .nav-links').classList.toggle('active')">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <div class="donate-container animate-up">
        <div style="text-align: center; margin-bottom: 40px;">
            <i class="bi bi-heart-fill" style="font-size: 4rem; color: var(--secondary-color); margin-bottom: 20px;"></i>
            <h1 style="font-size: 2.5rem; color: var(--primary-color);">Support Our Mission</h1>
            <p style="color: #777;">Every contribution helps us reach more people in need.</p>
        </div>

        <form id="donation-form" onsubmit="processDonation(event)">
            <h3 style="margin-bottom: 20px;">Select Amount</h3>
            <div class="donation-options">
                <div class="amount-btn active" onclick="setAmount(500)"><?php echo currency(); ?>500</div>
                <div class="amount-btn" onclick="setAmount(1000)"><?php echo currency(); ?>1000</div>
                <div class="amount-btn" onclick="setAmount(2000)"><?php echo currency(); ?>2000</div>
                <div class="amount-btn" onclick="setAmount(5000)"><?php echo currency(); ?>5000</div>
                <div class="amount-btn" onclick="setAmount(0)">Custom</div>
            </div>

            <div class="form-group" id="custom-amount-div" style="display: none;">
                <label>Enter Custom Amount (<?php echo currency(); ?>)</label>
                <input type="number" id="final_amount" value="500" min="1" required>
            </div>

            <h3 style="margin-bottom: 20px; margin-top: 40px;">Contact Information</h3>
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="name" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="email" placeholder="john@example.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Message (Optional)</label>
                <textarea id="message" rows="3" style="width: 100%; padding: 15px; border-radius: 12px; border: 1.5px solid #eee; background: #fafafa;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; font-size: 1.1rem; margin-top: 20px;">
                Complete Contribution <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div id="success-screen" style="display: none; text-align: center;">
            <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: #2ecc71; margin-bottom: 25px;"></i>
            <h2 style="font-size: 2rem;">Thank You, <span id="don_name">Donor</span>!</h2>
            <p style="color: #777; margin-bottom: 30px;">Your generosity is truly appreciated. We've sent a receipt to your email.</p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="index.php" class="btn btn-outline" style="border-radius: 12px;">Back to Home</a>
                <a href="#" id="download-slip" class="btn btn-primary" style="border-radius: 12px;"><i class="bi bi-file-earmark-arrow-down"></i> Download Slip</a>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer style="margin-top: 100px; padding: 50px 0; background: #111; color: #777; text-align: center; border-top: 5px solid var(--primary-color);">
        <p>Planted by <a href="https://OfferPlant.com" style="color: var(--secondary-color); font-weight: 700;">OfferPlant.com</a> with NgoExpress (Ver.<?php echo APP_VERSION; ?>)</p>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function setAmount(amt) {
            $('.amount-btn').removeClass('active');
            $(event.target).addClass('active');
            if (amt === 0) {
                $('#custom-amount-div').slideDown();
                $('#final_amount').focus();
            } else {
                $('#custom-amount-div').slideUp();
                $('#final_amount').val(amt);
            }
        }

        function processDonation(e) {
            e.preventDefault();
            const data = {
                name: $('#name').val(),
                email: $('#email').val(),
                amount: $('#final_amount').val(),
                message: $('#message').val()
            };

            // AJAX to process_donation.php
            $.ajax({
                url: 'process_donation.php',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#don_name').text(data.name);
                        $('#donation-form').fadeOut(300, function() {
                            $('#success-screen').fadeIn(300);
                        });
                        $('#download-slip').attr('href', 'receipt.php?id=' + res.id);
                    } else {
                        alert("Error: " + res.message);
                    }
                }
            });
        }
    </script>
</body>
</html>
