<?php
require_once __DIR__ . '/../Model/init.php';

if (!is_logged_in()) {
    redirect(BASE_URL . 'index.php');
    exit;
}

$user = current_user();
$role = (int)($user['role_id'] ?? 1);

switch ($role) {
    case 4:
        redirect(BASE_URL . 'View/admin-dashboard.php');
        exit;

    case 3:
        redirect(BASE_URL . 'View/manager-dashboard.php');
        exit;

    case 2:
        redirect(BASE_URL . 'View/employee-dashboard.php');
        exit;

    default:
        redirect(BASE_URL . 'View/user-dashboard.php');
        exit;
}
