<?php

if (!defined('BASE_URL')) {
  exit;
}

if (!function_exists('e')) {
  function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

require_once __DIR__ . '/../View/layout/header.php';

$pageTitle  = $pageTitle ?? 'Admin Dashboard';
$activeDash = $activeDash ?? 'dashboard';
?>

<div class="dash">
  <aside class="dash-side" aria-label="Dashboard sidebar">
    <div class="dash-side__brand">
      <div class="dash-side__badge" aria-hidden="true">LL</div>
      <div>
        <div class="dash-side__title">Land Linker</div>
        <div class="dash-side__subtitle"><?= e($pageTitle) ?></div>
      </div>
    </div>

    <nav class="dash-nav" aria-label="Dashboard navigation">
      <a class="dash-link <?= ($activeDash === 'dashboard') ? 'is-active' : '' ?>" href="<?= BASE_URL ?>View/admin-dashboard.php">
        <span class="dash-ico" aria-hidden="true">🏠</span><span>Dashboard</span>
      </a>

      <a class="dash-link <?= ($activeDash === 'employee') ? 'is-active' : '' ?>" href="<?= BASE_URL ?>View/admin/users.php">
        <span class="dash-ico" aria-hidden="true">👥</span><span>Employee</span>
      </a>

      <a class="dash-link <?= ($activeDash === 'deals') ? 'is-active' : '' ?>" href="<?= BASE_URL ?>View/admin/deals.php">
        <span class="dash-ico" aria-hidden="true">🧾</span><span>Deals</span>
      </a>

      <a class="dash-link <?= ($activeDash === 'payments') ? 'is-active' : '' ?>" href="<?= BASE_URL ?>View/admin/payments.php">
        <span class="dash-ico" aria-hidden="true">💳</span><span>Payments</span>
      </a>

      <a class="dash-link <?= ($activeDash === 'activities') ? 'is-active' : '' ?>" href="<?= BASE_URL ?>View/employee-tasks.php">
        <span class="dash-ico" aria-hidden="true">📌</span><span>Activities</span>
      </a>

      <a class="dash-link <?= ($activeDash === 'stats') ? 'is-active' : '' ?>" href="<?= BASE_URL ?>View/deal-statistics.php">
        <span class="dash-ico" aria-hidden="true">📊</span><span>Statistics</span>
      </a>
    </nav>

    <div class="dash-side__foot">
      <a class="btn btn-outline dash-side__btn" href="<?= BASE_URL ?>View/subscription.php">Upgrade Plan</a>
      <a class="btn btn-primary dash-side__btn" href="<?= BASE_URL ?>Controller/logout.php">Log Out</a>
    </div>
  </aside>

  <section class="dash-main">
    <div class="dash-top">
      <div>
        <h1 class="dash-h1"><?= e($pageTitle) ?></h1>
      </div>
      <button class="dash-toggle" type="button" aria-label="Toggle sidebar">☰</button>
    </div>

    <div class="dash-content">
