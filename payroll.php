<?php
/**
 * PAYROLL PAGE - SENG412 Group Project
 * Simple Payroll System for 50 Employees
 * Computes Gross Pay and Net Pay
 */
include 'header.php';
include 'db.php';

// Fetch all employees
$employees = [];
$result = $conn->query("SELECT * FROM employees ORDER BY emp_id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['gross_pay'] = $row['hours_worked'] * $row['hourly_rate'];
        $row['net_pay'] = $row['gross_pay'] - $row['deductions'];
        $employees[] = $row;
    }
}

// Calculate totals
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

// Get unique departments
$departments = array_unique(array_column($employees, 'department'));
sort($departments);
?>

<!-- Hero Section -->
<section class="hero">
    <h1><i class="fas fa-money-bill-wave"></i> Employee Payroll</h1>
    <p class="subtitle">Small-Sized Company Payroll System</p>
    <p class="dept">Wages computed based on Hours Worked, Hourly Rate, and Deductions</p>
</section>

<!-- Payroll Stats -->
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

<!-- Search and Filter -->
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

<!-- Payroll Table -->
<section class="section">
    <div class="section-header">
        <i class="fas fa-file-invoice-dollar"></i>
        <h2>Payslip Summary</h2>
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
                </tr>
            </thead>
            <tbody id="payrollBody">
                <?php foreach ($employees as $i => $emp): ?>
                <tr data-dept="<?= htmlspecialchars($emp['department']) ?>">
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= htmlspecialchars($emp['emp_id']) ?></strong></td>
                    <td><?= htmlspecialchars($emp['full_name']) ?></td>
                    <td><?= htmlspecialchars($emp['department']) ?></td>
                    <td><?= number_format($emp['hours_worked'], 2) ?></td>
                    <td><?= number_format($emp['hourly_rate'], 2) ?></td>
                    <td><strong><?= number_format($emp['gross_pay'], 2) ?></strong></td>
                    <td style="color: var(--danger);"><?= number_format($emp['deductions'], 2) ?></td>
                    <td><strong style="color: var(--success);"><?= number_format($emp['net_pay'], 2) ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <!-- Grand Total -->
                <tr class="total-row" id="totalRow">
                    <td colspan="4" style="text-align:right;">GRAND TOTAL:</td>
                    <td><?= number_format($totalHours, 2) ?></td>
                    <td>&mdash;</td>
                    <td><?= number_format($totalGross, 2) ?></td>
                    <td><?= number_format($totalDeductions, 2) ?></td>
                    <td><?= number_format($totalNet, 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="no-results" id="noResults">
        <i class="fas fa-search"></i>
        No employees found matching your search.
    </div>
</section>

<!-- Payroll Computation Note -->
<div class="card">
    <h3 style="color: var(--primary); margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Payroll Computation Formula</h3>
    <p style="color: var(--text-light); line-height: 1.8;">
        <strong>Gross Pay</strong> = Hours Worked &times; Hourly Rate<br>
        <strong>Net Pay</strong> = Gross Pay &minus; Deductions<br><br>
        <em>All amounts are in Nigerian Naira (&#8358;). Deductions include tax, pension, and other statutory contributions.</em>
    </p>
</div>

<!-- JavaScript for Search and Filter -->
<script>
let currentDept = 'all';

function filterTable() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#payrollBody tr:not(.total-row)');
    let visible = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const dept = row.getAttribute('data-dept');
        const matchSearch = text.includes(query);
        const matchDept = currentDept === 'all' || dept === currentDept;

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
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterTable();
}
</script>

<?php
$conn->close();
include 'footer.php';
?>
