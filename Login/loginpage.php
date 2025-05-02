<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form </title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>
<body>
    <div class="wrapper">
        <span class="bg"></span>
        

        <div class="form-box login">
            <h2>Login</h2>
            <form action="login.php" method="POST">

                <div class="input-box">
                    <input type="text" name="username" required>
                    <label >Username</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="password" required>
                    <label >Password</label>
                    <i class='bx bxs-lock-alt' ></i>
                </div>

                <!-- Display error message below password input if it exists -->
                

                <button type="submit" class="btn">Login</button>

                <div class="logreg-link">
                    <p>Don't have an account? <a href="../Registration/registerpage.php" class="register-link">Sign Up</a></p>
                </div>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <p style="color: red;"><?php echo $_SESSION['login_error']; ?></p>
                    <?php unset($_SESSION['login_error']); ?> <!-- Clear the error after displaying it -->
                <?php endif; ?>

            </form>
        </div>
        <div class="info-text login">
            <h2>Welcome back!</h2>
            <p>Login to your existing account or sign up now!</p>
        </div>

    </div>

    <script src="script.js"></script>

</body>
</html>