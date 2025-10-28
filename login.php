<?php
// login.php
// Refactored to use the Database and User classes for better organization and reusability.

session_start();
require 'Database.php';
require 'User.php';

// Initialize the Database and User classes
$db = (new Database())->connect();
$user = new User($db);

$error = ''; // Variable to store error messages

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize user inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Ensure both fields are filled
    if (!empty($username) && !empty($password)) {
        // Attempt to log in the user
        $loggedInUser = $user->login($username, $password);
        if ($loggedInUser) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $loggedInUser['id'];
            $_SESSION['role'] = $loggedInUser['role'];
            // Redirect to the dashboard
            header("Location: index.php");
            exit;
        } else {
            // Invalid username or password
            $error = "Invalid username or password.";
        }
    } else {
        // Missing fields
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    <!-- Display error message if any -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <!-- Login form -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
</body>
</html>