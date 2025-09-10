<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$db = 'Vet';
$user = 'root';
$password = 'root';

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed.']));
}

// Assume user is logged in, and the email is stored in session
session_start();

// Check if the email is stored in the session
if (!isset($_SESSION['Email'])) {
    echo json_encode(['error' => 'User is not logged in.']);
    exit;
}

// Retrieve the logged-in user's email from the session
$Email = $_SESSION['Email'];

// Fetch the UserID associated with the email
$query = "SELECT UserID FROM User WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $Email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'User not found.']);
    exit;
}

$userRow = $result->fetch_assoc();
$userId = $userRow['UserID'];
$petId = isset($_GET['petId']) ? $_GET['petId'] : '';


// Validate input
if (empty($petId)) {
    echo json_encode(['error' => 'Pet ID is required.']);
    exit;
}

// Prepare and execute query to fetch pet data for the logged-in user
$query = "
    SELECT 
        p.PatientID, 
        p.petName,
        GROUP_CONCAT(DISTINCT a.AppointmentID) AS AppointmentIDs,
        GROUP_CONCAT(DISTINCT a.AppointmentDate) AS AppointmentDates,
        GROUP_CONCAT(DISTINCT f.FollowUpID) AS FollowUpIDs,
        GROUP_CONCAT(DISTINCT f.NextCheckUpDate) AS FollowUpDates,
        GROUP_CONCAT(DISTINCT t.TreatmentPlanID) AS TreatmentPlanIDs,
        GROUP_CONCAT(DISTINCT t.HealthStatus) AS HealthStatuses
    FROM Patient p
    LEFT JOIN Appointment a ON p.PatientID = a.PatientID
    LEFT JOIN FollowUp f ON p.PatientID = f.PatientID
    LEFT JOIN TreatmentPlan t ON p.PatientID = t.PatientID
    WHERE p.PatientID = ? AND p.UserID = ?
    GROUP BY p.PatientID
";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $petId, $userId);  // Use prepared statement to avoid SQL injection
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $data = [
        'PatientID' => $row['PatientID'],
        'petName' => $row['petName'],
        'appointments' => [],
        'followups' => [],
        'treatmentPlans' => []
    ];

    // Split grouped data into arrays
    if ($row['AppointmentIDs']) {
        $appointmentIDs = explode(',', $row['AppointmentIDs']);
        $appointmentDates = explode(',', $row['AppointmentDates']);
        foreach ($appointmentIDs as $index => $id) {
            $data['appointments'][] = [
                'AppointmentID' => $id,
                'AppointmentDate' => $appointmentDates[$index]
            ];
        }
    }

    if ($row['FollowUpIDs']) {
        $followUpIDs = explode(',', $row['FollowUpIDs']);
        $followUpDates = explode(',', $row['FollowUpDates']);
        foreach ($followUpIDs as $index => $id) {
            $data['followups'][] = [
                'FollowUpID' => $id,
                'NextCheckUpDate' => $followUpDates[$index]
            ];
        }
    }

    if ($row['TreatmentPlanIDs']) {
        $treatmentPlanIDs = explode(',', $row['TreatmentPlanIDs']);
        $healthStatuses = explode(',', $row['HealthStatuses']);
        foreach ($treatmentPlanIDs as $index => $id) {
            $data['treatmentPlans'][] = [
                'TreatmentPlanID' => $id,
                'HealthStatus' => $healthStatuses[$index]
            ];
        }
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'No data found for the provided Pet ID. Please enter correct ID.']);
}

$conn->close();
?>
