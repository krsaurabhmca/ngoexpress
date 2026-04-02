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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
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
    <a href="#" class="whatsapp-widget"><i class="fab fa-whatsapp"></i></a>

    <nav id="navbar">
        <a href="index.php" class="logo">
            <?php if ($logo = get_setting('site_logo')): ?>
                <img src="<?php echo $logo; ?>" style="height: 40px;" alt="<?php echo $site_name; ?>">
            <?php else: ?>
                <i class="fas fa-hand-holding-heart"></i> NGOEXPRESS
            <?php endif; ?>
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="membership.php">Membership</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="admin/login.php" class="btn btn-outline" style="border-color: #e2e8f0; color: #475569;">Admin</a>
            <a href="donate.php" class="btn btn-primary">Donate Now</a>
        </div>
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

    <!-- Impactful Notice Board / What's New Section -->
    <section class="notices-section" style="padding: 100px 0; background: #fff;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px;">
                <div class="section-title" style="text-align: left; margin: 0;">
                    <span style="color: var(--secondary-color); font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Real-time Updates</span>
                    <h2 style="font-size: 2.8rem; margin-top: 10px;">What's New at NGOExpress</h2>
                </div>
                <button class="btn btn-outline" style="padding: 12px 30px; border-radius: 50px;">Explore All Projects <i class="fas fa-arrow-right" style="margin-left: 10px;"></i></button>
            </div>
            
            <div class="grid grid-3" style="gap: 30px;">
                <?php 
                $notices = mysqli_query($conn, "SELECT * FROM notices WHERE status = 'active' ORDER BY date DESC LIMIT 3");
                if(mysqli_num_rows($notices) > 0):
                    while($row = mysqli_fetch_assoc($notices)): ?>
                        <div style="background: #f8fafc; padding: 40px; border-radius: 30px; transition: 0.4s; border: 1.5px solid transparent;" onmouseover="this.style.borderColor='var(--secondary-color)'; this.style.transform='translateY(-10px)';" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)';">
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                                <div style="background: var(--secondary-color); color: white; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 700;"><?php echo date('M d, Y', strtotime($row['date'])); ?></span>
                            </div>
                            <h4 style="font-size: 1.2rem; margin-bottom: 20px; color: var(--primary-color); line-height: 1.4;"><?php echo $row['title']; ?></h4>
                            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 30px;">
                                <?php echo substr(strip_tags($row['content']), 0, 150); ?>...
                            </p>
                            <a href="#" style="color: var(--secondary-color); font-weight: 800; text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                                Project Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    <?php endwhile;
                else: ?>
                    <!-- Demo Notice -->
                    <div style="background: #f8fbff; padding: 30px; border-radius: 24px; border: 1.5px dashed #3b82f630;">
                        <span style="color: #64748b; font-size: 0.8rem;">[SYSTEM NOTICE]</span>
                        <h4 style="margin: 15px 0;">No Active Projects Posted</h4>
                        <p style="font-size: 0.85rem; color: #777;">Please visit the admin portal to publish the latest notices for our global community.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Success Stats -->
    <section class="stats container">
        <div class="grid grid-3">
            <div class="stat-card">
                <i class="fas fa-hand-holding-heart"></i>
                <h3><?php echo currency(); ?>4.5L+</h3>
                <p>Funds Raised</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>15K+</h3>
                <p>Global Members</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-globe-asia"></i>
                <h3>120+</h3>
                <p>Villages Impacted</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section container">
        <div class="grid" style="grid-template-columns: 1fr 1fr; align-items: center;">
            <div class="about-img">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1713&q=80" alt="About US" style="width: 100%; border-radius: 30px; box-shadow: var(--shadow);">
            </div>
            <div class="about-text" style="padding-left: 50px;">
                <span style="color: var(--secondary-color); font-weight: 700;">WHO WE ARE</span>
                <h2>Dedicated to providing education and healthcare.</h2>
                <p>We are a non-profit organization focused on making the world a better place. Our mission is to provide support, education, and resources to those in need, fostering a community of resilience and hope.</p>
                <ul style="margin-top: 20px;">
                    <li><i class="fas fa-check-circle" style="color: var(--secondary-color); margin-right: 10px;"></i> Transparent funding directly to projects.</li>
                    <li><i class="fas fa-check-circle" style="color: var(--secondary-color); margin-right: 10px;"></i> Global network of dedicated volunteers.</li>
                    <li><i class="fas fa-check-circle" style="color: var(--secondary-color); margin-right: 10px;"></i> Sustainable impact on local communities.</li>
                </ul>
                <a href="#" class="btn btn-outline" style="margin-top: 30px;">Read Full Story</a>
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
                <div class="service-card">
                    <i class="fas fa-graduation-cap" style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 20px;"></i>
                    <h3>Education for All</h3>
                    <p>Providing books, uniforms, and scholarships to children in underprivileged areas.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-heartbeat" style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 20px;"></i>
                    <h3>Health Support</h3>
                    <p>Organizing free medical camps and providing essential medicines to remote villages.</p>
                </div>
                <div class="service-card">
                    <i class="fas fa-seedling" style="font-size: 2.5rem; color: #2ecc71; margin-bottom: 20px;"></i>
                    <h3>Clean Environment</h3>
                    <p>Reforestation projects and clean water initiatives for a sustainable future.</p>
                </div>
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
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div>
                    <h4 style="color: white;">Quick Links</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Our Projects</a></li>
                        <li><a href="#">Latest News</a></li>
                        <li><a href="#">Contact Us</a></li>
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
        <p>&copy; 2026 Developed with Hope by <a href="https://OfferPlant.com" style="color: var(--secondary-color); font-weight: 700;">OfferPlant.com</a> for NGOExpress Portal.</p>
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
