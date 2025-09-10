<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['Email'])) {
    // If not logged in, redirect to the login page
    header("Location: login.html");
    exit();
}

$user_email = $_SESSION['Email']; // Get the logged-in user's email

// Prepare SQL to fetch user details
$sql = "SELECT * FROM User WHERE Email = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

// Bind the email parameter
$stmt->bind_param("s", $user_email); // "s" is for string

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if the user was found
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    // User not found (this shouldn't happen if the session is correct)
    echo "<script>alert('User not found. Please login again.');</script>";
    session_destroy();
    header("Location: login.html");
    exit();
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Check the form data
    var_dump($_POST); // This will print out all form data (useful for debugging)

    $username = isset($_POST['UserName']) ? trim($_POST['UserName']) : '';
    $name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
    $email = isset($_POST['Email']) ? filter_var(trim($_POST['Email']), FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['Phone']) ? trim($_POST['Phone']) : '';

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        // Check if the username already exists in the database (excluding the current user's email)
        $check_username_query = "SELECT * FROM User WHERE UserName = ? AND Email != ?";
        $check_username_stmt = $conn->prepare($check_username_query);
        
        if ($check_username_stmt === false) {
            die("Error preparing SQL statement: " . $conn->error);
        }

        $check_username_stmt->bind_param("ss", $username, $user_email);
        $check_username_stmt->execute();
        $check_username_result = $check_username_stmt->get_result();

        if ($check_username_result->num_rows > 0) {
            // If the username already exists
            echo "<script>alert('Username already exists. Please choose a different username.');window.location.href = 'admin-user.html';</script>";
        } else {
            // Check if the email already exists in the database (excluding the current user's email)
            $check_email_query = "SELECT * FROM User WHERE Email = ? AND Email != ?";
            $check_email_stmt = $conn->prepare($check_email_query);
            
            if ($check_email_stmt === false) {
                die("Error preparing SQL statement: " . $conn->error);
            }

            $check_email_stmt->bind_param("ss", $email, $user_email);
            $check_email_stmt->execute();
            $check_email_result = $check_email_stmt->get_result();

            if ($check_email_result->num_rows > 0) {
                // If the email already exists
                echo "<script>alert('Email already exists. Please choose a different email.');window.location.href = 'admin-user.html';</script>";
            } else {
                // Check if any data has changed
                if ($username == $user['UserName'] && $name == $user['Name'] && $email == $user['Email'] && $phone == $user['Phone']) {
                    // If no changes, just redirect to user.html without echoing anything
                    header("Location: admin-user.html");
                    exit();
                } else {
                    // Proceed with the update since there is a change in data
                    $update_query = "UPDATE User SET UserName = ?, Name = ?, Email = ?, Phone = ? WHERE Email = ?";
                    $update_stmt = $conn->prepare($update_query);

                    if ($update_stmt === false) {
                        die("Error preparing SQL statement: " . $conn->error);
                    }

                    // Bind parameters for the update query
                    $update_stmt->bind_param("sssss", $username, $name, $email, $phone, $user_email);

                    if ($update_stmt->execute()) {
                         header("Location: admin-user.html");
                        exit();
                    } else {
                        // Error updating the profile
                        echo "<script>alert('Error updating profile: " . $update_stmt->error . "');window.location.href = 'admin-user.html';</script>";
                    }
                }
            }
        }
    }
}
?>
