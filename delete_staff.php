<?php
// delete_staff.php
// Page for Admin users to delete staff records

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
    echo "Access denied. Only Admins can delete staff.";
    exit;
}

// Check if a staff ID is provided in the query string
if (!isset($_GET['id'])) {
    echo "No staff ID provided.";
    exit;
}

$staff_id = intval($_GET['id']);

// Start a transaction to delete the staff record
$conn->begin_transaction();

try {
    // Delete the user record associated with the staff member
    $stmt = $conn->prepare("DELETE FROM users WHERE id = (SELECT user_id FROM staff WHERE id = ?)");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();

    // Delete the staff record
    $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Redirect to the staff list with a success message
    header("Location: staff_list.php?message=Staff member deleted successfully.");
    exit;
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $conn->rollback();
    echo "Error deleting staff member: " . $e->getMessage();
    exit;
}
?>