<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([3], BASE_URL . 'View/login.php');

$pageTitle  = "Users";
$activeDash = "employee"; // sidebar highlight

$q = trim((string)($_POST['q'] ?? ''));
$rows = db_manager_users_all($conn, $q);

include __DIR__ . '/../../Model/dashboard-start-manager.php';
?>

<div class="dash-card card">
  <div class="dash-card__head">
    <div>
      <h3 class="dash-h3">Users</h3>
      <p class="dash-note">View all users. You may delete only User & Employee.</p>
    </div>

    <a class="btn btn-primary" href="<?= BASE_URL ?>View/manager/user-create.php">
      + Create User / Employee
    </a>
  </div>

  <form method="get" style="margin-bottom:12px; display:flex; gap:8px;">
    <input class="input" name="q" value="<?= e($q) ?>" placeholder="Search name/email">
    <button class="btn btn-outline" type="submit">Search</button>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th style="width:140px;">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $u): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= e($u['first_name'].' '.$u['surname']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['role_name']) ?></td>
          <td><?= e($u['status']) ?></td>
          <td>
            <?php if (in_array((int)$u['role_id'], [1,2], true)): ?>
              <a class="btn btn-danger btn-sm"
                 href="<?= BASE_URL ?>View/manager/user-delete.php?id=<?= (int)$u['id'] ?>">
                Delete
              </a>
            <?php else: ?>
              <span style="color:#888;">Restricted</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$rows): ?>
        <tr><td colspan="6">No users found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../../Model/dashboard-end.php'; ?>
