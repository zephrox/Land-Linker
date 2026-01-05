<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$id = get_int('id');
$user = $id ? db_fetch_user_by_id($conn, $id) : null;
if (!$user) { http_response_code(404); die('User not found.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  // prevent deleting yourself
  if ((int)current_user()['id'] === $id) {
    flash_set('error', "You can't delete your own account.php");
    redirect(BASE_URL . 'View/admin/users.php');
  }
  db_admin_delete_user($conn, $id);
  flash_set('success', 'User deleted.');
  redirect(BASE_URL . 'View/admin/users.php');
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Delete User #<?= (int)$id ?></h2>
  <div class="card" style="padding:16px;">
    <p>Delete <b><?= e($user['email']) ?></b>?</p>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <button class="btn btn-primary" type="submit">Yes, Delete</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/users.php">Cancel</a>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
