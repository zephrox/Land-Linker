<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$rows = db_admin_inquiries($conn);
require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Inquiries</h2>
  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th><th align="left">Property</th><th align="left">Name</th>
          <th align="left">Email</th><th align="left">Phone</th><th align="left">Status</th><th align="left">Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $i): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$i['id'] ?></td>
            <td><?= e($i['property_title']) ?></td>
            <td><?= e($i['full_name']) ?></td>
            <td><?= e($i['email']) ?></td>
            <td><?= e($i['phone'] ?? '-') ?></td>
            <td><?= e($i['status']) ?></td>
            <td><?= e($i['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="7">No inquiries.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
