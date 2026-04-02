<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Member | Hope & Help NGO</title>
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

        .membership-container {
            max-width: 900px;
            margin: 150px auto 100px;
            padding: 50px;
            background: white;
            border-radius: 40px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .benefit-card {
            background: #f8fbff;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .benefit-card i { font-size: 1.5rem; color: var(--secondary-color); margin-bottom: 10px; display: block; }
        .benefit-card h4 { font-size: 1.1rem; margin-bottom: 5px; }
        .benefit-card p { font-size: 0.85rem; color: #777; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--primary-color); }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #eee; background: #fafafa; font-size: 0.95rem; }

        #member-photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #eee;
            background: #eee url('https://ui-avatars.com/api/?name=Member&background=cbd5e1&color=fff&size=100') center/cover;
            margin-bottom: 15px;
            margin-inline: auto;
            cursor: pointer;
        }
    </style>
</head>
<body style="background: #f0f4f7;">

    <nav id="navbar">
        <a href="index.php" class="logo">
            <?php if ($logo = get_setting('site_logo')): ?>
                <img src="<?php echo $logo; ?>" style="height: 40px;" alt="Logo">
            <?php else: ?>
                <i class="fas fa-hand-holding-heart" style="color: var(--secondary-color);"></i> NGO PORTAL
            <?php endif; ?>
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="membership.php">Membership</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <a href="donate.php" class="btn btn-primary" style="border-radius: 50px; padding: 10px 25px;">Donate Now</a>
    </nav>

    <div class="membership-container animate-up">
        <!-- Benefits Section -->
        <div>
            <h2 style="font-size: 2.2rem; color: var(--primary-color); margin-bottom: 20px;">Why Join Us?</h2>
            <p style="color: #666; margin-bottom: 30px;">Become a part of our global family and contribute to sustainable development and community growth.</p>
            
            <div class="benefit-card">
                <i class="fas fa-id-badge"></i>
                <h4>Official ID Card</h4>
                <p>Receive a digital membership identity recognized by our organization worldwide.</p>
            </div>
            <div class="benefit-card">
                <i class="fas fa-certificate"></i>
                <h4>Participation Certificate</h4>
                <p>Get a formal certificate of contribution and participation in our social activities.</p>
            </div>
            <div class="benefit-card">
                <i class="fas fa-calendar-check"></i>
                <h4>Early Event Access</h4>
                <p>Get priority registration for our volunteer camps and educational seminars.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div>
            <form id="membership-form" enctype="multipart/form-data">
                <div style="text-align: center;">
                    <div id="member-photo-preview" onclick="document.getElementById('member_photo').click()"></div>
                    <p style="font-size: 0.8rem; color: #777; margin-bottom: 20px;">Click to upload passport photo</p>
                    <input type="file" name="member_photo" id="member_photo" style="display: none;" accept="image/*" onchange="previewImage(this)">
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" name="phone" placeholder="+1..." required>
                </div>
                <div class="form-group">
                    <label>Complete Address</label>
                    <textarea name="address" rows="3" placeholder="Enter residential address" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; margin-top: 10px;">
                    Send Membership Application <i class="fas fa-paper-plane"></i>
                </button>
            </form>

            <div id="success-msg" style="display: none; text-align: center; margin-top: 30px;">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #2ecc71; margin-bottom: 15px;"></i>
                <h3>Application Received!</h3>
                <p style="color: #777; font-size: 0.9rem;">Your membership request is being reviewed. We will email your Certificate and ID Card soon.</p>
                <a href="index.php" class="btn btn-primary" style="margin-top: 25px;">Return to Home</a>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#member-photo-preview').css('background-image', 'url(' + e.target.result + ')');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#membership-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'process_membership.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#membership-form').fadeOut(300, function() {
                            $('#success-msg').fadeIn(300);
                        });
                    } else {
                        alert("Error: " + res.message);
                    }
                }
            });
        });
    </script>
</body>
</html>
