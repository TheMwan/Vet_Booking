<?php
session_start();
if (!isset($_SESSION['developer_logged_in'])) {
    header('Location: dev-login.php');
    exit;
}

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = intval($_POST['report_id']);

    $query = "DELETE FROM Reports WHERE report_id = $report_id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
    exit;
}
