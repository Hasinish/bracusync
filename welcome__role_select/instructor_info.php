<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $id_no = $_POST['id_no'];
    $initial = $_POST['initial'];


    $insertInstructor = "INSERT INTO instructor (user_id, department, designation, initial) 
                         VALUES ($user_id, '$department', '$designation', '$initial')";
    mysqli_query($conn, $insertInstructor);


    $updateUser = "UPDATE user SET id_no = '$id_no' WHERE user_id = $user_id";
    mysqli_query($conn, $updateUser);

    header("Location: /BracuSync/index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instructor Info</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <div class="container fade-in">
        <h1>Enter Your Instructor Info</h1>
        <form method="POST" class="info-form">
            <label for="department">Department</label>
            <input type="text" name="department" id="department" placeholder="e.g., CSE" required>

            <label for="designation">Designation</label>
            <input type="text" name="designation" id="designation" placeholder="e.g., Lecturer" required>

            <label for="initial">Initial</label>
            <input type="text" name="initial" id="initial" placeholder="e.g., ZKH" required>

            <label for="id_no">Employee ID</label>
            <input type="text" name="id_no" id="id_no" placeholder="Employee ID" required>

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</body>
</html>