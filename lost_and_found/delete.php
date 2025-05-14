<?php
session_start();
require_once '../connect.php';

if (isset($_GET['id'], $_SESSION['user_id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT * FROM lost_and_found WHERE item_id = $id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) === 1) {
        
        if (mysqli_query($conn,"DELETE FROM lost_and_found WHERE item_id = $id")) {
            header("Location: lost_and_foundpage.php");
            exit();
        } else {
            echo "Error deleting report.";
        }
    }
}

$conn->close();
?>