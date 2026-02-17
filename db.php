<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'seng412_project';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('<div style="text-align:center;padding:50px;font-family:Poppins,sans-serif;">
        <h2 style="color:#e53e3e;">Database Connection Failed</h2>
        <p>Please run <strong>setup.sql</strong> in phpMyAdmin first to create the database.</p>
        <p style="color:#718096;font-size:14px;">Error: ' . $conn->connect_error . '</p>
    </div>');
}

$conn->set_charset("utf8mb4");
?>
