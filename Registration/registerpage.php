<?php
require_once '../connect.php';

$error = "";
$username = "";
$email = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered!";
    } else {
        // Insert user into database
        $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../Login/loginpage.php"); 
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="video-background">
        <iframe 
            src="https://www.youtube.com/embed/tthY4pJqfEw?autoplay=1&mute=1&controls=0&showinfo=0&modestbranding=1&rel=0&loop=1&playlist=tthY4pJqfEw&playsinline=1" 
            frameborder="0" 
            allow="autoplay; fullscreen" 
            allowfullscreen>
        </iframe>
    </div>

    <div class="video-overlay"></div>
    <div class="wrapper">
        <span class="bg"></span>

        <div class="form-box register">
            <h2>Sign Up</h2>
            <form method="POST">
                <div class="input-box">
                    <input type="text" name="username" required
                        pattern="[A-Z][a-z]*"
                        title="Start with a capital letter, no spaces (e.g., John)"
                        value="<?php echo htmlspecialchars($username); ?>">
                    <label>Username</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="email" name="email" required 
                        value="<?php echo htmlspecialchars($email); ?>">
                    <label>Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="password" required
                        pattern="(?=.*[0-9])(?=.*[\W_]).{8,}" 
                        title="At least 8 characters, with a number and special character"
                        value="<?php echo htmlspecialchars($password); ?>">
                    <label>Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" class="btn">Sign Up</button>

                <div class="logreg-link">
                    <p>Already have an account? <a href="../Login/loginpage.php" class="login-link">Login</a></p>
                </div>

                <?php if (!empty($error)): ?>
                    <p style="font-size: 15px; color: red; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </div>

        <div class="info-text register">
            <h2>Welcome to <p>bracusync</p></h2>
            <p>Sign up now to access all the features.</p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
