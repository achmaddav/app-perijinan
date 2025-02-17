<?php
class PerizinanModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mengambil daftar atasan berdasarkan jabatan
    public function getAtasanList($jabatan) {
        $stmt = $this->conn->prepare("SELECT id, nama FROM users WHERE jabatan = :jabatan");
        $stmt->bindParam(':jabatan', $jabatan, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menyimpan pengajuan perizinan
    public function insertPerizinan($user_id, $alasan, $tanggal_rencana_keluar, $durasi_keluar, $atasan_id) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO perizinan (user_id, alasan, status, tanggal_rencana_keluar, durasi_keluar, approved_by, created_at, updated_at) 
                                          VALUES (?, ?, 'Pending', ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$user_id, $alasan, $tanggal_rencana_keluar, $durasi_keluar, $atasan_id]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Mengambil riwayat perizinan berdasarkan user_id
    public function getRiwayatPerizinan($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM perizinan WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil semua perizinan yang belum diproses oleh atasan
    public function getPendingPerizinan() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
    
        $user_id = $_SESSION['user_id'];
        $query = "SELECT p.*, u.nama AS nama_pengaju 
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  WHERE p.status = 'Pending'
                  AND p.approved_by = :user_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    // Update status perizinan
    public function updateStatus($id, $status, $approved_by) {
        $query = "UPDATE perizinan 
                  SET status = :status, approved_by = :approved_by, approved_at = NOW() 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':approved_by', $approved_by);
        return $stmt->execute();    
    }

    public function getLaporanPerizinan($month, $status, $pemohon, $limit, $offset) {
        // Pastikan user telah login
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        
        // Mulai query dengan filter bulan dan user_id
        $query = "SELECT p.id, u.nama AS nama_pemohon, p.alasan, p.status, 
                         p.tanggal_rencana_keluar, p.durasi_keluar, 
                         a.nama AS nama_atasan, p.created_at 
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN users a ON p.approved_by = a.id
                  WHERE DATE_FORMAT(p.created_at, '%Y-%m') = :month
                    AND p.approved_by = :user_id";
        
        // Siapkan parameter dasar
        $params = [
            ':month'   => $month,
            ':user_id' => $user_id
        ];
        
        // Tambahkan filter status jika diberikan
        if (!empty($status)) {
            $query .= " AND p.status = :status";
            $params[':status'] = $status;
        }
        
        // Tambahkan filter pemohon jika diberikan
        if (!empty($pemohon)) {
            $query .= " AND u.nama LIKE :pemohon";
            $params[':pemohon'] = "%" . $pemohon . "%";
        }
        
        $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameter dinamis
        foreach ($params as $key => $value) {
            // Gunakan PDO::PARAM_INT untuk nilai integer, PDO::PARAM_STR untuk string
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        // Bind limit dan offset sebagai integer
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    // Tambahkan metode untuk menghitung total data
    public function countTotalLaporan($month, $status, $pemohon) {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit();
        }
        $user_id = $_SESSION['user_id'];
        
        $query = "SELECT COUNT(*) 
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN users a ON p.approved_by = a.id
                  WHERE DATE_FORMAT(p.created_at, '%Y-%m') = :month
                    AND p.approved_by = :user_id";
        
        $params = [
            ':month'   => $month,
            ':user_id' => $user_id
        ];
        
        if (!empty($status)) {
            $query .= " AND p.status = :status";
            $params[':status'] = $status;
        }
        
        if (!empty($pemohon)) {
            $query .= " AND u.nama LIKE :pemohon";
            $params[':pemohon'] = "%" . $pemohon . "%";
        }
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchColumn();
    }
        
    public function getApprovedRequests() {
        $query = "SELECT p.id, u.nama AS nama_pengaju, p.tanggal_rencana_keluar, 
                         p.alasan, p.status, l.tanggal_keluar, l.tanggal_masuk
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN log_keluar_masuk l ON p.id = l.perizinan_id 
                  WHERE p.status = 'approved'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
