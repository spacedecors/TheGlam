<?php
session_start();

// Check if artist is logged in
if (!isset($_SESSION['artist_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php'; // Database connection

$artist_id = $_SESSION['artist_id'];

// Fetch artist profile data
$stmt = $conn->prepare("SELECT name, username, email, phone, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$stmt->bind_result($name, $username, $email, $phone, $profile_picture);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Profile</title>
    <link rel="stylesheet" href="artprofile.css">
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
                <h1>My Profile</h1>
            </header>

            <section class="profile-container">
                <div class="profile-card">
                    <div class="profile-image">
                        <img src="Profile.jpg" alt="Profile Picture">
                    </div>
                    <div class="profile-details">
                        <h2><?= htmlspecialchars($name) ?></h2>
                        <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
                        <button class="update-btn" onclick="window.location.href='updateartistprofile.php'">Update Profile</button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
