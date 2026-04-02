<?php
include('layout_header.php');

$success_msg = "";

if (isset($_GET['approve'])) {
    $id = mysqli_real_escape_string($conn, $_GET['approve']);
    mysqli_query($conn, "UPDATE members SET status = 'active' WHERE id = '$id'");
    $success_msg = "Member approved successfully!";
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM members WHERE id = '$id'");
    $success_msg = "Member removed successfully!";
}

$members = mysqli_query($conn, "SELECT * FROM members ORDER BY joined_date DESC");
?>

<div class="dashboard-card animate-up">
    <div class="card-header">
        <h3 style="font-size: 1.5rem;">NGO Member Directory</h3>
        <?php if ($success_msg): ?>
            <span style="color: #2ecc71; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-check-circle"></i>
                <?php echo $success_msg; ?></span>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Profile</th>
                <th>Full Name</th>
                <th>Contact info</th>
                <th>Joined On</th>
                <th>Status</th>
                <th>Identity (ID/Cert)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($members) > 0) {
                while ($row = mysqli_fetch_assoc($members)) {
                    $status_badge = $row['status'] == 'active' ? 'badge-success' : 'badge-warning';
                    $photo = !empty($row['photo']) ? "../" . $row['photo'] : "https://ui-avatars.com/api/?name=" . urlencode($row['name']);

                    echo "<tr>
                            <td><img src='{$photo}' style='width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid #eee;'></td>
                            <td><div style='font-weight: 600;'>{$row['name']}</div><div style='color: #999; font-size: 0.75rem;'>UID: #M00{$row['id']}</div></td>
                            <td><div style='font-size: 0.85rem;'><i class='fas fa-envelope' style='color: #777;'></i> {$row['email']}</div><div style='font-size: 0.85rem;'><i class='fas fa-phone' style='color: #777;'></i> {$row['phone']}</div></td>
                            <td>" . date('d M, Y', strtotime($row['joined_date'])) . "</td>
                            <td><span class='badge {$status_badge}'>" . ucfirst($row['status']) . "</span></td>
                            <td>
                                <div style='display: flex; gap: 8px;'>
                                    <a href='id_card.php?id={$row['id']}' target='_blank' class='btn btn-outline' style='padding: 5px 10px; font-size: 0.7rem; border-radius: 8px;'><i class='fas fa-id-card'></i> ID Card</a>
                                    <a href='certificate.php?id={$row['id']}' target='_blank' class='btn btn-outline' style='padding: 5px 10px; font-size: 0.7rem; border-radius: 8px;'><i class='fas fa-certificate'></i> Certificate</a>
                                </div>
                            </td>
                            <td>
                                <div style='display: flex; gap: 10px;'>
                                    " . ($row['status'] == 'pending' ? "<a href='?approve={$row['id']}' class='btn btn-primary' style='padding: 5px 12px; font-size: 0.75rem; border-radius: 8px;'><i class='fas fa-check'></i></a>" : "") . "
                                    <a href='?delete={$row['id']}' class='btn btn-outline' style='padding: 5px 12px; font-size: 0.75rem; border-radius: 8px; color: #e74c3c; border-color: #e74c3c;' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash-alt'></i></a>
                                </div>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center; padding: 40px; color: #aaa;'>No registered members found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('layout_footer.php'); ?>