<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Hope & Help NGO</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    
    <!-- Dynamic Theme Injection -->
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

        .logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
        }

        .nav-links li a:hover { color: var(--secondary-color); }

        .contact-container {
            max-width: 1200px;
            margin: 150px auto 100px;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 50px;
        }

        .contact-info-card {
            background: var(--primary-color);
            color: white;
            padding: 50px;
            border-radius: 30px;
            height: fit-content;
        }

        .info-item { display: flex; gap: 20px; align-items: flex-start; margin-bottom: 35px; }
        .info-item i { font-size: 1.5rem; color: var(--secondary-color); }
        .info-item h4 { font-size: 1.1rem; margin-bottom: 5px; }
        .info-item p { font-size: 0.9rem; opacity: 0.8; }

        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-color); }
        .form-group input, .form-group textarea { width: 100%; padding: 15px; border-radius: 12px; border: 1.5px solid #eee; background: #fafafa; font-size: 1rem; }

        .map-section {
            margin-top: 80px;
            border-radius: 30px;
            overflow: hidden;
            height: 450px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        }

        .map-section iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body style="background: #f8fbff;">

    <nav id="navbar">
        <a href="index.php" class="logo">
            <?php if ($logo = get_setting('site_logo')): ?>
                <img src="<?php echo $logo; ?>" style="height: 40px;" alt="Logo">
            <?php else: ?>
                <i class="fas fa-hand-holding-heart" style="color: var(--secondary-color);"></i> NGOEXPRESS
            <?php endif; ?>
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="membership.php">Membership</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <a href="donate.php" class="btn btn-primary" style="border-radius: 50px; padding: 10px 25px;">Donate Now</a>
    </nav>

    <?php 
    require_once('includes/db.php');
    $site_email = get_setting('site_email');
    $site_phone = get_setting('site_phone');
    $site_address = get_setting('site_address');
    $map_iframe = get_setting('google_map_iframe');
    ?>

    <div class="container">
        <div class="contact-container animate-up">
            <!-- Sidebar Info -->
            <div class="contact-info-card">
                <h2 style="font-size: 2.2rem; margin-bottom: 30px;">Let's talk about the world's problems.</h2>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Our Location</h4>
                        <p><?php echo $site_address; ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email Us</h4>
                        <p><?php echo $site_email; ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <div>
                        <h4>Call Anytime</h4>
                        <p><?php echo $site_phone; ?></p>
                    </div>
                </div>

                <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <p style="font-size: 0.85rem; margin-bottom: 15px;">Follow our journey:</p>
                    <div class="social-links">
                        <a href="#" style="color: white; margin-right: 20px; font-size: 1.2rem;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: white; margin-right: 20px; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: white; margin-right: 20px; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <span style="color: var(--secondary-color); font-weight: 700;">GET IN TOUCH</span>
                <h2 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 40px;">Send us a message</h2>
                <form onsubmit="alert('Message Sent Successfully!'); return false;">
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" placeholder="john@example.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" placeholder="How can we help?" required>
                    </div>
                    <div class="form-group">
                        <label>Message Details</label>
                        <textarea rows="6" placeholder="Your message and contribution ideas..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 18px 50px; font-size: 1.1rem; border-radius: 12px; margin-top: 10px;">
                        Send Inquiry <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Google Maps Widget -->
        <?php if (!empty($map_iframe)): ?>
            <div class="map-section animate-up" style="animation-delay: 0.4s;">
                <?php echo str_replace('width="600" height="450"', 'width="100%" height="100%"', $map_iframe); ?>
            </div>
        <?php else: ?>
            <div class="map-section animate-up" style="animation-delay: 0.4s; background: #eee; display: flex; align-items: center; justify-content: center; color: #777;">
                <p><i class="fas fa-map-marked-alt"></i> Google Maps iframe not configured in Admin Settings.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="margin-top: 100px; padding: 50px 0; background: #111; color: #777; text-align: center;">
        <p>&copy; 2026 Developed with Hope by NGO Platform.</p>
    </footer>

</body>
</html>
