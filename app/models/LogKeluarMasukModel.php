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

    public function getHistoryOutIn() {
        $query = "SELECT 
                    l.id, 
                    up.nama AS nama_pemohon, 
                    p.tanggal_rencana_keluar, 
                    p.alasan,  
                    us.nama AS nama_satpam,
                    l.tanggal_keluar, 
                    l.tanggal_masuk
                  FROM log_keluar_masuk l
                  JOIN perizinan p ON l.perizinan_id = p.id
                  JOIN users us ON l.satpam_id = us.id
                  JOIN users up ON p.user_id = up.id
                  WHERE l.tanggal_keluar IS NOT NULL
                    AND l.tanggal_masuk IS NOT NULL";
                    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}