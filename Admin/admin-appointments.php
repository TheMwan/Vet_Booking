<?php
session_start();
include('db_connection.php'); // Include the database connection file

// Handle cancel (delete) action
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $appointmentID = intval($_POST['AppointmentID']);

    // Delete the appointment from the database
    $deleteQuery = "DELETE FROM Appointment WHERE AppointmentID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $appointmentID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Appointment canceled successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error canceling the appointment: ' . $stmt->error]);
    }

    $stmt->close();
    exit();
}

// Fetch appointments
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
    <title>Admin - Manage Appointments</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f9fc;
            color: #333;
        }

        .navbar {
            background-color: #013251;
            padding: 15px 0;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-links {
            list-style-type: none;
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #00aaff;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #013251;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .admin-table th, .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .admin-table th {
            background-color: #013251;
            color: white;
        }

        .admin-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .admin-table tr:hover {
            background-color: #f1f1f1;
        }

        .admin-table button {
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .admin-table button.danger {
            background-color: #d9534f;
            color: white;
        }

        .admin-table button.danger:hover {
            background-color: #c9302c;
        }

        .message {
            padding: 10px;
            border: 1px solid transparent;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container">
            <div class="logo">VETZ Admin</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="admin-dashboard.html">Dashboard</a></li>
                    <li><a href="admin-appointments.php">View Appointments</a></li>
                    <li><a href="admin-follow-up.html">Follow Up</a></li>
                    <li><a href="admin-pet-care.html">Pet Care</a></li>
                    <li><a href="admin-user.html">Admin</a></li>
                    <li><a href="user_report.php">Report</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h2>Manage Appointments</h2>
        <div id="message" class="message" style="display: none;"></div>
        <table class="admin-table" id="appointmentTable">
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
                    <tr id="row-<?= $row['AppointmentID']; ?>">
                        <td><?= $row['AppointmentID']; ?></td>
                        <td><?= $row['petName']; ?></td>
                        <td><?= $row['OwnerName']; ?></td>
                        <td><?= $row['Contact']; ?></td>
                        <td><?= $row['AppointmentDate']; ?></td>
                        <td><?= $row['AppointmentTime']; ?></td>
                        <td>
                            <button class="danger" onclick="cancelAppointment(<?= $row['AppointmentID']; ?>)">Cancel</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function cancelAppointment(appointmentID) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                fetch('admin-appointments.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'delete', AppointmentID: appointmentID }),
                })
                .then(response => response.json())
                .then(data => {
                    const messageBox = document.getElementById('message');
                    if (data.success) {
                        messageBox.textContent = data.message;
                        messageBox.className = 'message success';
                        document.getElementById(`row-${appointmentID}`).remove();
                    } else {
                        messageBox.textContent = data.message;
                        messageBox.className = 'message error';
                    }
                    messageBox.style.display = 'block';
                })
                .catch(error => {
                    alert('An error occurred: ' + error);
                });
            }
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
