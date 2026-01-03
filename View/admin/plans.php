<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$res = $conn->query("SELECT * FROM plans ORDER BY id ASC");
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Plans</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">Code</th><th align="left">Name</th>
          <th align="left">Price</th><th align="left">Period</th><th align="left">Active</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $p): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$p['id'] ?></td>
            <td><?= e($p['code']) ?></td>
            <td><?= e($p['name']) ?></td>
            <td><?= (int)$p['price_bdt'] ?> BDT</td>
            <td><?= e($p['period']) ?></td>
            <td><?= (int)$p['is_active'] ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6">No plans.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
