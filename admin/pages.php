<?php
include('layout_header.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['page_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    mysqli_query($conn, "UPDATE pages SET title = '$title', content = '$content' WHERE id = '$id'");
    $success_msg = "Page content updated successfully!";
}

// Ensure base pages exist
$pages_to_check = [
    ['slug' => 'about', 'title' => 'About Our NGO'],
    ['slug' => 'services', 'title' => 'Our Core Services'],
    ['slug' => 'donation_policy', 'title' => 'Donation & Refund Policy']
];

foreach ($pages_to_check as $p) {
    $slug = $p['slug'];
    $check = mysqli_query($conn, "SELECT id FROM pages WHERE slug = '$slug'");
    if (mysqli_num_rows($check) === 0) {
        $title = $p['title'];
        mysqli_query($conn, "INSERT INTO pages (slug, title, content) VALUES ('$slug', '$title', '<p>Enter content here...</p>')");
    }
}

$page_id = isset($_GET['edit']) ? mysqli_real_escape_string($conn, $_GET['edit']) : null;
$editing_page = null;
if ($page_id) {
    $editing_page = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pages WHERE id = '$page_id'"));
}

$all_pages = mysqli_query($conn, "SELECT * FROM pages ORDER BY title ASC");
?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="grid" style="grid-template-columns: 1fr 3fr; gap: 20px;">
    <!-- Page List -->
    <div class="dashboard-card animate-up">
        <h3 style="font-size: 1.1rem; margin-bottom: 20px;">System Pages</h3>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <?php while ($p = mysqli_fetch_assoc($all_pages)): ?>
                <a href="?edit=<?php echo $p['id']; ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #f8fafc; border: 1px solid <?php echo ($page_id == $p['id']) ? 'var(--secondary-color)' : '#e2e8f0'; ?>; border-radius: 6px; font-size: 0.85rem; font-weight: 500; color: #1e293b;">
                    <?php echo $p['title']; ?>
                    <i class="fas fa-edit" style="color: var(--secondary-color); font-size: 0.75rem;"></i>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Editor -->
    <?php if ($editing_page): ?>
    <div class="dashboard-card animate-up" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h3 style="font-size: 1.1rem;">Editing: <span style="color: var(--secondary-color);"><?php echo $editing_page['title']; ?></span></h3>
            <?php if ($success_msg): ?>
                <span style="color: #22c55e; font-weight: 600; font-size: 0.8rem; background: #f0fdf4; padding: 4px 10px; border-radius: 4px; border: 1px solid #bbf7d0;">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </span>
            <?php endif; ?>
        </div>

        <form method="POST" id="page-editor-form" style="margin-top: 15px;">
            <input type="hidden" name="page_id" value="<?php echo $editing_page['id']; ?>">
            <div class="form-group">
                <label>Page Navigation Title</label>
                <input type="text" name="title" value="<?php echo $editing_page['title']; ?>" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.9rem; margin-bottom: 15px;">
            </div>
            
            <div class="form-group">
                <label>Page Content (Rich Text)</label>
                <div id="editor-container" style="height: 400px; border-radius: 0 0 6px 6px; border-color: #e2e8f0;"><?php echo $editing_page['content']; ?></div>
                <input type="hidden" name="content" id="page_content">
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight: 700;">Update Page Content</button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="dashboard-card animate-up" style="animation-delay: 0.1s; display: flex; align-items: center; justify-content: center; min-height: 500px; color: #94a3b8;">
        <div style="text-align: center;">
            <i class="fas fa-file-signature" style="font-size: 4rem; opacity: 0.1; margin-bottom: 20px;"></i>
            <p>Select a page from the sidebar to manage its content.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    <?php if ($editing_page): ?>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                ['link', 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    $('#page-editor-form').on('submit', function() {
        $('#page_content').val(quill.root.innerHTML);
    });
    <?php endif; ?>
</script>

<?php include('layout_footer.php'); ?>
