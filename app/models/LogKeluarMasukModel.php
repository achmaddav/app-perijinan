<?php
class LogKeluarMasukModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertKeluar($perizinan_id, $satpam_id)
    {
        $tanggal_keluar = date("Y-m-d H:i:s"); // Set waktu sekarang
        $sql = "INSERT INTO log_keluar_masuk (perizinan_id, satpam_id, tanggal_keluar) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$perizinan_id, $satpam_id, $tanggal_keluar]);
    }

    public function updateMasuk($id)
    {
        $tanggal_masuk = date("Y-m-d H:i:s"); // Set waktu sekarang
        $sql = "UPDATE log_keluar_masuk SET tanggal_masuk = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tanggal_masuk, $id]);
    }

    public function findLogByPerizinanId($perizinan_id)
    {
        $sql = "SELECT * FROM log_keluar_masuk WHERE perizinan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$perizinan_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}