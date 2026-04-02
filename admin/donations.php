<?php
include('layout_header.php');

$success_msg = "";

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM donations WHERE id = '$id'");
    $success_msg = "Donation record deleted!";
}

$donations = mysqli_query($conn, "SELECT * FROM donations ORDER BY date DESC");
?>

<div class="dashboard-card animate-up">
    <div class="card-header">
        <h3 style="font-size: 1.5rem;">Contribution Registry</h3>
        <?php if ($success_msg): ?>
            <span style="color: #2ecc71; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-check-circle"></i> <?php echo $success_msg; ?></span>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Donor Name</th>
                <th>Email & Contact</th>
                <th>Amount</th>
                <th>Trans ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Receipt</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($donations) > 0) {
                while ($row = mysqli_fetch_assoc($donations)) {
                    $status_badge = $row['status'] == 'paid' ? 'badge-success' : 'badge-warning';
                    echo "<tr>
                            <td style='font-weight: 600;'>{$row['name']}</td>
                            <td><div style='font-size: 0.85rem;'>{$row['email']}</div></td>
                            <td style='font-weight: 700; color: var(--primary-color);'>".currency().number_format($row['amount'], 2)."</td>
                            <td style='font-family: monospace; font-size: 0.85rem; color: #777;'>{$row['transaction_id']}</td>
                            <td>".date('d M, Y', strtotime($row['date']))."</td>
                            <td><span class='badge {$status_badge}'>".ucfirst($row['status'])."</span></td>
                            <td>
                                <a href='../receipt.php?id={$row['id']}' target='_blank' class='btn btn-outline' style='padding: 5px 12px; font-size: 0.75rem; border-radius: 8px;'><i class='fas fa-file-invoice-dollar'></i> SLIP</a>
                            </td>
                            <td>
                                <a href='?delete={$row['id']}' style='color: #e74c3c; font-size: 1rem;' onclick='return confirm(\"Permanently remove record?\")'><i class='fas fa-trash-alt'></i></a>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center; padding: 40px; color: #aaa;'>No contribution records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('layout_footer.php'); ?>
