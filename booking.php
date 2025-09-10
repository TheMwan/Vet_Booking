<?php
// submit-booking.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $petID = htmlspecialchars($_POST['PatientID']);
    $petName = htmlspecialchars($_POST['petName']);
    $ownerName = htmlspecialchars($_POST['OwnerName']);
    $contact = htmlspecialchars($_POST['Contact']);
    $appointmentDate = htmlspecialchars($_POST['AppointmentDate']);
    $appointmentTime = htmlspecialchars($_POST['AppointmentTime']);

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

    // Check if the PatientID exists in the Patient table
    $checkPetSQL = "SELECT PatientID FROM Patient WHERE PatientID = '$petID'";
    $result = $conn->query($checkPetSQL);

    if ($result->num_rows > 0) {
        // Patient ID exists, proceed with the appointment booking

        // Prepare the SQL query to insert data into the Appointment table
        $sql = "INSERT INTO Appointment (PatientID, petName, OwnerName, Contact, AppointmentDate, AppointmentTime)
                VALUES ('$petID', '$petName', '$ownerName', '$contact', '$appointmentDate', '$appointmentTime')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Get the last inserted AppointmentID
            $appointmentID = $conn->insert_id;
            echo "<script>
            alert('Appointment successfully booked. Your Appointment ID is: " . $appointmentID . "');
            window.location.href='index.html';
            </script>";
        } else {
            // Error handling
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Pet not found in the Patient table, display an error message
        echo "<script>
                    alert('Please enter the correct Pet\'s ID or add your pet first.');
                    window.location.href='book-appointment.html';
                  </script>";
    }

    // Close the database connection
    $conn->close();
}
?>
