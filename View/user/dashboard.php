<?php require_once('../../Controller/userAuthCheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head><title>User Dashboard</title></head>
<body>
  <h1>User Dashboard</h1>
  <p>Welcome, <?php echo $_SESSION['user']['username']; ?></p>
  <a href="../../Controller/logout.php">Logout</a>
</body>
</html>
