<?php
// index.php
// Dashboard/Home Page

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

// Retrieve the user's role from the session
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <!-- Display a welcome message -->
    <h1>Welcome to the Staff Management System</h1>
    <p>You are logged in as <strong><?php echo htmlspecialchars($role); ?></strong>.</p>

    <!-- Navigation menu -->
    <nav class="mt-4">
        <ul class="nav nav-pills">
            <?php if ($role === 'Admin'): ?>
                <!-- Admin-specific navigation options -->
                <li class="nav-item">
                    <a class="nav-link" href="staff_list.php">Manage Staff</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_staff.php">Add Staff</a>
                </li>
            <?php endif; ?>
            <!-- Common navigation options for all users -->
            <li class="nav-item">
                <a class="nav-link" href="profile.php">My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>
</div>
</body>
</html>