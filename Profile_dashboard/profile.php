<?php 
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$user_sql = "SELECT * FROM user WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);


$student_sql = "SELECT * FROM student WHERE user_id = $user_id";
$student_result = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_result);


$instructor_sql = "SELECT * FROM instructor WHERE user_id = $user_id";
$instructor_result = mysqli_query($conn, $instructor_sql);
$instructor = mysqli_fetch_assoc($instructor_result);


$routine_sql = "
SELECT s.course_code, s.section, cs.day, cs.start_time, cs.end_time, cs.room
FROM enrollments e
JOIN sections s ON e.section_id = s.section_id
JOIN class_schedule cs ON s.section_id = cs.section_id
WHERE e.user_id = $user_id
ORDER BY s.course_code
";

$routine_result = mysqli_query($conn, $routine_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    if ($new_status === 'Online' || $new_status === 'Hidden') {
        $update_status_sql = "UPDATE user SET status = '$new_status' WHERE user_id = $user_id";
        mysqli_query($conn, $update_status_sql);
        $_SESSION['status'] = $new_status;
        $user['status'] = $new_status;
    }
} else {
    if (isset($_SESSION['status'])) {
        $user['status'] = $_SESSION['status'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Dashboard — BracuSync</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <main>
        <nav>
            <a href="../index.php">Home</a>
            <a href="profile.php" class="active">Profile</a>
            <a href="../lost_and_found/lost_and_foundpage.php">Lost And Found</a>
            <a href="../Resource_Repository/resourcepage.php">Resource Repository</a>
            <a href="../logout.php">Logout</a>
        </nav>

        <div class="container">
            <h1>Your Profile Dashboard</h1>

            <div class="section card">
                <h2>User Info</h2>
                <p><strong>Username:</strong> <?= ($user['username']); ?></p>
                <p><strong>Email:</strong> <?= ($user['email']); ?></p>
                <p><strong>ID No:</strong> <?= ($user['id_no']); ?></p>

                <form method="POST" class="status-toggle">
                    <label for="status"><strong>Status:</strong></label>
                    <select name="status" id="status" onchange="this.form.submit()">
                        <option value="Online" <?php if ($user['status'] === 'Online') echo 'selected'; ?>>Online</option>
                        <option value="Hidden" <?php if ($user['status'] === 'Hidden') echo 'selected'; ?>>Hidden</option>
                    </select>
                </form>
            </div>

            <?php if ($student): ?>
                <div class="section card">
                    <h2>Student Info</h2>
                    <p><strong>Batch:</strong> <?= ($student['batch']); ?></p>
                    <p><strong>Major:</strong> <?= ($student['major']); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($instructor): ?>
                <div class="section card">
                    <h2>Instructor Info</h2>
                    <p><strong>Department:</strong> <?= ($instructor['department']); ?></p>
                    <p><strong>Designation:</strong> <?= ($instructor['designation']); ?></p>
                    <p><strong>Initial:</strong> <?= ($instructor['initial']); ?></p>
                </div>
            <?php endif; ?>

            <div class="section card">
                <h2>Class Routine</h2>
                <?php if (mysqli_num_rows($routine_result) > 0): ?>
                    <table class="routine-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Section</th>
                                <th>Day</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($routine_result)): ?>
                                <tr>
                                    <td><?= ($row['course_code']); ?></td>
                                    <td><?= ($row['section']); ?></td>
                                    <td><?= ($row['day']); ?></td>
                                    <td><?= ($row['start_time']); ?></td>
                                    <td><?= ($row['end_time']); ?></td>
                                    <td><?= ($row['room']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No class routines found.</p>
                <?php endif; ?>
            </div>

            <div class="section card button-group">
                <a href="../group_post/group_select.php">Groups</a>
                <a href="../messaging/messages.php">Messages</a>
                <a href="../course_enrollment/select_courses.php">Edit Routine</a>
                <a href="edit_profile.php">Edit Profile</a>
                
            </div>
        </div>
    </main>

    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>