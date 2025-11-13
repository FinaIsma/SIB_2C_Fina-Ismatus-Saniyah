<?php
include 'views/header.php';
include 'assets/php/config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'salary_stats':
        include 'pages/salary_stats.php';
        break;
    case 'tenure_stats':
        include 'pages/tenure_stats.php';
        break;
    case 'employee_overview':
        include 'pages/employee_overview.php';
        break;
    default:
        include 'pages/dashboard.php';
        break;
}

include 'views/footer.php';
?>
