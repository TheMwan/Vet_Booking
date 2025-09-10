<?php
session_start();
include 'db_connect.php'; // Include your database connection file

if (isset($_POST['Login'])) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // Query the database to get the user info
    $sql = "SELECT * FROM User WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashedPassword = $user['Password'];

        // Verify the password entered by the user
        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['UserID'] = $user['UserID']; // Set the UserID
            $_SESSION['Email'] = $user['Email'];

            // Check if the logged-in user is an admin (using the hardcoded admin email)
            if ($user['Email'] === 'vet@admin.co.th') {
                // Redirect to admin dashboard
                echo "<script>alert('Welcome Admin!'); window.location.href='Admin/admin-dashboard.html';</script>";
            } else {
                // Redirect to the pet data page (display_pet_data.php)
                echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href='login.html';</script>"; // Invalid login
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='login.html';</script>"; // No user found
    }

    $stmt->close();
    $conn->close();
}
?>
