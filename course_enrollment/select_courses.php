<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$dept_query = "SELECT DISTINCT LEFT(course_code, 3) AS department_prefix FROM sections ORDER BY department_prefix";
$dept_result = mysqli_query($conn, $dept_query);

$selected_dept = isset($_POST['department']) ? $_POST['department'] : '';
$selected_course = isset($_POST['course']) ? $_POST['course'] : '';
$selected_section = isset($_POST['section']) ? $_POST['section'] : '';

$enrolled = false;


if ($selected_dept!=="") {
    $course_query = "SELECT DISTINCT course_code FROM sections WHERE course_code LIKE '{$selected_dept}%' ORDER BY course_code";
    $course_result = mysqli_query($conn, $course_query);
}


if ($selected_course!=="") {
    $section_query = "SELECT DISTINCT section FROM sections WHERE course_code = '$selected_course' ORDER BY section";
    $section_result = mysqli_query($conn, $section_query);
}


if ($selected_course!="" and $selected_section!=="") {
    $details_query = "SELECT * FROM class_schedule cs join sections s ON s.section_id=cs.section_id WHERE course_code = '$selected_course' AND section = '$selected_section'";
    $details_result = mysqli_query($conn, $details_query);
}


if (isset($_POST['enroll']) and $selected_course!=="" and $selected_section!=="") {
    $section_id_query = "SELECT section_id FROM sections 
                         WHERE course_code = '$selected_course' 
                         AND section = '$selected_section'";
    $section_id_result = mysqli_query($conn, $section_id_query);

    $success_count = 0;
    while ($row = mysqli_fetch_assoc($section_id_result)) {
        $section_id = $row['section_id'];

        
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit routine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <main>
        <nav>
            <a href="../index.php">Home</a>
            <a href="../Profile_dashboard/profile.php">Profile</a>
            <a href="../lost_and_found/lost_and_foundpage.php">Lost And Found</a>
            <a href="../Resource_Repository/resourcepage.php">Resource Repository</a>
            <a href="../logout.php">Logout</a>
        </nav>

        <div class="container enroll-section">
            <h1>Routine Maker</h1>
            <div class="section card">
                <?php if ($enrolled): ?>
                    <p class="success">✅ Successfully added to routine: <?= htmlspecialchars($selected_course) ?> Section <?= htmlspecialchars($selected_section) ?></p>
                <?php elseif (!empty($error)): ?>
                    <p class="error">❌ <?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <form method="POST" action="" class="report-form">
                    
                    <label for="department">Department</label>
                    <select id="department" name="department" onchange="this.form.submit()" required>
                        <option value="">-- Select Department --</option>
                        <?php while ($dept = mysqli_fetch_assoc($dept_result)): ?>
                            <option value="<?= htmlspecialchars($dept['department_prefix']) ?>" <?= $selected_dept == $dept['department_prefix'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department_prefix']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    
                    <?php if ($selected_dept): ?>
                        <label for="course">Course</label>
                        <select id="course" name="course" onchange="this.form.submit()" required>
                            <option value="">-- Select Course --</option>
                            <?php while ($course = mysqli_fetch_assoc($course_result)): ?>
                                <option value="<?= htmlspecialchars($course['course_code']) ?>" <?= $selected_course == $course['course_code'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['course_code']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    <?php endif; ?>

                    
                    <?php if ($selected_course): ?>
                        <label for="section">Section</label>
                        <select id="section" name="section" onchange="this.form.submit()" required>
                            <option value="">-- Select Section --</option>
                            <?php while ($sec = mysqli_fetch_assoc($section_result)): ?>
                                <option value="<?= htmlspecialchars($sec['section']) ?>" <?= $selected_section == $sec['section'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sec['section']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    <?php endif; ?>

                    
                    <?php if ($selected_course && $selected_section): ?>
                        <table class="routine-table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Room</th>
                                    <th>Faculty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($details_result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['day']) ?></td>
                                        <td><?= htmlspecialchars(substr($row['start_time'], 0, 5)) ?> - <?= htmlspecialchars(substr($row['end_time'], 0, 5)) ?></td>
                                        <td><?= htmlspecialchars($row['room']) ?></td>
                                        <td><?= htmlspecialchars($row['faculty'] ?? 'N/A') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <button type="submit" name="enroll" class="btn">Add to routine</button>
                    <?php endif; ?>
                </form>
                
            </div>
        </div>
    </main>

    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>