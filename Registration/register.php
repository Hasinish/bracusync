<?php
session_start();
require_once '../connect.php';

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

$_SESSION['username'] = $username;
$_SESSION['email'] = $email;


$query = "SELECT * FROM user WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $_SESSION["reg_error"] = "Email already registered!";
    header("Location: registerpage.php");
    exit();
} else {
    $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
    mysqli_query($conn, $sql); 
    header("Location: ../Login/loginpage.php"); 
    exit();
} 
?>