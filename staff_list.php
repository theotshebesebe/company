<?php
// Page for Admin users to view all staff records

// Start the session to access session variables
session_start();

// Include the database connection file
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit;
}

// Check if the logged-in user is an Admin
if ($_SESSION['role'] !== 'Admin') {
    // If the user is not an Admin, deny access and display an error message
    echo "Access denied. Only Admins can view this page.";
    exit;
}

// Fetch all staff records from the database
$query = "SELECT staff.id, staff.name, staff.department, staff.contact_info, staff.position, users.username 
          FROM staff 
          JOIN users ON staff.user_id = users.id";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    die("Error fetching staff records: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Staff List</h1>
    <p>Below is the list of all staff members:</p>

    <!-- Table to display staff records -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Contact Info</th>
                <th>Position</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Link to go back to the dashboard -->
    <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
</div>
</body>
</html>