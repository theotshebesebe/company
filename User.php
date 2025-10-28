<?php
// User.php
// This class encapsulates user-related operations such as login and role checking.

class User {
    private $db; // Holds the database connection instance

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to handle user login
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the entered password against the hashed password in the database
            if (password_verify($password, $user['password'])) {
                return $user; // Return user details if login is successful
            }
        }
        return null; // Return null if login fails
    }

    // Method to check if the user is an admin
    public function isAdmin($role) {
        return $role === 'Admin';
    }
}
?>