<?php
//connection
require_once '../connect.php';

// fetch data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// check if email already exists
$query = "SELECT * FROM user WHERE email = '$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    echo "Email already registered!";
    exit();
}


// insert user into database
$sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
if (mysqli_query($conn, $sql)) {
    header("Location: ../Login/loginpage.php"); 
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>