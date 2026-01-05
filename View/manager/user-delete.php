<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([3], BASE_URL . 'View/login.php');

$id = get_int('id');
if ($id <= 0) {
  flash_set('error', 'Invalid user id.');
  redirect(BASE_URL . 'View/manager/users.php');
}

$role_id = db_user_role_id($conn, $id);

// ✅ manager can delete only user/employee
if (!in_array((int)$role_id, [1,2], true)) {
  flash_set('error', 'You are not allowed to delete this account.');
  redirect(BASE_URL . 'View/manager/users.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  db_manager_delete_user($conn, $id);
  flash_set('success', 'User deleted.');
  redirect(BASE_URL . 'View/manager/users.php');
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Delete User</h2>

  <div class="card" style="padding:16px; margin-top:12px;">
    <p>Are you sure you want to delete this user?</p>

    <form method="post" style="margin-top:12px;">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <button class="btn btn-danger" type="submit">Yes, Delete</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/manager/users.php">Cancel</a>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
