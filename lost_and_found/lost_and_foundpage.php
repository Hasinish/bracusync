<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lost and Found</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <!-- Header -->
  <header class="site-header">
    <div class="container">
      <h1>Brac University Lost and Found</h1>
      <nav>
        <ul class="nav-links">
          <li><a href="../index.php">Home</a></li>
          <li><a href="../Resource_Repository/resourcepage.php">Resource Repository</a></li>
          <li><a href="#">Messages</a></li>
          <li><a href="#">Profile</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Report Form -->
  <main>
    <section class="report-section">
      <h2>Report Lost Item</h2>
      <form action="upload.php" method="post" enctype="multipart/form-data">

        <label>Item Name</label>
        <input type="text" name="item_name" required>

        <label>Description</label>
        <textarea rows="4" name="description" required></textarea>

        <label>Location Found</label>
        <input type="text" name="location_found" required>

        <label>Date</label>
        <input type="date" name="date" required>

        <label>Time</label>
        <input type="time" name="time" required>

        <label>Upload Picture</label>
        <input type="file" name="item_image" accept="image/*"><br>

        <button type="submit">Submit Report</button>
      </form>
    </section>

    <!-- Table -->
    <section class="table-section">
      <h2>Recently Reported Items</h2>
      <table>
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
            // Connect to DB
            require_once '../connect.php';
            // Fetch all resources
            $sql = "SELECT lost_and_found.*, user.username 
                    FROM lost_and_found 
                    JOIN user ON lost_and_found.user_id = user.user_id 
                    ORDER BY lost_and_found.date DESC";
            
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                  <td>{$row['item_name']}</td>
                  <td>{$row['description']}</td>
                  <td>{$row['location']}</td> 
                  <td>{$row['date']}</td>
                  <td>{$row['time']}</td>
                  <td><a href='{$row['image_path']}' target='_blank'>Show Image</a></td>
                  <td>{$row['username']}</td>";
  
                  // Show delete button only if logged-in user is the uploader
                  if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                      echo "<td><a href='delete.php?id={$row['item_id']}' onclick=\"return confirm('Delete this report?');\">Delete</a></td>";
                  } else {
                      echo "<td>-</td>";
                  }
  
                  echo "</tr>";
              } 
            } else {
                echo "<tr><td colspan='6'>No resources uploaded yet.</td></tr>";
            }

              $conn->close();
            ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Footer -->
  <footer class="site-footer">
    &copy; 2025 Brac University. All rights reserved.
  </footer>
</body>
</html>