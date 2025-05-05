<?php 
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$user_sql = "SELECT * FROM user WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// Fetch student info
$student_sql = "SELECT * FROM student WHERE user_id = $user_id";
$student_result = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_result);

// Fetch instructor info
$instructor_sql = "SELECT * FROM instructor WHERE user_id = $user_id";
$instructor_result = mysqli_query($conn, $instructor_sql);
$instructor = mysqli_fetch_assoc($instructor_result);

// Fetch enrolled sections and routines
$routine_sql = "
SELECT s.course_code, s.section, cs.day, cs.start_time, cs.end_time, cs.room
FROM enrollments e
JOIN sections s ON e.section_id = s.section_id
JOIN class_schedule cs ON s.section_id = cs.section_id
WHERE e.user_id = $user_id
ORDER BY FIELD(cs.day, 'Sun','Mon','Tue','Wed','Thu','Fri','Sat'), cs.start_time
";

$routine_result = mysqli_query($conn, $routine_sql);

// Handle hide status toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hide_status'])) {
    $new_status = $_POST['hide_status'] === 'on' ? 'Hidden' : 'Online';
    $update_status_sql = "UPDATE user SET status = '$new_status' WHERE user_id = $user_id";
    mysqli_query($conn, $update_status_sql);
    
    // Update session to remember the status
    $_SESSION['status'] = $new_status;

    $user['status'] = $new_status; // Update local value
} else {
    // Set status from session if available
    if (isset($_SESSION['status'])) {
        $user['status'] = $_SESSION['status'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Dashboard</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="container">
    <h1>Your Profile Dashboard</h1>

    <div class="section">
        <h2>User Info</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>ID No:</strong> <?php echo htmlspecialchars($user['id_no']); ?></p>
        <p><strong>Status:</strong> <?php echo $user['status']; ?></p>

        <form method="POST" class="status-toggle">
            <label>
                <!-- Hidden input ensures value is always submitted -->
                <input type="hidden" name="hide_status" value="off">
                <input type="checkbox" name="hide_status" value="on" onchange="this.form.submit()" <?php if ($user['status'] === 'Hidden') echo 'checked'; ?>>
                Hide my online status
            </label>
        </form>

    </div>

    <?php if ($student): ?>
        <div class="section">
            <h2>Student Info</h2>
            <p><strong>Batch:</strong> <?php echo htmlspecialchars($student['batch']); ?></p>
            <p><strong>Major:</strong> <?php echo htmlspecialchars($student['major']); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($instructor): ?>
        <div class="section">
            <h2>Instructor Info</h2>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($instructor['department']); ?></p>
            <p><strong>Designation:</strong> <?php echo htmlspecialchars($instructor['designation']); ?></p>
            <p><strong>Initial:</strong> <?php echo htmlspecialchars($instructor['initial']); ?></p>
        </div>
    <?php endif; ?>

    <div class="section">
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
                            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['section']); ?></td>
                            <td><?php echo htmlspecialchars($row['day']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['room']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No class routines found.</p>
        <?php endif; ?>
    </div>

    <div class="section" style="text-align: center;">
        <a class="btn" href="../index.php" style="background-color:rgb(43, 175, 192);">Home</a>
        <a class="btn" href="../select_courses.php" style="background-color:rgb(43, 192, 110);">Enroll New Course</a>
        <a class="btn" href="edit_profile.php">Edit Profile</a>
        <a class="btn" href="../logout.php" style="background-color: #c0392b;">Logout</a>
    </div>
</div>

</body>
</html>
