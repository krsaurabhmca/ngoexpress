<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    mysqli_query($conn, "INSERT INTO services (icon, title, description) VALUES ('$icon', '$title', '$description')");
    $success_msg = "Service added successfully!";
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM services WHERE id = '$id'");
    $success_msg = "Service removed successfully!";
}

$services = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
?>

<div class="grid" style="grid-template-columns: 1fr 2fr; gap: 30px;">
    <!-- Add New Service -->
    <div class="dashboard-card animate-up">
        <h3 style="margin-bottom: 25px;">Define New NGO Service</h3>
        <form method="POST">
            <div class="form-group">
                <label>FontAwesome Icon Class</label>
                <input type="text" name="icon" placeholder="fas fa-graduation-cap" required style="width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 10px;">
                <p style="font-size: 0.75rem; color: #777; margin-top: 5px;">Example: <code>fas fa-heart</code>, <code>fas fa-users</code></p>
            </div>
            <div class="form-group">
                <label>Service Title</label>
                <input type="text" name="title" placeholder="Education for All" required style="width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 10px;">
            </div>
            <div class="form-group">
                <label>Service Description</label>
                <textarea name="description" rows="5" placeholder="Detailed impact and delivery model..." style="width: 100%; padding: 12px; border: 1.5px solid #eee; border-radius: 10px; resize: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Publish Service Area</button>
        </form>
    </div>

    <!-- Active Services -->
    <div class="dashboard-card animate-up" style="animation-delay: 0.2s;">
        <div class="card-header">
            <h3 style="font-size: 1.2rem;">Our Published Impact Services</h3>
            <?php if ($success_msg): ?>
                <span style="color: #2ecc71; font-weight: 600; font-size: 0.85rem;"><i class="fas fa-check-circle"></i> <?php echo $success_msg; ?></span>
            <?php endif; ?>
        </div>

        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
            <?php
            if (mysqli_num_rows($services) > 0) {
                while ($row = mysqli_fetch_assoc($services)) {
                    echo "<div style='background: #fff; border: 1.5px solid #eee; border-radius: 15px; padding: 20px; text-align: center;'>
                            <i class='{$row['icon']}' style='font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 15px; display: block;'></i>
                            <h4 style='font-size: 1.1rem; margin-bottom: 10px;'>{$row['title']}</h4>
                            <p style='font-size: 0.85rem; color: #777; margin-bottom: 20px;'>{$row['description']}</p>
                            <a href='?delete={$row['id']}' style='color: #e74c3c; font-size: 0.85rem; font-weight: 600;' onclick='return confirm(\"Remove service?\")'><i class='fas fa-trash'></i> REMOVE</a>
                        </div>";
                }
            } else {
                echo "<p style='grid-column: span 2; text-align: center; color: #aaa; padding: 40px;'>No published services yet.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('layout_footer.php'); ?>
