<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hope & Help | Premium NGO Platform</title>
    <meta name="description" content="Dedicated to social improvement and community support. Join us in making a difference.">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    
    <!-- Dynamic Theme Injection (Public Site) -->
    <style>
        :root {
            --primary-color: <?php echo get_setting('primary_color') ?: '#1e293b'; ?>;
            --secondary-color: <?php echo get_setting('secondary_color') ?: '#3b82f6'; ?>;
            --font-family: <?php echo get_setting('typography') ?: 'Outfit, sans-serif'; ?>;
        }
        * { font-family: var(--font-family) !important; }
    </style>

    <style>
        /* Modern Header */
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
            transition: var(--transition);
        }

        nav.scrolled {
            top: 10px;
            padding: 10px 25px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

        .nav-links li a { position: relative; padding: 5px 0; }
        .nav-links li a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--secondary-color);
            transition: 0.3s;
            transform: translateX(-50%);
        }
        .nav-links li a:hover::after { width: 100%; }
        .nav-links li a:hover { color: var(--primary-color); }

        /* Hero Slider */
        .hero {
            position: relative;
            height: 95vh;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.4));
            z-index: 1;
        }

        .hero-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(100%) opacity(0.3);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 0 10%;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 25px;
            color: #f8fafc;
        }

        /* Sections */
        .section {
            padding: 100px 0;
        }

        /* Stats Card */
        .stats {
            margin-top: -50px;
            z-index: 5;
            position: relative;
        }

        .stat-card {
            text-align: center;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            background: white;
            transition: var(--transition);
        }

        .stat-card:hover { transform: scale(1.05); }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .stat-card h3 {
            font-size: 2rem;
            margin: 5px 0;
        }

        /* Service Cards */
        .service-card {
            padding: 40px;
            background: var(--white);
            border-radius: var(--border-radius);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--transition);
        }

        .service-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        /* Donation Section */
        .donate-cta {
            background: var(--primary-color);
            color: white;
            padding: 80px 0;
            text-align: center;
            border-radius: 50px 50px 0 0;
        }

        /* WhatsApp Widget */
        .whatsapp-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 999;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-10px);}
            60% {transform: translateY(-5px);}
        }

        footer {
            background: #111;
            color: #ccc;
            padding: 80px 0 20px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-logo {
            font-size: 2rem;
            color: white;
            font-weight: 700;
            display: block;
            margin-bottom: 20px;
        }

        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #333;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: white;
        }

        .social-links a:hover {
            background: var(--secondary-color);
        }
    </style>
</head>
<body>

    <!-- WhatsApp Widget -->
    <a href="#" class="whatsapp-widget"><i class="bi bi-whatsapp"></i></a>

    <nav id="navbar">
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
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="membership.php">Membership</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="nav-actions" style="display: flex; gap: 10px; align-items: center;">
            <a href="admin/login.php" class="btn btn-outline" style="border-color: #e2e8f0; color: #475569;">Admin</a>
            <a href="donate.php" class="btn btn-primary">Donate Now</a>
        </div>
        <button class="mobile-menu-btn" onclick="document.querySelector('#navbar .nav-links').classList.toggle('active')">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <!-- Hero -->
    <header class="hero">
        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop" class="hero-img" alt="NGO Mission">
        <div class="hero-content">
            <h1 class="animate-up">Action for <br><span style="color: var(--secondary-color);">Humanity</span></h1>
            <p class="animate-up" style="animation-delay: 0.2s; font-size: 1.1rem; opacity: 0.8; max-width: 550px; line-height: 1.6; margin-bottom: 35px;">We are dedicated to providing education, healthcare, and sustainable support to communities in need across the globe.</p>
            <div class="animate-up" style="animation-delay: 0.4s; display: flex; gap: 15px;">
                <a href="donate.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 1rem; border-radius: 100px;">Get Started Today</a>
                <a href="#about" class="btn btn-outline" style="padding: 15px 40px; font-size: 1rem; border-radius: 100px; color: white; border-color: rgba(255,255,255,0.3);">Our Mission</a>
            </div>
        </div>
    </header>

    <!-- Unified About & Notices Widget Section -->
    <section id="about" class="section container" style="margin-top: 50px;">
        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
            
            <!-- Left: About Us Strategy -->
            <div style="padding-right: 20px;">
                <span style="color: var(--secondary-color); font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">WHO WE ARE</span>
                <h2 style="font-size: 2.5rem; margin-top: 10px;"><?php echo get_setting('about_title') ?: 'Dedicated to providing education and healthcare.'; ?></h2>
                <div style="margin: 25px 0;">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1713&q=80" alt="About US" style="width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); height: 300px; object-fit: cover;">
                </div>
                <p style="font-size: 1.05rem; line-height: 1.7; color: #475569;"><?php echo get_setting('about_description') ?: 'We are a non-profit organization focused on making the world a better place. Our mission is to provide support, education, and resources to those in need, fostering a community of resilience and hope.'; ?></p>
                <ul style="margin-top: 25px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <li style="display: flex; align-items: center; gap: 10px; font-weight: 500;"><i class="bi bi-check2-circle" style="color: var(--secondary-color); font-size: 1.2rem;"></i> Transparent Funding</li>
                    <li style="display: flex; align-items: center; gap: 10px; font-weight: 500;"><i class="bi bi-check2-circle" style="color: var(--secondary-color); font-size: 1.2rem;"></i> Global Volunteers</li>
                    <li style="display: flex; align-items: center; gap: 10px; font-weight: 500;"><i class="bi bi-check2-circle" style="color: var(--secondary-color); font-size: 1.2rem;"></i> Sustainable Impact</li>
                    <li style="display: flex; align-items: center; gap: 10px; font-weight: 500;"><i class="bi bi-check2-circle" style="color: var(--secondary-color); font-size: 1.2rem;"></i> Verified Real Results</li>
                </ul>
                <a href="#" class="btn btn-primary" style="margin-top: 35px; padding: 15px 30px; border-radius: 100px;">Discover Our Methodology <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Right: Notice/Updates Widget -->
            <div style="background: white; padding: 30px; border-radius: 24px; border: 1.5px solid #e2e8f0; position: sticky; top: 100px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
                    <h3 style="margin: 0; font-size: 1.2rem; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-broadcast" style="color: var(--secondary-color);"></i> Live Updates
                    </h3>
                    <a href="#" style="font-size: 0.8rem; color: var(--secondary-color); font-weight: 700; text-decoration: none;">View All</a>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php 
                    $notices = mysqli_query($conn, "SELECT * FROM notices WHERE status = 'active' ORDER BY date DESC LIMIT 4");
                    if(mysqli_num_rows($notices) > 0):
                        while($row = mysqli_fetch_assoc($notices)): ?>
                            <div style="padding: 15px; border-radius: 16px; background: #f8fafc; transition: 0.3s; cursor: pointer;" onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='#f8fafc';">
                                <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; margin-bottom: 5px; text-transform: uppercase;">
                                    <?php echo date('M d, Y', strtotime($row['date'])); ?>
                                </div>
                                <h4 style="font-size: 0.95rem; margin-bottom: 8px; color: var(--primary-color); line-height: 1.3;"><?php echo $row['title']; ?></h4>
                                <a href="#" style="color: var(--secondary-color); font-weight: 700; font-size: 0.75rem; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                    Read More <i class="bi bi-chevron-right" style="font-size: 0.6rem;"></i>
                                </a>
                            </div>
                        <?php endwhile;
                    else: ?>
                        <!-- Demo Notice Widget -->
                        <div style="background: #f8fbff; padding: 25px; border-radius: 16px; border: 1.5px dashed #3b82f630; text-align: center;">
                            <i class="bi bi-inbox" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                            <h4 style="font-size: 0.95rem; margin: 0 0 5px 0;">No Active Updates</h4>
                            <p style="font-size: 0.8rem; color: #94a3b8; margin: 0;">Check back soon for the latest projects.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section" style="background-color: #f0f4f8;">
        <div class="container">
            <div class="section-title">
                <h2>Our Mission Areas</h2>
                <div class="divider"></div>
                <p>We focus on critical areas where we can make the most significant impact.</p>
            </div>
            <div class="grid grid-3">
                <?php
                $services = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC LIMIT 6");
                if (mysqli_num_rows($services) > 0) {
                    while ($srv = mysqli_fetch_assoc($services)) {
                        echo '<div class="service-card">';
                        echo '<i class="' . $srv['icon'] . '" style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 20px; display: inline-block;"></i>';
                        echo '<h3>' . htmlspecialchars($srv['title']) . '</h3>';
                        echo '<p>' . htmlspecialchars($srv['description']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p style="grid-column: span 3; text-align: center; color: #888;">No foundation areas defined yet.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="donate-cta">
        <div class="container">
            <h2>Ready to make a change?</h2>
            <p style="margin-bottom: 40px; opacity: 0.8; max-width: 600px; margin-inline: auto;">Your contribution, no matter how small, can provide a meal for a child or a vaccine for someone in need.</p>
            <a href="donate.php" class="btn btn-primary" style="background: white; color: var(--primary-color);">Support Our Cause</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="#" class="footer-logo">HOPE&HELP</a>
                    <p>Making the world a better place through community action and dedicated support systems.</p>
                    <div class="social-links" style="margin-top: 20px;">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div>
                    <h4 style="color: white;">Quick Links</h4>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#services">Our Projects</a></li>
                        <li><a href="gallery.php">Media Gallery</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: white;">Support</h4>
                    <ul>
                        <li><a href="#">Donate Now</a></li>
                        <li><a href="#">Become a Member</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="color: white;">Subscribe</h4>
                    <p>Get latest updates directly in your inbox.</p>
                    <div style="margin-top: 20px; position: relative;">
                        <input type="email" placeholder="Email Address" style="width: 100%; padding: 15px; border-radius: 50px; border: none; background: #222; color: white;">
                        <button style="position: absolute; right: 5px; top: 5px; bottom: 5px; padding: 0 20px; border-radius: 50px; background: var(--secondary-color); border: none; color: white; cursor: pointer;">Join</button>
                    </div>
                </div>
            </div>
            <!-- Promotion Popup -->
            <?php if (get_setting('popup_enabled') == '1' && $popup = get_setting('site_popup_image')): ?>
            <div id="promo-popup" style="position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 10001; display: none; align-items: center; justify-content: center; padding: 20px;">
                <div style="max-width: 500px; width: 100%; position: relative; background: white; border-radius: 12px; overflow: hidden; animation: zoomIn 0.3s ease;">
                    <button onclick="closePopup()" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.5); color: white; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; z-index: 2;">&times;</button>
                    <img src="<?php echo $popup; ?>" style="width: 100%; display: block;">
                </div>
            </div>
            <script>
                window.addEventListener('load', function() {
                    if (!sessionStorage.getItem('popupShown')) {
                        setTimeout(() => {
                            document.getElementById('promo-popup').style.display = 'flex';
                            document.body.style.overflow = 'hidden'; // Prevent background scroll
                            sessionStorage.setItem('popupShown', 'true');
                        }, 2000);
                    }
                });
                function closePopup() { 
                    document.getElementById('promo-popup').style.display = 'none'; 
                    document.body.style.overflow = 'auto'; // Restore background scroll
                }
            </script>
            <style> @keyframes zoomIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } } </style>
            <?php endif; ?>
    <footer style="margin-top: 100px; padding: 50px 0; background: #111; color: #777; text-align: center; border-top: 5px solid var(--primary-color);">
        <p>Planted by <a href="https://OfferPlant.com" style="color: var(--secondary-color); font-weight: 700;">OfferPlant.com</a> with NgoExpress (Ver.<?php echo CORE_VERSION; ?>)</p>
    </footer>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 50) {
                $('#navbar').addClass('scrolled');
            } else {
                $('#navbar').removeClass('scrolled');
            }
        });
    </script>
</body>
</html>
