<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE user SET username='$username', email='$email', password='$password' WHERE user_id=$user_id";
    } else {
        $query = "UPDATE user SET username='$username', email='$email' WHERE user_id=$user_id";
    }

    mysqli_query($conn, $query);
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    header("Location: profile.php");
    exit();
}

$query = "SELECT * FROM user WHERE user_id=$user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <div class="dashboard">
        <h1>Edit Profile</h1>
        <form action="" method="POST" class="card">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

            <label>New Password (leave blank to keep):</label>
            <input type="password" name="password"><br>

            <button type="submit" class="button">Save Changes</button>
            <a href="profile.php" class="button">Back</a>
        </form>
    </div>
</body>
</html>
