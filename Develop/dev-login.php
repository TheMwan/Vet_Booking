<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Direct login for "vetzdev"
    if ($username === 'vetzdev' && $password === 'vetzdev') {
        $_SESSION['developer_logged_in'] = true;
        header('Location: manage-reports.php');
        exit;
    }

    // Verify user credentials from the database
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['developer_logged_in'] = true;
        header('Location: manage-reports.php');
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>