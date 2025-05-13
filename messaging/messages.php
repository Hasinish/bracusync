<?php
session_start();
require_once '../connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$users_query = "SELECT user_id, username FROM user WHERE user_id != $user_id ORDER BY username";
$users_result = mysqli_query($conn, $users_query);


$selected_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$selected_username = '';
$messages = [];


if ($selected_user_id !== 0) {
    $user_check_query = "SELECT username FROM user WHERE user_id = $selected_user_id";
    $user_check_result = mysqli_query($conn, $user_check_query);
    if (mysqli_num_rows($user_check_result) > 0) {
        $selected_username = mysqli_fetch_assoc($user_check_result)['username'];
        $messages_query = "
            SELECT m.content, m.timestamp, m.sender_id, u.username
            FROM message m
            JOIN user u ON m.sender_id = u.user_id
            WHERE (m.sender_id = $user_id AND m.recever_id = $selected_user_id)
               OR (m.sender_id = $selected_user_id AND m.recever_id = $user_id)
            ORDER BY m.timestamp ASC";
        $messages_result = mysqli_query($conn, $messages_query);
        while ($row = mysqli_fetch_assoc($messages_result)) {
            $messages[] = $row;
        }
    } else {
        $selected_user_id = 0; 
    }
}

if (isset($_POST['message_content']) && $selected_user_id > 0) {
    $content = trim($_POST['message_content']);
    if (!empty($content)) {
        $message_query = "
            INSERT INTO message (sender_id, recever_id, content, timestamp)
            VALUES ($user_id, $selected_user_id, '$content', CURRENT_TIMESTAMP)";
        mysqli_query($conn, $message_query);
        
        header("Location: messages.php?user_id=$selected_user_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — BracuSync</title>
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
            <a href="../group_post/group_select.php">Groups</a>
            <a href="messages.php" class="active">Messages</a>
            <a href="../logout.php">Logout</a>
        </nav>

        
        <div class="container messaging-section">
            <h1>Messages</h1>
            <div class="messaging-container">
                
                <div class="sidebar">
                    <h2>Users</h2>
                    <ul class="user-list">
                        <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                            <li class="<?= $selected_user_id == $user['user_id'] ? 'active' : '' ?>">
                                <a href="messages.php?user_id=<?= ($user['user_id']) ?>">
                                    <?= ($user['username']) ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                
                <div class="chat-area">
                    <?php if ($selected_user_id > 0): ?>
                        <h2>Chat with <?= ($selected_username) ?></h2>
                        <div class="messages">
                            <?php if (empty($messages)): ?>
                                <p class="no-messages">No messages yet.</p>
                            <?php else: ?>
                                <?php foreach ($messages as $message): ?>
                                    <div class="message <?= $message['sender_id'] == $user_id ? 'sent' : 'received' ?>">
                                        <div class="message-header">
                                            <span class="username"><?= ($message['username']) ?></span>
                                            <span class="timestamp"><?= ($message['timestamp']) ?></span>
                                        </div>
                                        <p><?= ($message['content']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <form action="" method="POST" class="report-form message-form">
                            <textarea name="message_content" placeholder="Type a message..." required></textarea>
                            <button type="submit" class="btn">Send</button>
                        </form>
                    <?php else: ?>
                        <p class="no-selection">Select a user to start chatting.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    
    <footer class="site-footer">
        © 2025 Brac University. All rights reserved.
    </footer>
</body>
</html>