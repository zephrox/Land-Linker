<?php
require_once __DIR__ . '/../View/layout/header.php';
?>

<div class="dash">
  <aside class="dash-side" aria-label="Dashboard sidebar">
    <div class="dash-side__brand">
      <div class="dash-side__badge" aria-hidden="true">LL</div>
      <div>
        <div class="dash-side__title">Land Linker</div>
        <div class="dash-side__subtitle">
          <?= isset($pageTitle) ? e($pageTitle) : "Dashboard"; ?>
        </div>
      </div>
    </div>

    <nav class="dash-nav" aria-label="Dashboard navigation">
      <a class="dash-link <?= (($activeDash ?? '') === 'dashboard') ? 'is-active' : ''; ?>" href="<?= BASE_URL ?>View/manager-dashboard.php">
        <span class="dash-ico" aria-hidden="true">🏠</span>
        <span>Dashboard</span>
      </a>

    <a class="dash-link <?= ($activeDash === 'employee') ? 'is-active' : '' ?>"
    href="<?= BASE_URL ?>View/manager/users.php">
    <span class="dash-ico">👥</span>
    <span>Users</span>
    </a>


      <a class="dash-link <?= (($activeDash ?? '') === 'activities') ? 'is-active' : ''; ?>" href="<?= BASE_URL ?>View/employee-tasks.php">
        <span class="dash-ico" aria-hidden="true">📌</span>
        <span>Activities</span>
      </a>

      <a class="dash-link <?= (($activeDash ?? '') === 'deal_stats') ? 'is-active' : ''; ?>" href="<?= BASE_URL ?>View/deal-statistics.php">
        <span class="dash-ico" aria-hidden="true">📈</span>
        <span>Deal Stats</span>
      </a>

      <a class="dash-link <?= (($activeDash ?? '') === 'profile') ? 'is-active' : ''; ?>" href="<?= BASE_URL ?>View/profile.php">
        <span class="dash-ico" aria-hidden="true">👤</span>
        <span>Profile</span>
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
        <h1 class="dash-h1"><?= isset($pageTitle) ? e($pageTitle) : "Dashboard"; ?></h1>
        <p class="dash-sub">Manager tools and performance overview.</p>
      </div>

      <button class="dash-toggle" type="button" aria-label="Toggle sidebar">☰</button>
    </div>

    <div class="dash-content">
