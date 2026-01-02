<?php
session_start();
require_once('../Model/userModel.php');

if(isset($_POST['submit'])){
    $id = $_REQUEST['id'];           // username or email
    $password = $_REQUEST['password'];

    if($id == "" || $password == ""){
        echo "null value!";
    }else{
        $user = ['id'=> $id, 'password'=> $password];
        $row = login($user);

        if($row){
            setcookie('status', 'true', time()+3000, '/');

            // store session like demo
            $_SESSION['user'] = [
                'id' => $row['id'] ?? null,
                'username' => $row['username'] ?? ($row['name'] ?? ''),
                'email' => $row['email'] ?? '',
                'role_id' => (int)($row['role_id'] ?? 1),
            ];

            header('location: ../View/user/home.php');
        }else{
            echo "invalid user!";
        }
    }
}else{
    header('location: ../View/auth/login.php');
}
?>
