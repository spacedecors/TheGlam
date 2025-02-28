<?php
session_start();
include 'config.php'; // Use your main connection file

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized. Please log in first.");
}

// Check if appointment ID is provided
if (!isset($_GET['id'])) {
    die("Invalid request. No appointment ID provided.");
}

$appointment_id = intval($_GET['id']); // Always sanitize inputs

// Fetch appointment from database
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Appointment not found.");
}

$appointment = $result->fetch_assoc();

// OPTIONAL: Check if this appointment belongs to the logged-in user
if ($_SESSION['user_id'] != $appointment['user_id']) {
    die("Unauthorized access.");
}

// Handle form submission (updating the appointment)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    $stmt = $conn->prepare("UPDATE appointments SET name=?, email=?, phone=?, address=?, date=?, time=?, service=? WHERE id=?");
    $stmt->bind_param("sssssssi", $name, $email, $phone, $address, $date, $time, $service, $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment updated successfully!";
        header("Location: userupcoming.php"); 
        exit();
    } else {
        $error = "Failed to update appointment. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <link rel="stylesheet" href="updateappstyle.css">
</head>

<body>
    <div class="form-container">
        <form action="updateapp.php?id=<?= $appointment_id ?>" method="post">
            <fieldset>
                <legend>Update Appointment</legend>
                <h2>The Glam</h2>

                <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($appointment['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($appointment['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($appointment['phone']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($appointment['address']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?= $appointment['date'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" value="<?= $appointment['time'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="service">Service</label>
                    <div class="select-box">
                        <select id="service" name="service" required>
                            <option value="" disabled>Select a service</option>
                            <option value="makeup" <?= $appointment['service'] == 'makeup' ? 'selected' : '' ?>>Makeup</option>
                            <option value="hairstyling" <?= $appointment['service'] == 'hairstyling' ? 'selected' : '' ?>>Hairstyling</option>
                            <option value="bridal" <?= $appointment['service'] == 'bridal' ? 'selected' : '' ?>>Hair & Makeup</option>
                            <option value="photoshoot1" <?= $appointment['service'] == 'photoshoot1' ? 'selected' : '' ?>>Package 1 (Bridal Package)</option>
                            <option value="photoshoot2" <?= $appointment['service'] == 'photoshoot2' ? 'selected' : '' ?>>Package 2 (Graduation Package)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" value="Update" class="submit-btn">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
