<?php
require_once __DIR__ . '/../../Model/init.php';

// Flash messages (safe)
$flash_success = flash_get('success') ?? null;
$flash_error   = flash_get('error') ?? null;

$u = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Land Linker</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="<?= url('index.php') ?>">
      <span class="brand-name">Land Linker</span>
    </a>

    <nav class="nav" id="siteNav">
      <a href="<?= url('index.php') ?>">Home</a>
      <a href="<?= BASE_URL ?>View/map-view.php">Map View</a>
      <a href="<?= BASE_URL ?>View/deal-statistics.php">Deal Statistics</a>
      <a href="<?= BASE_URL ?>View/vision.php">Vision</a>
      <a href="<?= BASE_URL ?>View/about.php">About Us</a>

      <?php if (!is_logged_in()): ?>
        <a href="<?= BASE_URL ?>View/login.php">Sign In</a>
        <a class="btn btn-primary" href="<?= BASE_URL ?>View/signup.php">Sign Up</a>
      <?php else: ?>

        <!-- Common (all logged in) -->
        <a href="<?= BASE_URL ?>View/my-properties.php">My Properties</a>
        <a href="<?= BASE_URL ?>View/profile.php">Profile</a>

        <!-- Role dashboards -->
        <?php if (is_admin()): ?>
          <a href="<?= BASE_URL ?>View/admin-dashboard.php">Admin Dashboard</a>
        <?php elseif (is_manager()): ?>
          <a href="<?= BASE_URL ?>View/manager-dashboard.php">Manager Dashboard</a>
        <?php elseif (is_employee()): ?>
          <a href="<?= BASE_URL ?>View/employee-dashboard.php">Dashboard</a>
        <?php else: ?>
        <a href="<?= BASE_URL ?>View/user-dashboard.php">Dashboard</a>
        <?php endif; ?>

        <a href="<?= url('Controller/logout.php') ?>">Logout</a>      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="page">
  <div class="container" style="padding-top:14px;">

    <?php if (!empty($flash_success)): ?>
      <div class="card" style="border-left:6px solid #2e7d32; padding:10px 12px; margin-bottom:10px;">
        <?= e($flash_success) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
      <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px; margin-bottom:10px;">
        <?= e($flash_error) ?>
      </div>
    <?php endif; ?>

  </div>
