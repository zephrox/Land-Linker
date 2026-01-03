<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$rows = db_admin_deals($conn);
require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Deals</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">Property</th>
          <th align="left">Buyer</th><th align="left">Seller</th>
          <th align="left">Value</th><th align="left">Status</th><th align="left">Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $d): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$d['id'] ?></td>
            <td><?= e($d['property_title']) ?></td>
            <td><?= e($d['buyer_email']) ?></td>
            <td><?= e($d['seller_email']) ?></td>
            <td><?= (int)$d['deal_value_bdt'] ?> BDT</td>
            <td><?= e($d['status']) ?></td>
            <td><?= e($d['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="7">No deals.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
