<?php
/**
 * PERSONAL DETAILS PAGE - SENG412 Group Project
 * Displays personal details of all group members
 */
include 'header.php';
include 'db.php';

// Fetch members
$members = [];
$result = $conn->query("SELECT * FROM members ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

// Generate initials from name
function getInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach ($parts as $p) {
        if (!empty($p)) $initials .= strtoupper($p[0]);
        if (strlen($initials) >= 2) break;
    }
    return $initials;
}

// Generate a consistent color for each member
$colors = [
    'linear-gradient(135deg, #1a365d, #2b6cb0)',
    'linear-gradient(135deg, #1a4731, #276749)',
    'linear-gradient(135deg, #553c2e, #975a16)',
    'linear-gradient(135deg, #3c1361, #6b46c1)',
    'linear-gradient(135deg, #742a2a, #c53030)',
    'linear-gradient(135deg, #1a365d, #2c5282)',
    'linear-gradient(135deg, #234e52, #2c7a7b)',
    'linear-gradient(135deg, #44337a, #7c3aed)',
    'linear-gradient(135deg, #7b341e, #c05621)',
];
?>

<!-- Hero Section -->
<section class="hero">
    <h1><i class="fas fa-id-card"></i> Personal Details</h1>
    <p class="subtitle">Meet Our Group Members</p>
    <p class="dept">Blood Group &bull; State of Origin &bull; Phone Number &bull; Hobbies</p>
</section>

<!-- Members Count -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-value"><?= count($members) ?></div>
        <div class="stat-label">Group Members</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-map-marker-alt"></i></div>
        <div class="stat-value"><?= count(array_unique(array_column($members, 'state_of_origin'))) ?></div>
        <div class="stat-label">States Represented</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-tint"></i></div>
        <div class="stat-value"><?= count(array_unique(array_column($members, 'blood_group'))) ?></div>
        <div class="stat-label">Blood Groups</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-heart"></i></div>
        <div class="stat-value"><?= count($members) ?></div>
        <div class="stat-label">Unique Individuals</div>
    </div>
</div>

<!-- Details Section -->
<section class="section">
    <div class="section-header">
        <i class="fas fa-address-book"></i>
        <h2>Member Profiles</h2>
    </div>

    <div class="details-grid">
        <?php foreach ($members as $i => $m): ?>
        <div class="detail-card">
            <div class="detail-card-header" style="background: <?= $colors[$i % count($colors)] ?>;">
                <div class="detail-avatar"><?= getInitials($m['full_name']) ?></div>
                <h3><?= htmlspecialchars($m['full_name']) ?></h3>
                <span class="matric"><?= htmlspecialchars($m['matric_no']) ?></span>
            </div>
            <div class="detail-card-body">
                <div class="detail-item">
                    <i class="fas fa-tint"></i>
                    <div>
                        <div class="detail-label">Blood Group</div>
                        <div class="detail-value"><?= htmlspecialchars($m['blood_group']) ?></div>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <div class="detail-label">State of Origin</div>
                        <div class="detail-value"><?= htmlspecialchars($m['state_of_origin']) ?></div>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value"><?= htmlspecialchars($m['phone']) ?></div>
                    </div>
                </div>
                <div class="detail-item">
                    <i class="fas fa-gamepad"></i>
                    <div>
                        <div class="detail-label">Hobbies</div>
                        <div class="detail-value"><?= htmlspecialchars($m['hobbies']) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Summary Table -->
<section class="section">
    <div class="section-header">
        <i class="fas fa-table"></i>
        <h2>Quick Reference Table</h2>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Matric No.</th>
                    <th>Blood Group</th>
                    <th>State of Origin</th>
                    <th>Phone</th>
                    <th>Hobbies</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $i => $m): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($m['full_name']) ?></strong></td>
                    <td><?= htmlspecialchars($m['matric_no']) ?></td>
                    <td><span class="badge badge-b"><?= htmlspecialchars($m['blood_group']) ?></span></td>
                    <td><?= htmlspecialchars($m['state_of_origin']) ?></td>
                    <td><?= htmlspecialchars($m['phone']) ?></td>
                    <td><?= htmlspecialchars($m['hobbies']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
$conn->close();
include 'footer.php';
?>
