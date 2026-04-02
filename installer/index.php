<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Platform | Installation Wizard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .install-container {
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: 24px;
            background: white;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .install-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .install-header h1 {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .install-header p {
            color: var(--text-muted);
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step-num {
            width: 30px;
            height: 30px;
            background: #eee;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #777;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .step.active .step-num {
            background: var(--secondary-color);
            color: white;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #eee;
            z-index: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #eee;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.1);
        }

        #install-progress {
            display: none;
            text-align: center;
        }

        .progress-bar {
            height: 8px;
            background: #eee;
            border-radius: 4px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--secondary-color);
            width: 0%;
            transition: 0.5s;
        }

        .success-msg {
            display: none;
            text-align: center;
            color: #2ecc71;
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="install-container animate-up">
        <div class="install-header">
            <i class="fas fa-magic" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 15px;"></i>
            <h1>NGO Setup Wizard</h1>
            <p>Configure your database and site basic info to get started.</p>
        </div>

        <form id="install-form">
            <div id="step-1">
                <h3 style="margin-bottom: 20px;">Database Configuration</h3>
                <div class="form-group">
                    <label>DB Host</label>
                    <input type="text" name="db_host" value="localhost" required>
                </div>
                <div class="form-group">
                    <label>DB Username</label>
                    <input type="text" name="db_user" value="root" required>
                </div>
                <div class="form-group">
                    <label>DB Password</label>
                    <input type="password" name="db_pass" value="">
                </div>
                <div class="form-group">
                    <label>DB Name</label>
                    <input type="text" name="db_name" value="ngo_db" required>
                </div>
                <button type="button" class="btn btn-primary" style="width: 100%;" onclick="nextStep(2)">Next Step</button>
            </div>

            <div id="step-2" style="display: none;">
                <h3 style="margin-bottom: 20px;">Administrator Account</h3>
                <div class="form-group">
                    <label>Admin Username</label>
                    <input type="text" name="admin_user" value="admin" required>
                </div>
                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" name="admin_email" placeholder="admin@example.com" required>
                </div>
                <div class="form-group">
                    <label>Admin Password</label>
                    <input type="password" name="admin_pass" required>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-secondary" style="background:#eee; color:#333;" onclick="nextStep(1)">Back</button>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Start Installation</button>
                </div>
            </div>
        </form>

        <div id="install-progress">
            <h3 id="progress-text">Installing Database...</h3>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <p style="font-size: 0.9rem; color: #777;">Please wait, we are setting up your premium NGO platform.</p>
        </div>

        <div class="success-msg">
            <i class="fas fa-check-circle" style="font-size: 4rem; margin-bottom: 15px;"></i>
            <h2>Installation Complete!</h2>
            <p>Your NGO platform has been successfully installed. You can now login to your admin panel.</p>
            <a href="../admin/login.php" class="btn btn-primary" style="margin-top: 25px;">Go to Admin Panel</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function nextStep(step) {
            if (step === 2) {
                $('#step-1').fadeOut(300, function() {
                    $('#step-2').fadeIn(300);
                });
            } else {
                $('#step-2').fadeOut(300, function() {
                    $('#step-1').fadeIn(300);
                });
            }
        }

        $('#install-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $(this).fadeOut(300, function() {
                $('#install-progress').fadeIn(300);
            });

            // Simulate progress for UI feel
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                $('.progress-fill').css('width', progress + '%');
                if (progress >= 30) $('#progress-text').text('Creating Tables...');
                if (progress >= 60) $('#progress-text').text('Seeding Initial Data...');
                if (progress >= 90) $('#progress-text').text('Configuring Site...');
                if (progress >= 100) clearInterval(progressInterval);
            }, 100);

            // AJAX Call to installer/process.php
            $.ajax({
                url: 'process.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        setTimeout(() => {
                            $('#install-progress').fadeOut(300, function() {
                                $('.success-msg').fadeIn(300);
                            });
                        }, 2500);
                    } else {
                        alert('Error: ' + response.message);
                        location.reload();
                    }
                },
                error: function() {
                    alert('An error occurred during installation.');
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>
