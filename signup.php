<?php
session_start();
include 'db_connect.php'; 

// Handle registration logic
if (isset($_POST['register'])) {
    // Sanitize and validate inputs
    $username = filter_var($_POST['UserName'], FILTER_SANITIZE_SPECIAL_CHARS);
    $name = filter_var($_POST['Name'], FILTER_SANITIZE_SPECIAL_CHARS);     
    $phone = filter_var($_POST['Phone'], FILTER_SANITIZE_SPECIAL_CHARS);      
    $email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);             
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format. Please enter a valid email address.'); window.location.href='signup.html';</script>";
        exit;
    }

    $password = $_POST['Password']; // Password entered by the user
    $confirmPassword = $_POST['confirmPassword']; // Password confirmation

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href='signup.html';</script>";
        exit;
    }

    // Check if password length is at least 8 characters
    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.'); window.location.href='signup.html';</script>";
        exit;
    }

    // Check if user already exists (check by email)
    $sql_check = "SELECT * FROM User WHERE Email = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check === false) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt_check->bind_param("s", $email); // Bind email to the query
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Email already exists
        echo "<script>alert('Email already taken. Please use a different email.'); window.location.href='signup.html';</script>";
        exit;
    } else {
        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database (exclude UserID since it's AUTO_INCREMENT)
        $sql = "INSERT INTO User (UserName, Name, Phone, Email, Password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            // Error preparing the statement
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        $stmt->bind_param("sssss", $username, $name, $phone, $email, $hashedPassword);

        // Execute the insert query
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.html';</script>";
            exit; // Prevent further code execution
        } else {
            echo "Error executing statement: " . $stmt->error;
            exit;
        }

        // Close the statement
        $stmt->close();
    }
}
?>
