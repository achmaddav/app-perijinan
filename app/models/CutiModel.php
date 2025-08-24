<?php
class CutiModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Menyimpan pengajuan cuti
    public function insertCuti($user_id, $jenis_cuti_id, $atasan_id, $ketua_id, $from_date, $to_date, $alamat, $deskripsi, $jabatan) {
        try {

            if ($jabatan == "STF") {
                $query = "INSERT INTO cuti (user_id, tipe_cuti_id, tanggal_mulai, tanggal_selesai, approved_by, final_approved_by, alamat, alasan, created_at) 
                    VALUES (:user_id, :tipe_cuti_id, :tanggal_mulai, :tanggal_selesai, :approved_by, :final_approved_by, :alamat, :alasan, NOW())";
            } else {
                $query = "INSERT INTO cuti (user_id, tipe_cuti_id, tanggal_mulai, tanggal_selesai, approved_by, final_approved_by, alamat, alasan, status, created_at) 
                    VALUES (:user_id, :tipe_cuti_id, :tanggal_mulai, :tanggal_selesai, :approved_by, :final_approved_by, :alamat, :alasan, 'Progress', NOW())";
            }
            

            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ':user_id' => $user_id,
                ':tipe_cuti_id' => $jenis_cuti_id,
                ':tanggal_mulai' => $from_date,
                ':tanggal_selesai' => $to_date,
                ':approved_by' => $ketua_id,
                ':final_approved_by' => $atasan_id,
                ':alamat' => $alamat,
                ':alasan' => $deskripsi
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Insert Cuti Error: " . $e->getMessage());
            return false;
        }
    }

    // Mengambil riwayat perizinan berdasarkan user_id
    public function getLeaveHistory($user_id) {
        $query = "SELECT 
                    ab.nama AS tahap_1,
                    kep.nama AS tahap_2,
                    DATEDIFF(c.tanggal_selesai, c.tanggal_mulai) + 1 AS jumlah_cuti,
                    c.* 
                FROM cuti c
                LEFT JOIN users ab ON c.approved_by = ab.id 
                INNER JOIN users kep ON c.final_approved_by = kep.id
                WHERE c.user_id = :user_id
                ORDER BY c.created_at DESC";


        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  

    // Mengambil jumlah sisa cuti
   public function getLeaveRemaining($user_id) {
        $jatahCutiTahunan = 12;

        $query = "
            SELECT 
                SUM(
                    DATEDIFF(
                        LEAST(ct.tanggal_selesai, DATE_FORMAT(NOW(), '%Y-12-31')),
                        GREATEST(ct.tanggal_mulai, DATE_FORMAT(NOW(), '%Y-01-01'))
                    ) + 1
                ) AS cutiDipakai
            FROM cuti ct
            INNER JOIN tipe_cuti tc ON tc.id = ct.tipe_cuti_id
            WHERE ct.status = 'Disetujui' 
                AND tc.kode_cuti = 'CT'
                AND ct.user_id = :user_id
                AND (
                    ct.tanggal_mulai <= DATE_FORMAT(NOW(), '%Y-12-31') AND
                    ct.tanggal_selesai >= DATE_FORMAT(NOW(), '%Y-01-01')
                )
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $cutiDipakai = $stmt->fetchColumn();
        $cutiDipakai = $cutiDipakai !== false && $cutiDipakai !== null ? (int)$cutiDipakai : 0;

        $sisaCuti = $jatahCutiTahunan - $cutiDipakai;

        return max(0, $sisaCuti);
    }

    
    // Ambil semua cuti yang belum diproses oleh atasan
    public function getPengajuanCuti() {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT c.*, 
                    u.nama AS nama_pengaju,
                    DATEDIFF(c.tanggal_selesai, c.tanggal_mulai) + 1 AS lama_cuti 
                  FROM cuti c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.status = 'Diajukan'
                  AND c.approved_by = :user_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Ambil semua cuti yang belum diproses oleh kepala balai
    public function getPengajuanCutiForHeadOffice() {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT c.*, 
                    u.nama AS nama_pengaju,
                    DATEDIFF(c.tanggal_selesai, c.tanggal_mulai) + 1 AS lama_cuti 
                  FROM cuti c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.status = 'Progress'
                  AND c.final_approved_by = :user_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update status perizinan
    public function updateStatus($id, $status, $approved_by) {
        $query = "UPDATE cuti 
                  SET status = :status, approved_by = :approved_by, approved_at = NOW() 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':approved_by', $approved_by);
        return $stmt->execute();    
    }

    public function updateStatusByHeadOffice($id, $status, $approved_by) {
        $query = "UPDATE cuti 
                  SET status = :status, final_approved_by = :approved_by, final_approved_at = NOW() 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':approved_by', $approved_by);
        return $stmt->execute();    
    }

    public function getLaporanCuti($user_id, $jabatan, $month, $status, $pemohon, $limit, $offset) {        
        if ($jabatan == "KTA") {
            $query = "SELECT 
                        c.id, 
                        u.nama AS nama_pemohon, 
                        c.alasan, 
                        c.status, 
                        c.tanggal_mulai,
                        c.tanggal_selesai, 
                        DATEDIFF(c.tanggal_selesai, c.tanggal_mulai) + 1 AS lama_cuti,
                        a.nama AS approver, 
                        DATE(c.created_at) AS tanggal_pengajuan
                    FROM cuti c
                    JOIN users u ON c.user_id = u.id
                    JOIN users a ON c.approved_by = a.id
                    WHERE DATE_FORMAT(c.created_at, '%Y-%m') = :month
                        AND c.approved_by = :user_id";
        } else {
            $query = "SELECT 
                        c.id, 
                        u.nama AS nama_pemohon, 
                        c.alasan, 
                        c.status, 
                        c.tanggal_mulai,
                        c.tanggal_selesai, 
                        DATEDIFF(c.tanggal_selesai, c.tanggal_mulai) + 1 AS lama_cuti,
                        a.nama AS approver, 
                        DATE(c.created_at) AS tanggal_pengajuan
                    FROM cuti c
                    JOIN users u ON c.user_id = u.id
                    JOIN users a ON c.final_approved_by = a.id
                    WHERE DATE_FORMAT(c.created_at, '%Y-%m') = :month
                        AND c.final_approved_by = :user_id";
        }

        // Siapkan parameter dasar
        $params = [
            ':month' => $month,
            ':user_id' => (int)$user_id
        ];

        // Tambahkan filter status jika diberikan
        if (!empty($status)) {
            $query .= " AND c.status = :status";
            $params[':status'] = $status;
        }

        // Tambahkan filter nama pemohon jika diberikan
        if (!empty($pemohon)) {
            $query .= " AND u.nama LIKE :pemohon";
            $params[':pemohon'] = "%" . $pemohon . "%";
        }

        // Urutkan + limit
        $query .= " ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset";

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
    public function countTotalLaporan($user_id, $jabatan, $month, $status, $pemohon) {
        if ($jabatan == "KTA") {
            $query = "SELECT COUNT(*) 
                    FROM cuti c
                    JOIN users u ON c.user_id = u.id
                    WHERE DATE_FORMAT(c.created_at, '%Y-%m') = :month
                        AND c.approved_by = :user_id";
        } else {
            $query = "SELECT COUNT(*) 
                    FROM cuti c
                    JOIN users u ON c.user_id = u.id
                    WHERE DATE_FORMAT(c.created_at, '%Y-%m') = :month
                        AND c.final_approved_by = :user_id";
        }       

        $params = [
            ':month' => $month,
            ':user_id' => (int)$user_id
        ];

        if (!empty($status)) {
            $query .= " AND c.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($pemohon)) {
            $query .= " AND u.nama LIKE :pemohon";
            $params[':pemohon'] = "%" . $pemohon . "%";
        }

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getCutiById($id)
    {
        $query = "
            SELECT 
                c.*, 
                tc.nama AS jenis_cuti, 
                u.nama AS nama_pemohon, 
                u.nip, 
                u.phone_number,
                CONCAT(j.nama, ' ', dt.nama) AS jabatan,
                u.tanggal_mulai_kerja,
                TIMESTAMPDIFF(YEAR, u.tanggal_mulai_kerja, CURDATE()) AS tahun_masa_kerja,
                MOD(TIMESTAMPDIFF(MONTH, u.tanggal_mulai_kerja, CURDATE()), 12) AS bulan_masa_kerja,
                DATEDIFF(c.tanggal_selesai, c.tanggal_mulai) + 1 AS lama_cuti,
                a.nama AS approver,
                tc.kode_cuti,

                -- Sisa cuti tahunan N, N-1, N-2
                12 - IFNULL(ct.total_n_0, 0) AS sisa_cuti_n_0,
                12 - IFNULL(ct.total_n_1, 0) AS sisa_cuti_n_1,
                12 - IFNULL(ct.total_n_2, 0) AS sisa_cuti_n_2,

                IFNULL(cb.total_cb, 0) AS cuti_besar,
                IFNULL(cs.total_cs, 0) AS cuti_sakit,
                IFNULL(cm.total_cm, 0) AS cuti_melahirkan,
                IFNULL(ckap.total_ckap, 0) AS cuti_alasan_penting,
                IFNULL(cltn.total_cltn, 0) AS cuti_diluar_tanggungan_negara

            FROM cuti c
            INNER JOIN users u ON u.id = c.user_id
            LEFT JOIN users a ON a.id = c.approved_by
            INNER JOIN jabatan j ON u.jabatan_id = j.id
            INNER JOIN divisitim dt ON u.divisi_id = dt.id 
            INNER JOIN tipe_cuti tc ON tc.id = c.tipe_cuti_id

            -- Subquery total cuti tahunan N, N-1, N-2
            LEFT JOIN (
                SELECT 
                    cct.user_id,
                    SUM(CASE WHEN YEAR(cct.tanggal_mulai) = YEAR(CURDATE())     THEN DATEDIFF(cct.tanggal_selesai, cct.tanggal_mulai) + 1 ELSE 0 END) AS total_n_0,
                    SUM(CASE WHEN YEAR(cct.tanggal_mulai) = YEAR(CURDATE()) - 1 THEN DATEDIFF(cct.tanggal_selesai, cct.tanggal_mulai) + 1 ELSE 0 END) AS total_n_1,
                    SUM(CASE WHEN YEAR(cct.tanggal_mulai) = YEAR(CURDATE()) - 2 THEN DATEDIFF(cct.tanggal_selesai, cct.tanggal_mulai) + 1 ELSE 0 END) AS total_n_2
                FROM cuti cct
                INNER JOIN tipe_cuti tcct ON tcct.id = cct.tipe_cuti_id
                WHERE cct.status = 'Disetujui' AND tcct.kode_cuti = 'CT'
                GROUP BY cct.user_id
            ) ct ON ct.user_id = c.user_id

            -- Subquery total cuti besar tahun ini
            LEFT JOIN (
                SELECT 
                    ccb.user_id,
                    SUM(DATEDIFF(ccb.tanggal_selesai, ccb.tanggal_mulai) + 1) AS total_cb
                FROM cuti ccb
                INNER JOIN tipe_cuti tccb ON tccb.id = ccb.tipe_cuti_id
                WHERE ccb.status = 'Disetujui'
                AND tccb.kode_cuti = 'CB'
                AND YEAR(ccb.tanggal_mulai) = YEAR(CURDATE())
                GROUP BY ccb.user_id
            ) cb ON cb.user_id = c.user_id

            -- Subquery total cuti sakit tahun ini
            LEFT JOIN (
                SELECT 
                    ccs.user_id,
                    SUM(DATEDIFF(ccs.tanggal_selesai, ccs.tanggal_mulai) + 1) AS total_cs
                FROM cuti ccs
                INNER JOIN tipe_cuti tccs ON tccs.id = ccs.tipe_cuti_id
                WHERE ccs.status = 'Disetujui'
                AND tccs.kode_cuti = 'CS'
                AND YEAR(ccs.tanggal_mulai) = YEAR(CURDATE())
                GROUP BY ccs.user_id
            ) cs ON cs.user_id = c.user_id

            -- Subquery total cuti melahirkan tahun ini
            LEFT JOIN (
                SELECT 
                    ccm.user_id,
                    SUM(DATEDIFF(ccm.tanggal_selesai, ccm.tanggal_mulai) + 1) AS total_cm
                FROM cuti ccm
                INNER JOIN tipe_cuti tccm ON tccm.id = ccm.tipe_cuti_id
                WHERE ccm.status = 'Disetujui'
                AND tccm.kode_cuti = 'CM'
                AND YEAR(ccm.tanggal_mulai) = YEAR(CURDATE())
                GROUP BY ccm.user_id
            ) cm ON cm.user_id = c.user_id

            -- Subquery total cuti karena alasan penting tahun ini
            LEFT JOIN (
                SELECT 
                    cckap.user_id,
                    SUM(DATEDIFF(cckap.tanggal_selesai, cckap.tanggal_mulai) + 1) AS total_ckap
                FROM cuti cckap
                INNER JOIN tipe_cuti tcckap ON tcckap.id = cckap.tipe_cuti_id
                WHERE cckap.status = 'Disetujui'
                AND tcckap.kode_cuti = 'CKAP'
                AND YEAR(cckap.tanggal_mulai) = YEAR(CURDATE())
                GROUP BY cckap.user_id
            ) ckap ON ckap.user_id = c.user_id

            -- Subquery total cuti di luar tanggungan negara tahun ini
            LEFT JOIN (
                SELECT 
                    ccltn.user_id,
                    SUM(DATEDIFF(ccltn.tanggal_selesai, ccltn.tanggal_mulai) + 1) AS total_cltn
                FROM cuti ccltn
                INNER JOIN tipe_cuti tccltn ON tccltn.id = ccltn.tipe_cuti_id
                WHERE ccltn.status = 'Disetujui'
                AND tccltn.kode_cuti = 'CLTN'
                AND YEAR(ccltn.tanggal_mulai) = YEAR(CURDATE())
                GROUP BY ccltn.user_id
            ) cltn ON cltn.user_id = c.user_id

            WHERE c.id = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStatusCuti($id)
    {
        $query = "SELECT status FROM cuti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $query = "DELETE FROM cuti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

}
