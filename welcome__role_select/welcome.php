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
    <div class="container fade-in">
        <h1>Welcome to <span style="color: #007bff;">BracuSync</span></h1>
        <p class="info-text">
            BracuSync is your all-in-one academic assistant designed for the BRAC University community.
            Whether you're a student or an instructor, BracuSync helps you stay connected, informed, and organized.
            <br><br>
            📚 <strong>Students</strong> can view their batch and major info, stay up to date with announcements, manage academic activities, and connect with instructors.
            <br><br>
            🎓 <strong>Instructors</strong> can manage course details, post updates, and communicate with students easily.
            <br><br>
            ✅ Secure login, personalized profiles, and a clean interface make BracuSync a powerful but simple tool for everyone at BRACU.
            <br><br>
            Click <strong>Next</strong> to select your role and complete your profile setup.
        </p>
        <a href="role_select.php" class="next-button">Next</a>
    </div>
</body>
</html>
