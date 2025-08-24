<?php
class DivisiModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllDivisi() {
        $query = "SELECT * FROM divisitim";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
