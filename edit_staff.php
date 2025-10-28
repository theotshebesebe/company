<?php
// edit_staff.php
// Page for editing staff details (Admin can edit any staff, Staff can edit their own profile)

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

// Get the logged-in user's role and ID
$role = $_SESSION['role'];
$logged_in_user_id = $_SESSION['user_id'];

// Initialize variables for error and success messages
$error = '';
$success = '';

// Check if a staff ID is provided in the query string
if (!isset($_GET['id'])) {
    echo "No staff ID provided.";
    exit;
}

$staff_id = intval($_GET['id']);

// Fetch the staff details from the database
$stmt = $conn->prepare("SELECT staff.*, users.username FROM staff JOIN users ON staff.user_id = users.id WHERE staff.id = ?");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Staff member not found.";
    exit;
}

$staff = $result->fetch_assoc();

// Check if the logged-in user has permission to edit this staff member
if ($role !== 'Admin' && $staff['user_id'] !== $logged_in_user_id) {
    echo "Access denied. You can only edit your own profile.";
    exit;
}

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
    if (!empty($name) && !empty($department) && !empty($contact_info) && !empty($position) && !empty($username)) {
        // Update the `users` table
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $staff['user_id']);
        $stmt->execute();

        // Update the `staff` table
        $stmt = $conn->prepare("UPDATE staff SET name = ?, department = ?, contact_info = ?, position = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $department, $contact_info, $position, $staff_id);
        $stmt->execute();

        // Update the password if provided
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $staff['user_id']);
            $stmt->execute();
        }

        $success = "Staff details updated successfully.";
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
    <title>Edit Staff</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Staff</h1>
    <p>Use the form below to edit staff details:</p>

    <!-- Display error or success messages -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Form for editing staff -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($staff['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" name="department" id="department" class="form-control" value="<?php echo htmlspecialchars($staff['department']); ?>" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" name="contact_info" id="contact_info" class="form-control" value="<?php echo htmlspecialchars($staff['contact_info']); ?>" required>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" id="position" class="form-control" value="<?php echo htmlspecialchars($staff['position']); ?>" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($staff['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Staff</button>
    </form>

    <!-- Link to go back to the staff list -->
    <a href="staff_list.php" class="btn btn-secondary mt-3">Back to Staff List</a>
</div>
</body>
</html>