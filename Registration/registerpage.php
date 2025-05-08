<?php session_start(); ?>

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
            <form action="register.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" required
                        pattern="[A-Z][a-z]*"
                        title="Start with a capital letter, no spaces (e.g., John)"
                        value="<?= isset($_SESSION['username']) ? ($_SESSION['username']) : ''; ?>" placeholder=" ">
                    <label>Username</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input-box">
                    <input type="email" name="email" required 
                        value="<?= isset($_SESSION['email']) ? ($_SESSION['email']) : ''; ?>" placeholder=" ">
                    <label>Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-box">
                    <input type="password" name="password" required
                        pattern="(?=.*[0-9])(?=.*[\W_]).{8,}" 
                        title="At least 8 characters, with a number and special character"
                        value="<?= isset($_SESSION['password']) ? ($_SESSION['password']) : ''; ?>" placeholder=" ">
                    <label>Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button type="submit" class="btn">Sign Up</button>

                <div class="logreg-link">
                    <p>Already have an account? <a href="../Login/loginpage.php" class="login-link">Login</a></p>
                </div>

                <?php if (isset($_SESSION["reg_error"])): ?>
                    <p style="font-size: 15px; color: red; text-align: center;"><?= $_SESSION["reg_error"]; ?></p>
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
