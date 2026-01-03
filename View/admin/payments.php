<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$sql = "SELECT pay.*, u.email
        FROM payments pay
        JOIN users u ON u.id=pay.user_id
        ORDER BY pay.id DESC";
$res = $conn->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Payments</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">User</th><th align="left">Amount</th>
          <th align="left">Method</th><th align="left">Status</th><th align="left">Txn</th><th align="left">Paid At</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $p): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$p['id'] ?></td>
            <td><?= e($p['email']) ?></td>
            <td><?= (int)$p['amount_bdt'] ?> <?= e($p['currency']) ?></td>
            <td><?= e($p['method']) ?></td>
            <td><?= e($p['status']) ?></td>
            <td><?= e($p['provider_txn_id'] ?? '-') ?></td>
            <td><?= e($p['paid_at'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="7">No payments.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
