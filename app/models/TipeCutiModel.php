<?php
class TipeCutiModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mengambil list tipe cuti
    public function getTipeCutiList() {
        $query = "SELECT *
                  FROM tipe_cuti t 
                  ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  
}
