<?php
class PerizinanNonApprovalModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getNonPerizinanRequests() {        
        $query = "SELECT 
                    l.id, 
                    u.nama AS nama_pengaju, 
                    l.tanggal_keluar, 
                    l.tanggal_masuk
                  FROM log_keluar_masuk_non_perizinan l
                  JOIN users u ON l.user_id = u.id
                  WHERE DATE(l.created_at) = CURDATE()";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findLogByNonPerizinanId($non_perizinan_id) 
    {
        $sql = "SELECT * FROM log_keluar_masuk_non_perizinan WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$non_perizinan_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function updateMasukNonPerizinan($id)
    {
        try {
            $sql = "UPDATE log_keluar_masuk_non_perizinan SET tanggal_masuk = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            
            // Bind parameter untuk keamanan
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            
            $stmt->execute();

            // Pastikan data benar-benar diperbarui
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return $e->getMessage(); // Kembalikan pesan error jika gagal
        }
    }

    public function insertNonPerizinan($user_id, $satpam_id, $waktu_keluar) 
    {
        try {
            $query = "INSERT INTO log_keluar_masuk_non_perizinan (user_id, satpam_id, tanggal_keluar) 
                      VALUES (:user_id, :satpam_id, :waktu_keluar)";
            
            $stmt = $this->conn->prepare($query);
            
            // Bind parameter untuk keamanan
            $stmt->bindValue(':user_id', (int) $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':satpam_id', (int) $satpam_id, PDO::PARAM_INT);
            $stmt->bindValue(':waktu_keluar', $waktu_keluar, PDO::PARAM_STR); // Pastikan format datetime valid
            
            $stmt->execute();
    
            // Pastikan data benar-benar masuk
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Insert Non-Perizinan Error: " . $e->getMessage()); // Logging untuk debugging
            return false; // Hindari mengembalikan pesan error langsung ke frontend
        }
    }

    public function getHistoryNonPerizinan() {
        $query = "SELECT 
                    l.id, 
                    u.nama AS nama_pemohon, 
                    l.tanggal_keluar AS waktu_keluar, 
                    l.tanggal_masuk AS waktu_masuk
                  FROM log_keluar_masuk_non_perizinan l
                  JOIN users u ON l.user_id = u.id
                  WHERE l.tanggal_keluar IS NOT NULL
                    AND l.tanggal_masuk IS NOT NULL";
                    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}