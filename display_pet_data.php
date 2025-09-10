<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('You must be logged in.');window.location.href='login.html';</script>";
    exit();
}

$userID = $_SESSION['UserID'];

// Fetch pet data for the logged-in user
$fetchQuery = "SELECT * FROM Patient WHERE UserID = ?";
$stmt = $conn->prepare($fetchQuery);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$pets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row; // Store all pets
    }
} else {
    $pets = []; // No pets found
}

$stmt->close();

// Return the data as JSON
echo json_encode($pets);
?>
