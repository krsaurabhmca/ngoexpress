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
            <span style="color: #2ecc71; font-weight: 600; font-size: 0.9rem;"><i class="bi bi-check-circle-fill"></i>
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
                            <td><div style='font-size: 0.85rem;'><i class='bi bi-envelope' style='color: #777;'></i> {$row['email']}</div><div style='font-size: 0.85rem;'><i class='bi bi-telephone' style='color: #777;'></i> {$row['phone']}</div></td>
                            <td>" . date('d M, Y', strtotime($row['joined_date'])) . "</td>
                            <td><span class='badge {$status_badge}'>" . ucfirst($row['status']) . "</span></td>
                            <td>
                                <div style='display: flex; gap: 8px;'>
                                    <a href='id_card.php?id=" . md5($row['id']) . "' target='_blank' class='btn btn-outline' style='padding: 5px 10px; font-size: 0.7rem; border-radius: 8px;'><i class='bi bi-person-vcard'></i> ID</a>
                                    <a href='certificate.php?id=" . md5($row['id']) . "' target='_blank' class='btn btn-outline' style='padding: 5px 10px; font-size: 0.7rem; border-radius: 8px;'><i class='bi bi-award'></i> Cert</a>
                                </div>
                            </td>
                            <td>
                                <div style='display: flex; gap: 10px;'>
                                    <button class='btn btn-outline' style='padding: 5px 12px; font-size: 0.75rem; border-radius: 8px;' onclick='viewMember(".json_encode($row).")'><i class='bi bi-eye'></i> View</button>
                                    " . ($row['status'] == 'pending' ? "<a href='?approve={$row['id']}' class='btn btn-primary' style='padding: 5px 12px; font-size: 0.75rem; border-radius: 8px;'><i class='bi bi-check'></i></a>" : "") . "
                                    <a href='?delete={$row['id']}' class='btn btn-outline' style='padding: 5px 12px; font-size: 0.75rem; border-radius: 8px; color: #e74c3c; border-color: #e74c3c;' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash'></i></a>
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

<!-- Custom Modal for Member Details -->
<div id="memberModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="animate-up" style="background: white; width: 100%; max-width: 500px; border-radius: 20px; padding: 30px; position: relative;">
        <button onclick="document.getElementById('memberModal').style.display='none'" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #94a3b8;"><i class="bi bi-x-circle"></i></button>
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
            <img id="m_photo" src="" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
            <div>
                <h3 id="m_name" style="margin: 0; font-size: 1.2rem;"></h3>
                <p id="m_uid" style="margin: 0; font-size: 0.8rem; color: #94a3b8;"></p>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
            <div>
                <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Date of Birth</p>
                <p id="m_dob" style="font-size: 0.9rem; font-weight: 600;"></p>
            </div>
            <div>
                <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Gender</p>
                <p id="m_gender" style="font-size: 0.9rem; font-weight: 600;"></p>
            </div>
            <div>
                <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Blood Group</p>
                <p id="m_blood" style="font-size: 0.9rem; font-weight: 600; color: #e74c3c;"></p>
            </div>
            <div>
                <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Occupation</p>
                <p id="m_job" style="font-size: 0.9rem; font-weight: 600;"></p>
            </div>
            <div style="grid-column: 1 / -1;">
                <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Aadhaar / ID Proof</p>
                <p id="m_idproof" style="font-size: 0.9rem; font-weight: 600;"></p>
            </div>
            <div style="grid-column: 1 / -1;">
                <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Complete Address</p>
                <p id="m_address" style="font-size: 0.9rem; font-weight: 600;"></p>
            </div>
        </div>
    </div>
</div>

<script>
function viewMember(data) {
    document.getElementById('m_photo').src = data.photo ? '../' + data.photo : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.name);
    document.getElementById('m_name').innerText = data.name;
    document.getElementById('m_uid').innerText = 'UID: #M00' + data.id + ' | Status: ' + data.status.toUpperCase();
    document.getElementById('m_dob').innerText = data.dob || 'N/A';
    document.getElementById('m_gender').innerText = data.gender || 'N/A';
    document.getElementById('m_blood').innerText = data.blood_group || 'N/A';
    document.getElementById('m_job').innerText = data.occupation || 'N/A';
    document.getElementById('m_idproof').innerText = data.id_proof || 'N/A';
    document.getElementById('m_address').innerText = data.address || 'N/A';
    
    document.getElementById('memberModal').style.display = 'flex';
}

// Close modal on outside click
document.getElementById('memberModal').addEventListener('click', function(e) {
    if(e.target === this) this.style.display = 'none';
});
</script>

<?php include('layout_footer.php'); ?>