<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$rows = db_tasks($conn);
require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Tasks</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">Title</th><th align="left">Assigned</th>
          <th align="left">Deadline</th><th align="left">Status</th><th align="left">Progress</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $t): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$t['id'] ?></td>
            <td><?= e($t['title']) ?></td>
            <td><?= e($t['assigned_email'] ?? '-') ?></td>
            <td><?= e($t['deadline'] ?? '-') ?></td>
            <td><?= e($t['status']) ?></td>
            <td><?= (int)$t['progress'] ?>%</td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6">No tasks.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
