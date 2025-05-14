<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Resource Repository — BracuSync</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <main>
        <nav>
            <a href="../index.php">Home</a>
            <a href="../Profile_dashboard/profile.php">Profile</a>
            <a href="../lost_and_found/lost_and_foundpage.php">Lost And Found</a>
            <a href="resourcepage.php" class="active">Resource Repository</a>
            <a href="../logout.php">Logout</a>
        </nav>

        <div class="container upload-section">
            <h1>Upload New Resource</h1>
            <div class="section card">
                <form action="resource_submit.php" method="POST" class="report-form">
                    <label for="resource_name">Resource Name</label>
                    <input type="text" id="resource_name" name="resource_name" required>

                    <label for="course_name">Course Name</label>
                    <input type="text" id="course_name" name="course_name"  required>

                    <label for="description">Description</label>
                    <textarea id="description" rows="5" name="description"  required></textarea>

                    <label for="resource_link">Resource Link</label>
                    <input type="text" id="resource_link" name="resource_link" required>

                    <button type="submit" class="btn">Upload Resource</button>
                </form>
            </div>
        </div>

        <div class="container resources-section">
            <h1>Resources</h1>
            <div class="section card search-section">
                
                <form method="GET" class="report-form">
                    
                    <input type="text" id="search" name="search" placeholder="Search by Course Name or Resource Name" value="<?= isset($_GET['search']) ? ($_GET['search']) : ''; ?>">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>

            <div class="section card items-section">
                <h2>Available Resources</h2>
                <table class="routine-table">
                    <thead>
                        <tr>
                            <th>Resource Name</th>
                            <th>Course Name</th>
                            <th>Uploaded By</th>
                            <th>Date</th>
                            <th>Links</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                        $sql = "SELECT resources.*, user.username 
                                FROM resources 
                                JOIN user ON resources.user_id = user.user_id";

                        if (!empty($search)) {
                            $sql .= " WHERE LOWER(resources.course_name) LIKE LOWER('%$search%') 
                                    OR LOWER(resources.resource_name) LIKE LOWER('%$search%')";
                        }

                        $sql .= " ORDER BY resources.date DESC";
                        
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>
                                    <td>" . ($row['resource_name']) . "</td>
                                    <td>" . ($row['course_name']) . "</td>
                                    <td>" . ($row['username']) . "</td> 
                                    <td>" . ($row['date']) . "</td>
                                    <td><a href='" . ($row['resource_link']) . "' target='_blank'>Link</a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No resources uploaded yet.</td></tr>";
                        }

                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>