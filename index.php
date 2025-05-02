<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BracuSync — Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
        }
        header {
            background: #3b5998;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            background: #444;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            text-align: center;
            margin-top: 60px;
        }
        .container h1 {
            font-size: 2.5rem;
            color: #333;
        }
        .container p {
            font-size: 1.2rem;
            color: #555;
            max-width: 600px;
            margin: 10px auto;
        }
        .btn {
            background: #3b5998;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
        }
        .btn:hover {
            background: #2d4373;
        }
    </style>
</head>
<body>

    <header>
        <h1>BracuSync</h1>
        <p>Your portal to connect and collaborate</p>
    </header>

    
    <nav>
    <a href="/index.php">Home</a>
    
    <a href="lost_and_found/lost_and_foundpage.php">Lost And Found</a>
    <a href="Resource_Repository/resourcepage.php">Resource Repository</a>
    <a href="logout.php">Logout</a>
    
    </nav>

    <div class="container">
    
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> to BracuSync!</h1>
        <p>This is a simple and secure platform for students to log in, register, and access services easily. Get started by registering a new account or logging in if you're already a member.</p>
        <a href="/Registration/register.html" class="btn">Get Started</a>
    </div>

</body>
</html>
