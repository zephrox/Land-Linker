<?php
require_once __DIR__ . '/../Model/init.php';

if (is_logged_in()) {
  redirect(BASE_URL . 'View/profile.php');
}

require_once __DIR__ . '/layout/header.php';
?>

<div class="container">
  <h2>Sign Up</h2>

  <?php $err = flash_get('error'); ?>
  <?php if ($err): ?>
    <div class="card" style="border-left:6px solid #b71c1c; padding:10px 12px;">
      <?= e($err) ?>
    </div>
  <?php endif; ?>

  <form class="card" method="post" action="<?= url('Controller/signupCheck.php') ?>" style="padding:16px;">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <label>First Name</label>
    <input class="input" type="text" name="first_name" required>

    <label>Surname</label>
    <input class="input" type="text" name="surname" required>

    <label>Email</label>
    <input class="input" type="email" name="email" required>

    <label>Phone</label>
    <input class="input" type="text" name="phone">

    <label>Password</label>
    <input class="input" type="password" name="password" required>

    <div style="margin-top:12px;">
      <button class="btn btn-primary" type="submit" name="submit">Create Account</button>
      <a class="btn btn-outline" href="<?= BASE_URL ?>View/login.php">Back to Login</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
