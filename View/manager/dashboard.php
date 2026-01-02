<?php require_once('../../Controller/managerAuthCheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head><title>Manager Dashboard</title></head>
<body>
  <h1>Manager Dashboard</h1>
  <p>Welcome, <?php echo $_SESSION['user']['username']; ?></p>
  <a href="../../Controller/logout.php">Logout</a>
</body>
</html>
