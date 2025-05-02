<?php

session_start(); 
require_once '../connect.php'; 

// Get data
$item_name = $_POST['item_name'];
$description = $_POST['description'];
$location_found = $_POST['location_found'];
$date = $_POST['date'];
$time = $_POST['time'];
$user_id = $_SESSION['user_id'];

// handle image upload
$image_name = $_FILES['item_image']['name'];
$unique_image_name = time() . "_" . $image_name;

$tmp_name = $_FILES['item_image']['tmp_name'];
$upload_folder = "uploads/";

if (!empty($image_name)) {
    move_uploaded_file($tmp_name, $upload_folder . $unique_image_name);
    $target_path = $upload_folder . $unique_image_name;
}
else{
    $target_path = null;
}

// Insert data into database
$sql = "INSERT INTO lost_and_found 
        (item_name, description, location, date, time, image_path, user_id)
        VALUES 
        ('$item_name', '$description', '$location_found', '$date', '$time', '$target_path', '$user_id')";

if (mysqli_query($conn, $sql)) {
    header("Location: lost_and_foundpage.php"); 
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}



?>


