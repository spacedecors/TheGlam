<?php
session_start();
include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to book an appointment.";
    exit;
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];
    $artist_id = intval($_POST['artist']); // Get selected artist ID

    // Validate artist selection
    if (empty($artist_id)) {
        echo "Please select an artist.";
        exit;
    }

    $check_artist = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $check_artist->bind_param("i", $artist_id);
    $check_artist->execute();
    $check_artist->store_result();

    if ($check_artist->num_rows == 0) {
        die("Error: Selected artist does not exist.");
    }
    $check_artist->close();

    // SQL to insert data into appointments table
    $sql = "INSERT INTO appointments (user_id, artist_id, name, email, address, date, time, service) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssss", $user_id, $artist_id, $name, $email, $address, $date, $time, $service);


    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location.href='policy.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Appointment</title>
    <link rel="stylesheet" href="appointstyle.css">
</head>

<body>
    <div class="form-container">
        <form action="appointment.php" method="post">
            <fieldset>
                <legend>Book an Appointment</legend>
                <h2>The Glam</h2>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group"> 
                    <label for="time">Time</label>
                    <input type="time" id="time" name="time" required>
                </div>

                <div class="form-group">
                    <label for="service">Service</label>
                    <div class="select-box">
                        <select id="service" name="service" required>
                            <option value="" disabled selected>Select a service</option>
                            <option value="makeup">Makeup</option>
                            <option value="hairstyling">Hairstyling</option>
                            <option value="bridal">Hair & Makeup</option>
                            <option value="photoshoot1">Package 1 (Bridal Package)</option>
                            <option value="photoshoot2">Package 2 (Graduation Package)</option>
                        </select>
                    </div>
                </div>

                <?php

include 'config.php';

// Fetch all artists from the artists table
$query = "SELECT id, name FROM artists";
$result = $conn->query($query);
?>

<div class="form-group">
    <label for="artist">Artist</label>
    <div class="select-box">
        <select id="artist" name="artist" required>
            <option value="" disabled selected>Select an artist</option>
            <?php
            // Loop through the results and populate the dropdown
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
    </div>
</div>


                <div class="form-group">
                    <input type="submit" value="Book" class="submit-btn">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
