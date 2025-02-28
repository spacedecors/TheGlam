<?php
session_start();

if (!isset($_SESSION['artist_id'])) {
    header("Location: signin.php");
    exit();
}

include 'config.php';
 
$artist_id = $_SESSION['artist_id'];

// Fetch artist profile from users table where role = 'artist'
$stmt = $conn->prepare("SELECT name, username, email, phone FROM users WHERE id = ? AND role = 'artist'");
$stmt->bind_param("i", $artist_id);
$stmt->execute();
$stmt->bind_result($name, $username, $email, $phone);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $update_stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ? WHERE id = ? AND role = 'artist'");
    $update_stmt->bind_param("ssssi", $name, $username, $email, $phone, $artist_id);

    if ($update_stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: artprofile.php");
        exit();
    } else {
        $error = "Failed to update profile. Please try again.";
    }

    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="updateartist.css">
</head>
<body>
    <div class="form-container">
        <form action="updateartistprofile.php" method="post">
            <fieldset>
                <legend>Update Profile</legend>
                <h2>The Glam - Artist Profile</h2>

                <?php if (isset($error)): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                </div>

                <div class="form-group">
                    <input type="submit" value="Update Profile" class="submit-btn">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
