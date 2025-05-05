<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$status = isset($_POST['hide_status']) ? 'Hidden' : 'Online';

mysqli_query($conn, "UPDATE user SET status = '$status' WHERE user_id = $user_id");

header("Location: profile.php");
exit();
?>
