<?php
// add_staff.php
// Page for Admin users to add new staff members

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
    echo "Access denied. Only Admins can add staff.";
    exit;
}

// Initialize variables for error and success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $contact_info = trim($_POST['contact_info']);
    $position = trim($_POST['position']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($name) && !empty($department) && !empty($contact_info) && !empty($position) && !empty($username) && !empty($password)) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Start a transaction to insert into both `users` and `staff` tables
        $conn->begin_transaction();

        try {
            // Insert into `users` table
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'Staff')");
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();
            $user_id = $stmt->insert_id;

            // Insert into `staff` table
            $stmt = $conn->prepare("INSERT INTO staff (name, department, contact_info, position, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $name, $department, $contact_info, $position, $user_id);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();
            $success = "Staff member added successfully.";
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $conn->rollback();
            $error = "Error adding staff member: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Add Staff</h1>
    <p>Use the form below to add a new staff member:</p>

    <!-- Display error or success messages -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Form for adding staff -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" name="department" id="department" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" name="contact_info" id="contact_info" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" id="position" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Staff</button>
    </form>

    <!-- Link to go back to the staff list -->
    <a href="staff_list.php" class="btn btn-secondary mt-3">Back to Staff List</a>
</div>
</body>
</html>