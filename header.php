<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SENG412 - Group 3b Project</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-brand">
                <i class="fas fa-code"></i>
                <span>SENG412</span>
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li>
                    <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li>
                    <a href="payroll.php" class="<?= $currentPage === 'payroll.php' ? 'active' : '' ?>">
                        <i class="fas fa-money-bill-wave"></i> Payroll
                    </a>
                </li>
                <li>
                    <a href="gpa.php" class="<?= $currentPage === 'gpa.php' ? 'active' : '' ?>">
                        <i class="fas fa-graduation-cap"></i> GPA
                    </a>
                </li>
                <li>
                    <a href="details.php" class="<?= $currentPage === 'details.php' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Details
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
