<?php
// submit-followup.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data with checks for missing values
    $petID = isset($_POST['PatientID']) ? htmlspecialchars($_POST['PatientID']) : '';
    $lastTreatmentDate = isset($_POST['LastTreatmentDate']) ? htmlspecialchars($_POST['LastTreatmentDate']) : '';
    $recoveryStatus = isset($_POST['RecoveryStatus']) ? htmlspecialchars($_POST['RecoveryStatus']) : '';
    $nextCheckUpDate = isset($_POST['nextCheckUpDate']) ? htmlspecialchars($_POST['nextCheckUpDate']) : '';
    $nextCheckUpTime = isset($_POST['nextCheckUpTime']) ? htmlspecialchars($_POST['nextCheckUpTime']) : '';
    $notes = isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '';
    $specialInstructions = isset($_POST['specialInstructions']) ? htmlspecialchars($_POST['specialInstructions']) : '';

    // Check if any required fields are empty
    if (empty($petID) || empty($lastTreatmentDate) || empty($recoveryStatus) || empty($nextCheckUpDate) || empty($nextCheckUpTime)) {
        echo "<script>
                alert('Please fill in all required fields.');
                window.location.href='admin-follow-up.html';  // Redirect back to the follow-up form
              </script>";
        exit;
    }

    // Database connection
    $host = "localhost";  // Your database host
    $username = "root";   // Your database username
    $password = "root";   // Your database password
    $dbname = "Vet";      // Your database name

    // Create a connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the PetID exists in the Patient table
    $checkPetSQL = "SELECT PatientID FROM Patient WHERE PatientID = '$petID'";
    $result = $conn->query($checkPetSQL);

    if ($result->num_rows > 0) {
        // Pet exists, proceed with inserting follow-up data

        // Prepare the SQL query to insert data into the FollowUp table
        $sql = "INSERT INTO FollowUp (PatientID, LastTreatmentDate, RecoveryStatus, NextCheckUpDate, NextCheckUpTime, Notes, SpecialInstructions)
        VALUES ('$petID', '$lastTreatmentDate', '$recoveryStatus', '$nextCheckUpDate', '$nextCheckUpTime', '$notes', '$specialInstructions')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Success message and redirect back to the patient history or another page
            echo "<script>
            alert('Follow-Up information successfully saved.');
            window.location.href='admin-dashboard.html'; // Redirect to the patient history page
            </script>";
        } else {
            // Error handling
            echo "<script>
            alert('Error: " . $conn->error . "');
            window.location.href='admin-follow-up.html';  // Redirect back to the follow-up form
            </script>";
        }
    } else {
        // Pet not found in the Patient table, display an error message
        echo "<script>
                alert('Please enter the correct Pet\'s ID or add your pet first.');
                window.location.href='admin-follow-up.html';  // Redirect back to the follow-up form
              </script>";
    }

    // Close the database connection
    $conn->close();
}
?>
