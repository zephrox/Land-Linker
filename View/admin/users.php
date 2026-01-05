<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$q = trim((string)($_POST['q'] ?? ''));
$rows = db_admin_users($conn, $q);

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Admin: Users</h2>

  <div style="display:flex; gap:10px; align-items:center; margin-bottom:12px;">
    <a class="btn btn-primary" href="<?= BASE_URL ?>View/admin/user-create.php">+ Create User</a>
    <form method="get" style="display:flex; gap:8px; align-items:center;">
      <input class="input" name="q" value="<?= e($q) ?>" placeholder="Search name/email">
      <button class="btn btn-outline" type="submit">Search</button>
    </form>
  </div>

  <div class="card" style="padding:12px;">
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left">ID</th>
          <th align="left">Name</th>
          <th align="left">Email</th>
          <th align="left">Role</th>
          <th align="left">Status</th>
          <th align="left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $u): ?>
          <tr style="border-top:1px solid rgba(255,255,255,0.08);">
            <td><?= (int)$u['id'] ?></td>
            <td><?= e($u['first_name'].' '.$u['surname']) ?></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e($u['role_name']) ?></td>
            <td><?= e($u['status']) ?></td>
            <td>
              <a class="btn btn-edit" href="<?= BASE_URL ?>View/admin/user-edit.php?id=<?= (int)$u['id'] ?>">Edit</a>
              <a class="btn btn-danger" href="<?= BASE_URL ?>View/admin/user-delete.php?id=<?= (int)$u['id'] ?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?><tr><td colspan="6">No users found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>

  <div style="margin-top:14px;">
    <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin-dashboard.php">Back to Admin Dashboard</a>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
