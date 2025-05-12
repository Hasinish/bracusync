<?php
session_start();
require_once '../connect.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch enrolled sections for the user
$sections_query = "
    SELECT s.section_id, s.course_code, s.section
    FROM enrollments e
    JOIN sections s ON e.section_id = s.section_id
    WHERE e.user_id = $user_id
    ORDER BY s.course_code, s.section";
$sections_result = mysqli_query($conn, $sections_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Group — BracuSync</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <main>
        <!-- Navigation Bar -->
        <nav>
            <a href="../index.php">Home</a>
            <a href="../Profile_dashboard/profile.php">Profile</a>
            <a href="../lost_and_found/lost_and_foundpage.php">Lost And Found</a>
            <a href="../Resource_Repository/resourcepage.php">Resource Repository</a>
            <a href="../logout.php">Logout</a>
        </nav>

        <!-- Group Selection Section -->
        <div class="container report-section">
            <h1>Select Group</h1>
            <div class="section card">
                <form action="group_posts.php" method="GET" class="report-form">
                    <label for="section_id">Your Enrolled Sections</label>
                    <select id="section_id" name="section_id" required>
                        <option value="">-- Select Group --</option>
                        <?php while ($section = mysqli_fetch_assoc($sections_result)): ?>
                            <option value="<?= ($section['section_id']) ?>">
                                <?= ($section['course_code']) ?> - Section <?= ($section['section']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="btn">View Group</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>