<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([4]);

$roles = db_all_roles($conn);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $role_id = post_int('role_id');
  $first = post_str('first_name', 60);
  $last  = post_str('surname', 60);
  $email = strtolower(post_str('email', 191));
  $phone = post_str('phone', 30);
  $pass  = (string)($_POST['password'] ?? '');

  if ($first===''||$last===''||$email===''||$pass==='') $errors[]='All required fields required.';
  if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]='Invalid email.';
  if (strlen($pass) < 6) $errors[]='Password min 6 chars.';
  if (db_fetch_user_by_email($conn, $email)) $errors[]='Email already exists.';

  if (!$errors) {
    db_admin_create_user($conn, $role_id ?: 1, $first, $last, $email, $phone, $pass);
    flash_set('success', 'User created.');
    redirect(BASE_URL . 'View/admin/users.php');
  }
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Create User</h2>

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
        <option value="<?= (int)$r['id'] ?>"><?= e($r['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>First Name*</label>
    <input class="input" name="first_name" required>

    <label>Surname*</label>
    <input class="input" name="surname" required>

    <label>Email*</label>
    <input class="input" type="email" name="email" required>

    <label>Phone</label>
    <input class="input" name="phone">

    <label>Password*</label>
    <input class="input" type="password" name="password" required>

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Create</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/admin/users.php">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
