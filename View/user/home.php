<?php
require_once('../../Controller/authCheck.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
</head>
<body>
  <h1>Welcome Home! <?php echo $_SESSION['user']['username']; ?></h1>

  <p>
    <a href="../../Controller/logout.php">Logout</a>
  </p>

  <p>
    <a href="dashboard.php">Go to Dashboard</a>
  </p>
</body>
</html>
