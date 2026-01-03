<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$sql = "SELECT s.*, u.email, p.name AS plan_name
        FROM subscriptions s
        JOIN users u ON u.id=s.user_id
        JOIN plans p ON p.id=s.plan_id
        ORDER BY s.id DESC";
$res = $conn->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Subscriptions</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">User</th><th align="left">Plan</th>
          <th align="left">Status</th><th align="left">Started</th><th align="left">Ends</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $s): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$s['id'] ?></td>
            <td><?= e($s['email']) ?></td>
            <td><?= e($s['plan_name']) ?></td>
            <td><?= e($s['status']) ?></td>
            <td><?= e($s['started_at']) ?></td>
            <td><?= e($s['ends_at'] ?? '-') ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6">No subscriptions.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
