<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $role = strtolower(trim($_POST['role'])); // Prevent role manipulation
    $error = "";

    // Check if passwords match
    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists in users or artists table
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? UNION SELECT id FROM artists WHERE email = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            // Insert into appropriate table
            if ($role == "artist") {
                $stmt = $conn->prepare("INSERT INTO artists (name, username, email, password, phone, address) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $phone, $address);
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, phone, address) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $phone, $address);
            }

            if ($stmt->execute()) {
                // Get last inserted ID and set session variables
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                if ($role == "artist") {
                    header("Location: artdash.php");
                } else {
                    header("Location: userdash.php");
                }
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>The Glam Sign Up</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head> 
<body>
    <div class="container">
        <div class="box form-box">
            <header>Sign Up</header>
            <h2>The Glam</h2>

            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

            <form action="signup.php" method="post">
                <div class="field input">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" name="confirm" id="confirm" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="role">Register as:</label>
                    <select name="role" id="role" required>
                        <option value="user">Client</option>
                        <option value="artist">Artist</option>
                    </select>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register">
                </div>

                <div class="links">
                    I have an account. <a href="signin.php">Log in</a>
                </div>
            </form>
            <button type="button" class="back" onclick="window.history.back();">Back</button>
        </div>
    </div>
</body>
</html>
