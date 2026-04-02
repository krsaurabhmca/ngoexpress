<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['slider_image'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $sub_title = mysqli_real_escape_string($conn, $_POST['sub_title']);
    
    // Upload image
    $upload_dir = '../uploads/sliders/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $file_ext = pathinfo($_FILES['slider_image']['name'], PATHINFO_EXTENSION);
    $filename = time() . '.' . $file_ext;
    $target_file = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['slider_image']['tmp_name'], $target_file)) {
        $db_path = 'uploads/sliders/' . $filename;
        mysqli_query($conn, "INSERT INTO sliders (image, title, sub_title) VALUES ('$db_path', '$title', '$sub_title')");
        $success_msg = "Slider added successfully!";
    }
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $res = mysqli_query($conn, "SELECT image FROM sliders WHERE id = '$id'");
    $row = mysqli_fetch_assoc($res);
    if (file_exists('../' . $row['image'])) unlink('../' . $row['image']);
    mysqli_query($conn, "DELETE FROM sliders WHERE id = '$id'");
    $success_msg = "Slider deleted successfully!";
}

$sliders = mysqli_query($conn, "SELECT * FROM sliders ORDER BY id DESC");
?>

<div class="grid" style="grid-template-columns: 1fr 2fr; gap: 30px;">
    <!-- Add New Slider -->
    <div class="dashboard-card animate-up">
        <h3 style="margin-bottom: 25px;">Add New Hero Slider</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Slider Image (1920x800 recommended)</label>
                <input type="file" name="slider_image" required style="width: 100%; padding: 10px; background: #f9f9f9; border-radius: 10px; border: 1.5px solid #eee;">
            </div>
            <div class="form-group">
                <label>Main Heading</label>
                <input type="text" name="title" placeholder="Empowering Communities" required style="width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 10px;">
            </div>
            <div class="form-group">
                <label>Sub-text</label>
                <textarea name="sub_title" rows="3" placeholder="Short description for the slider..." style="width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 10px; resize: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Add Slider to Homepage</button>
        </form>
    </div>

    <!-- Active Sliders -->
    <div class="dashboard-card animate-up" style="animation-delay: 0.2s;">
        <div class="card-header">
            <h3 style="font-size: 1.2rem;">Active Website Sliders</h3>
            <?php if ($success_msg): ?>
                <span style="color: #2ecc71; font-weight: 600; font-size: 0.85rem;"><i class="bi bi-check-circle-fill"></i> <?php echo $success_msg; ?></span>
            <?php endif; ?>
        </div>

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <?php
            if (mysqli_num_rows($sliders) > 0) {
                while ($row = mysqli_fetch_assoc($sliders)) {
                    echo "<div style='background: #fff; border: 1.5px solid #eee; border-radius: 15px; overflow: hidden;'>
                            <img src='../{$row['image']}' style='width: 100%; height: 150px; object-fit: cover;'>
                            <div style='padding: 15px;'>
                                <h4 style='font-size: 1rem; margin-bottom: 5px;'>{$row['title']}</h4>
                                <p style='font-size: 0.75rem; color: #777; margin-bottom: 15px;'>{$row['sub_title']}</p>
                                <a href='?delete={$row['id']}' style='color: #e74c3c; font-size: 0.8rem; font-weight: 600;' onclick='return confirm(\"Delete this slider?\")'><i class='bi bi-trash'></i> REMOVE SLIDER</a>
                            </div>
                        </div>";
                }
            } else {
                echo "<p style='grid-column: span 2; text-align: center; color: #aaa; padding: 40px;'>No active sliders found. Add one to display on your homepage.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('layout_footer.php'); ?>
