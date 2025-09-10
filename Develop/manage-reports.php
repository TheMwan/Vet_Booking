<?php
session_start();
if (!isset($_SESSION['developer_logged_in'])) {
    header('Location: dev-login.php');
    exit;
}

include 'db_connect.php'; // Ensure this file connects to the correct database

// Fetch all reports
$query = "SELECT r.report_id, r.UserID , r.Email ,r.title, r.Notes, r.status, r.created_at 
          FROM Reports r "; // Correct table and column names
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer - Manage Reports</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function viewReport(report_id) {
            window.location.href = `view-report.php?report_id=${report_id}`;
        }

        function deleteReport(report_id) {
            if (confirm("Are you sure you want to delete this report?")) {
                fetch('delete-report.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        report_id: report_id,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Report deleted successfully!");
                        document.getElementById(`report-row-${report_id}`).remove(); // Remove the row
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => alert("Error: " + error));
            }
        }

        function updateStatus(report_id, status) {
            fetch('update-status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    report_id: report_id,
                    status: status,
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Status updated successfully!");
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => alert("Error: " + error));
        }
    </script>
</head>
<body>
    <header class="navbar">
        <div class="logo">VETZ Reports</div>
        <ul class="nav-links">
            <li><a href="manage-reports.php">Manage Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </header>

    <div class="container">
        <h2>Manage Reports</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>Title</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr id="report-row-<?php echo $row['report_id']; ?>">
                    <td><?php echo $row['report_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['UserID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['Notes']); ?></td>
                    <td>
                        <select onchange="updateStatus(<?php echo $row['report_id']; ?>, this.value)">
                            <option value="Not Started" <?php echo $row['status'] === 'Not Started' ? 'selected' : ''; ?>>Not Started</option>
                            <option value="In Progress" <?php echo $row['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Done" <?php echo $row['status'] === 'Done' ? 'selected' : ''; ?>>Done</option>
                        </select>
                    </td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <button onclick="viewReport(<?php echo $row['report_id']; ?>)">View</button>
                        <button onclick="deleteReport(<?php echo $row['report_id']; ?>)" class="danger">Delete</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <style>
        /* Style for the container that holds the title and the table */
.container {
    width: 100%;
}

/* Style for the h2 to match the width of the table */
h2 {
    width: 100%;
    text-align: left; /* Adjust text alignment if needed */
}

/* Style for the table to ensure it stretches to full width */
.admin-table {
    width: 100%;
    border-collapse: collapse; /* Makes sure the table looks neat */
}

/* Style for table headers and cells */
.admin-table th, .admin-table td {
    padding: 10px;
    border: 1px solid #ddd; /* You can customize the border */
}

/* Optional: Add some margin or padding to space things out */
h2 {
    margin-bottom: 20px;
}

        </style>
</body>
</html>
