<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activate_theme'])) {
    $theme_data = json_decode($_POST['theme_colors'], true);
    set_setting('primary_color', $theme_data['primary']);
    set_setting('secondary_color', $theme_data['secondary']);
    $success_msg = "Theme Profile Activated Successfully!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_text'])) {
    set_setting('typography', $_POST['typography']);
    set_setting('about_description', $_POST['about_description']);
    $success_msg = "Branding content updated!";
}

$current_primary = get_setting('primary_color');
$current_secondary = get_setting('secondary_color');
$typography = get_setting('typography');
$about_description = get_setting('about_description'); 

$themes = [
    ['name' => 'Deep Ocean', 'primary' => '#1e293b', 'secondary' => '#3b82f6', 'desc' => 'Professional & Trustworthy'],
    ['name' => 'Eco Nature', 'primary' => '#064e3b', 'secondary' => '#10b981', 'desc' => 'Growth & Sustainability'],
    ['name' => 'Solar Energy', 'primary' => '#78350f', 'secondary' => '#f59e0b', 'desc' => 'Warmth & Community'],
    ['name' => 'Royal Charity', 'primary' => '#4c1d95', 'secondary' => '#8b5cf6', 'desc' => 'Dignity & Respect'],
    ['name' => 'Emergency Red', 'primary' => '#7f1d1d', 'secondary' => '#ef4444', 'desc' => 'Urgency & Medical Aid'],
    ['name' => 'Slate Modern', 'primary' => '#334155', 'secondary' => '#64748b', 'desc' => 'Minimalist & Sober']
];
?>

<div class="dashboard-card animate-up">
    <div class="card-header">
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <h2 style="font-size: 1.25rem;">Theme Collection</h2>
            <p style="font-size: 0.8rem; color: #64748b;">Select and activate a professionally curated color profile for your NGO.</p>
        </div>
        <?php if ($success_msg): ?>
            <span style="color: #22c55e; font-weight: 700; font-size: 0.8rem; background: #f0fdf4; padding: 6px 15px; border-radius: 8px; border: 1.5px solid #bbf7d0;">
                <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Theme Selection Grid -->
    <div class="grid grid-3" style="gap: 20px; margin-top: 30px;">
        <?php foreach ($themes as $t): 
            $is_active = ($t['primary'] == $current_primary && $t['secondary'] == $current_secondary);
        ?>
            <div style="border: 2px solid <?php echo $is_active ? '#3b82f6' : '#f1f5f9'; ?>; padding: 20px; border-radius: 16px; background: white; transition: 0.3s; position: relative; <?php echo $is_active ? 'box-shadow: 0 10px 25px -10px rgba(59, 130, 246, 0.3);' : ''; ?>">
                <?php if ($is_active): ?>
                    <div style="position: absolute; top: 10px; right: 10px; color: #3b82f6; font-size: 0.8rem; font-weight: 800;"><i class="fas fa-check-circle"></i> ACTIVE</div>
                <?php endif; ?>
                
                <h4 style="margin-bottom: 15px; font-size: 1rem;"><?php echo $t['name']; ?></h4>
                
                <!-- Color Sample -->
                <div style="display: flex; height: 40px; border-radius: 8px; overflow: hidden; margin-bottom: 15px; border: 1px solid #eee;">
                    <div style="flex: 2; background: <?php echo $t['primary']; ?>;"></div>
                    <div style="flex: 1; background: <?php echo $t['secondary']; ?>;"></div>
                </div>
                
                <p style="font-size: 0.75rem; color: #64748b; margin-bottom: 20px;"><?php echo $t['desc']; ?></p>
                
                <form method="POST">
                    <input type="hidden" name="theme_colors" value='<?php echo json_encode($t); ?>'>
                    <button type="submit" name="activate_theme" class="btn <?php echo $is_active ? 'btn-primary' : 'btn-outline'; ?>" style="width: 100%; font-size: 0.75rem; padding: 10px; border-radius: 8px;">
                        <?php echo $is_active ? 'Currently Active' : 'Activate Theme'; ?>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Typography & Content -->
    <form method="POST" id="content-form" style="margin-top: 50px; border-top: 2px solid #f8fafc; padding-top: 40px;">
        <input type="hidden" name="save_text" value="1">
        
        <div class="grid grid-2" style="gap: 30px;">
             <div style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9;">
                <h4 style="font-size: 0.95rem; color: var(--primary-color); margin-bottom: 15px;">System Typography</h4>
                <select name="typography" style="width: 100%; height: 50px; padding: 10px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 0.95rem;">
                    <option value="Outfit, sans-serif" <?php echo $typography == "Outfit, sans-serif" ? 'selected' : ''; ?>>Outfit (Modern / Premium)</option>
                    <option value="'Inter', sans-serif" <?php echo $typography == "'Inter', sans-serif" ? 'selected' : ''; ?>>Inter (Clean / High-Density)</option>
                    <option value="'Roboto', sans-serif" <?php echo $typography == "'Roboto', sans-serif" ? 'selected' : ''; ?>>Roboto (Corporate / Standard)</option>
                </select>
            </div>

            <div style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9;">
                <h4 style="font-size: 0.95rem; color: var(--primary-color); margin-bottom: 15px;">Active Description</h4>
                <p style="font-size: 0.8rem; color: #64748b;">The content below is used to describe your organization on the homepage and about sections.</p>
            </div>
        </div>

        <div style="margin-top: 30px; background: #fff; border: 1.5px dashed #e2e8f0; border-radius: 20px; padding: 20px;">
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <div id="editor-wrapper" style="height: 300px; border-radius: 12px; overflow: hidden; border: 1.5px solid #f1f5f9;">
                <div id="main-editor"><?php echo $about_description; ?></div>
            </div>
            <input type="hidden" name="about_description" id="hidden_about_desc">
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 15px 50px; font-weight: 800; border-radius: 100px;">
               Update Visual Content <i class="fas fa-check-circle" style="margin-left: 10px;"></i>
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#main-editor', {
        theme: 'snow',
        modules: { toolbar: [ [{ 'header': [2, 3, false] }], ['bold', 'italic', 'link'], [{ 'list': 'ordered'}, { 'list': 'bullet' }] ] }
    });
    $('#content-form').submit(function() { $('#hidden_about_desc').val(quill.root.innerHTML); });
</script>

<?php include('layout_footer.php'); ?>
