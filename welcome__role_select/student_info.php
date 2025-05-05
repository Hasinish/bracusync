<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $batch = $_POST['batch'];
    $major = $_POST['major'];
    $id_no = $_POST['id_no'];

    // Save to student table
    $insertStudent = "INSERT INTO student (user_id, batch, major) VALUES ($user_id, '$batch', '$major')";
    mysqli_query($conn, $insertStudent);

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
    <title>Student Info</title>
    <link rel="stylesheet" href="student_info.css">
</head>
<body>
    <div class="container">
        <h2>Enter Your Student Info</h2>
        <form method="POST">
            <input type="text" name="batch" placeholder="Batch (e.g., 52)" required><br>
            <input type="text" name="major" placeholder="Major (e.g., CSE)" required><br>
            <input type="text" name="id_no" placeholder="Student ID" required><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
