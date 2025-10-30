<?php
class DivisiModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // === READ: Ambil semua data divisi ===
    public function getAllDivisi() {
        $query = "SELECT * FROM divisitim ORDER BY kode";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    // === CREATE: Tambah data divisi baru ===
    public function insert($data) {
        $query = "INSERT INTO divisitim (kode, nama) VALUES (:kode, :nama)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kode", $data['kode']);
        $stmt->bindParam(":nama", $data['nama']);
        return $stmt->execute();
    }

    // === READ: Ambil detail divisi by ID ===
    public function getDivisiById($id) {
        $query = "SELECT * FROM divisitim WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === UPDATE: Edit data divisi ===
    public function update($id, $data) {
        $query = "UPDATE divisitim 
                  SET kode = :kode, nama = :nama 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":kode", $data['kode']);
        $stmt->bindParam(":nama", $data['nama']);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // === DELETE: Hapus data divisi ===
    public function deleteDivisi($id) {
        try {
            // Set divisi_id jadi NULL di tabel users
            $queryUpdate = "UPDATE users SET divisi_id = NULL WHERE divisi_id = :id";
            $stmtUpdate = $this->conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Baru hapus divisi
            $queryDelete = "DELETE FROM divisitim WHERE id = :id";
            $stmtDelete = $this->conn->prepare($queryDelete);
            $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmtDelete->execute();
        } catch (PDOException $e) {
            error_log("Delete Divisi Error: " . $e->getMessage());
            return false;
        }
    }

}
