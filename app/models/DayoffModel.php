<?php
class DayoffModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Ambil semua dayoff, optional filter tahun
    public function getAll($year = null) {
        $sql = "SELECT * FROM dayoff";
        if ($year) {
            $sql .= " WHERE YEAR(tanggal) = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$year]);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah dayoff baru
    public function insert($tanggal, $description) {
        $sql = "INSERT INTO dayoff (tanggal, keterangan) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tanggal, $description]);
    }

    // Ambil dayoff berdasarkan id
    public function getById($id) {
        $sql = "SELECT * FROM dayoff WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update dayoff
    public function update($id, $tanggal, $description) {
        $sql = "UPDATE dayoff SET tanggal = ?, keterangan = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tanggal, $description, $id]);
    }

    // Hapus dayoff
    public function delete($id) {
        $sql = "DELETE FROM dayoff WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
