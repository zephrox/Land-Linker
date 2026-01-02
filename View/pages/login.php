<?php require_once __DIR__ . '/../../Model/init.php'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">
  <div class="card">
    <h2>Login</h2>

    <form method="POST" action="<?= url('Controller/loginCheck.php') ?>">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <label>Email</label>
      <input class="input" type="email" name="email" required>

      <label>Password</label>
      <input class="input" type="password" name="password" required>

      <div style="margin-top:12px;">
        <button class="btn btn-primary" type="submit">Login</button>
        <a class="btn btn-outline" href="<?= url('View/pages/signup.php') ?>">Create account</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
