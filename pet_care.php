<?php
session_start();
include 'db_connect.php';

$userID = $_SESSION['UserID']; 

$query = "
    SELECT 
        p.petName, 
        tp.HealthStatus, 
        tp.VaccinationRecords, 
        tp.LastTreatmentDate, 
        tp.NextCheckUpDate, 
        tp.NextCheckUpTime, 
        tp.SpecialInstructions
    FROM TreatmentPlan tp
    INNER JOIN Patient p ON tp.PatientID = p.PatientID
    WHERE p.UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care Details</title>
    <link rel="stylesheet" href="styles1.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #013251;
            color: white;
        }

        table td {
            color: #333;
        }

        .no-data {
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="container">
            <h1 class="logo">VETZ</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="book-appointment.html">Book Appointment</a></li>
                    <li><a href="follow-up.php">Follow Up</a></li>
                    <li><a href="pet_care.php">Pet Care</a></li>
                    <li><a href="patient-history.html">Patient History</a></li>
                    <li><a href="user.html">Profile</a></li>
                    <li><a href="user_report.php">Report</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Pet Care Details</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Pet Name</th>
                        <th>Health Status</th>
                        <th>Vaccination Records</th>
                        <th>Last Treatment Date</th>
                        <th>Next Appointment Date</th>
                        <th>Next Appointment Time</th>
                        <th>Special Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['petName']); ?></td>
                            <td><?php echo htmlspecialchars($row['HealthStatus']); ?></td>
                            <td><?php echo htmlspecialchars($row['VaccinationRecords']); ?></td>
                            <td><?php echo htmlspecialchars($row['LastTreatmentDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['NextCheckUpDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['NextCheckUpTime']); ?></td>
                            <td><?php echo htmlspecialchars($row['SpecialInstructions']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No pet care details found for your account.</p>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>