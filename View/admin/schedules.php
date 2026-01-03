<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$rows = db_schedules($conn);
require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Schedules</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">User</th><th align="left">Title</th>
          <th align="left">Start</th><th align="left">End</th><th align="left">Color</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $s): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$s['id'] ?></td>
            <td><?= e($s['email']) ?></td>
            <td><?= e($s['title']) ?></td>
            <td><?= e($s['starts_at']) ?></td>
            <td><?= e($s['ends_at']) ?></td>
            <td><?= e($s['color_tag']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6">No schedules.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
