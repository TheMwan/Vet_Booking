<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow-Up Submission</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="container">
            <h1 class="logo">VETZ</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="book-appointment.html">Book Appointment</a></li>
                    <li><a href="update-pet.html">Update Pet Info</a></li>
                    <li><a href="login.html">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Follow-Up Confirmation -->
    <div class="container confirmation">
        <?php
        // submit-followup.php
        include 'db_connect.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect form data
            $petName = $_POST['petName'];
            $lastTreatmentDate = $_POST['lastTreatmentDate'];
            $recoveryStatus = $_POST['recoveryStatus'];
            $nextCheckUpDate = $_POST['nextCheckUpDate'];
            $nextCheckUpTime = $_POST['nextCheckUpTime'];
            $specialInstructions = $_POST['specialInstructions'];

            // Insert data into the follow_up table
            $sql = "INSERT INTO follow_up (pet_name, last_treatment_date, recovery_status, next_checkup_date, next_checkup_time, special_instructions)
            VALUES ('$petName', '$lastTreatmentDate', '$recoveryStatus', '$nextCheckUpDate', '$nextCheckUpTime', '$specialInstructions')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='confirmation'><h2>Follow-Up Saved Successfully!</h2><p>Details for $petName have been saved.</p></div>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
        ?>

    </div>
</body>

</html>