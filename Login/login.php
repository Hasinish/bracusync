<?php
session_start();
require_once '../connect.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE email = '$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['status'] = $user['status']; 

        if ($user['status'] !== 'Hidden') {
            $update_status = "UPDATE user SET status = 'Online' WHERE user_id = " . $user['user_id'];
            mysqli_query($conn, $update_status);
        }

        header("Location: ../welcome__role_select/welcome.php");
        exit();
    } else {
        $_SESSION['login_error'] = "Incorrect username or password!";
        header("Location: /BracuSync/Login/loginpage.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = "Incorrect username or password!";
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}

mysqli_close($conn);
