<?php
session_start();
include 'config.php'; // Use centralized connection

// Redirect if artist is not logged in
if (!isset($_SESSION['artist_id'])) {
    header("Location: signin.php");
    exit();
}

$artist_id = $_SESSION['artist_id'];

// Handle marking appointment as done
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $stmt = $conn->prepare("UPDATE appointments SET status = 'done' WHERE id = ? AND artist_id = ?");
    $stmt->bind_param("ii", $appointment_id, $artist_id);
    $stmt->execute();
    $stmt->close();
    header("Location: artupcoming.php"); // Refresh page after marking as done
    exit();
}

// Fetch upcoming appointments
$stmt = $conn->prepare("SELECT id, name, date, time, service FROM appointments WHERE artist_id = ? AND date >= CURDATE() AND status = 'pending' ORDER BY date, time");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointments - Artist</title>
    <link rel="stylesheet" href="artupcomingstyle.css">
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
                <h1>Upcoming Appointments</h1>
            </header>

            <section class="appointments">
                <?php if (empty($appointments)): ?>
                    <p>No upcoming appointments.</p>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <div class="appointment-card">
                            <div class="appointment-details">
                                <h3>Client: <?= htmlspecialchars($appointment['name']) ?></h3>
                                <p><strong>Date:</strong> <?= date("F j, Y", strtotime($appointment['date'])) ?></p>
                                <p><strong>Time:</strong> <?= date("h:i A", strtotime($appointment['time'])) ?></p>
                                <p><strong>Service:</strong> <?= htmlspecialchars($appointment['service']) ?></p>
                            </div>
                            <form method="post" class="mark-done-form">
                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                <button type="submit" class="done-btn">Mark as Done</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
