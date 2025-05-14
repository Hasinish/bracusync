<?php
session_start();
require_once 'connect.php';
$user_id = $_SESSION['user_id'];

$users_query = "SELECT user_id, username FROM user WHERE user_id != $user_id ORDER BY username";
$users_result = mysqli_query($conn, $users_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="search_user.css">
</head>
<body>
    <div class="sidebar">
                    <h2>Users</h2>
                    <ul class="user-list">
                        <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                            <li class="<?= $selected_user_id == $user['user_id'] ? 'active' : '' ?>">
                                <p><a href="Profile_dashboard\others_profile.php?user_id=<?= ($user['user_id']) ?>">
                                    <?= ($user['username']) ?>
                                </a> 
                            </p>
                            </li>
                        <?php endwhile; ?>
                    </ul>
    </div>
</body>
</html>