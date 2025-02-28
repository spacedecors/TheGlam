<?php
session_start();
include 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $user_found = false;

        // First, check in the users table
        $stmt = $conn->prepare("SELECT id, password, 'user' AS role FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $hashed_password, $role);
                $stmt->fetch();
                $user_found = true;
            }
            $stmt->close();
        }

        // If not found in users, check in artists table
        if (!$user_found) {
            $stmt = $conn->prepare("SELECT id, password, 'artist' AS role FROM artists WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($user_id, $hashed_password, $role);
                    $stmt->fetch();
                    $user_found = true;
                }
                $stmt->close();
            }
        }

        // If user found in either table, proceed with authentication
        if ($user_found && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            if ($role === 'artist') {
                $_SESSION['artist_id'] = $user_id;
                header("Location: artdash.php");
            } else {
                header("Location: userdash.php");
            }
            exit();
        } else {
            $error = "Invalid email or password!";
        }
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
    <title>The Glam Sign In</title>
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
            <header>Sign In</header>
            <h2>The Glam</h2>

            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <form action="signin.php" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Log in">
                </div>

                <div class="links">
                    Don't have an account? <a href="signup.php">Sign up here!</a>
                </div>
            </form>
            <button type="button" class="back" onclick="window.history.back();">Back</button>
        </div>
    </div>
</body>
</html>
