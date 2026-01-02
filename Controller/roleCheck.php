<?php
session_start();
require_once('authCheck.php');

function requireRole($allowedRoles){
    $role = 0;
    if(isset($_SESSION['user']) && isset($_SESSION['user']['role_id'])){
        $role = (int)$_SESSION['user']['role_id'];
    }

    $ok = false;
    foreach($allowedRoles as $r){
        if((int)$r === $role){
            $ok = true;
            break;
        }
    }

    if(!$ok){
        header('location: ../View/auth/login.php');
        exit;
    }
}
?>
