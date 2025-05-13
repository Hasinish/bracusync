<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = ''; 


$session = $_POST['session']; 
$year = $_POST['year']; 
$batch = $session . '-' . $year;
$major = $_POST['major'];
$id_no = $_POST['id_no'];


$checkIdNo = "SELECT * FROM user WHERE id_no = '$id_no'";
$result = mysqli_query($conn, $checkIdNo);

if (mysqli_num_rows($result) > 0) {

    $error_message = "This Student ID already exists. Please enter a different ID.";
} else {

    $insertStudent = "INSERT INTO student (user_id, batch, major) VALUES ($user_id, '$batch', '$major')";
    mysqli_query($conn, $insertStudent);


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
    <title>Student Info</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <div class="container fade-in">
        <h1>Enter Your Student Info</h1>

        <!-- Display error message if id_no already exists -->
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" class="info-form">
            <label for="session">Select Admission Session</label>
            <select name="session" id="session" required>
                <option value="Spring">Spring</option>
                <option value="Summer">Summer</option>
                <option value="Fall">Fall</option>
            </select>

            <label for="year">Admission Year</label>
            <input type="text" name="year" id="year" placeholder="e.g., 2022" required>

            <label for="major">Major</label>
            <input type="text" name="major" id="major" placeholder="e.g., CSE" required>

            <label for="id_no">Student ID</label>
            <input type="text" name="id_no" id="id_no" placeholder="Student ID" required>

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</body>
</html>