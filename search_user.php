<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$selected_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
$search_term = isset($_POST['search']) ? $_POST['search'] : '';

$users_query = "SELECT user_id, username, status FROM user WHERE user_id != $user_id AND username LIKE '%$search_term%' ORDER BY username";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users</title>
    <link rel="stylesheet" href="search_user.css">
</head>
<body>
    <nav class="button-group">
        <a href="index.php">Home</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="user-container">
        <h2>Users</h2>
        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search users..." value="<?php echo $search_term; ?>">
            <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
        </form>
        <ul class="user-list">
            <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                <li class="<?php echo $selected_user_id == $user['user_id'] ? 'active' : ''; ?>">
                    <a href="Profile_dashboard/others_profile.php?user_id=<?php echo $user['user_id']; ?>">
                        <span class="status-dot <?php echo $user['status'] == 'Online' ? 'online' : 'offline'; ?>"></span>
                        <?php echo $user['username']; ?>
                    </a>
                </li>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($users_result) == 0): ?>
                <li class="no-results">No users found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
