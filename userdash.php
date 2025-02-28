<?php
session_start(); // Start session for authentication

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php"); // Redirect to login if not logged in
    exit();
}

include 'config.php'; // Use the correct database connection file

$user_id = $_SESSION['user_id'];

// Fetch user details (fetching the name)
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name);
$stmt->fetch();
$stmt->close();

// Fetch count of upcoming appointments for the current week
$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM appointments 
    WHERE user_id = ? AND date >= CURDATE() 
    AND WEEK(date) = WEEK(CURDATE())
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($appointment_count);
$stmt->fetch();
$stmt->close();

// Fetch unread message count (assuming 'is_read' column exists in messages table)
$stmt = $conn->prepare("SELECT COUNT(*) FROM messages WHERE user_id = ? AND is_read = 0");
$stmt->bind_param("i", $user_id);
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
    <title>Dashboard - User</title>
    <link rel="stylesheet" href="udashstyle.css">
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
                <h1>Welcome, <?= htmlspecialchars($user_name) ?>!</h1>
                <button class="btn" onclick="window.location.href='appointment.php'">Book an Appointment</button>
            </header>

            <section class="overview">
                <div class="card">
                    <h3>Upcoming Appointments</h3>
                    <p>You have <strong><?= $appointment_count ?></strong> upcoming appointment(s) this week.</p>
                    <button class="card-btn" onclick="window.location.href='userupcoming.php'">View Details</button>
                </div>
                <div class="card">
                    <h3>Messages</h3>
                    <p>You have <strong><?= $message_count ?></strong> new message(s) from artists.</p>
                    <button class="card-btn" onclick="window.location.href='usermess.php'">View Details</button>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
