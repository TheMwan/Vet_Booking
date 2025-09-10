<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';
date_default_timezone_set('Asia/Bangkok'); // Set timezone globally

// Function to validate email existence
function is_valid_user($conn, $email)
{
    $query = "SELECT Email, UserID FROM User WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email, $userID);
    $isValid = $stmt->num_rows > 0;
    if ($isValid) {
        $stmt->fetch(); // Fetch the UserID from the result
    }
    $stmt->close();
    return $isValid ? $userID : null;
}

// Function to check if a report exists
function report_exists($conn, $email, $title)
{
    $query = "SELECT report_id FROM Reports WHERE Email = ? AND title = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $title);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure user is logged in
    if (!isset($_SESSION['Email'])) {
        $_SESSION['message'] = "Error: User not logged in.";
        header("Location: login.html"); // Redirect to login page
        exit;
    }

    // Sanitize inputs
    $email = $conn->real_escape_string($_SESSION['Email']);
    $title = $conn->real_escape_string(trim($_POST['title']));
    $notes = $conn->real_escape_string(trim($_POST['Notes']));
    $created_at = date('Y-m-d H:i:s'); // Current time in Bangkok timezone
    $default_status = 'Not Started';

    // Get the UserID from the session
    $userID = is_valid_user($conn, $email);

    if (!$userID) {
        $_SESSION['message'] = "Error: Invalid user. Please log in again.";
        header("Location: login.html");
        exit;
    }

    try {
        // Check if the report exists
        if (report_exists($conn, $email, $title)) {
            // Update the report
            $updateQuery = "
                UPDATE Reports 
                SET Notes = ?, 
                    created_at = ?, 
                    status = IF(status IS NULL, ?, status), 
                    UserID = ?
                WHERE Email = ? AND title = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ssssss", $notes, $created_at, $default_status, $userID, $email, $title);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Report updated successfully.";
            } else {
                throw new Exception("Error executing UPDATE: " . $stmt->error);
            }
            $stmt->close();
        } else {
            // Insert a new report
            $insertQuery = "
                INSERT INTO Reports (Email, title, Notes, created_at, status, UserID)
                VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssssss", $email, $title, $notes, $created_at, $default_status, $userID);
            if ($stmt->execute()) {
                $_SESSION['message'] = "New report created successfully.";
            } else {
                throw new Exception("Error executing INSERT: " . $stmt->error);
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        // Log error details (if needed, write them to a file for debugging)
        error_log($e->getMessage(), 3, 'error.log'); 
        $_SESSION['message'] = "Error: An error occurred. Please try again.";
    } finally {
        $conn->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Report</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <style>
        .message {
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            background-color: #f0f4f8;
            border: 1px solid #ccc;
        }

        .message.success {
            color: green;
            border-color: green;
        }

        .message.error {
            color: red;
            border-color: red;
        }

        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Form Styles */
        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
            color: #013251;
        }

        form input[type="number"],
        form input[type="text"],
        form input[type="datetime-local"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        form input[type="number"]:focus,
        form input[type="text"]:focus,
        form input[type="datetime-local"]:focus {
            border-color: #00aaff;
            box-shadow: 0 0 5px rgba(0, 170, 255, 0.5);
            outline: none;
        }

        /* Button Styles */
        form button {
            width: 100%;
            padding: 12px;
            background-color: #013251;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        form button:hover {
            background-color: #00aaff;
            transform: scale(1.05);
        }

        /* Message Styles */
        .message {
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
            background-color: #f0f4f8;
            border: 1px solid #ccc;
        }

        .message.success {
            color: green;
            border-color: green;
            background-color: #e8ffe8;
        }

        .message.error {
            color: red;
            border-color: red;
            background-color: #ffe8e8;
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="container">
            <h1 class="logo">VETZ</h1>
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

    <div class="container">
        <h2>Report</h2>
        <?php
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'Error') === false ? 'success' : 'error';
            echo "<div class='message $messageClass'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>
        <form method="POST">
            <label for="Email">Email</label>
            <input type="text" name="email" id="email" placeholder="Enter your email" required>

            <label for="title">Title</label>
            <input type="text" name="title" id="title" placeholder="Enter the title" required>

            <label for="Notes">Notes</label>
            <textarea id="Notes" name="Notes" placeholder="Enter any additional notes" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <style>
    #Notes {
        width: 100%; /* Adjust the width to fit the container */
        height: 150px; /* Optional: Set a height for better visibility */
        padding: 12px;
        margin-top: 0px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        resize: vertical; /* Allow resizing vertically */
    }

    #Notes:focus {
        border-color: #00aaff;
        box-shadow: 0 0 5px rgba(0, 170, 255, 0.5);
        outline: none;
    }
</style>
</body>

</html>