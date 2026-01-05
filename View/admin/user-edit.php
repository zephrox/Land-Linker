<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$id = get_int('id');
$user = $id ? db_fetch_user_by_id($conn, $id) : null;
if (!$user) { http_response_code(404); die('User not found.'); }

$roles = db_all_roles($conn);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();

  $role_id = post_int('role_id');
  $first = post_str('first_name', 60);
  $last  = post_str('surname', 60);
  $email = strtolower(post_str('email', 191));
  $phone = post_str('phone', 30);
  $status = post_str('status', 20);
  $new_pass = (string)($_POST['new_password'] ?? '');

  if ($first===''||$last===''||$email==='') $errors[]='Name and email required.';
  if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]='Invalid email.';

  if (!$errors) {
    db_admin_update_user($conn, $id, $role_id ?: 1, $first, $last, $email, $phone, $status ?: 'active');
    if ($new_pass !== '') {
      if (strlen($new_pass) < 6) $errors[]='New password min 6 chars.';
      else db_admin_update_user_password($conn, $id, $new_pass);
    }
  }

  if (!$errors) {
    flash_set('success', 'User updated.');
    redirect(BASE_URL . 'View/admin/users.php');
  }
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Edit User #<?= (int)$id ?></h2>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px;">
      <ul style="margin:0; padding-left:18px;"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:16px; margin-top:12px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>Role</label>
    <select class="input" name="role_id">
      <?php foreach ($roles as $r): ?>
        <option value="<?= (int)$r['id'] ?>" <?= ((int)$user['role_id']===(int)$r['id'])?'selected':''; ?>>
          <?= e($r['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>First Name</label>
    <input class="input" name="first_name" value="<?= e($user['first_name']) ?>">

    <label>Surname</label>
    <input class="input" name="surname" value="<?= e($user['surname']) ?>">

    <label>Email</label>
    <input class="input" type="email" name="email" value="<?= e($user['email']) ?>">

    <label>Phone</label>
    <input class="input" name="phone" value="<?= e($user['phone'] ?? '') ?>">

    <label>Status</label>
    <select class="input" name="status">
      <option value="active" <?= ($user['status']==='active')?'selected':''; ?>>active</option>
      <option value="suspended" <?= ($user['status']==='suspended')?'selected':''; ?>>suspended</option>
      <option value="deleted" <?= ($user['status']==='deleted')?'selected':''; ?>>deleted</option>
    </select>

    <label>New Password (optional)</label>
    <input class="input" type="password" name="new_password" placeholder="leave blank to keep">

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/users.php">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
