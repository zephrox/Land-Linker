<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$rows = db_properties_latest($conn, 500);
require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Properties</h2>
  <a class="btn btn-primary" href="<?= BASE_URL ?>View/property-create.php">+ Add Property</a>

  <div class="card" style="padding:12px; margin-top:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">Title</th><th align="left">Owner</th>
          <th align="left">Price</th><th align="left">Status</th><th align="left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $p): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$p['id'] ?></td>
            <td><?= e($p['title']) ?></td>
            <td><?= e($p['first_name'].' '.$p['surname']) ?></td>
            <td><?= (int)$p['price_bdt'] ?> BDT</td>
            <td><?= e($p['status']) ?></td>
            <td>
              <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/property-edit.php?id=<?= (int)$p['id'] ?>">Edit</a>
              <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/property-delete.php?id=<?= (int)$p['id'] ?>">Delete</a>
              <a class="btn btn-primary" href="<?= BASE_URL ?>View/property-details.php?id=<?= (int)$p['id'] ?>">View</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6">No properties.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
