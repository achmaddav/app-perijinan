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
            $query = "INSERT INTO perizinan (user_id, alasan, status, tanggal_rencana_keluar, durasi_keluar, approved_by, created_at, updated_at) 
                    VALUES (:user_id, :alasan, 'Pending', :tanggal_rencana_keluar, :durasi_keluar, :approved_by, NOW(), NOW())";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ':user_id' => $user_id,
                ':alasan' => $alasan,
                ':tanggal_rencana_keluar' => $tanggal_rencana_keluar,
                ':durasi_keluar' => $durasi_keluar,
                ':approved_by' => $atasan_id
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Insert Perizinan Error: " . $e->getMessage()); // Logging untuk debugging
            return false; // Hindari mengembalikan pesan error langsung ke frontend
        }
    }

    // Mengambil riwayat perizinan berdasarkan user_id
    public function getStatusPerizinan($user_id) {
        $query = "SELECT p.*, u.nama AS nama_atasan 
                  FROM perizinan p 
                  JOIN users u ON p.approved_by = u.id
                  WHERE p.user_id = :user_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil semua perizinan yang belum diproses oleh atasan
    public function getPendingPerizinan() {
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

    public function getLaporanPerizinan($jabatan, $month, $status, $pemohon, $limit, $offset) {
        $user_id = $_SESSION['user_id'];
        
        // Dasar query 
        $query = "SELECT 
                    p.id, 
                    u.nama AS nama_pemohon, 
                    p.alasan, 
                    p.status, 
                    p.tanggal_rencana_keluar, 
                    p.durasi_keluar, 
                    a.nama AS nama_atasan, 
                    p.created_at,
                    CASE 
                        WHEN SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)) < 60 
                            THEN CONCAT(SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)), ' menit')
                        ELSE CONCAT(
                            FLOOR(SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)) / 60), ' jam ',
                            MOD(SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)), 60), ' menit'
                        )
                    END AS total_waktu_keluar
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN users a ON p.approved_by = a.id
                  LEFT JOIN log_keluar_masuk AS l ON l.perizinan_id = p.id
                  WHERE DATE_FORMAT(p.created_at, '%Y-%m') = :month";
        
        // Jika jabatan Atasan, filter perizinan yang disetujui oleh user tersebut
        if ($jabatan === 'Atasan') {
            $query .= " AND p.approved_by = :user_id";
        }
        
        // Siapkan parameter dasar
        $params = [
            ':month' => $month
        ];
        
        if ($jabatan === 'Atasan') {
            $params[':user_id'] = $user_id;
        }
        
        // Tambahkan filter status jika diberikan
        if (!empty($status)) {
            $query .= " AND p.status = :status";
            $params[':status'] = $status;
        }
        
        // Tambahkan filter nama pemohon jika diberikan
        if (!empty($pemohon)) {
            $query .= " AND u.nama LIKE :pemohon";
            $params[':pemohon'] = "%" . $pemohon . "%";
        }
        
        // Tambahkan GROUP BY karena kita menggunakan fungsi agregat
        $query .= " GROUP BY p.id";
        
        $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameter dinamis
        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }
        
        // Bind limit dan offset sebagai integer
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
            
    // Metode untuk menghitung total data
    public function countTotalLaporan($jabatan, $month, $status, $pemohon) {
        $user_id = $_SESSION['user_id'];
        
        $query = "SELECT COUNT(*) 
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN users a ON p.approved_by = a.id
                  WHERE DATE_FORMAT(p.created_at, '%Y-%m') = :month";
        
        if ($jabatan === 'Atasan') {
            $query .= " AND p.approved_by = :user_id";
        }
        
        $params = [
            ':month' => $month
        ];
        
        if ($jabatan === 'Atasan') {
            $params[':user_id'] = $user_id;
        }
        
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
        $query = "SELECT 
                    p.id, 
                    u.nama AS nama_pengaju, 
                    p.tanggal_rencana_keluar, 
                    p.alasan, 
                    p.status, 
                    l.tanggal_keluar, 
                    l.tanggal_masuk
                  FROM perizinan p
                  JOIN users u ON p.user_id = u.id
                  LEFT JOIN log_keluar_masuk l ON p.id = l.perizinan_id 
                  WHERE p.status = 'approved'
                    AND DATE(p.tanggal_rencana_keluar) = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cek status perizinan berdasarkan ID
    public function getStatus($id)
    {
        $query = "SELECT status FROM perizinan WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Hapus perizinan jika status belum Approved atau Rejected
    public function delete($id)
    {
        $query = "DELETE FROM perizinan WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

}
