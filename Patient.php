<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['UserID'])) {
    echo "<script>alert('You must be logged in.');window.location.href='login.html';</script>";
    exit;
}
$userID = $_SESSION['UserID'];

// Get the pet data from the form
$petName = isset($_POST['petName']) ? trim($_POST['petName']) : '';
$breed = isset($_POST['Breed']) ? trim($_POST['Breed']) : '';
$species = isset($_POST['Species']) ? trim($_POST['Species']) : '';
$age = isset($_POST['Age']) ? (int)$_POST['Age'] : 0; // Convert to integer
// Check if the VaccinationStatus checkbox is checked (1) or unchecked (0)
$vaccinated = isset($_POST['VaccinationStatus']) ? 1 : 0; // 1 for vaccinated, 0 for not vaccinated
$medicalHistory = isset($_POST['MedicalHistory']) ? trim($_POST['MedicalHistory']) : '';

// Validate the input data
if (empty($petName) || empty($breed) || empty($species) || $age <= 0) {
    echo "<script>alert('Please fill in all required fields.');window.location.href='user.html';</script>";
} else {
    // Check if the UserID exists in the User table
    $userCheckQuery = "SELECT * FROM User WHERE UserID = ?";
    $userStmt = $conn->prepare($userCheckQuery);
    $userStmt->bind_param("i", $userID);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    // Check if the 'vaccinated' checkbox was checked
    $vaccinated = isset($_POST['vaccinated']) ? 1 : 0; // If checked, set to 1; if unchecked, set to 0

    if ($userResult->num_rows === 0) {
        echo "<script>alert('Invalid user. Please log in again.');window.location.href='login.html';</script>";
    } else {
        // Insert the pet data into the Patient table and associate it with the UserID
        $insertQuery = "INSERT INTO Patient (UserID, petName, Breed, Species, Age, VaccinationStatus, MedicalHistory) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insertQuery);

        if ($stmt === false) {
            die("Error preparing SQL statement: " . $conn->error);
        }

        // Bind the parameters for the SQL query
        $stmt->bind_param("isssiss", $userID, $petName, $breed, $species, $age, $vaccinated, $medicalHistory);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Pet data added successfully.');window.location.href='user.html';</script>"; // Redirect to a page that shows all pets
        } else {
            echo "<script>alert('Error adding pet data. Please try again.');window.location.href='user.html';</script>";
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the user check statement
    $userStmt->close();
}
?>
