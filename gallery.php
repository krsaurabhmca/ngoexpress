<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Gallery | NGOExpress</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome - Robust Load -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    
    <style>
        :root {
            --primary-color: <?php echo get_setting('primary_color') ?: '#1e293b'; ?>;
            --secondary-color: <?php echo get_setting('secondary_color') ?: '#3b82f6'; ?>;
            --font-family: <?php echo get_setting('typography') ?: 'Outfit, sans-serif'; ?>;
        }
        * { font-family: var(--font-family) !important; }

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
            transition: 0.3s ease;
        }

        .logo {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
            font-weight: 600;
            font-size: 0.85rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: #475569;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.3s;
        }
        .nav-links a:hover { color: var(--secondary-color); }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 50px;
        }

        .gallery-item {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 250px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.4s;
        }

        .gallery-item:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
        
        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            display: flex;
            align-items: flex-end;
            padding: 20px;
            opacity: 0;
            transition: 0.3s;
        }
        .gallery-item:hover .gallery-overlay { opacity: 1; }
        .gallery-overlay h4 { color: white; margin: 0; font-size: 1rem; }

        .page-header {
            background: var(--primary-color);
            padding: 180px 20px 100px;
            text-align: center;
            color: white;
            border-radius: 0 0 50px 50px;
        }
    </style>
</head>
<body style="background: #f8fafc;">

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
            <li><a href="membership.php">Membership</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="nav-actions" style="display: flex; gap: 10px; align-items: center;">
            <a href="admin/login.php" class="btn btn-outline" style="border-color: #e2e8f0; color: #475569;">Admin</a>
            <a href="donate.php" class="btn btn-primary" style="padding:12px 25px; border-radius:50px;">Donate Now</a>
        </div>
        <button class="mobile-menu-btn" onclick="document.querySelector('#navbar .nav-links').classList.toggle('active')">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <section class="page-header">
        <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Memories & Impact</h1>
        <p style="opacity: 0.8; max-width: 600px; margin: 0 auto;">Visualizing our journey toward social change. Every photo tells a story of transformation and hope.</p>
    </section>

    <div class="container" style="padding: 100px 0;">
        <div class="gallery-grid animate-up">
            <?php 
            $gallery = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id DESC LIMIT 12");
            if(mysqli_num_rows($gallery) > 0):
                while($row = mysqli_fetch_assoc($gallery)): ?>
                    <div class="gallery-item">
                        <img src="uploads/gallery/<?php echo $row['image']; ?>" alt="Gallery Image">
                        <div class="gallery-overlay">
                            <h4><?php echo $row['title']; ?></h4>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <!-- Demo Images if empty -->
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1470" alt="Demo"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?q=80&w=1470" alt="Demo"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1594708767771-a7502209ff51?q=80&w=1471" alt="Demo"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1462" alt="Demo"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1470" alt="Demo"></div>
                <div class="gallery-item"><img src="https://images.unsplash.com/photo-1532629345422-7515f3d16bb8?q=80&w=1470" alt="Demo"></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer style="margin-top: 100px; padding: 50px 0; background: #111; color: #777; text-align: center; border-top: 5px solid var(--primary-color);">
        <p>Planted by <a href="https://OfferPlant.com" style="color: var(--secondary-color); font-weight: 700;">OfferPlant.com</a> with NgoExpress (Ver.<?php echo CORE_VERSION; ?>)</p>
    </footer>

</body>
</html>
