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
  
}
