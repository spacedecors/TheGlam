<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Glam</title>
    <link rel="stylesheet" href="indexstyle.css">
</head>
<body>
    <header>
        <div class="logo">THE GLAM</div>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="userdash.php">Dashboard</a>
                <a href="logout.php"><button class="btn">Logout</button></a>
            <?php elseif (isset($_SESSION['artist_id'])): ?>
                <a href="artistdash.php">Dashboard</a>
                <a href="logout.php"><button class="btn">Logout</button></a>
            <?php else: ?>
                <a href="signin.php">Sign In</a>
                <a href="signup.php"><button class="btn">Get Started</button></a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="tagline">
            <h1>DARE TO BE</h1>
            <h2>CAPTIVATING</h2>
            <button class="book-btn" onclick="window.location.href='policy.php'">Book Now! <span>></span></button>
        </section>

        <section class="gallery">
            <div class="image-container">
                <img src="Makeup.jpg" alt="Makeup look">
            </div>
            <div class="image-container">
                <img src="Hairstyle.jpg" alt="Hair styling">
            </div>
            <div class="image-container">
                <img src="Elegantt.jpg" alt="Elegant makeup">
            </div>
        </section>
    </main>
    
</body>
</html>
