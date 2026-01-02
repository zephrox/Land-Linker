<?php
require_once('db.php');

function login($user){
    $con = getConnection();

    // Expected columns: users.username OR users.email, users.password, users.role_id
    // We'll support login by email OR username with minimal logic (no regex).
    $id = $user['id']; // could be username or email
    $password = $user['password'];

    // Try email match first
    $sql = "SELECT * FROM users WHERE email='{$id}' LIMIT 1";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);

    // If not found by email, try username (if your table has username)
    if(!$row){
        $sql2 = "SELECT * FROM users WHERE username='{$id}' LIMIT 1";
        $result2 = mysqli_query($con, $sql2);
        $row = mysqli_fetch_assoc($result2);
    }

    if(!$row){
        return false;
    }

    $stored = (string)($row['password'] ?? '');

    // Support both plain password and hashed password (no regex).
    // If hash looks like bcrypt ($2y$...), use password_verify. Otherwise compare directly.
    $isBcrypt = (strlen($stored) > 4 && substr($stored, 0, 4) === '$2y$');

    if($isBcrypt){
        if(password_verify($password, $stored)){
            return $row;
        }
        return false;
    }else{
        if($password === $stored){
            return $row;
        }
        return false;
    }
}

function addUser($user){
    $con = getConnection();

    $username = $user['username'] ?? '';
    $email = $user['email'] ?? '';
    $password = $user['password'] ?? '';
    $role_id = $user['role_id'] ?? 1;

    // If your DB requires hashed passwords, uncomment:
    // $password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, role_id)
            VALUES ('{$username}', '{$email}', '{$password}', '{$role_id}')";

    return mysqli_query($con, $sql) ? true : false;
}
?>
