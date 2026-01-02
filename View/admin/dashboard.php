<?php require_once('../../Controller/adminAuthCheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head><title>Admin Dashboard</title></head>
<body>
  <h1>Admin Dashboard</h1>
  <p>Welcome, <?php echo $_SESSION['user']['username']; ?></p>
  <a href="../../index.php">Home</a>
  <a href="../../Controller/logout.php">Logout</a>
</body>
</html>
