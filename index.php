<?php
include 'header.php';
include 'db.php';

$members = [];
$result = $conn->query("SELECT * FROM members ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

$courses = [];
$result = $conn->query("SELECT * FROM courses ORDER BY id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$courseCodes = array_column($courses, 'course_code');
?>

<section class="hero">
    <h1><i class="fas fa-laptop-code"></i> SENG412 Group 3b Project</h1>
    <p class="subtitle">Internet Technologies and Web Applications Development</p>
    <p class="dept">Department of Software Engineering &bull; 2025/2026 Academic Session</p>
</section>

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

<section class="section">
    <div class="section-header">
        <i class="fas fa-users"></i>
        <h2>Group Members</h2>
    </div>
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddMember()">
            <i class="fas fa-user-plus"></i> Add Member
        </button>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Matric Number</th>
                    <th>Name of Student</th>
                    <th>Courses Registered</th>
                    <th>Actions</th>
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
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-primary" title="Edit" onclick="openEditMember(<?= $m['id'] ?>)">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-icon btn-danger" title="Delete" onclick="deleteMember(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['full_name'])) ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <i class="fas fa-book-open"></i>
        <h2>Registered Courses</h2>
    </div>
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddCourse()">
            <i class="fas fa-plus"></i> Add Course
        </button>
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
                    <th>Actions</th>
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
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-primary" title="Edit" onclick="openEditCourse(<?= $c['id'] ?>)">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-icon btn-danger" title="Delete" onclick="deleteCourse(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['course_code'])) ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right;">Total Credit Units:</td>
                    <td><strong><?= number_format($totalUnits, 1) ?></strong></td>
                    <td colspan="3"></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- Add/Edit Member Modal -->
<div class="modal-overlay" id="memberModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user"></i> <span id="memberModalTitle">Add Member</span></h3>
            <button class="modal-close" onclick="closeModal('memberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="memberEditId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>Matric Number <span class="required">*</span></label>
                    <input type="text" class="form-control" id="memberMatric" placeholder="e.g. 22/0001">
                </div>
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="memberName" placeholder="Full name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Blood Group</label>
                    <select class="form-control" id="memberBlood">
                        <option value="">Select...</option>
                        <option value="A+">A+</option><option value="A-">A-</option>
                        <option value="A">A</option>
                        <option value="B+">B+</option><option value="B-">B-</option>
                        <option value="B">B</option>
                        <option value="O+">O+</option><option value="O-">O-</option>
                        <option value="O">O</option>
                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>State of Origin</label>
                    <input type="text" class="form-control" id="memberState" placeholder="e.g. Lagos State">
                </div>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" class="form-control" id="memberPhone" placeholder="e.g. 08012345678">
            </div>
            <div class="form-group">
                <label>Hobbies</label>
                <textarea class="form-control" id="memberHobbies" placeholder="e.g. Football and gaming"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('memberModal')">Cancel</button>
            <button class="btn btn-success" onclick="saveMember()">
                <i class="fas fa-save"></i> <span id="memberSaveBtn">Save Member</span>
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Course Modal -->
<div class="modal-overlay" id="courseModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-book"></i> <span id="courseModalTitle">Add Course</span></h3>
            <button class="modal-close" onclick="closeModal('courseModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="courseEditId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>Course Code <span class="required">*</span></label>
                    <input type="text" class="form-control" id="courseCode" placeholder="e.g. SENG412">
                </div>
                <div class="form-group">
                    <label>Credit Units <span class="required">*</span></label>
                    <input type="number" class="form-control" id="courseUnits" placeholder="e.g. 3.0" min="0" step="0.5">
                </div>
            </div>
            <div class="form-group">
                <label>Course Title <span class="required">*</span></label>
                <input type="text" class="form-control" id="courseTitle" placeholder="Course title">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" class="form-control" id="courseDept" placeholder="e.g. Software Engineering">
                </div>
                <div class="form-group">
                    <label>Lecturer</label>
                    <input type="text" class="form-control" id="courseLecturer" placeholder="Lecturer name">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('courseModal')">Cancel</button>
            <button class="btn btn-success" onclick="saveCourse()">
                <i class="fas fa-save"></i> <span id="courseSaveBtn">Save Course</span>
            </button>
        </div>
    </div>
</div>

<script>
// ===== Member CRUD =====
function openAddMember() {
    document.getElementById('memberEditId').value = '';
    document.getElementById('memberMatric').value = '';
    document.getElementById('memberName').value = '';
    document.getElementById('memberBlood').value = '';
    document.getElementById('memberState').value = '';
    document.getElementById('memberPhone').value = '';
    document.getElementById('memberHobbies').value = '';
    document.getElementById('memberModalTitle').textContent = 'Add Member';
    document.getElementById('memberSaveBtn').textContent = 'Save Member';
    openModal('memberModal');
}

function openEditMember(id) {
    fetch('api.php?action=get&entity=member&id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                var d = result.data;
                document.getElementById('memberEditId').value = d.id;
                document.getElementById('memberMatric').value = d.matric_no;
                document.getElementById('memberName').value = d.full_name;
                document.getElementById('memberBlood').value = d.blood_group;
                document.getElementById('memberState').value = d.state_of_origin;
                document.getElementById('memberPhone').value = d.phone;
                document.getElementById('memberHobbies').value = d.hobbies;
                document.getElementById('memberModalTitle').textContent = 'Edit Member';
                document.getElementById('memberSaveBtn').textContent = 'Update Member';
                openModal('memberModal');
            } else {
                showToast(result.message || 'Could not load member', 'error');
            }
        })
        .catch(function() { showToast('Network error', 'error'); });
}

function saveMember() {
    var editId = document.getElementById('memberEditId').value;
    var data = {
        matric_no: document.getElementById('memberMatric').value.trim(),
        full_name: document.getElementById('memberName').value.trim(),
        blood_group: document.getElementById('memberBlood').value,
        state_of_origin: document.getElementById('memberState').value.trim(),
        phone: document.getElementById('memberPhone').value.trim(),
        hobbies: document.getElementById('memberHobbies').value.trim()
    };

    if (!data.matric_no || !data.full_name) {
        showToast('Please fill in matric number and full name', 'error');
        return;
    }

    var action = editId ? 'update' : 'create';
    if (editId) data.id = editId;

    apiRequest(action, 'member', data)
        .then(function(result) {
            showToast(result.message, 'success');
            closeModal('memberModal');
            setTimeout(function() { location.reload(); }, 800);
        })
        .catch(function(err) { showToast(err, 'error'); });
}

function deleteMember(id, name) {
    showConfirm(
        'Delete Member',
        'Are you sure you want to remove "' + name + '"? This will also remove their GPA records.',
        function() {
            apiRequest('delete', 'member', { id: id })
                .then(function(result) {
                    showToast(result.message, 'success');
                    setTimeout(function() { location.reload(); }, 800);
                })
                .catch(function(err) { showToast(err, 'error'); });
        }
    );
}

// ===== Course CRUD =====
function openAddCourse() {
    document.getElementById('courseEditId').value = '';
    document.getElementById('courseCode').value = '';
    document.getElementById('courseTitle').value = '';
    document.getElementById('courseUnits').value = '';
    document.getElementById('courseDept').value = '';
    document.getElementById('courseLecturer').value = '';
    document.getElementById('courseModalTitle').textContent = 'Add Course';
    document.getElementById('courseSaveBtn').textContent = 'Save Course';
    openModal('courseModal');
}

function openEditCourse(id) {
    fetch('api.php?action=get&entity=course&id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                var d = result.data;
                document.getElementById('courseEditId').value = d.id;
                document.getElementById('courseCode').value = d.course_code;
                document.getElementById('courseTitle').value = d.course_title;
                document.getElementById('courseUnits').value = d.credit_units;
                document.getElementById('courseDept').value = d.department;
                document.getElementById('courseLecturer').value = d.lecturer;
                document.getElementById('courseModalTitle').textContent = 'Edit Course';
                document.getElementById('courseSaveBtn').textContent = 'Update Course';
                openModal('courseModal');
            } else {
                showToast(result.message || 'Could not load course', 'error');
            }
        })
        .catch(function() { showToast('Network error', 'error'); });
}

function saveCourse() {
    var editId = document.getElementById('courseEditId').value;
    var data = {
        course_code: document.getElementById('courseCode').value.trim(),
        course_title: document.getElementById('courseTitle').value.trim(),
        credit_units: document.getElementById('courseUnits').value,
        department: document.getElementById('courseDept').value.trim(),
        lecturer: document.getElementById('courseLecturer').value.trim()
    };

    if (!data.course_code || !data.course_title || !data.credit_units) {
        showToast('Please fill in course code, title, and credit units', 'error');
        return;
    }

    var action = editId ? 'update' : 'create';
    if (editId) data.id = editId;

    apiRequest(action, 'course', data)
        .then(function(result) {
            showToast(result.message, 'success');
            closeModal('courseModal');
            setTimeout(function() { location.reload(); }, 800);
        })
        .catch(function(err) { showToast(err, 'error'); });
}

function deleteCourse(id, code) {
    showConfirm(
        'Delete Course',
        'Are you sure you want to remove "' + code + '"? This will also remove associated GPA records.',
        function() {
            apiRequest('delete', 'course', { id: id })
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
