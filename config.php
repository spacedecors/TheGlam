<?php
$host = "localhost";      // Change this if your database is hosted somewhere else
$username = "root";       // Your database username (default for XAMPP/WAMP is 'root')
$password = "";           // Your database password (default is usually empty in local servers)
$database = "glam";       // Name of your database (from the file you sent)

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
