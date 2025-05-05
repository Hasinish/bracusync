<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['role'])) {
    header("Location: role_select.php");
    exit();
}

$role = $_POST['role'];

if ($role === 'student') {
    header("Location: student_info.php");
} elseif ($role === 'instructor') {
    header("Location: instructor_info.php");
} else {
    header("Location: role_select.php");
}
exit();
