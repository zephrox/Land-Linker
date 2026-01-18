<?php
require_once __DIR__ . '/../Model/init.php';

require_login(BASE_URL . 'View/login.php');

$u = current_user();
$user_id = (int)$u['id'];

$properties = db_user_properties($conn, $user_id);

require_once __DIR__ . '/layout/header.php';
?>

<div class="container">
  <h2>My Properties</h2>

  <div style="margin-top:12px;">
    <a class="btn btn-primary" href="<?= BASE_URL ?>View/property-create.php">+ Add New Property</a>
  </div>

  <?php if ($properties): ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:16px; margin-top:16px;">
      <?php foreach ($properties as $p):
        $media = db_property_media($conn, (int)$p['id']);
        $primary_img = null;
        foreach ($media as $m) {
          if ($m['is_primary']) { $primary_img = $m; break; }
        }
        if (!$primary_img && $media) $primary_img = $media[0];
      ?>
        <div class="card" style="overflow:hidden;">
          <?php if ($primary_img): ?>
            <img src="<?= BASE_URL . e($primary_img['file_path']) ?>" style="width:100%; height:160px; object-fit:cover;">
          <?php else: ?>
            <div style="width:100%; height:160px; background:#333; display:flex; align-items:center; justify-content:center; color:#666;">No Image</div>
          <?php endif; ?>
          
          <div style="padding:12px;">
            <h3 style="margin:0 0 8px 0; font-size:18px;"><?= e($p['title']) ?></h3>
            <p style="margin:0; color:#666; font-size:14px;"><?= (int)$p['price_bdt'] ?> BDT</p>
            <p style="margin:4px 0 0 0; color:#888; font-size:13px;">
              <span style="display:inline-block; padding:2px 8px; background:#444; border-radius:3px;"><?= e($p['status']) ?></span>
            </p>
            
            <div style="display:flex; gap:8px; margin-top:12px;">
              <a class="btn btn-outline" href="<?= BASE_URL ?>View/property-details.php?id=<?= (int)$p['id'] ?>" style="font-size:13px; padding:6px 12px;">View</a>
              <a class="btn btn-outline" href="<?= BASE_URL ?>View/property-edit.php?id=<?= (int)$p['id'] ?>" style="font-size:13px; padding:6px 12px;">Edit</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="card" style="padding:16px; margin-top:12px;">
      <p>You haven't added any properties yet.</p>
      <a class="btn btn-primary" href="<?= BASE_URL ?>View/property-create.php" style="margin-top:10px;">Create Your First Property</a>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
