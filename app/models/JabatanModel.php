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
        $query = "SELECT * FROM jabatan ORDER BY nama";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // Ambil berdasarkan ID
    public function getById($id) {
        $query = "SELECT * FROM jabatan WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ambil semua data
    public function getAll() {
        $query = "SELECT * FROM jabatan ORDER BY nama";
        return $this->conn->query($query);
    }

    // Tambah data
    public function insert($data) {
        $query = "INSERT INTO jabatan (kode, nama) VALUES (:kode, :nama)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kode", $data['kode']);
        $stmt->bindParam(":nama", $data['nama']);
        return $stmt->execute();
    }

    // Update data
    public function update($id, $data) {
        $query = "UPDATE jabatan 
                  SET kode = :kode, nama = :nama 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kode", $data['kode']);
        $stmt->bindParam(":nama", $data['nama']);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Hapus data
    public function delete($id) {
        $query = "DELETE FROM jabatan WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
