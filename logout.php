<?php
session_start();
session_unset(); 
session_destroy(); 
header("Location: /BracuSync/Login/loginpage.php"); 
exit();
?>