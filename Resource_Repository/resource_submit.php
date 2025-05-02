
<?php
session_start();
require_once '../connect.php';

// collect data
$resourceName = $_POST['resource_name'];
$courseName = $_POST['course_name'];
$description = $_POST['description'];
$resourceLink = $_POST['resource_link'];
$uploadedBy = $_SESSION['username']; 
$date = date('Y-m-d H:i:s');
$user_id = $_SESSION['user_id'];
// Insert 

$sql = "INSERT INTO resources (course_name, resource_name, description, resource_link, date, user_id )
        VALUES ('$courseName', '$resourceName', '$description', '$resourceLink', '$date', '$user_id')";

if (mysqli_query($conn, $sql)) {
    header("Location: resource.php"); 
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}

$conn->close();
?>
