<?php
require_once __DIR__ . '/../Model/init.php';
require_role([3], BASE_URL . 'View/login.php');

$pageTitle = "Manager Dashboard";
$activeDash = "dashboard";

include __DIR__ . '/../Model/dashboard-start-manager.php';
?>

<div class="dash-grid">
  <div class="dash-card card">
    <h3 class="dash-h3">Total Employees</h3>
    <p class="dash-note">Active employees in system</p>
    <div style="font-size:26px;font-weight:800;">3</div>
  </div>

  <div class="dash-card card">
    <h3 class="dash-h3">Total Deals</h3>
    <p class="dash-note">Deals completed</p>
    <div style="font-size:26px;font-weight:800;">38</div>
  </div>

  <div class="dash-card card">
    <h3 class="dash-h3">Revenue</h3>
    <p class="dash-note">Total earnings</p>
    <div style="font-size:26px;font-weight:800;">৳124,500</div>
  </div>
</div>

<div class="card" style="margin-top:14px; padding:16px;">
  <h3 class="dash-h3">Quick Actions</h3>
  <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
    <a class="btn btn-outline" href="<?= BASE_URL ?>View/manager/users.php">Employee Directory</a>
    <a class="btn btn-outline" href="<?= BASE_URL ?>View/employee-tasks.php">Activities</a>
    <a class="btn btn-outline" href="<?= BASE_URL ?>View/deal-statistics.php">Deal Statistics</a>
  </div>
</div>

<?php include __DIR__ . '/../Model/dashboard-end.php'; ?>
