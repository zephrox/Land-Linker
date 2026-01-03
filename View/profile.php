<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

$u = current_user();
$user = db_fetch_user_by_id($conn, (int)$u['id']);
if (!$user) { logout_user(); redirect(BASE_URL . 'View/login.php'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $first = post_str('first_name', 60);
  $last  = post_str('surname', 60);
  $phone = post_str('phone', 30);

  if ($first === '' || $last === '') $errors[] = "First name and surname required.php";

  if (!$errors) {
    $stmt = $conn->prepare("UPDATE users SET first_name=?, surname=?, phone=? WHERE id=?");
    $uid = (int)$user['id'];
    $stmt->bind_param('sssi', $first, $last, $phone, $uid);
    $stmt->execute();
    $stmt->close();

    // update session name
    $_SESSION['auth_user']['first_name'] = $first;
    $_SESSION['auth_user']['surname'] = $last;

    flash_set('success', 'Profile updated.');
    redirect(BASE_URL . 'View/profile.php');
  }
}

$sub = db_user_active_subscription($conn, (int)$user['id']);

require_once __DIR__ . '/layout/header.php';
?>
<div class="container">
  <h2>My Profile</h2>

  <div class="grid" style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
    <div class="card" style="padding:16px;">
      <h3>Account</h3>
      <p><b>Name:</b> <?= e($user['first_name'] . ' ' . $user['surname']) ?></p>
      <p><b>Email:</b> <?= e($user['email']) ?></p>
      
      <?php
      $roleMap = [
        4 => 'Admin',
        3 => 'Manager',
        2 => 'Employee',
        1 => 'User'
      ];
      ?>

      <p><b>Role:</b> <?= $roleMap[(int)$user['role_id']] ?? 'Unknown' ?></p>
      <p><b>Status:</b> <?= e($user['status']) ?></p>
    </div>

    <div class="card" style="padding:16px;">
      <h3>Subscription</h3>
      <?php if ($sub): ?>
        <p><b>Plan:</b> <?= e($sub['plan_name']) ?></p>
        <p><b>Price:</b> <?= (int)$sub['price_bdt'] ?> BDT / <?= e($sub['period']) ?></p>
        <p><b>Status:</b> <?= e($sub['status']) ?></p>
      <?php else: ?>
        <p>No active subscription.</p>
      <?php endif; ?>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/subscription.php">Manage subscription</a>
    </div>
  </div>

  <h3 style="margin-top:18px;">Edit Profile</h3>

  <?php if ($errors): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px;">
      <ul style="margin:0; padding-left:18px;">
        <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form class="card" method="post" style="padding:16px; margin-top:10px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>First Name</label>
    <input class="input" name="first_name" value="<?= e($user['first_name']) ?>" required>

    <label>Surname</label>
    <input class="input" name="surname" value="<?= e($user['surname']) ?>" required>

    <label>Phone</label>
    <input class="input" name="phone" value="<?= e($user['phone'] ?? '') ?>">

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit">Save</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/logout.php">Logout</a>
    </div>
  </form>
</div>
<?php require_once __DIR__ . '/layout/footer.php'; ?>
