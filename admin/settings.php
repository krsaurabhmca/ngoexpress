<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process text settings
    foreach ($_POST as $key => $value) {
        if ($key != 'new_password') {
            set_setting($key, $value);
        }
    }

    // Process File Uploads (Logo & Popup)
    $upload_dir = '../uploads/branding/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION);
        $logo_name = 'logo_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $upload_dir . $logo_name)) {
            set_setting('site_logo', 'uploads/branding/' . $logo_name);
        }
    }

    if (isset($_FILES['popup_file']) && $_FILES['popup_file']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['popup_file']['name'], PATHINFO_EXTENSION);
        $popup_name = 'popup_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['popup_file']['tmp_name'], $upload_dir . $popup_name)) {
            set_setting('site_popup_image', 'uploads/branding/' . $popup_name);
        }
    }

    // Password Update
    if (!empty($_POST['new_password'])) {
        $hashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE role = 'admin' LIMIT 1");
        $success_msg .= " Password Protocol Updated!";
    }

    $success_msg .= " System configurations synchronized!";
}
?>

<div class="dashboard-card animate-up" style="padding: 0; overflow: hidden; border: none; background: transparent; box-shadow: none;">
    <div style="background: white; padding: 40px; border-radius: 24px; border: 1.5px solid #e2e8f0; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2 style="font-size: 1.5rem; color: #1e293b; font-weight: 800;">NGOExpress Operations Control</h2>
                <p style="color: #64748b; font-size: 0.85rem; margin-top: 5px;">Manage global system variables, branding identities, and security protocols.</p>
            </div>
            <?php if ($success_msg): ?>
                <div style="background: #f0fdf4; border: 1.2px solid #bbf7d0; padding: 12px 20px; border-radius: 12px; display: flex; align-items: center; gap: 10px; animation: slideDown 0.4s ease;">
                    <i class="bi bi-check-circle-fill" style="color: #22c55e;"></i>
                    <span style="color: #166534; font-size: 0.8rem; font-weight: 700;"><?php echo $success_msg; ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tabbed Configuration Interface -->
    <div style="display: flex; gap: 30px; align-items: flex-start;">
        
        <!-- Sidebar Navigation (Tabs) -->
        <div style="width: 280px; background: white; border-radius: 20px; border: 1.5px solid #e2e8f0; padding: 15px; position: sticky; top: 20px;">
            <div style="margin-bottom: 20px; padding: 10px;">
                <p style="font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Control Sections</p>
            </div>
            <a href="javascript:void(0)" onclick="showTab('identity')" class="tab-link active" id="link-identity"><i class="bi bi-fingerprint"></i> Brand Identity</a>
            <a href="javascript:void(0)" onclick="showTab('visuals')" class="tab-link" id="link-visuals"><i class="bi bi-paint-bucket"></i> Visual Assets</a>
            <a href="javascript:void(0)" onclick="showTab('contact')" class="tab-link" id="link-contact"><i class="bi bi-globe"></i> Global Reach</a>
            <a href="javascript:void(0)" onclick="showTab('security')" class="tab-link" id="link-security"><i class="bi bi-shield-shaded"></i> Access & Security</a>
            <hr style="margin: 20px 0; border: none; border-top: 1.5px solid #f1f5f9;">
            <a href="?repair=1" class="btn btn-outline" style="width: 100%; border-color: #fda4af; color: #ef4444; font-size: 0.8rem; padding: 10px;">Repair Structure <i class="bi bi-cpu" style="margin-left: 5px;"></i></a>
        </div>

        <!-- Working Panel -->
        <div style="flex: 1;">
            <form method="POST" enctype="multipart/form-data">
                
                <!-- Tab: Identity -->
                <div id="tab-identity" class="tab-content" style="display: block;">
                    <div style="background: white; padding: 35px; border-radius: 24px; border: 1.5px solid #e2e8f0;">
                         <h4 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-fingerprint" style="color: #3b82f6;"></i> Primary Identity Settings
                        </h4>
                        <div class="grid grid-2" style="gap: 25px;">
                            <div class="form-group">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Legal Organization Name</label>
                                <input type="text" name="site_name" value="<?php echo get_setting('site_name'); ?>" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 10px; padding: 0 15px; font-weight: 600;">
                                <p style="font-size: 0.7rem; color: #94a3b8; margin-top: 8px;">Used in page titles, invoices, and branded documents.</p>
                            </div>
                            <div class="form-group">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Fiscal Currency Symbol</label>
                                <input type="text" name="currency_symbol" value="<?php echo currency(); ?>" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 10px; padding: 0 15px; font-weight: 800; font-size: 1.2rem; text-align: center;">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">"About Us" Headline</label>
                                <input type="text" name="about_title" value="<?php echo get_setting('about_title'); ?>" placeholder="Dedicated to providing education and healthcare." style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 10px; padding: 0 15px; font-weight: 600;">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">"About Us" Mission Statement</label>
                                <textarea name="about_description" style="width: 100%; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 10px; padding: 15px; min-height: 100px; font-size: 0.9rem; resize: vertical;"><?php echo get_setting('about_description'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Visuals -->
                <div id="tab-visuals" class="tab-content" style="display: none;">
                    <div style="background: white; padding: 35px; border-radius: 24px; border: 1.5px solid #e2e8f0;">
                        <h4 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-paint-bucket" style="color: #f59e0b;"></i> Graphic Assets & Controls
                        </h4>
                        <div class="grid grid-2" style="gap: 25px;">
                            <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1.2px dashed #cbd5e1;">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Master Brand Logo</label>
                                <input type="file" name="logo_file" style="margin-top: 12px; display: block; font-size: 0.8rem;">
                                
                                <div style="display: flex; gap: 15px; align-items: center; margin-top: 15px;">
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Header Display Mode:</span>
                                    <select name="logo_display_mode" style="height: 35px; border-radius: 8px; border: 1.2px solid #cbd5e1; font-size: 0.75rem; padding: 0 10px;">
                                        <option value="both" <?php echo get_setting('logo_display_mode') == 'both' || !get_setting('logo_display_mode') ? 'selected' : ''; ?>>Logo & Name</option>
                                        <option value="logo" <?php echo get_setting('logo_display_mode') == 'logo' ? 'selected' : ''; ?>>Logo Only</option>
                                        <option value="name" <?php echo get_setting('logo_display_mode') == 'name' ? 'selected' : ''; ?>>Name Only</option>
                                    </select>
                                </div>
                                <?php if ($logo = get_setting('site_logo')): ?>
                                    <div style="background: white; padding: 10px; border-radius: 10px; border: 1.2px solid #e2e8f0; margin-top: 15px; display: inline-block;">
                                        <img src="../<?php echo $logo; ?>" style="height: 30px;" alt="Logo Mini">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group" style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1.2px dashed #cbd5e1;">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Promotional Popup Asset</label>
                                <input type="file" name="popup_file" style="margin-top: 12px; display: block; font-size: 0.8rem;">
                                <div style="display: flex; gap: 15px; align-items: center; margin-top: 15px;">
                                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Visibility Control:</span>
                                    <select name="popup_enabled" style="height: 35px; border-radius: 8px; border: 1.2px solid #cbd5e1; font-size: 0.75rem; padding: 0 10px;">
                                        <option value="0" <?php echo get_setting('popup_enabled') == '0' ? 'selected' : ''; ?>>Silent Mode</option>
                                        <option value="1" <?php echo get_setting('popup_enabled') == '1' ? 'selected' : ''; ?>>Active Display</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                 <!-- Tab: Contact -->
                <div id="tab-contact" class="tab-content" style="display: none;">
                    <div style="background: white; padding: 35px; border-radius: 24px; border: 1.5px solid #e2e8f0;">
                        <h4 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-globe" style="color: #10b981;"></i> Communication Configuration
                        </h4>
                        <div class="grid grid-2" style="gap: 25px;">
                            <div class="form-group">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Official Contact Email</label>
                                <input type="email" name="site_email" value="<?php echo get_setting('site_email'); ?>" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 8px; padding: 0 15px;">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Operation Contact Line</label>
                                <input type="text" name="site_phone" value="<?php echo get_setting('site_phone'); ?>" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 8px; padding: 0 15px;">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Geographical Coordinate (IFrame)</label>
                                <textarea name="google_map_iframe" rows="4" style="width: 100%; border-radius: 16px; border: 1.5px solid #e2e8f0; margin-top: 10px; padding: 15px; font-size: 0.8rem; font-family: monospace; border-left: 5px solid #3b82f6;"><?php echo get_setting('google_map_iframe'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Security -->
                <div id="tab-security" class="tab-content" style="display: none;">
                    <div style="background: white; padding: 35px; border-radius: 24px; border: 1.5px solid #e2e8f0;">
                         <h4 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-shield-shaded" style="color: #ef4444;"></i> Access Control & SMTP Protocols
                        </h4>
                        <div class="grid grid-2" style="gap: 25px;">
                            <div class="form-group" style="grid-column: span 2; background: #fff1f2; padding: 20px; border-radius: 16px; border: 1.2px solid #fecdd3;">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #e11d48; text-transform: uppercase;">Master Administrator Password</label>
                                <input type="password" name="new_password" placeholder="••••••••••••" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #fda4af; margin-top: 10px; padding: 0 15px; background: white;">
                                <p style="font-size: 0.7rem; color: #e11d48; margin-top: 10px;"><i class="bi bi-exclamation-triangle"></i> Leave blank to maintain current high-security encryption.</p>
                            </div>
                            <div class="form-group">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">SMTP Relay Host</label>
                                <input type="text" name="smtp_host" value="<?php echo get_setting('smtp_host'); ?>" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 8px; padding: 0 15px;">
                            </div>
                            <div class="form-group">
                                <label style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase;">SMTP Identification Key</label>
                                <input type="text" name="smtp_user" value="<?php echo get_setting('smtp_user'); ?>" style="width: 100%; height: 50px; border-radius: 12px; border: 1.5px solid #e2e8f0; margin-top: 8px; padding: 0 15px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                     <button type="submit" class="btn btn-primary" style="padding: 18px 80px; border-radius: 100px; font-weight: 800; letter-spacing: 1px; box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.4);">
                        Synchronize System State <i class="bi bi-cloud-arrow-up" style="margin-left: 10px;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showTab(tabId) {
        // Hide all
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
        
        // Show selected
        document.getElementById('tab-' + tabId).style.display = 'block';
        document.getElementById('link-' + tabId).classList.add('active');
        
        // Update URL to keep track (optional)
        window.history.replaceState(null, null, '#link-' + tabId);
    }
    
    // Auto-load tab from hash
    if(window.location.hash) {
        const h = window.location.hash.replace('#link-', '');
        if(['identity', 'visuals', 'contact', 'security'].includes(h)) showTab(h);
    }
</script>

<style>
    .tab-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 20px;
        border-radius: 14px;
        color: #64748b;
        font-weight: 700;
        font-size: 0.9rem;
        transition: 0.3s;
        text-decoration: none;
        margin-bottom: 5px;
    }
    .tab-link:hover { background: #f8fafc; color: #1e293b; }
    .tab-link.active { background: #3b82f630; color: #3b82f6; }
    .tab-link i { width: 20px; font-size: 1.1rem; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<?php 
// Repair Logic
if (isset($_GET['repair'])) {
     $schema_sql = "
    CREATE TABLE IF NOT EXISTS `gallery` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `image` varchar(255) NOT NULL,
      `date` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `pages` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `slug` varchar(100) NOT NULL,
      `title` varchar(100) NOT NULL,
      `content` text DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `notices` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `content` text DEFAULT NULL,
      `date` timestamp NOT NULL DEFAULT current_timestamp(),
      `status` varchar(20) DEFAULT 'active',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    INSERT IGNORE INTO `settings` (`setting_key`, `value`) VALUES
    ('site_name', 'NGOExpress'),
    ('primary_color', '#1e293b'),
    ('secondary_color', '#3b82f6'),
    ('currency_symbol', '₹'),
    ('site_logo', ''),
    ('site_popup_image', ''),
    ('popup_enabled', '0');
    ";
    $queries = explode(';', $schema_sql);
    foreach ($queries as $q) { if (!empty(trim($q))) mysqli_query($conn, $q); }
    echo "<script>alert('NGOExpress Core Repaired Successfully!'); window.location='settings.php';</script>";
}
include('layout_footer.php'); ?>
