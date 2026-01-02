<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = null;
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Land-Linker</title>
    <link rel="stylesheet" href="/Land-Linker/assets/css/style.css">
</head>
<body>

<div style="padding:10px; border-bottom:1px solid #ccc;">
    <div style="max-width:900px; margin:auto;">
    <a href="../../index.php">Home</a>

    <?php if($user): ?>
        <span style="margin-left:10px;">
            Logged in as: <?php echo $user['username']; ?> (Role: <?php echo $user['role_id']; ?>)
        </span>
        <a style="margin-left:10px;" href="../../Controller/logout.php">Logout</a>
    <?php else: ?>
        <a style="margin-left:10px;" href="../auth/login.php">Login</a>
        <a style="margin-left:10px;" href="../auth/signup.php">Signup</a>
    <?php endif; ?>
</div>

<div style="padding:15px;">
