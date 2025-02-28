<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php'; // Correct file for DB connection

$user_id = $_SESSION['user_id'];

// Fetch user profile details including address
$stmt = $conn->prepare("SELECT name, username, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $username, $email, $phone, $address);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="userprofile.css">
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
                <h1>User Profile</h1>
            </header>

            <section class="profile-container">
                <div class="profile-card">
                    <div class="profile-image">
                        <img src="Profile.jpg" alt="User Profile Picture">
                    </div>
                    <div class="profile-details">
                        <h2><?= htmlspecialchars($name) ?></h2>
                        <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
                        <button class="update-btn" onclick="window.location.href='updateuserprofile.php'">Update Profile</button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

