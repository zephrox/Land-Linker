<?php
require_once __DIR__ . '/../Model/init.php';

$id = get_int('id');
$p = $id > 0 ? db_property_by_id($conn, $id) : null;

if (!$p) {
  flash_set('error', 'Property not found.');
  redirect(url('index.php'));
}

$media = db_property_media($conn, $id);

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2><?= e($p['title']) ?></h2>
  <p style="color:#666;">Owner: <?= e(($p['first_name'] ?? '') . ' ' . ($p['surname'] ?? '')) ?> (<?= e($p['email'] ?? '') ?>)</p>

  <?php if ($media): ?>
  <div style="margin-top:16px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:12px;">
      <?php foreach ($media as $m): ?>
        <div style="position:relative; border-radius:8px; overflow:hidden; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
          <img src="<?= BASE_URL . e($m['file_path']) ?>" style="width:100%; height:180px; object-fit:cover;">
          <?php if ($m['is_primary']): ?>
            <div style="position:absolute; top:8px; right:8px; background:#4CAF50; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px; font-weight:bold;">Primary</div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="card" style="padding:16px; margin-top:12px;">
    <p><strong>Price:</strong> <?= (int)$p['price_bdt'] ?> BDT</p>
    <p><strong>Status:</strong> <?= e($p['status']) ?></p>
    <p><strong>Type:</strong> <?= e($p['land_type']) ?></p>
    <p><strong>Location:</strong> <?= e($p['city'] ?? '') ?> <?= e($p['state'] ?? '') ?> <?= e($p['country'] ?? '') ?></p>
    <p><strong>Address:</strong> <?= e($p['address_text'] ?? '') ?></p>
    <p style="margin-top:10px;"><strong>Description:</strong><br><?= nl2br(e($p['description'] ?? '')) ?></p>

    <div style="margin-top:12px;">
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/map-view.php">View on Map</a>
      <?php if (is_logged_in()): ?>
        <a class="btn btn-outline" href="<?= BASE_URL ?>View/my-properties.php">Back</a>
      <?php else: ?>
        <a class="btn btn-outline" href="<?=url('index.php')?>">Back</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
