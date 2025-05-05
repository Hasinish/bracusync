<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /BracuSync/Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = ''; // To hold error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session = $_POST['session']; // Spring, Summer, or Fall
    $year = $_POST['year']; // Year input
    $batch = $session . '-' . $year; // Concatenate to form the batch string
    $major = $_POST['major'];
    $id_no = $_POST['id_no'];

    // Check if the id_no already exists in the user table
    $checkIdNo = "SELECT * FROM user WHERE id_no = '$id_no'";
    $result = mysqli_query($conn, $checkIdNo);

    if (mysqli_num_rows($result) > 0) {
        // If id_no exists, show an error message
        $error_message = "This Student ID already exists. Please enter a different ID.";
    } else {
        // Save to student table
        $insertStudent = "INSERT INTO student (user_id, batch, major) VALUES ($user_id, '$batch', '$major')";
        mysqli_query($conn, $insertStudent);

        // Update user table with id_no
        $updateUser = "UPDATE user SET id_no = '$id_no' WHERE user_id = $user_id";
        mysqli_query($conn, $updateUser);

        header("Location: /BracuSync/index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Info</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Enter Your Student Info</h2>

        <!-- Display error message if id_no already exists -->
        <?php if ($error_message): ?>
            <div style="color: red; font-weight: bold; margin-bottom: 15px;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label for="session">Select Admission Session:</label>
            <select name="session" required>
                <option value="Spring">Spring</option>
                <option value="Summer">Summer</option>
                <option value="Fall">Fall</option>
            </select><br>

            <label for="year">Enter Admission Year:</label>
            <input type="text" name="year" placeholder="Year (e.g., 2022)" required><br>

            <input type="text" name="major" placeholder="Major (e.g., CSE)" required><br>
            <input type="text" name="id_no" placeholder="Student ID" required><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
