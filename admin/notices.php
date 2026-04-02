<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notice_title'])) {
    $title = mysqli_real_escape_string($conn, $_POST['notice_title']);
    $content = mysqli_real_escape_string($conn, $_POST['notice_content']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    mysqli_query($conn, "INSERT INTO notices (title, content, status) VALUES ('$title', '$content', '$status')");
    $success_msg = "Notice posted successfully!";
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM notices WHERE id = '$id'");
    $success_msg = "Notice deleted!";
}

$notices = mysqli_query($conn, "SELECT * FROM notices ORDER BY id DESC");
?>

<div class="grid" style="grid-template-columns: 1fr 2fr; gap: 20px;">
    <!-- Add Notice -->
    <div class="dashboard-card animate-up">
        <h3 style="font-size: 1.1rem; margin-bottom: 20px;">Post New Notice</h3>
        <form method="POST">
            <div class="form-group">
                <label>Notice / Project Title</label>
                <input type="text" name="notice_title" placeholder="School building project..." required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
            </div>
            <div class="form-group">
                <label>Brief Content</label>
                <textarea name="notice_content" rows="4" placeholder="Brief update here..." required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem; resize: none;"></textarea>
            </div>
            <div class="form-group">
                <label>Display Status</label>
                <select name="status" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem;">
                    <option value="active">Visible</option>
                    <option value="hidden">Hidden</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; border-radius: 6px; font-weight: 600;">Publish Update</button>
        </form>
    </div>

    <!-- Active Notices -->
    <div class="dashboard-card animate-up" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h3 style="font-size: 1.1rem;">Latest Notices & Programs</h3>
            <?php if ($success_msg): ?>
                <span style="color: #22c55e; font-weight: 600; font-size: 0.8rem; background: #f0fdf4; padding: 4px 10px; border-radius: 4px; border: 1px solid #bbf7d0;">
                   <?php echo $success_msg; ?>
                </span>
            <?php endif; ?>
        </div>

        <div style="margin-top: 15px;">
            <?php
            if (mysqli_num_rows($notices) > 0) {
                while ($row = mysqli_fetch_assoc($notices)) {
                    $status_badge = ($row['status'] == 'active') ? 'background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;' : 'background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;';
                    echo "<div style='padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 10px; position: relative;'>
                            <div style='display: flex; justify-content: space-between; align-items: start;'>
                                <div>
                                    <h4 style='font-size: 0.95rem; margin-bottom: 5px; color: var(--primary-color);'>{$row['title']}</h4>
                                    <p style='font-size: 0.8rem; color: #64748b; margin-bottom: 8px;'>{$row['content']}</p>
                                    <span style='font-size: 0.7rem; {$status_badge} padding: 2px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;'>".strtoupper($row['status'])."</span>
                                    <span style='font-size: 0.7rem; color: #94a3b8; margin-left: 10px;'><i class='far fa-calendar-alt'></i> ".date('d M, Y', strtotime($row['date']))."</span>
                                </div>
                                <a href='?delete={$row['id']}' style='color: #ef4444; font-size: 0.85rem;' onclick='return confirm(\"Delete notice?\")'><i class='fas fa-trash'></i></a>
                            </div>
                        </div>";
                }
            } else {
                echo "<div style='padding: 40px; text-align: center; color: #94a3b8; border: 1px dashed #e2e8f0; border-radius: 10px;'>
                        <p>No active notices yet. Start by posting a new update!</p>
                      </div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('layout_footer.php'); ?>
