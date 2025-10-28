<?php
// Database.php
// This class encapsulates the database connection logic.

class Database {
    private $host = 'localhost';
    private $dbname = 'company';
    private $username = 'root';
    private $password = '';
    private $conn; // Holds the database connection instance

    // Method to establish and return the database connection
    public function connect() {
        if ($this->conn === null) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }
}
?>