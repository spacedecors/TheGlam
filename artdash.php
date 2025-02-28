<?php
session_start();
include 'config.php'; // Use the central config file for database connection

// Redirect if not logged in as artist
if (!isset($_SESSION['artist_id'])) {
    header("Location: signin.php");
    exit();
}

$artist_id = $_SESSION['artist_id'];

// Fetch artist name
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$stmt->bind_result($artist_name);
$stmt->fetch();
$stmt->close();

// Count upcoming appointments
$stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE artist_id = ? AND date >= CURDATE()");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$stmt->bind_result($appointment_count);
$stmt->fetch();
$stmt->close();

// Count unread messages
$stmt = $conn->prepare("SELECT COUNT(*) FROM messages WHERE artist_id = ? AND status = 'unread'");

$stmt->bind_param("i", $artist_id);
$stmt->execute();
$stmt->bind_result($message_count);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Artist</title>
    <link rel="stylesheet" href="artdashstyle.css">
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
                <h1>Welcome, <?= htmlspecialchars($artist_name) ?>!</h1>
                <button class="btn" onclick="window.location.href='artupcoming.php'">Manage Appointments</button>
            </header>

            <section class="overview">
                <div class="card">
                    <h3 style="font-weight: bold;">Upcoming Appointments</h3>
                    <p>You have <?= $appointment_count ?> upcoming appointment(s) this week.</p>
                    <button class="card-btn" onclick="window.location.href='artupcoming.php'">View Details</button>
                </div>
                <div class="card">
                    <h3>Messages</h3>
                    <p><?= $message_count ?> new message(s) from clients.</p>
                    <button class="card-btn" onclick="window.location.href='artmess.php'">View Details</button>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
