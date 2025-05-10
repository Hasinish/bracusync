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
    <title>Lost and Found — BracuSync</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="bg-overlay"></div>
    <main>
        

        <nav>
            <a href="../index.php">Home</a>
            <a href="../Profile_dashboard/profile.php">Profile</a>
            <a href="lost_and_foundpage.php" class="active">Lost And Found</a>
            <a href="../Resource_Repository/resourcepage.php">Resource Repository</a>
            <a href="../logout.php">Logout</a>
        </nav>

        <div class="container report-section">
            <h1>Report Lost Item</h1>
            <div class="section card">
                <form action="upload.php" method="post" enctype="multipart/form-data" class="report-form">
                    <label for="item_name">Item Name</label>
                    <input type="text" id="item_name" name="item_name" required>

                    <label for="description">Description</label>
                    <textarea id="description" rows="4" name="description" required></textarea>

                    <label for="location_found">Location Found</label>
                    <input type="text" id="location_found" name="location_found" required>

                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>

                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" required>

                    <label for="item_image">Upload Picture</label>
                    <input type="file" id="item_image" name="item_image" accept="image/*">

                    <button type="submit" class="btn">Submit Report</button>
                </form>
            </div>
        </div>

        <div class="container items-section">
            <h1>Recently Reported Items</h1>
            <div class="section card">
                <table class="routine-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Images</th>
                            <th>Reported By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT lost_and_found.*, user.username 
                                FROM lost_and_found 
                                JOIN user ON lost_and_found.user_id = user.user_id 
                                ORDER BY lost_and_found.date DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['item_name']) . "</td>
                                    <td>" . htmlspecialchars($row['description']) . "</td>
                                    <td>" . htmlspecialchars($row['location']) . "</td> 
                                    <td>" . htmlspecialchars($row['date']) . "</td>
                                    <td>" . htmlspecialchars($row['time']) . "</td>
                                    <td><a href='" . htmlspecialchars($row['image_path']) . "' target='_blank'>Show Image</a></td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>";

                                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                                    echo "<td><a href='delete.php?id=" . htmlspecialchars($row['item_id']) . "' onclick=\"return confirm('Delete this report?');\">Delete</a></td>";
                                } else {
                                    echo "<td>-</td>";
                                }

                                echo "</tr>";
                            } 
                        } else {
                            echo "<tr><td colspan='8'>No items reported yet.</td></tr>";
                        }

                        $conn->close();
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