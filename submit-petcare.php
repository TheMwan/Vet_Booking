<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care Submission</title>
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

    <!-- Pet Care Confirmation -->
    <div class="container confirmation">
        <?php
        // Assuming you have already connected to your database
        include 'database_connection.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $petId = $_POST['petId'];
            $feedingSchedule = $_POST['feedingSchedule'];
            $exerciseRequirements = $_POST['exerciseRequirements'];
            $groomingTips = $_POST['groomingTips'];

            // SQL query to insert data into the database
            $sql = "INSERT INTO petcare (petId, feedingSchedule, exerciseRequirements, groomingTips) VALUES ('$petId', '$feedingSchedule', '$exerciseRequirements', '$groomingTips')";

            if (mysqli_query($conn, $sql)) {
                echo "<h2>Pet Care Information Submitted Successfully</h2>";
                echo "<p>Thank you for updating the pet care information for Pet ID <strong>$petId</strong>.</p>";
            } else {
                echo "<h2>Error Submitting Pet Care Information</h2>";
                echo "<p>There was an error: " . mysqli_error($conn) . "</p>";
            }
            mysqli_close($conn);
        }
        ?>
    </div>
</body>
</html>
