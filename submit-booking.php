<?php
// submit-booking.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $petName = htmlspecialchars($_POST['petName']);
    $ownerName = htmlspecialchars($_POST['ownerName']);
    $contact = htmlspecialchars($_POST['contact']);
    $appointmentDate = htmlspecialchars($_POST['appointmentDate']);
    $appointmentTime = htmlspecialchars($_POST['appointmentTime']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
</head>

<body>
    <header class="navbar">
        <div class="container">
            <h1 class="logo">VETZ</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="book-appointment.html">Book Appointment</a></li>
                    <li><a href="follow-up.html">Follow-up</a></li>
                    <li><a href="pet-care.html">Pet-Care</a></li>
                    <li><a href="patient-history.html">Patient History</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container confirmation">
        <h2>Appointment Confirmation</h2>
        <p>Thank you, <?php echo $ownerName; ?>. Your appointment for <?php echo $petName; ?> has been booked.</p>
        <p><strong>Contact:</strong> <?php echo $contact; ?></p>
        <p><strong>Date:</strong> <?php echo $appointmentDate; ?></p>
        <p><strong>Time:</strong> <?php echo $appointmentTime; ?></p>
    </div>
</body>

</html>