<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    // Sanitize and validate input
    $patientID = filter_var($_POST['pet_id'], FILTER_VALIDATE_INT);

    if ($patientID) {
        $deleteQuery = "DELETE FROM Patient WHERE PatientID = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $patientID);

        if ($stmt->execute()) {
            // Redirect to the user page after successful deletion
            header('Location: user.html');
            exit();
        } else {
            // Redirect with error message if deletion fails
            header('Location: user.html?error=Error deleting pet');
            exit();
        }

        $stmt->close();
    } else {
        // Redirect with error message if invalid Pet ID
        header('Location: user.html?error=Invalid Pet ID');
        exit();
    }
}
?>
