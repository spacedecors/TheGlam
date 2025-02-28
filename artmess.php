<?php
session_start();
include 'config.php'; // Centralized DB connection

// Redirect if artist is not logged in
if (!isset($_SESSION['artist_id'])) {
    header("Location: signin.php"); // Make sure this is correct
    exit();
}

$artist_id = $_SESSION['artist_id'];

// Fetch messages for the logged-in artist
$stmt = $conn->prepare("SELECT sender_name, content, timestamp FROM messages WHERE artist_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Artist</title>
    <link rel="stylesheet" href="artmessstyle.css">
</head>
<body>
    <div class="dashboard">
        <aside class="sidebar"> 
            <h2>The Glam</h2>
            <ul>
                <li><a href="artdash.php">🏠 Home</a></li>
                <li><a href="artupcoming.php">📅 My Appointments</a></li>
                <li><a href="artmess.php">💬 Messages</a></li>
                <li><a href="artprofile.php">👤 Profile</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <header>
                <h1>Messages</h1>
            </header>

            <section class="messages-container">
                <?php if (empty($messages)): ?>
                    <p>No messages yet.</p>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="message-card">
                            <div class="message-header">
                                <h3><?= htmlspecialchars($msg['sender_name']) ?></h3>
                                <span class="timestamp"><?= date("h:i A, F j", strtotime($msg['timestamp'])) ?></span>
                            </div>
                            <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            <button class="reply-btn" onclick="replyToMessage('<?= htmlspecialchars($msg['sender_name']) ?>')">Reply</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script>
        function replyToMessage(senderName) {
            alert("Replying to " + senderName);
            // In future, you can replace this with an actual redirect to a message reply form
        }
    </script>
</body>
</html>
