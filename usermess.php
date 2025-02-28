<?php
session_start(); // Start session for authentication

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php'; // Use your main config file

$user_id = $_SESSION['user_id'];

// Fetch messages for the logged-in user
$stmt = $conn->prepare("
    SELECT messages.id, messages.content, messages.timestamp, users.name AS artist_name 
    FROM messages 
    JOIN users ON messages.artist_id = users.id 
    WHERE messages.user_id = ? AND users.role = 'artist'
    ORDER BY messages.timestamp DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - User</title>
    <link rel="stylesheet" href="usermessstyle.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar"> 
            <h2>The Glam</h2>
            <ul>
                <li><a href="userdash.php">🏠 Home</a></li>
                <li><a href="userupcoming.php">📅 My Appointments</a></li>
                <li><a href="usermess.php">💬 Messages</a></li>
                <li><a href="userprofile.php">👤 Profile</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <header>
                <h1>Messages</h1>
            </header>

            <section class="messages-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <h3><?= htmlspecialchars($row['artist_name']) ?></h3>
                                <span class="timestamp"><?= date("h:i A, M d", strtotime($row['timestamp'])) ?></span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                            <button class="reply-btn" onclick="replyMessage(<?= $row['id'] ?>)">Reply</button>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No messages found.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script>
        function replyMessage(messageId) {
            window.location.href = "reply.php?message_id=" + messageId;
        }
    </script>
</body>
</html>
