<?php
include 'header.php';
include 'db.php';

$members = [];
$result = $conn->query("SELECT * FROM members ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $members[$row['id']] = $row;
    }
}

$courses = [];
$result = $conn->query("SELECT * FROM courses ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[$row['id']] = $row;
    }
}

$gpaData = [];
$result = $conn->query("SELECT g.*, c.course_code, c.course_title, c.credit_units 
                         FROM gpa_records g 
                         JOIN courses c ON g.course_id = c.id 
                         ORDER BY g.member_id, c.id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $gpaData[$row['member_id']][] = $row;
    }
}

$gpaResults = [];
foreach ($members as $id => $member) {
    $totalWeightedPoints = 0;
    $totalUnits = 0;
    $records = $gpaData[$id] ?? [];
    
    foreach ($records as $rec) {
        $units = floatval($rec['credit_units']);
        if ($units > 0) {
            $totalWeightedPoints += $rec['grade_point'] * $units;
            $totalUnits += $units;
        }
    }
    
    $gpa = $totalUnits > 0 ? round($totalWeightedPoints / $totalUnits, 2) : 0;
    $gpaResults[$id] = [
        'member' => $member,
        'records' => $records,
        'total_weighted' => $totalWeightedPoints,
        'total_units' => $totalUnits,
        'gpa' => $gpa
    ];
}

$sorted = $gpaResults;
uasort($sorted, function($a, $b) { return $b['gpa'] <=> $a['gpa']; });

function getGpaClass($gpa) {
    if ($gpa >= 4.50) return ['First Class', 'gpa-excellent'];
    if ($gpa >= 3.50) return ['Second Class Upper', 'gpa-very-good'];
    if ($gpa >= 2.40) return ['Second Class Lower', 'gpa-good'];
    if ($gpa >= 1.50) return ['Third Class', 'gpa-fair'];
    return ['Pass', 'gpa-fair'];
}

function gradeBadge($grade) {
    $g = strtoupper($grade);
    if ($g === 'A') return 'badge-a';
    if ($g === 'B') return 'badge-b';
    if ($g === 'C') return 'badge-c';
    if ($g === 'D') return 'badge-d';
    return 'badge-f';
}
?>

<section class="hero">
    <h1><i class="fas fa-graduation-cap"></i> GPA Computation</h1>
    <p class="subtitle">Grade Point Average &mdash; 1st Semester Results</p>
    <p class="dept">Grading Scale: A(80-100)=5 &bull; B(60-79)=4 &bull; C(50-59)=3 &bull; D(45-49)=2 &bull; E(40-44)=1 &bull; F(0-39)=0</p>
</section>

<?php
$avgGpa = count($gpaResults) > 0 ? array_sum(array_column($gpaResults, 'gpa')) / count($gpaResults) : 0;
$highestGpa = count($gpaResults) > 0 ? max(array_column($gpaResults, 'gpa')) : 0;
$lowestGpa = count($gpaResults) > 0 ? min(array_column($gpaResults, 'gpa')) : 0;
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-value"><?= count($gpaResults) ?></div>
        <div class="stat-label">Students</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-chart-line"></i></div>
        <div class="stat-value"><?= number_format($highestGpa, 2) ?></div>
        <div class="stat-label">Highest GPA</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-chart-bar"></i></div>
        <div class="stat-value"><?= number_format($avgGpa, 2) ?></div>
        <div class="stat-label">Average GPA</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-chart-area"></i></div>
        <div class="stat-value"><?= number_format($lowestGpa, 2) ?></div>
        <div class="stat-label">Lowest GPA</div>
    </div>
</div>

<section class="section">
    <div class="section-header">
        <i class="fas fa-trophy"></i>
        <h2>GPA Summary (Ranked)</h2>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Matric No.</th>
                    <th>Student Name</th>
                    <th>Total Units</th>
                    <th>Weighted Points</th>
                    <th>GPA</th>
                    <th>Classification</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach ($sorted as $data): 
                    $class = getGpaClass($data['gpa']);
                ?>
                <tr>
                    <td><strong><?= $rank++ ?></strong></td>
                    <td><?= htmlspecialchars($data['member']['matric_no']) ?></td>
                    <td><strong><?= htmlspecialchars($data['member']['full_name']) ?></strong></td>
                    <td><?= number_format($data['total_units'], 1) ?></td>
                    <td><?= $data['total_weighted'] ?></td>
                    <td><strong><?= number_format($data['gpa'], 2) ?></strong></td>
                    <td><span class="badge <?= $class[1] ?>"><?= $class[0] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
</section>

<section class="section">
    <div class="section-header">
        <i class="fas fa-clipboard-list"></i>
        <h2>Detailed Results Per Student</h2>
    </div>
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddGpa()">
            <i class="fas fa-plus"></i> Add GPA Record
        </button>
    </div>

    <?php foreach ($gpaResults as $id => $data): 
        $class = getGpaClass($data['gpa']);
    ?>
    <div class="gpa-card">
        <div class="gpa-card-header" onclick="toggleGpa(<?= $id ?>)">
            <div class="student-info">
                <h3><?= htmlspecialchars($data['member']['full_name']) ?></h3>
                <span><?= htmlspecialchars($data['member']['matric_no']) ?></span>
            </div>
            <div class="gpa-badge <?= $class[1] ?>">GPA: <?= number_format($data['gpa'], 2) ?></div>
        </div>
        <div class="gpa-card-body" id="gpaBody<?= $id ?>" style="display:none;">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Units</th>
                        <th>Score</th>
                        <th>Grade</th>
                        <th>Grade Point</th>
                        <th>Weighted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['records'] as $j => $rec): 
                        $units = floatval($rec['credit_units']);
                        $weighted = $rec['grade_point'] * $units;
                    ?>
                    <tr>
                        <td><?= $j + 1 ?></td>
                        <td><strong><?= htmlspecialchars($rec['course_code']) ?></strong></td>
                        <td><?= htmlspecialchars($rec['course_title']) ?></td>
                        <td><?= number_format($units, 1) ?></td>
                        <td><?= $rec['score'] ?></td>
                        <td><span class="badge <?= gradeBadge($rec['grade']) ?>"><?= $rec['grade'] ?></span></td>
                        <td><?= $rec['grade_point'] ?></td>
                        <td><?= number_format($weighted, 1) ?></td>
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-icon btn-primary" title="Edit Score" onclick="openEditGpa(<?= $rec['id'] ?>, <?= $rec['member_id'] ?>, <?= $rec['course_id'] ?>, <?= $rec['score'] ?>)">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn btn-icon btn-danger" title="Delete" onclick="deleteGpa(<?= $rec['id'] ?>, '<?= htmlspecialchars(addslashes($rec['course_code'])) ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="gpa-card-footer">
            <span>Total Units: <strong><?= number_format($data['total_units'], 1) ?></strong></span>
            <span>Total Weighted Points: <strong><?= $data['total_weighted'] ?></strong></span>
            <span>GPA: <strong><?= number_format($data['gpa'], 2) ?></strong> (<?= $class[0] ?>)</span>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<div class="card">
    <h3 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-info-circle"></i> GPA Computation Guide</h3>
    <p style="color: var(--text-light); line-height: 1.8;">
        <strong>Formula:</strong> GPA = &Sigma;(Grade Point &times; Credit Units) &divide; &Sigma;(Credit Units)<br><br>
        <strong>Grading Scale:</strong><br>
        <span class="badge badge-a">A (80-100) = 5</span>&nbsp;
        <span class="badge badge-b">B (60-79) = 4</span>&nbsp;
        <span class="badge badge-c">C (50-59) = 3</span>&nbsp;
        <span class="badge badge-d">D (45-49) = 2</span>&nbsp;
        <span class="badge badge-f">E (40-44) = 1</span>&nbsp;
        <span class="badge badge-f">F (0-39) = 0</span><br><br>
        <strong>Classification:</strong><br>
        4.50 - 5.00: First Class &bull; 3.50 - 4.49: Second Class Upper &bull; 2.40 - 3.49: Second Class Lower &bull; 1.50 - 2.39: Third Class<br><br>
        <em>Note: GEDS002 (0.0 units) is excluded from GPA computation as it carries no credit weight.</em>
    </p>
</div>

<!-- Add/Edit GPA Record Modal -->
<div class="modal-overlay" id="gpaModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-graduation-cap"></i> <span id="gpaModalTitle">Add GPA Record</span></h3>
            <button class="modal-close" onclick="closeModal('gpaModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="gpaEditId" value="">
            <div class="form-group">
                <label>Student <span class="required">*</span></label>
                <select class="form-control" id="gpaMember">
                    <option value="">Select student...</option>
                    <?php foreach ($members as $mid => $m): ?>
                        <option value="<?= $mid ?>"><?= htmlspecialchars($m['full_name']) ?> (<?= htmlspecialchars($m['matric_no']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Course <span class="required">*</span></label>
                <select class="form-control" id="gpaCourse">
                    <option value="">Select course...</option>
                    <?php foreach ($courses as $cid => $c): ?>
                        <option value="<?= $cid ?>"><?= htmlspecialchars($c['course_code']) ?> - <?= htmlspecialchars($c['course_title']) ?> (<?= $c['credit_units'] ?> units)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Score (0-100) <span class="required">*</span></label>
                <input type="number" class="form-control" id="gpaScore" placeholder="e.g. 75" min="0" max="100" step="1">
            </div>
            <div class="card" style="background: #f8fafc; margin-bottom: 0; padding: 14px 18px;">
                <p style="color: var(--text-light); font-size: 0.88rem; margin: 0;">
                    <strong>Preview:</strong>
                    Grade = <span id="previewGrade">-</span> |
                    Grade Point = <span id="previewGP">-</span>
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('gpaModal')">Cancel</button>
            <button class="btn btn-success" onclick="saveGpa()">
                <i class="fas fa-save"></i> <span id="gpaSaveBtn">Save Record</span>
            </button>
        </div>
    </div>
</div>

<script>
function toggleGpa(id) {
    var body = document.getElementById('gpaBody' + id);
    body.style.display = body.style.display === 'none' ? '' : 'none';
}

// Score preview
document.getElementById('gpaScore').addEventListener('input', function() {
    var score = parseInt(this.value) || 0;
    var grade, gp;
    if (score >= 80) { grade = 'A'; gp = 5; }
    else if (score >= 60) { grade = 'B'; gp = 4; }
    else if (score >= 50) { grade = 'C'; gp = 3; }
    else if (score >= 45) { grade = 'D'; gp = 2; }
    else if (score >= 40) { grade = 'E'; gp = 1; }
    else { grade = 'F'; gp = 0; }
    document.getElementById('previewGrade').textContent = grade;
    document.getElementById('previewGP').textContent = gp;
});

function openAddGpa() {
    document.getElementById('gpaEditId').value = '';
    document.getElementById('gpaMember').value = '';
    document.getElementById('gpaCourse').value = '';
    document.getElementById('gpaScore').value = '';
    document.getElementById('previewGrade').textContent = '-';
    document.getElementById('previewGP').textContent = '-';
    document.getElementById('gpaMember').disabled = false;
    document.getElementById('gpaCourse').disabled = false;
    document.getElementById('gpaModalTitle').textContent = 'Add GPA Record';
    document.getElementById('gpaSaveBtn').textContent = 'Save Record';
    openModal('gpaModal');
}

function openEditGpa(id, memberId, courseId, score) {
    document.getElementById('gpaEditId').value = id;
    document.getElementById('gpaMember').value = memberId;
    document.getElementById('gpaCourse').value = courseId;
    document.getElementById('gpaScore').value = score;
    document.getElementById('gpaMember').disabled = false;
    document.getElementById('gpaCourse').disabled = false;
    document.getElementById('gpaModalTitle').textContent = 'Edit GPA Record';
    document.getElementById('gpaSaveBtn').textContent = 'Update Record';
    // Trigger preview
    document.getElementById('gpaScore').dispatchEvent(new Event('input'));
    openModal('gpaModal');
}

function saveGpa() {
    var editId = document.getElementById('gpaEditId').value;
    var data = {
        member_id: document.getElementById('gpaMember').value,
        course_id: document.getElementById('gpaCourse').value,
        score: document.getElementById('gpaScore').value
    };

    if (!data.member_id || !data.course_id || !data.score) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    var score = parseInt(data.score);
    if (score < 0 || score > 100) {
        showToast('Score must be between 0 and 100', 'error');
        return;
    }

    var action = editId ? 'update' : 'create';
    if (editId) data.id = editId;

    apiRequest(action, 'gpa', data)
        .then(function(result) {
            showToast(result.message, 'success');
            closeModal('gpaModal');
            setTimeout(function() { location.reload(); }, 800);
        })
        .catch(function(err) { showToast(err, 'error'); });
}

function deleteGpa(id, courseCode) {
    showConfirm(
        'Delete GPA Record',
        'Are you sure you want to remove the grade for "' + courseCode + '"?',
        function() {
            apiRequest('delete', 'gpa', { id: id })
                .then(function(result) {
                    showToast(result.message, 'success');
                    setTimeout(function() { location.reload(); }, 800);
                })
                .catch(function(err) { showToast(err, 'error'); });
        }
    );
}
</script>

<?php
$conn->close();
include 'footer.php';
?>
