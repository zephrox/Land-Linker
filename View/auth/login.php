<?php session_start(); ?>
<?php include('../layout/header.php'); ?>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <form action="../../Controller/loginCheck.php" method="post">
    <fieldset>
      <legend>Login</legend>

      <table>
        <tr>
          <td>Username / Email</td>
          <td><input type="text" name="id" value=""></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><input type="password" name="password" value=""></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input type="submit" name="submit" value="Submit">
            <a href="signup.php">Signup</a>
          </td>
        </tr>
      </table>

    </fieldset>
  </form>
</body>
</html>
