<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Resource Repository</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Brac University Resource Repository</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                
                <li><a href="../lost_and_found/lost_and_foundpage.php">Lost & Found</a></li>
                <li><a href="#">Messages</a></li>
                <li><a href="#">Profile</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="upload-section">
            <h2>Upload New Resource</h2>
            <form action="resource_submit.php" method="POST">
                <input type="text" placeholder="Resource Name" name="resource_name" required>
                <input type="text" placeholder="Course Name" name="course_name" required>
                <textarea placeholder="Description" rows="5" name="description" required></textarea>
                <input type="text" placeholder="Resource Link" name="resource_link" required>
                <button type="submit">Upload Resource</button>
            </form>
        </section>

        <section class="search-section">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by Course Name or Resource Name" value="<?= isset($_GET['search']) ? ($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </section>

        <section class="resources-section">
            <h2>Available Resources</h2>
            <table>
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
                session_start();
                require_once ('../connect.php');

                $search = isset($_GET['search']) ? trim($_GET['search']) : '';

                $sql = "SELECT resources.*, user.username 
                        FROM resources 
                        JOIN user ON resources.user_id = user.user_id";


                // Apply filtering if search term exists
                if (!empty($search)) {
                    $sql .= " WHERE LOWER(resources.course_name) LIKE LOWER('%$search%') 
                            OR LOWER(resources.resource_name) LIKE LOWER('%$search%')";
                }

                $sql .= " ORDER BY resources.date DESC";
                
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) != 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "
                            <tr>
                                <td>{$row['resource_name']}</td>
                                <td>{$row['course_name']}</td>
                                <td>{$row['username']}</td> 
                                <td>{$row['date']}</td>
                                <td><a href='{$row['resource_link']}' target='_blank'>Link</a></td>
                            </tr>";
                    }
                } else {
                    echo "
                        <tr>
                            <td colspan='5'>No resources uploaded yet.</td>
                        </tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
            </table>
        </section>

        
    </main>
</body>
</html>