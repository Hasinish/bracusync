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
    <link rel="stylesheet" href="role_select.css">
</head>
<body>
    <div class="container">
        <h1>Select Your Role</h1>
        <form method="POST" action="process_role.php">
            <button type="submit" name="role" value="student" class="role-button">Student</button>
            <button type="submit" name="role" value="instructor" class="role-button">Instructor</button>
        </form>
    </div>
</body>
</html>
