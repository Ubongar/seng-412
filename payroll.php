<?php
include 'header.php';
include 'db.php';

$employees = [];
$result = $conn->query("SELECT * FROM employees ORDER BY emp_id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['gross_pay'] = $row['hours_worked'] * $row['hourly_rate'];
        $row['net_pay'] = $row['gross_pay'] - $row['deductions'];
        $employees[] = $row;
    }
}

$totalGross = 0;
$totalDeductions = 0;
$totalNet = 0;
$totalHours = 0;
foreach ($employees as $emp) {
    $totalGross += $emp['gross_pay'];
    $totalDeductions += $emp['deductions'];
    $totalNet += $emp['net_pay'];
    $totalHours += $emp['hours_worked'];
}

$departments = array_unique(array_column($employees, 'department'));
sort($departments);

// Generate next emp_id
$maxEmpNum = 0;
foreach ($employees as $emp) {
    $num = intval(str_replace('EMP', '', $emp['emp_id']));
    if ($num > $maxEmpNum) $maxEmpNum = $num;
}
$nextEmpId = 'EMP' . str_pad($maxEmpNum + 1, 3, '0', STR_PAD_LEFT);
?>

<section class="hero">
    <h1><i class="fas fa-money-bill-wave"></i> Employee Payroll</h1>
    <p class="subtitle">Small-Sized Company Payroll System</p>
    <p class="dept">Wages computed based on Hours Worked, Hourly Rate, and Deductions</p>
</section>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-user-tie"></i></div>
        <div class="stat-value"><?= count($employees) ?></div>
        <div class="stat-label">Total Employees</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-naira-sign"></i></div>
        <div class="stat-value">&#8358;<?= number_format($totalGross, 2) ?></div>
        <div class="stat-label">Total Gross Pay</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-minus-circle"></i></div>
        <div class="stat-value">&#8358;<?= number_format($totalDeductions, 2) ?></div>
        <div class="stat-label">Total Deductions</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-wallet"></i></div>
        <div class="stat-value">&#8358;<?= number_format($totalNet, 2) ?></div>
        <div class="stat-label">Total Net Pay</div>
    </div>
</div>

<div class="search-box">
    <i class="fas fa-search"></i>
    <input type="text" id="searchInput" placeholder="Search by name, department, or employee ID..." oninput="filterTable()">
</div>

<div class="filter-bar">
    <button class="filter-btn active" onclick="filterByDept('all', this)">All Departments</button>
    <?php foreach ($departments as $dept): ?>
        <button class="filter-btn" onclick="filterByDept('<?= htmlspecialchars($dept) ?>', this)"><?= htmlspecialchars($dept) ?></button>
    <?php endforeach; ?>
</div>

<section class="section">
    <div class="section-header">
        <i class="fas fa-file-invoice-dollar"></i>
        <h2>Payslip Summary</h2>
    </div>
    <div class="action-bar">
        <button class="btn btn-primary" onclick="openAddEmployee()">
            <i class="fas fa-plus"></i> Add Employee
        </button>
    </div>
    <div class="table-wrapper">
        <table id="payrollTable">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Emp. ID</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Hours Worked</th>
                    <th>Hourly Rate (&#8358;)</th>
                    <th>Gross Pay (&#8358;)</th>
                    <th>Deductions (&#8358;)</th>
                    <th>Net Pay (&#8358;)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="payrollBody">
                <?php foreach ($employees as $i => $emp): ?>
                <tr data-dept="<?= htmlspecialchars($emp['department']) ?>" data-id="<?= $emp['id'] ?>">
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($emp['emp_id']) ?></strong></td>
                    <td><?= htmlspecialchars($emp['full_name']) ?></td>
                    <td><?= htmlspecialchars($emp['department']) ?></td>
                    <td><?= number_format($emp['hours_worked'], 2) ?></td>
                    <td><?= number_format($emp['hourly_rate'], 2) ?></td>
                    <td><strong><?= number_format($emp['gross_pay'], 2) ?></strong></td>
                    <td style="color: var(--danger);"><?= number_format($emp['deductions'], 2) ?></td>
                    <td><strong style="color: var(--success);"><?= number_format($emp['net_pay'], 2) ?></strong></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-primary" title="Edit" onclick="openEditEmployee(<?= $emp['id'] ?>)">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-icon btn-danger" title="Delete" onclick="deleteEmployee(<?= $emp['id'] ?>, '<?= htmlspecialchars(addslashes($emp['full_name'])) ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row" id="totalRow">
                    <td colspan="4" style="text-align:right;">GRAND TOTAL:</td>
                    <td><?= number_format($totalHours, 2) ?></td>
                    <td>&mdash;</td>
                    <td><?= number_format($totalGross, 2) ?></td>
                    <td><?= number_format($totalDeductions, 2) ?></td>
                    <td><?= number_format($totalNet, 2) ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="no-results" id="noResults">
        <i class="fas fa-search"></i>
        No employees found matching your search.
    </div>
</section>

<div class="card">
    <h3 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Payroll Computation Formula</h3>
    <p style="color: var(--text-light); line-height: 1.8;">
        <strong>Gross Pay</strong> = Hours Worked &times; Hourly Rate<br>
        <strong>Net Pay</strong> = Gross Pay &minus; Deductions<br><br>
        <em>All amounts are in Nigerian Naira (&#8358;). Deductions include tax, pension, and other statutory contributions.</em>
    </p>
</div>

<!-- Add/Edit Employee Modal -->
<div class="modal-overlay" id="employeeModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-tie"></i> <span id="empModalTitle">Add Employee</span></h3>
            <button class="modal-close" onclick="closeModal('employeeModal')">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="empEditId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>Employee ID <span class="required">*</span></label>
                    <input type="text" class="form-control" id="empId" placeholder="e.g. EMP051" required>
                </div>
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="empName" placeholder="Full name" required>
                </div>
            </div>
            <div class="form-group">
                <label>Department <span class="required">*</span></label>
                <select class="form-control" id="empDept">
                    <option value="">Select department...</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                    <?php endforeach; ?>
                    <option value="__new__">+ Add new department...</option>
                </select>
                <input type="text" class="form-control" id="empDeptNew" placeholder="Enter new department" style="display:none; margin-top:8px;">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Hours Worked <span class="required">*</span></label>
                    <input type="number" class="form-control" id="empHours" placeholder="e.g. 40" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Hourly Rate (&#8358;) <span class="required">*</span></label>
                    <input type="number" class="form-control" id="empRate" placeholder="e.g. 2500" min="0" step="0.01" required>
                </div>
            </div>
            <div class="form-group">
                <label>Deductions (&#8358;) <span class="required">*</span></label>
                <input type="number" class="form-control" id="empDeductions" placeholder="e.g. 12000" min="0" step="0.01" required>
            </div>
            <div class="card" style="background: #f8fafc; margin-bottom: 0; padding: 14px 18px;">
                <p style="color: var(--text-light); font-size: 0.88rem; margin: 0;">
                    <strong>Preview:</strong>
                    Gross Pay = <span id="previewGross">&#8358;0.00</span> |
                    Net Pay = <span id="previewNet">&#8358;0.00</span>
                </p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('employeeModal')">Cancel</button>
            <button class="btn btn-success" onclick="saveEmployee()">
                <i class="fas fa-save"></i> <span id="empSaveBtn">Save Employee</span>
            </button>
        </div>
    </div>
</div>

<script>
let currentDept = 'all';
const nextEmpId = '<?= $nextEmpId ?>';

// Department new option toggle
document.getElementById('empDept').addEventListener('change', function() {
    const newInput = document.getElementById('empDeptNew');
    newInput.style.display = this.value === '__new__' ? 'block' : 'none';
    if (this.value !== '__new__') newInput.value = '';
});

// Live preview of gross/net pay
['empHours', 'empRate', 'empDeductions'].forEach(function(id) {
    document.getElementById(id).addEventListener('input', updatePreview);
});

function updatePreview() {
    const hours = parseFloat(document.getElementById('empHours').value) || 0;
    const rate = parseFloat(document.getElementById('empRate').value) || 0;
    const ded = parseFloat(document.getElementById('empDeductions').value) || 0;
    const gross = hours * rate;
    const net = gross - ded;
    document.getElementById('previewGross').innerHTML = '&#8358;' + gross.toLocaleString('en', {minimumFractionDigits: 2});
    document.getElementById('previewNet').innerHTML = '&#8358;' + net.toLocaleString('en', {minimumFractionDigits: 2});
    document.getElementById('previewNet').style.color = net < 0 ? 'var(--danger)' : 'var(--success)';
}

function openAddEmployee() {
    document.getElementById('empEditId').value = '';
    document.getElementById('empId').value = nextEmpId;
    document.getElementById('empName').value = '';
    document.getElementById('empDept').value = '';
    document.getElementById('empDeptNew').value = '';
    document.getElementById('empDeptNew').style.display = 'none';
    document.getElementById('empHours').value = '';
    document.getElementById('empRate').value = '';
    document.getElementById('empDeductions').value = '';
    document.getElementById('empModalTitle').textContent = 'Add Employee';
    document.getElementById('empSaveBtn').textContent = 'Save Employee';
    updatePreview();
    openModal('employeeModal');
}

function openEditEmployee(id) {
    fetch('api.php?action=get&entity=employee&id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.success) {
                var d = result.data;
                document.getElementById('empEditId').value = d.id;
                document.getElementById('empId').value = d.emp_id;
                document.getElementById('empName').value = d.full_name;
                document.getElementById('empDept').value = d.department;
                document.getElementById('empDeptNew').style.display = 'none';
                document.getElementById('empHours').value = d.hours_worked;
                document.getElementById('empRate').value = d.hourly_rate;
                document.getElementById('empDeductions').value = d.deductions;
                document.getElementById('empModalTitle').textContent = 'Edit Employee';
                document.getElementById('empSaveBtn').textContent = 'Update Employee';
                updatePreview();
                openModal('employeeModal');
            } else {
                showToast(result.message || 'Could not load employee', 'error');
            }
        })
        .catch(function() { showToast('Network error', 'error'); });
}

function saveEmployee() {
    var editId = document.getElementById('empEditId').value;
    var dept = document.getElementById('empDept').value === '__new__'
        ? document.getElementById('empDeptNew').value.trim()
        : document.getElementById('empDept').value;

    var data = {
        emp_id: document.getElementById('empId').value.trim(),
        full_name: document.getElementById('empName').value.trim(),
        department: dept,
        hours_worked: document.getElementById('empHours').value,
        hourly_rate: document.getElementById('empRate').value,
        deductions: document.getElementById('empDeductions').value
    };

    if (!data.emp_id || !data.full_name || !data.department || !data.hours_worked || !data.hourly_rate || !data.deductions) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    var action = editId ? 'update' : 'create';
    if (editId) data.id = editId;

    apiRequest(action, 'employee', data)
        .then(function(result) {
            showToast(result.message, 'success');
            closeModal('employeeModal');
            setTimeout(function() { location.reload(); }, 800);
        })
        .catch(function(err) { showToast(err, 'error'); });
}

function deleteEmployee(id, name) {
    showConfirm(
        'Delete Employee',
        'Are you sure you want to remove "' + name + '" from the payroll? This cannot be undone.',
        function() {
            apiRequest('delete', 'employee', { id: id })
                .then(function(result) {
                    showToast(result.message, 'success');
                    setTimeout(function() { location.reload(); }, 800);
                })
                .catch(function(err) { showToast(err, 'error'); });
        }
    );
}

function filterTable() {
    var query = document.getElementById('searchInput').value.toLowerCase();
    var rows = document.querySelectorAll('#payrollBody tr:not(.total-row)');
    var visible = 0;

    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        var dept = row.getAttribute('data-dept');
        var matchSearch = text.includes(query);
        var matchDept = currentDept === 'all' || dept === currentDept;

        if (matchSearch && matchDept) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
    document.getElementById('totalRow').style.display = visible === 0 ? 'none' : '';
}

function filterByDept(dept, btn) {
    currentDept = dept;
    document.querySelectorAll('.filter-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    filterTable();
}
</script>

<?php
$conn->close();
include 'footer.php';
?>
