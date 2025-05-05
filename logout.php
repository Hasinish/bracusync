<?php
session_start();
require_once 'connect.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['status'] !== "Hidden") {
    $user_id = $_SESSION['user_id'];
    $update_status = "UPDATE user SET status = 'Offline' WHERE user_id = $user_id";
    mysqli_query($conn, $update_status);
}


session_unset();
session_destroy();

header("Location: /BracuSync/Login/loginpage.php");
exit();
?>
