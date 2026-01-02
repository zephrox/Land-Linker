<?php require_once('../../Controller/employeeAuthCheck.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head><title>Employee Dashboard</title></head>
<body>
  <h1>Employee Dashboard</h1>
  <p>Welcome, <?php echo $_SESSION['user']['username']; ?></p>
  <a href="../../Controller/logout.php">Logout</a>
</body>
</html>
