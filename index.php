<?php 
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login/loginpage.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BracuSync — Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <main>
        <header>
            <h1>BracuSync</h1>
            <p>Your portal to connect and collaborate</p>
        </header>

        <nav>
            <a href="index.php" class="active">Home</a>
            <a href="Profile_dashboard/profile.php">Profile</a>
            <a href="lost_and_found/lost_and_foundpage.php">Lost And Found</a>
            <a href="Resource_Repository/resourcepage.php">Resource Repository</a>
            <a href="logout.php">Logout</a>
        </nav>  

        <div class="container">
            <h1>Welcome, <?= ($_SESSION['username']); ?> to BracuSync!</h1>
            <p>This is a simple and secure platform for students to log in, register, and access services easily. Get started by going to your profile and editing your routine, adding courses, and find out what's happeing in your groups.</p>
            
        </div>
    </main>

    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>