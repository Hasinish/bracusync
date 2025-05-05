<?php

session_start(); 
require_once '../connect.php'; 


$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    
    if (password_verify($password, $user['password'])) {
        
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: /BracuSync/index.php");
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
?>

