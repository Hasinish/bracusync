<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_student = false;
$is_instructor = false;

$check__student_query = "SELECT * FROM student WHERE user_id = {$_SESSION['user_id']}";
$check__instructor_query = "SELECT * FROM instructor WHERE user_id = {$_SESSION['user_id']}";

$check_student_result = mysqli_query($conn, $check__student_query);
$check_instructor_result = mysqli_query($conn, $check__instructor_query);

if(mysqli_num_rows($check_student_result) > 0){
    $is_student = true;
}
if(mysqli_num_rows($check_instructor_result) > 0){
    $is_instructor = true;
}


if($is_student){
    $student_query = "SELECT * FROM user JOIN student ON user.user_id = student.user_id WHERE user.user_id = $user_id";
    $student_result = mysqli_query($conn, $student_query);
    $student = mysqli_fetch_assoc($student_result);
}

if($is_instructor){
    $instructor_query = "SELECT * FROM user JOIN instructor ON user.user_id = instructor.user_id WHERE user.user_id = $user_id";
    $instructor_result = mysqli_query($conn, $instructor_query);
    $instructor = mysqli_fetch_assoc($instructor_result);
    $designation = $instructor['designation'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $major = trim($_POST['major']);
    $batch = trim($_POST['batch']);
    $designation = trim($_POST['designation']);
    $initial = trim($_POST['initial']);
    $department = trim($_POST['department']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if (!empty($_POST['password'])) {
        $query1 = "UPDATE user SET username='$username', email='$email', password='$password' WHERE user_id=$user_id";
    } else {
        $query1 = "UPDATE user SET username='$username', email='$email' WHERE user_id=$user_id";
    }
    
    mysqli_query($conn, $query1);

    if ($is_student) {
        $query2 = "UPDATE student SET major='$major', batch='$batch' WHERE user_id=$user_id";
    } 
    
    if ($is_instructor) {
        $query2 = "UPDATE instructor SET designation='$designation', initial='$initial', department='$department' WHERE user_id=$user_id";
    }
    
    mysqli_query($conn, $query2);
    
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    header("Location: profile.php");
    exit();
}

$query = "SELECT * FROM user WHERE user_id=$user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <div class="dashboard">
        <h1>Edit Profile</h1>
        <form action="" method="POST" class="card">
            <label>Username:</label>
            <input type="text" name="username" 
                        required
                        pattern="[A-Z][a-z]*"
                        title="Start with a capital letter, no spaces (e.g., John)"
                        value="<?= $user['username']; ?>" ><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

            <label>New Password (leave blank to keep):</label>
            <input type="password" name="password" 
                        pattern="(?=.*[0-9])(?=.*[\W_]).{8,}" 
                        title="At least 8 characters, with a number and special character" ><br>

            <?php if($is_student):?>
            
            <label>Major:</label>
            <input type="text" name="major" value="<?=  $student['major']; ?>"><br>

            <label>Batch:</label>
            <input type="text" name="batch" value="<?=  $student['batch']; ?>"><br>
            
            <?php endif; ?>

            <?php if($is_instructor):?>
            
            <label>Designation:</label>
            <input type="text" name="designation" value="<?=  $instructor['designation']; ?>"><br>

            <label>Initial:</label>
            <input type="text" name="initial" value="<?=  $instructor['initial']; ?>"><br>

            <label>Department:</label>
            <input type="text" name="department" value="<?=  $instructor['department']; ?>"><br>
            
            <?php endif; ?>

            <button type="submit" class="button">Save Changes</button>
            <a href="profile.php" class="button">Back</a>
        </form>
    </div>
</body>
</html>
