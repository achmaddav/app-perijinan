<?php
class JabatanModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getNamaJabatanByKode($kode) {
        $query = "
            SELECT nama
            FROM jabatan j
            WHERE j.kode = :kode
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':kode', $kode, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // karena satu data
    }
    
    public function getAllPosition() {
        $query = "SELECT * FROM jabatan d";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
