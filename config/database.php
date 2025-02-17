<?php
class Database {
    private static $instance = null;
    private $conn;
    private $host = "localhost";
    private $db_name = "perijinandb";
    private $username = "root";
    private $password = "";

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", 
                                  $this->username, 
                                  $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
    }

    // Metode untuk mendapatkan instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Metode untuk mendapatkan koneksi PDO
    public function getConnection() {
        return $this->conn;
    }
}
?>
