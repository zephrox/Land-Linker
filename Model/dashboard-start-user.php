<?php

$pageTitle  = $pageTitle ?? "User Dashboard";
$activeDash = $activeDash ?? "dashboard";

require_once __DIR__ . '/../View/layout/header.php';
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
      <a class="dash-link <?= (($activeDash ?? '') === 'dashboard') ? 'is-active' : '' ?>"
         href="<?= BASE_URL ?>View/user-dashboard.php">
        <span class="dash-ico" aria-hidden="true">🏠</span>
        <span>Dashboard</span>
      </a>

      <a class="dash-link <?= (($activeDash ?? '') === 'project') ? 'is-active' : '' ?>"
         href="#">
        <span class="dash-ico" aria-hidden="true">📁</span>
        <span>Project</span>
      </a>

      <a class="dash-link <?= (($activeDash ?? '') === 'activities') ? 'is-active' : '' ?>"
         href="<?= BASE_URL ?>View/activities.php">
        <span class="dash-ico" aria-hidden="true">📌</span>
        <span>Activities</span>
      </a>

      <a class="dash-link <?= (($activeDash ?? '') === 'email') ? 'is-active' : '' ?>"
         href="#">
        <span class="dash-ico" aria-hidden="true">✉️</span>
        <span>Email</span>
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
