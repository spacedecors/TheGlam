<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php'; // Use consistent config file

$user_id = $_SESSION['user_id'];

// Fetch upcoming appointments (only pending ones), joining with artist (users table)
$stmt = $conn->prepare("
    SELECT appointments.id, users.name AS artist_name, appointments.date, appointments.time, appointments.service
    FROM appointments
    JOIN users ON appointments.artist_id = users.id
    WHERE appointments.user_id = ? AND appointments.status = 'pending'
    ORDER BY appointments.date, appointments.time
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
    <title>Upcoming Appointments - User</title>
    <link rel="stylesheet" href="userupcomingstyle.css">
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
                <h1>Upcoming Appointments</h1>
            </header> 

            <section class="appointments">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="appointment-card">
                            <div class="appointment-details">
                                <h3>Artist: <?= htmlspecialchars($row['artist_name']) ?></h3>
                                <p><strong>Date:</strong> <?= date("F j, Y", strtotime($row['date'])) ?></p>
                                <p><strong>Time:</strong> <?= date("h:i A", strtotime($row['time'])) ?></p>
                                <p><strong>Service:</strong> <?= htmlspecialchars($row['service']) ?></p>
                            </div>
                            <div class="btn-container">
                                <button class="upd-btn" onclick="updateAppointment(<?= $row['id'] ?>)">Update</button>
                                <button class="cancel-btn" onclick="cancelAppointment(<?= $row['id'] ?>)">Cancel</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No upcoming appointments.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script>
        function updateAppointment(appointmentId) {
            window.location.href = "update_appointment.php?id=" + appointmentId;
        }

        function cancelAppointment(appointmentId) {
            if (confirm("Are you sure you want to cancel this appointment?")) {
                window.location.href = "cancel_appointment.php?id=" + appointmentId;
            }
        }
    </script>
</body>
</html>
