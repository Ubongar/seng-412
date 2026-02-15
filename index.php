<?php
/**
 * HOME PAGE - SENG412 Group Project
 * Displays group members and registered courses
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

// Fetch courses
$courses = [];
$result = $conn->query("SELECT * FROM courses ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Build course code list for display
$courseCodes = array_column($courses, 'course_code');
?>

<!-- Hero Section -->
<section class="hero">
    <h1><i class="fas fa-laptop-code"></i> SENG412 Group 3b Project</h1>
    <p class="subtitle">Internet Technologies and Web Applications Development</p>
    <p class="dept">Department of Software Engineering &bull; 2025/2026 Academic Session</p>
</section>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-value"><?= count($members) ?></div>
        <div class="stat-label">Group Members</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-book"></i></div>
        <div class="stat-value"><?= count($courses) ?></div>
        <div class="stat-label">Courses Registered</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-calculator"></i></div>
        <div class="stat-value">
            <?php
                $totalUnits = 0;
                foreach ($courses as $c) { $totalUnits += $c['credit_units']; }
                echo number_format($totalUnits, 1);
            ?>
        </div>
        <div class="stat-label">Total Credit Units</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-value"><?= count($courses) ?></div>
        <div class="stat-label">Lecturers</div>
    </div>
</div>

<!-- Group Members Section -->
<section class="section">
    <div class="section-header">
        <i class="fas fa-users"></i>
        <h2>Group Members</h2>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Matric Number</th>
                    <th>Name of Student</th>
                    <th>Courses Registered</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $i => $m): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($m['matric_no']) ?></strong></td>
                    <td><?= htmlspecialchars($m['full_name']) ?></td>
                    <td>
                        <div class="course-tags">
                            <?php foreach ($courseCodes as $code): ?>
                                <span class="course-tag"><?= htmlspecialchars($code) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Courses Section -->
<section class="section">
    <div class="section-header">
        <i class="fas fa-book-open"></i>
        <h2>Registered Courses</h2>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Units</th>
                    <th>Department</th>
                    <th>Lecturer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $i => $c): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($c['course_code']) ?></strong></td>
                    <td><?= htmlspecialchars($c['course_title']) ?></td>
                    <td><?= number_format($c['credit_units'], 1) ?></td>
                    <td><?= htmlspecialchars($c['department']) ?></td>
                    <td><?= htmlspecialchars($c['lecturer']) ?></td>
                </tr>
                <?php endforeach; ?>
                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Total Credit Units:</td>
                    <td><strong><?= number_format($totalUnits, 1) ?></strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php
$conn->close();
include 'footer.php';
?>
