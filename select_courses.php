<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Login required.");
}

$user_id = $_SESSION['user_id'];

// Fetch all departments
$dept_query = "SELECT DISTINCT LEFT(course_code, 3) AS department_prefix FROM sections ORDER BY department_prefix";
$dept_result = mysqli_query($conn, $dept_query);

$selected_dept = $_POST['department'] ?? '';
$selected_course = $_POST['course'] ?? '';
$selected_section = $_POST['section'] ?? '';
$enrolled = false;

// Fetch courses
if ($selected_dept) {
    $course_query = "SELECT DISTINCT course_code FROM sections WHERE course_code LIKE '{$selected_dept}%' ORDER BY course_code";
    $course_result = mysqli_query($conn, $course_query);
}

// Fetch sections
if ($selected_course) {
    $section_query = "SELECT DISTINCT section FROM sections WHERE course_code = '$selected_course' ORDER BY section";
    $section_result = mysqli_query($conn, $section_query);
}

// Fetch section rows
if ($selected_course && $selected_section) {
    $details_query = "SELECT * FROM class_schedule cs join sections s ON s.section_id=cs.section_id WHERE course_code = '$selected_course' AND section = '$selected_section'";
    $details_result = mysqli_query($conn, $details_query);
}

// Insert into Enrollments
if (isset($_POST['enroll']) && $selected_course && $selected_section) {
    $section_id_query = "SELECT section_id FROM sections 
                         WHERE course_code = '$selected_course' 
                         AND section = '$selected_section'";
    $section_id_result = mysqli_query($conn, $section_id_query);

    $success_count = 0;
    while ($row = mysqli_fetch_assoc($section_id_result)) {
        $section_id = $row['section_id'];

        // Insert enrollment (ignore duplicate key errors)
        $enroll_query = "INSERT IGNORE INTO enrollments (section_id, user_id, enrollment_date) 
                         VALUES ($section_id, $user_id, CURDATE())";
        if (mysqli_query($conn, $enroll_query)) {
            $success_count++;
        }
    }

    if ($success_count > 0) {
        $enrolled = true;
    } else {
        $error = "You might already be enrolled in this section.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll in Course</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 30px;
        }
        .container {
            background: #fff;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border-radius: 5px;
            border: 1px solid #999;
        }
        h2 {
            text-align: center;
        }
        .section-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .section-table th, .section-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Course Enrollment</h2>

    <?php if ($enrolled): ?>
        <p class="success">✅ Successfully enrolled in <?= $selected_course ?> Section <?= $selected_section ?></p>
    <?php elseif (!empty($error)): ?>
        <p style="color: red;">❌ <?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Department Dropdown -->
        <label>Department</label>
        <select name="department" onchange="this.form.submit()" required>
            <option value="">-- Select Department --</option>
            <?php while ($dept = mysqli_fetch_assoc($dept_result)): ?>
                <option value="<?= $dept['department_prefix'] ?>" <?= $selected_dept == $dept['department_prefix'] ? 'selected' : '' ?>>
                    <?= $dept['department_prefix'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Course Dropdown -->
        <?php if ($selected_dept): ?>
            <label>Course</label>
            <select name="course" onchange="this.form.submit()" required>
                <option value="">-- Select Course --</option>
                <?php while ($course = mysqli_fetch_assoc($course_result)): ?>
                    <option value="<?= $course['course_code'] ?>" <?= $selected_course == $course['course_code'] ? 'selected' : '' ?>>
                        <?= $course['course_code'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        <?php endif; ?>

        <!-- Section Dropdown -->
        <?php if ($selected_course): ?>
            <label>Section</label>
            <select name="section" onchange="this.form.submit()" required>
                <option value="">-- Select Section --</option>
                <?php while ($sec = mysqli_fetch_assoc($section_result)): ?>
                    <option value="<?= $sec['section'] ?>" <?= $selected_section == $sec['section'] ? 'selected' : '' ?>>
                        <?= $sec['section'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        <?php endif; ?>

        <!-- Section Details Table -->
        <?php if ($selected_course && $selected_section): ?>
            <table class="section-table">
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Faculty</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($details_result)): ?>
                    <tr>
                        <td><?= $row['day'] ?></td>
                        <td><?= substr($row['start_time'], 0, 5) ?> - <?= substr($row['end_time'], 0, 5) ?></td>
                        <td><?= $row['room'] ?></td>
                        <td><?= $row['faculty'] ?? 'N/A' ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <button type="submit" name="enroll">Enroll</button>
        <?php endif; ?>
    </form>
    <a href="index.php">Home</a>
</div>

</body>
</html>