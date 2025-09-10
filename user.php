<?php
session_start();
include 'db_connect.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['Email'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

// Get the logged-in user's email from session
$user_email = $_SESSION['Email'];

// Prepare the SQL query to fetch user data
$sql = "SELECT * FROM User WHERE Email = ?";
$stmt = $conn->prepare($sql);

// Check if the prepared statement was created successfully
if ($stmt === false) {
    echo json_encode(["error" => "Failed to prepare the query"]);
    exit();
}

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows == 1) {
    // Fetch user data and return it as JSON
    $user = $result->fetch_assoc();
    echo json_encode($user);  // Output the data in JSON format
} else {
    // Return error if user not found
    echo json_encode(["error" => "User not found"]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
