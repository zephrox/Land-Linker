<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

$u = current_user();
$user_id = (int)$u['id'];

$plans = db_active_plans($conn);
$current = db_user_active_subscription($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $plan_id = post_int('plan_id');
  if ($plan_id <= 0) {
    flash_set('error', 'Please select a plan.');
    redirect(BASE_URL . 'View/subscription.php');
  }
  $sub_id = db_create_subscription($conn, $user_id, $plan_id);
  flash_set('success', 'Subscription created. Please pay to confirm.');
  redirect(BASE_URL . 'View/payment.php?subscription_id=' . $sub_id);
}

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>Subscription Plans</h2>

  <?php if ($current): ?>
    <div class="card" style="padding:14px;">
      <b>Current:</b> <?= e($current['plan_name']) ?> — <?= (int)$current['price_bdt'] ?> BDT / <?= e($current['period']) ?>
      (<?= e($current['status']) ?>)
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:16px; margin-top:12px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label>Select Plan</label>
    <select class="input" name="plan_id" required>
      <option value="">-- choose --</option>
      <?php foreach ($plans as $p): ?>
        <option value="<?= (int)$p['id'] ?>">
          <?= e($p['name']) ?> — <?= (int)$p['price_bdt'] ?> BDT / <?= e($p['period']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Continue to Payment</button>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
