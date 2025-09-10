<?php
$servername = "localhost"; // Or 127.0.0.1
$username = "root"; // Default username for MAMP
$password = "root"; // Default password for MAMP
$dbname = "Vet"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
