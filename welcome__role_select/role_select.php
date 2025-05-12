<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Role</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <div class="container fade-in">
        <h1>Select Your Role</h1>
        <form method="POST" action="process_role.php" class="role-form">
            <button type="submit" name="role" value="student" class="role-button btn">Student</button>
            <button type="submit" name="role" value="instructor" class="role-button btn">Instructor</button>
        </form>
    </div>
</body>
</html>