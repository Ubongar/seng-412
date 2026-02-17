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

function getInitials($name) {
    $parts = explode(' ', trim($name));
    $initials = '';
    foreach ($parts as $p) {
        if (!empty($p)) $initials .= strtoupper($p[0]);
        if (strlen($initials) >= 2) break;
    }
    return $initials;
}

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

<section class="hero">
    <h1><i class="fas fa-id-card"></i> Personal Details</h1>
    <p class="subtitle">Meet Our Group Members</p>
    <p class="dept">Blood Group &bull; State of Origin &bull; Phone Number &bull; Hobbies</p>
</section>

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

<section class="section">
    <div class="section-header">
        <i class="fas fa-address-book"></i>
        <h2>Member Profiles</h2>
    </div>
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddMemberDetail()">
            <i class="fas fa-user-plus"></i> Add Member
        </button>
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
            <div class="detail-card-actions">
                <button class="btn btn-sm btn-primary" onclick="openEditMemberDetail(<?= $m['id'] ?>)">
                    <i class="fas fa-pen"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteMemberDetail(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['full_name'])) ?>')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

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
                    <th>Actions</th>
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
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-primary" title="Edit" onclick="openEditMemberDetail(<?= $m['id'] ?>)">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-icon btn-danger" title="Delete" onclick="deleteMemberDetail(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['full_name'])) ?>')">
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

<!-- Add/Edit Member Modal -->
<div class="modal-overlay" id="detailMemberModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user"></i> <span id="detailMemberModalTitle">Add Member</span></h3>
            <button class="modal-close" onclick="closeModal('detailMemberModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="detailMemberEditId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>Matric Number <span class="required">*</span></label>
                    <input type="text" class="form-control" id="detailMemberMatric" placeholder="e.g. 22/0001">
                </div>
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="detailMemberName" placeholder="Full name">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Blood Group</label>
                    <select class="form-control" id="detailMemberBlood">
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
                    <input type="text" class="form-control" id="detailMemberState" placeholder="e.g. Lagos State">
                </div>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" class="form-control" id="detailMemberPhone" placeholder="e.g. 08012345678">
            </div>
            <div class="form-group">
                <label>Hobbies</label>
                <textarea class="form-control" id="detailMemberHobbies" placeholder="e.g. Football and gaming"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('detailMemberModal')">Cancel</button>
            <button class="btn btn-success" onclick="saveMemberDetail()">
                <i class="fas fa-save"></i> <span id="detailMemberSaveBtn">Save Member</span>
            </button>
        </div>
    </div>
</div>

<script>
function openAddMemberDetail() {
    document.getElementById('detailMemberEditId').value = '';
    document.getElementById('detailMemberMatric').value = '';
    document.getElementById('detailMemberName').value = '';
    document.getElementById('detailMemberBlood').value = '';
    document.getElementById('detailMemberState').value = '';
    document.getElementById('detailMemberPhone').value = '';
    document.getElementById('detailMemberHobbies').value = '';
    document.getElementById('detailMemberModalTitle').textContent = 'Add Member';
    document.getElementById('detailMemberSaveBtn').textContent = 'Save Member';
    openModal('detailMemberModal');
}

function openEditMemberDetail(id) {
    fetch('api.php?action=get&entity=member&id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                var d = result.data;
                document.getElementById('detailMemberEditId').value = d.id;
                document.getElementById('detailMemberMatric').value = d.matric_no;
                document.getElementById('detailMemberName').value = d.full_name;
                document.getElementById('detailMemberBlood').value = d.blood_group;
                document.getElementById('detailMemberState').value = d.state_of_origin;
                document.getElementById('detailMemberPhone').value = d.phone;
                document.getElementById('detailMemberHobbies').value = d.hobbies;
                document.getElementById('detailMemberModalTitle').textContent = 'Edit Member';
                document.getElementById('detailMemberSaveBtn').textContent = 'Update Member';
                openModal('detailMemberModal');
            } else {
                showToast(result.message || 'Could not load member', 'error');
            }
        })
        .catch(function() { showToast('Network error', 'error'); });
}

function saveMemberDetail() {
    var editId = document.getElementById('detailMemberEditId').value;
    var data = {
        matric_no: document.getElementById('detailMemberMatric').value.trim(),
        full_name: document.getElementById('detailMemberName').value.trim(),
        blood_group: document.getElementById('detailMemberBlood').value,
        state_of_origin: document.getElementById('detailMemberState').value.trim(),
        phone: document.getElementById('detailMemberPhone').value.trim(),
        hobbies: document.getElementById('detailMemberHobbies').value.trim()
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
            closeModal('detailMemberModal');
            setTimeout(function() { location.reload(); }, 800);
        })
        .catch(function(err) { showToast(err, 'error'); });
}

function deleteMemberDetail(id, name) {
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
</script>

<?php
$conn->close();
include 'footer.php';
?>
