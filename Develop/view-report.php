<?php
session_start();
if (!isset($_SESSION['developer_logged_in'])) {
    header('Location: dev-login.php');
    exit;
}

include 'db_connect.php';

$report_id = intval($_GET['report_id']);
$query = "SELECT r.*
          FROM Reports r 
          WHERE r.report_id = $report_id";

$result = mysqli_query($conn, $query);
$report = mysqli_fetch_assoc($result);

if (!$report) {
    echo "Report not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Report Details</h2>
        <form action="manage-reports.php" method="POST">
            <div>
                <label for="report_id">Report ID:</label>
                <input type="text" id="report_id" name="report_id" value="<?php echo $report['report_id']; ?>" readonly>
            </div>
            <div>
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($report['UserID']); ?>" readonly>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($report['Email']); ?>" readonly>
            </div>
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($report['title']); ?>" readonly>
            </div>
            <div>
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" readonly><?php echo htmlspecialchars($report['Notes']); ?></textarea>
            </div>
            <div>
                <label for="status">Status:</label>
                <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($report['status']); ?>" readonly>
            </div>
            <div>
                <label for="created_at">Created At:</label>
                <input type="text" id="created_at" name="created_at" value="<?php echo $report['created_at']; ?>" readonly>
            </div>
           
        </form>
    </div>
</body>
</html>
