<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$query = "SELECT id_no FROM user WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Redirect if ID already exists
if (!empty($user['id_no'])) {
    header("Location: /BracuSync/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to BracuSync</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to BracuSync</h1>
        <p>This platform helps BRACU students and instructors stay organized and connected.</p>
        <a href="role_select.php" class="next-button">Next</a>
    </div>
</body>
</html>
