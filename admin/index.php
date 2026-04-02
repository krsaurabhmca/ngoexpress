<?php
include('layout_header.php');

// Fetch some real-time counts for the dashboard cards
$member_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM members"));
$donation_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM donations WHERE status = 'paid'"))['total'] ?? 0;
$pending_donations = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM donations WHERE status = 'pending'"));
$service_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM services"));
?>

<!-- Stat Grid -->
<div class="stat-grid animate-up">
    <div class="dashboard-card" style="border-top: 3px solid #3b82f6;">
        <p style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Total Members</p>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 5px;">
            <h2 style="font-size: 1.5rem; margin: 0; font-weight: 800; color: #1e293b;"><?php echo $member_count; ?></h2>
            <i class="fas fa-users" style="font-size: 1.2rem; color: #3b82f6; opacity: 0.2;"></i>
        </div>
    </div>

    <div class="dashboard-card" style="border-top: 3px solid #10b981;">
        <p style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Total Contributions</p>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 5px;">
            <h2 style="font-size: 1.5rem; margin: 0; font-weight: 800; color: #1e293b;"><?php echo currency(); ?><?php echo number_format($donation_total, 2); ?></h2>
            <i class="fas fa-hand-holding-usd" style="font-size: 1.2rem; color: #10b981; opacity: 0.2;"></i>
        </div>
    </div>

    <div class="dashboard-card" style="border-top: 3px solid #f59e0b;">
        <p style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Pending Actions</p>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 5px;">
            <h2 style="font-size: 1.5rem; margin: 0; font-weight: 800; color: #1e293b;"><?php echo $pending_donations; ?></h2>
            <i class="fas fa-tasks" style="font-size: 1.2rem; color: #f59e0b; opacity: 0.2;"></i>
        </div>
    </div>

    <div class="dashboard-card" style="border-top: 3px solid #6366f1;">
        <p style="color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Active Services</p>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 5px;">
            <h2 style="font-size: 1.5rem; margin: 0; font-weight: 800; color: #1e293b;"><?php echo $service_count; ?></h2>
            <i class="fas fa-laptop-code" style="font-size: 1.2rem; color: #6366f1; opacity: 0.2;"></i>
        </div>
    </div>
</div>

<div class="grid" style="grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Recent Donations -->
    <div class="dashboard-card animate-up" style="animation-delay: 0.2s;">
        <div class="card-header">
            <h3 style="font-size: 1.2rem;">Recent Donations</h3>
            <a href="donations.php" class="btn btn-outline" style="padding: 5px 15px; font-size: 0.75rem;">View All</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Donor Name</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $donations = mysqli_query($conn, "SELECT * FROM donations ORDER BY date DESC LIMIT 5");
                if (mysqli_num_rows($donations) > 0) {
                    while ($row = mysqli_fetch_assoc($donations)) {
                        $status_class = $row['status'] == 'paid' ? 'badge-success' : 'badge-warning';
                        echo "<tr>
                                <td style='font-weight: 600;'>{$row['name']}</td>
                                <td style='color: #777; font-size: 0.85rem;'>".date('d M, Y', strtotime($row['date']))."</td>
                                <td style='font-weight: 700; color: var(--primary-color);'>".currency().number_format($row['amount'], 2)."</td>
                                <td><span class='badge {$status_class}'>".ucfirst($row['status'])."</span></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; padding: 20px; color: #aaa;'>No recent donations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Quick Actions -->
    <div class="dashboard-card animate-up" style="animation-delay: 0.4s;">
        <h3 style="font-size: 1.2rem; margin-bottom: 25px;">Quick Management</h3>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <a href="members.php" class="btn btn-primary" style="justify-content: flex-start; width: 100%; border-radius: 12px; font-size: 0.9rem;">
                <i class="fas fa-id-card"></i> Generate ID Cards
            </a>
            <a href="settings.php" class="btn btn-outline" style="justify-content: flex-start; width: 100%; border-radius: 12px; font-size: 0.9rem;">
                <i class="fas fa-user-shield"></i> Site Security
            </a>
            <a href="theme.php" class="btn btn-outline" style="justify-content: flex-start; width: 100%; border-radius: 12px; font-size: 0.9rem;">
                <i class="fas fa-paint-roller"></i> Change Primary Colors
            </a>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
            <p style="font-size: 0.85rem; color: #777; line-height: 1.4;">Need help with the portal? Access our <a href="#" style="color: var(--secondary-color); font-weight: 600;">Documentation</a> for guidance.</p>
        </div>
    </div>
</div>

<?php include('layout_footer.php'); ?>
