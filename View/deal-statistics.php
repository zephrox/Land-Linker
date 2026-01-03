<?php
require_once __DIR__ . '/../Model/init.php';

$u = current_user();
$isLogged = is_logged_in();
$isAdmin  = $isLogged && is_admin();
$isMgr    = $isLogged && is_manager();

// Load stats based on role
if (!$isLogged) {
  $stats = db_stats_public($conn);
} elseif ($isAdmin || $isMgr) {
  $stats = db_stats_global($conn);
} else {
  $stats = db_stats_user($conn, (int)$u['id']);
}

require_once __DIR__ . '/layout/header.php';

function stat_card(string $title, $value, string $sub = ''): string {
  $v = is_float($value) ? number_format($value, 2) : number_format((float)$value);
  $subHtml = $sub !== '' ? '<div style="color:#666; font-size:13px; margin-top:4px;">'.e($sub).'</div>' : '';
  return '
    <div class="card" style="padding:14px;">
      <div style="font-size:14px; color:#666;">'.e($title).'</div>
      <div style="font-size:28px; font-weight:800; margin-top:4px;">'.$v.'</div>
      '.$subHtml.'
    </div>';
}
?>

<div class="container">
  <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
    <h2>Deal Statistics</h2>

    <?php if ($isAdmin): ?>
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin-dashboard.php">Admin Dashboard</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/deals.php">Deals</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/payments.php">Payments</a>
      </div>
    <?php elseif ($isMgr): ?>
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/manager-dashboard.php">Manager Dashboard</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/employee-tasks.php">Activities</a>
      </div>
    <?php elseif ($isLogged): ?>
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">My Properties</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/subscription.php">Upgrade Plan</a>
      </div>
    <?php else: ?>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/login.php">Sign in to see your stats</a>
    <?php endif; ?>
  </div>

  <?php if (!$isLogged): ?>
    <p style="color:#666; margin-top:6px;">Showing public marketplace stats.</p>
  <?php elseif ($isAdmin): ?>
    <p style="color:#666; margin-top:6px;">Showing platform-wide statistics (Admin view).</p>
  <?php elseif ($isMgr): ?>
    <p style="color:#666; margin-top:6px;">Showing operational statistics (Manager view).</p>
  <?php else: ?>
    <p style="color:#666; margin-top:6px;">Showing your personal statistics.</p>
  <?php endif; ?>

  <div style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap:12px; margin-top:12px;">
    <?php if (!$isLogged): ?>

      <?= stat_card('Published Properties', $stats['properties_pub'], 'Visible on Map') ?>
      <?= stat_card('Total Properties', $stats['properties_total'], 'All statuses') ?>
      <?= stat_card('Map View', 1, 'Browse listings') ?>
      <?= stat_card('Sign in', 1, 'Get more insights') ?>

    <?php elseif ($isAdmin || $isMgr): ?>

      <?= stat_card('Total Users', $stats['users_total'], 'Registered accounts') ?>
      <?= stat_card('Active Users', $stats['users_active'], 'status=active') ?>
      <?= stat_card('Total Properties', $stats['properties_total'], 'All listings') ?>
      <?= stat_card('Published Properties', $stats['properties_pub'], 'Visible on Map') ?>

      <?= stat_card('Draft Properties', $stats['properties_draft'], 'Not visible publicly') ?>
      <?= stat_card('Sold Properties', $stats['properties_sold'], 'Completed listings') ?>
      <?= stat_card('Total Inquiries', $stats['inquiries_total'], 'Customer requests') ?>
      <?= stat_card('Total Deals', $stats['deals_total'], 'Closed deals') ?>

      <?= stat_card('Total Payments', $stats['payments_total'], 'All records') ?>
      <?= stat_card('Revenue (BDT)', $stats['revenue_total_bdt'], 'Paid payments sum') ?>
      <?= stat_card('Total Subscriptions', $stats['subs_total'], 'All plans') ?>
      <?= stat_card('Active Subscriptions', $stats['subs_active'], 'status=active') ?>

    <?php else: ?>

      <?= stat_card('My Properties', $stats['my_properties_total'], 'All statuses') ?>
      <?= stat_card('Published', $stats['my_properties_pub'], 'Visible on Map') ?>
      <?= stat_card('Draft', $stats['my_properties_draft'], 'Not visible publicly') ?>
      <?= stat_card('Sold', $stats['my_properties_sold'], 'Completed') ?>

      <?= stat_card('My Inquiries', $stats['my_inquiries_total'], 'Requests you made') ?>
      <?= stat_card('My Payments', $stats['my_payments_total'], 'All records') ?>
      <?= stat_card('My Spend (BDT)', $stats['my_spend_bdt'], 'Paid payments sum') ?>
      <?= stat_card('Upgrade', 1, 'Get more leads') ?>

    <?php endif; ?>
  </div>

  <div class="card" style="padding:14px; margin-top:14px;">
    <h3 style="margin:0 0 8px 0;">Quick Actions</h3>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <a class="btn btn-primary" href="<?= BASE_URL ?>View/map-view.php">Open Map View</a>

      <?php if ($isLogged): ?>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">My Properties</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/property-create.php">Add Property</a>
      <?php endif; ?>

      <?php if ($isAdmin): ?>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/users.php">Manage Users</a>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/properties.php">Manage Properties</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
