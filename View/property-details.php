<?php
require_once __DIR__ . '/../Model/init.php';

$id = get_int('id');
$p = $id > 0 ? db_property_get($conn, $id) : null;

if (!$p) {
  flash_set('error', 'Property not found.');
  redirect(url('index.php'));
}

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2><?= e($p['title']) ?></h2>
  <p style="color:#666;">Owner: <?= e(($p['first_name'] ?? '') . ' ' . ($p['surname'] ?? '')) ?> (<?= e($p['email'] ?? '') ?>)</p>

  <div class="card" style="padding:16px; margin-top:12px;">
    <p><strong>Price:</strong> <?= (int)$p['price_bdt'] ?> BDT</p>
    <p><strong>Status:</strong> <?= e($p['status']) ?></p>
    <p><strong>Type:</strong> <?= e($p['land_type']) ?></p>
    <p><strong>Location:</strong> <?= e($p['city'] ?? '') ?> <?= e($p['state'] ?? '') ?> <?= e($p['country'] ?? '') ?></p>
    <p><strong>Address:</strong> <?= e($p['address_text'] ?? '') ?></p>
    <p style="margin-top:10px;"><strong>Description:</strong><br><?= nl2br(e($p['description'] ?? '')) ?></p>

    <div style="margin-top:12px;">
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/map-view.php') ?>">View on Map</a>
      <?php if (is_logged_in()): ?>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php') ?>">Back</a>
      <?php else: ?>
        <a class="btn btn-outline" href="<?= url('index.php') ?>">Back</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
