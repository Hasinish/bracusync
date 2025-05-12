<?php
session_start();
require_once '../connect.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$section_id = isset($_GET['section_id']) ? intval($_GET['section_id']) : 0;

// Verify user is enrolled in the section
$verify_query = "SELECT 1 FROM enrollments WHERE user_id = $user_id AND section_id = $section_id";
$verify_result = mysqli_query($conn, $verify_query);
if (mysqli_num_rows($verify_result) == 0) {
    header("Location: group_select.php");
    exit();
}

// Check if user is an instructor
$is_instructor = false;
$instructor_query = "
    SELECT 1 
    FROM instructor i 
    JOIN enrollments e ON i.user_id = e.user_id 
    WHERE e.user_id = $user_id AND e.section_id = $section_id";
$instructor_result = mysqli_query($conn, $instructor_query);
if (mysqli_num_rows($instructor_result) > 0) {
    $is_instructor = true;
}

// Handle new post submission (instructors only)
if ($is_instructor && isset($_POST['post_content'])) {
    $content = trim($_POST['post_content']);
    if (!empty($content)) {
        $post_query = "
            INSERT INTO group_post (content, user_id, section_id, timestamp) 
            VALUES ('$content', $user_id, $section_id, CURRENT_TIMESTAMP)";
        mysqli_query($conn, $post_query);
    }
}

// Handle new comment submission
if (isset($_POST['comment_content']) && isset($_POST['post_id'])) {
    $content = trim($_POST['comment_content']);
    $post_id = intval($_POST['post_id']);
    if (!empty($content)) {
        $comment_query = "
            INSERT INTO comments (content, timestamp, user_id, post_id) 
            VALUES ('$content', CURRENT_TIMESTAMP, $user_id, $post_id)";
        mysqli_query($conn, $comment_query);
    }
}

// Fetch section details
$section_query = "SELECT course_code, section FROM sections WHERE section_id = $section_id";
$section_result = mysqli_query($conn, $section_query);
$section = mysqli_fetch_assoc($section_result);

// Fetch posts
$posts_query = "
    SELECT gp.post_id, gp.content, gp.timestamp, u.username
    FROM group_post gp
    JOIN user u ON gp.user_id = u.user_id
    WHERE gp.section_id = $section_id
    ORDER BY gp.timestamp DESC";
$posts_result = mysqli_query($conn, $posts_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Posts — BracuSync</title>
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
            <a href="group_select.php" class="active">Groups</a>
            <a href="../logout.php">Logout</a>
        </nav>

        <!-- Group Posts Section -->
        <div class="container posts-section">
            <h1><?= ($section['course_code']) ?> - Section <?= ($section['section']) ?> Group</h1>
            <div class="section card">
                <!-- Post Creation Form (Instructors Only) -->
                <?php if ($is_instructor): ?>
                    <form action="" method="POST" class="report-form">
                        <label for="post_content">Create Announcement</label>
                        <textarea id="post_content" name="post_content" placeholder="Write your announcement..." required></textarea>
                        <button type="submit" class="btn">Post Announcement</button>
                    </form>
                <?php endif; ?>

                <!-- Posts Feed -->
                <div class="posts-feed">
                    <?php if (mysqli_num_rows($posts_result) == 0): ?>
                        <p class="no-posts">No posts available yet.</p>
                    <?php else: ?>
                        <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                            <div class="post">
                                <div class="post-header">
                                    <span class="username"><?= ($post['username']) ?></span>
                                    <span class="timestamp"><?= ($post['timestamp']) ?></span>
                                </div>
                                <div class="post-content">
                                    <p><?= ($post['content']) ?></p>
                                </div>
                                <!-- Comments Section -->
                                <div class="comments-section">
                                    <?php
                                    $comments_query = "
                                        SELECT c.content, c.timestamp, u.username
                                        FROM comments c
                                        JOIN user u ON c.user_id = u.user_id
                                        WHERE c.post_id = {$post['post_id']}
                                        ORDER BY c.timestamp ASC";
                                    $comments_result = mysqli_query($conn, $comments_query);
                                    while ($comment = mysqli_fetch_assoc($comments_result)):
                                    ?>
                                        <div class="comment">
                                            <div class="comment-header">
                                                <span class="username"><?= ($comment['username']) ?></span>
                                                <span class="timestamp"><?= ($comment['timestamp']) ?></span>
                                            </div>
                                            <p><?= ($comment['content']) ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                    <!-- Comment Form -->
                                    <form action="" method="POST" class="report-form comment-form">
                                        <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                                        <textarea name="comment_content" placeholder="Add a comment..." required></textarea>
                                        <button type="submit" class="btn">Comment</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>