<?php
require_once __DIR__ . '/../../Model/init.php';
require_role([3], <?= BASE_URL ?>View/login.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();

  $role_id = (int)($_POST['role_id'] ?? 0);

  // ✅ manager can only create user or employee
  if (!in_array($role_id, [1,2], true)) {
    $errors[] = "You can only create User or Employee.php";
  }

  $first = post_str('first_name', 80);
  $sur   = post_str('surname', 80);
  $email = strtolower(post_str('email', 191));
  $phone = post_str('phone', 40);
  $pass  = (string)($_POST['password'] ?? '');
  $status = post_str('status', 20);

  if ($first === '' || $sur === '' || $email === '' || $pass === '') {
    $errors[] = "Name, email and password are required.php";
  }

  // no regex: use built-in validator
  if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email.php";
  }

  if (strlen($pass) < 6) {
    $errors[] = "Password must be at least 6 characters.php";
  }

  if (!$errors) {
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    db_manager_create_user($conn, [
      'role_id' => $role_id,
      'first_name' => $first,
      'surname' => $sur,
      'email' => $email,
      'phone' => $phone,
      'password_hash' => $hash,
      'status' => $status ?: 'active',
    ]);

    flash_set('success', 'User created.');
    redirect(BASE_URL . 'View/manager/users.php');
  }
}

require_once __DIR__ . '/../layout/header.php';
?>
<div class="container">
  <h2>Manager: Create User/Employee</h2>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px; margin-top:12px;">
      <ul style="margin:0; padding-left:18px;">
        <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:16px; margin-top:12px; max-width:700px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>Role</label>
    <select class="input" name="role_id" required>
      <option value="1">User</option>
      <option value="2">Employee</option>
    </select>

    <label>First Name</label>
    <input class="input" name="first_name" value="<?= e($_POST['first_name'] ?? '') ?>" required>

    <label>Surname</label>
    <input class="input" name="surname" value="<?= e($_POST['surname'] ?? '') ?>" required>

    <label>Email</label>
    <input class="input" type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>

    <label>Phone (optional)</label>
    <input class="input" name="phone" value="<?= e($_POST['phone'] ?? '') ?>">

    <label>Password</label>
    <input class="input" type="password" name="password" required>

    <label>Status</label>
    <select class="input" name="status">
      <option value="active">active</option>
      <option value="inactive">inactive</option>
    </select>

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Create</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/manager/users.php">Back</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
