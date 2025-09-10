<?php
session_start();
include('db_connection.php'); // Include the database connection file

// Handle cancel (delete) action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $appointmentID = intval($_POST['AppointmentID']);

    // Prepare and execute delete query
    $deleteQuery = "DELETE FROM Appointment WHERE AppointmentID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $appointmentID);

    if ($stmt->execute()) {
        $message = "Appointment canceled successfully.";
    } else {
        $message = "Error canceling the appointment: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch the updated appointments table
$query = "SELECT AppointmentID, petName, OwnerName, Contact, AppointmentDate, AppointmentTime FROM Appointment";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Updated Appointments</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f9fc;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #013251;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .navbar .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .navbar .nav-links a:hover {
            color: #00aaff;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message {
            background-color: #eafaf1;
            border: 1px solid #28a745;
            padding: 10px;
            margin-bottom: 20px;
            color: #155724;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #013251;
            color: white;
            text-align: left;
            padding: 10px;
        }

        td {
            padding: 10px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .danger {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .danger:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <h1>VETZ Admin</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="admin-dashboard.html">Dashboard</a></li>
                <li><a href="admin-appointments.php">View Appointments</a></li>
                <li><a href="admin-followup.html">Follow Up</a></li>
                <li><a href="admin-pet-care.html">Pet Care</a></li>
                <li><a href="admin-user.html">Admin</a></li>
                <li><a href="user_report.php">Report</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Container -->
    <div class="container">
        <?php if (isset($message)) : ?>
            <div class="message">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <h2>Updated Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Pet Name</th>
                    <th>Owner Name</th>
                    <th>Contact</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['AppointmentID']); ?></td>
                        <td><?= htmlspecialchars($row['petName']); ?></td>
                        <td><?= htmlspecialchars($row['OwnerName']); ?></td>
                        <td><?= htmlspecialchars($row['Contact']); ?></td>
                        <td><?= htmlspecialchars($row['AppointmentDate']); ?></td>
                        <td><?= htmlspecialchars($row['AppointmentTime']); ?></td>
                        <td>
                            <form method="POST" action="update-booking.php" style="display:inline;">
                                <input type="hidden" name="AppointmentID" value="<?= htmlspecialchars($row['AppointmentID']); ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="danger">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
mysqli_close($conn); // Close the database connection
?>