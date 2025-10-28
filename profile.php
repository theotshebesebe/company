<?php
// profile.php
// Page for Staff users to view and update their own profile

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

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch the user's profile details from the database
$stmt = $conn->prepare("SELECT staff.*, users.username FROM staff JOIN users ON staff.user_id = users.id WHERE users.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Profile not found.";
    exit;
}

$profile = $result->fetch_assoc();

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
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($name) && !empty($department) && !empty($contact_info) && !empty($position)) {
        // Update the `staff` table
        $stmt = $conn->prepare("UPDATE staff SET name = ?, department = ?, contact_info = ?, position = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $name, $department, $contact_info, $position, $user_id);
        $stmt->execute();

        // Update the password if provided
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            $stmt->execute();
        }

        $success = "Profile updated successfully.";
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
    <title>My Profile</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>My Profile</h1>
    <p>Use the form below to update your profile details:</p>

    <!-- Display error or success messages -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Form for updating profile -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($profile['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" name="department" id="department" class="form-control" value="<?php echo htmlspecialchars($profile['department']); ?>" required>
        </div>
        <div class="form-group">
            <label for="contact_info">Contact Info</label>
            <input type="text" name="contact_info" id="contact_info" class="form-control" value="<?php echo htmlspecialchars($profile['contact_info']); ?>" required>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" id="position" class="form-control" value="<?php echo htmlspecialchars($profile['position']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <!-- Link to go back to the dashboard -->
    <a href="index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>