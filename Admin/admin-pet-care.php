<?php
// submit-petcare.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $petID = htmlspecialchars($_POST['PatientID']);
    $healthStatus = htmlspecialchars($_POST['HealthStatus']);
    $vaccinationRecords = htmlspecialchars($_POST['VaccinationRecords']);
    $lastTreatmentDate = htmlspecialchars($_POST['LastTreatmentDate']);
    $nextCheckUpDate = htmlspecialchars($_POST['NextCheckUpDate']);
    $nextCheckUpTime = htmlspecialchars($_POST['NextCheckUpTime']);
    $specialInstructions = htmlspecialchars($_POST['SpecialInstructions']);

    // Database connection
    $conn = new mysqli("localhost", "root", "root", "Vet");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if PatientID exists in Patient table
    $stmt = $conn->prepare("SELECT PatientID FROM Patient WHERE PatientID = ?");
    $stmt->bind_param("s", $petID);  // 's' means string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Insert data into TreatmentPlan table
        $stmt = $conn->prepare("INSERT INTO TreatmentPlan (PatientID, HealthStatus, VaccinationRecords, LastTreatmentDate, NextCheckUpDate, NextCheckUpTime, SpecialInstructions) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $petID, $healthStatus, $vaccinationRecords, $lastTreatmentDate, $nextCheckUpDate, $nextCheckUpTime, $specialInstructions);

        if ($stmt->execute()) {
            echo "<script>
            alert('Pet care details saved successfully!');
            window.location.href='admin-dashboard.html';
            </script>";
        } else {
            echo "<script>
            alert('Error: " . $conn->error . "');
            window.location.href='admin-pet-care.html';
            </script>";
        }
    } else {
        echo "<script>
                alert('Please enter the correct Pet\'s ID or add your pet first.');
                window.location.href='admin-pet-care.html';
              </script>";
    }

    // Close connection
    $conn->close();
}
?>
