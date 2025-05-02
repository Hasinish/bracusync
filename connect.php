<?php

$conn = new mysqli('localhost', 'root', '', 'bracusync');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo "";
}
?>