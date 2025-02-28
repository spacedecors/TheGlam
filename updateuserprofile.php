<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
 
include 'config.php'; // Database connection

$user_id = $_SESSION['user_id'];

// Fetch existing user profile details
$stmt = $conn->prepare("SELECT name, username, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $username, $email, $phone, $address);
$stmt->fetch();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $update_stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $update_stmt->bind_param("sssssi", $name, $username, $email, $phone, $address, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: userprofile.php");
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
    <title>Update Profile - User</title>
    <link rel="stylesheet" href="updateuser.css">
</head>
<body>
    <div class="form-container">
        <form action="updateuserprofile.php" method="post">
            <fieldset>
                <legend>Update Profile</legend>
                <h2>The Glam - User Profile</h2>

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
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($address) ?>" required>
                </div>

                <div class="form-group">
                    <input type="submit" value="Update Profile" class="submit-btn">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
