<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gallery_image'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    
    // Upload image
    $upload_dir = '../uploads/gallery/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $file_ext = pathinfo($_FILES['gallery_image']['name'], PATHINFO_EXTENSION);
    $filename = 'gallery_' . time() . '.' . $file_ext;
    $target_file = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $target_file)) {
        $db_path = 'uploads/gallery/' . $filename;
        mysqli_query($conn, "INSERT INTO gallery (image, title) VALUES ('$db_path', '$title')");
        $success_msg = "Image added to gallery!";
    }
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $res = mysqli_query($conn, "SELECT image FROM gallery WHERE id = '$id'");
    if ($row = mysqli_fetch_assoc($res)) {
        if (file_exists('../' . $row['image'])) unlink('../' . $row['image']);
        mysqli_query($conn, "DELETE FROM gallery WHERE id = '$id'");
        $success_msg = "Image removed from gallery!";
    }
}

$gallery_items = mysqli_query($conn, "SELECT * FROM gallery ORDER BY id DESC");
?>

<div class="grid" style="grid-template-columns: 1fr 3fr; gap: 20px;">
    <!-- Add Image -->
    <div class="dashboard-card animate-up">
        <h3 style="font-size: 1.1rem; margin-bottom: 20px;">Upload Image</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select Photo</label>
                <input type="file" name="gallery_image" required style="font-size: 0.8rem; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; width: 100%;">
            </div>
            <div class="form-group">
                <label>Photo Caption</label>
                <input type="text" name="title" placeholder="School build program..." required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 6px; font-weight: 600;">Add to Gallery</button>
        </form>
    </div>

    <!-- Gallery View -->
    <div class="dashboard-card animate-up" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h3 style="font-size: 1.1rem;">Media Library</h3>
            <?php if ($success_msg): ?>
                <span style="color: #22c55e; font-weight: 600; font-size: 0.8rem; background: #f0fdf4; padding: 4px 10px; border-radius: 4px; border: 1px solid #bbf7d0;">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </span>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-top: 15px;">
            <?php
            if (mysqli_num_rows($gallery_items) > 0) {
                while ($row = mysqli_fetch_assoc($gallery_items)) {
                    echo "<div style='background: white; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; position: relative;' class='gallery-item'>
                            <img src='../{$row['image']}' style='width: 100%; height: 140px; object-fit: cover;'>
                            <div style='padding: 10px;'>
                                <p style='font-size: 0.75rem; color: #64748b; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>{$row['title']}</p>
                                <a href='?delete={$row['id']}' style='color: #ef4444; font-size: 0.7rem; font-weight: 700;' onclick='return confirm(\"Delete this photo?\")'><i class='fas fa-trash'></i> REMOVE IMAGE</a>
                            </div>
                        </div>";
                }
            } else {
                echo "<div style='grid-column: 1 / -1; padding: 60px; text-align: center; color: #94a3b8;'>
                        <i class='fas fa-images' style='font-size: 3rem; opacity: 0.1; margin-bottom: 15px;'></i>
                        <p>Your media gallery is empty. Upload icons or impact photos above.</p>
                      </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('layout_footer.php'); ?>
