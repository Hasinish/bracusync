<?php

session_start(); 
require_once '../connect.php'; 


$item_name = $_POST['item_name'];
$description = $_POST['description'];
$location_found = $_POST['location_found'];
$date = $_POST['date'];
$time = $_POST['time'];
$user_id = $_SESSION['user_id'];


$image = $_FILES['item_image'];
$target_path = null;

if ($image['error'] === 0) {
    $unique_name = time() . '_' . basename($image['name']);
    $target_path = 'uploads/' . $unique_name;
    move_uploaded_file($image['tmp_name'], $target_path);
}

$sql = "INSERT INTO lost_and_found 
        (item_name, description, location, date, time, image_path, user_id)
        VALUES 
        ('$item_name', '$description', '$location_found', '$date', '$time', '$target_path', '$user_id')";
mysqli_query($conn, $sql);
header("Location: lost_and_foundpage.php");




?>


