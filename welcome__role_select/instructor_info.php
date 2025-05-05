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

    // Save to instructor table with initial
    $insertInstructor = "INSERT INTO instructor (user_id, department, designation, initial) 
                         VALUES ($user_id, '$department', '$designation', '$initial')";
    mysqli_query($conn, $insertInstructor);

    // Update user table with id_no
    $updateUser = "UPDATE user SET id_no = '$id_no' WHERE user_id = $user_id";
    mysqli_query($conn, $updateUser);

    header("Location: /BracuSync/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Instructor Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Enter Your Instructor Info</h2>
        <form method="POST">
            <input type="text" name="department" placeholder="Department (e.g., CSE)" required><br>
            <input type="text" name="designation" placeholder="Designation (e.g., Lecturer)" required><br>
            <input type="text" name="initial" placeholder="Initial (e.g., ZKH)" required><br>
            <input type="text" name="id_no" placeholder="Employee ID" required><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
