<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Handle the policy confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['policy_accepted'] = true; // Optional flag if you want to track policy acceptance
    header("Location: userdash.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <link rel="stylesheet" href="policy.css">
</head>
<body>
    <div class="container">
        <h1>Privacy Policy</h1>
        <h2>Payment and Refund Integration Policy</h2>
        <p>At The Glam, we are committed to providing a seamless and secure payment experience for both artists and clients. Our platform integrates trusted payment gateways to facilitate smooth transactions.</p>

        <h3>Payment Processing</h3>
        <p>All payments are securely processed through our integrated payment system, ensuring compliance with industry standards for data protection and security.</p>

        <h3>Refund Policy</h3>
        <p>Refunds are subject to the cancellation and refund terms agreed upon by the artist and client. The Glam provides a structured refund process, but final decisions may depend on the artist’s specific policies.</p>

        <h3>Dispute Resolution</h3>
        <p>In case of payment disputes, our support team will assist in resolving issues in accordance with our platform guidelines.</p>

        <h3>Policy Updates</h3>
        <p>We reserve the right to update our payment and refund policies as needed to improve our services and ensure compliance with legal and financial regulations.</p>

        <!-- Confirm Button Form -->
        <form method="post">
            <button type="submit" class="confirm-btn">Confirm</button>
        </form>
    </div>

    <style>
        .container {
            text-align: center;
            padding: 20px;
        }

        .confirm-btn {
            background-color: #89a8b2;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .confirm-btn:hover {
            background-color: #718b96;
        }
    </style>
</body>
</html>

