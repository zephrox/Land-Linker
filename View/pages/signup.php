<?php require_once __DIR__ . '/../../Model/init.php'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">
  <div class="card">
    <h2>Sign Up</h2>

    <form method="POST" action="<?= url('Controller/signupCheck.php') ?>">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <label>First Name</label>
      <input class="input" type="text" name="first_name" required>

      <label>Surname</label>
      <input class="input" type="text" name="surname" required>

      <label>Email</label>
      <input class="input" type="email" name="email" required>

      <label>Phone (optional)</label>
      <input class="input" type="text" name="phone">

      <label>Password</label>
      <input class="input" type="password" name="password" required>

      <div style="margin-top:12px;">
        <button class="btn btn-primary" type="submit">Create account</button>
        <a class="btn btn-outline" href="<?= url('View/pages/login.php') ?>">Back to login</a>
      </div>
    </form>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
