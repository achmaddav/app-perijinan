<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($nama, $email, $password, $jabatan) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table_name . " (nama, email, password, jabatan) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nama, $email, $hashed_password, $jabatan]);
    }

    public function getAllUser() {
        $query = "SELECT id, nama, nip, email, jabatan FROM " . $this->table_name . " WHERE jabatan NOT IN ('SuperUser', 'Satpam') ORDER By nama ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }    

    public function findDetailUser($id) {
        $query = "SELECT 
                    u.nama AS user_nama,
                    u.nip,
                    u.email,
                    u.jabatan,
                    CONCAT(TIMESTAMPDIFF(YEAR, u.tanggal_mulai_kerja, CURDATE()), ' tahun') AS tahun_masa_kerja,
                    CONCAT(TIMESTAMPDIFF(MONTH, u.tanggal_mulai_kerja, CURDATE()) % 12, ' bulan') AS bulan_masa_kerja,
                    COALESCE(
                        CASE 
                            WHEN SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)) < 60 
                                THEN CONCAT(SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)), ' menit')
                            ELSE CONCAT(
                                FLOOR(SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)) / 60), ' jam ',
                                MOD(SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)), 60), ' menit'
                            )
                        END, '0 menit'
                    ) AS total_waktu_keluar,
                    COALESCE(
                        CASE 
                            WHEN SUM(TIMESTAMPDIFF(MINUTE, ln.tanggal_keluar, ln.tanggal_masuk)) < 60 
                                THEN CONCAT(SUM(TIMESTAMPDIFF(MINUTE, ln.tanggal_keluar, ln.tanggal_masuk)), ' menit')
                            ELSE CONCAT(
                                FLOOR(SUM(TIMESTAMPDIFF(MINUTE, ln.tanggal_keluar, ln.tanggal_masuk)) / 60), ' jam ',
                                MOD(SUM(TIMESTAMPDIFF(MINUTE, ln.tanggal_keluar, ln.tanggal_masuk)), 60), ' menit'
                            )
                        END, '0 menit'
                    ) AS total_waktu_keluar_non_berizin
                  FROM users AS u
                  LEFT JOIN perizinan AS p 
                      ON p.user_id = u.id AND p.status = 'Approved'
                  LEFT JOIN log_keluar_masuk AS l 
                      ON l.perizinan_id = p.id
                  LEFT JOIN log_keluar_masuk_non_perizinan AS ln 
                      ON ln.user_id = u.id
                  WHERE u.jabatan NOT IN ('SuperUser', 'Satpam')
                    AND u.id = :id
                  GROUP BY u.id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    
    
    public function rincianPerizinan($id) {
        $query = "SELECT 
                    DATE(p.tanggal_rencana_keluar) AS tanggal,
                    TIME(l.tanggal_keluar) AS jam_keluar,
                    TIME(l.tanggal_masuk) AS jam_kembali,
                    CASE 
                        WHEN COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0) < 60 
                            THEN CONCAT(COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0), ' menit')
                        ELSE CONCAT(
                            FLOOR(COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0) / 60), ' jam ',
                            MOD(COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0), 60), ' menit'
                        )
                    END AS durasi
                  FROM perizinan AS p
                  LEFT JOIN log_keluar_masuk AS l ON l.perizinan_id = p.id
                  JOIN users AS u ON p.user_id = u.id
                  WHERE u.jabatan NOT IN ('SuperUser', 'Satpam')
                    AND p.status = 'Approved'
                    AND u.id = :id
                  ORDER BY p.tanggal_rencana_keluar ASC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua hasil
    } 

    public function rincianNonBerizin($id) {
        $query = "SELECT 
                    DATE(l.tanggal_keluar) AS tanggal,
                    TIME(l.tanggal_keluar) AS jam_keluar,
                    TIME(l.tanggal_masuk) AS jam_kembali,
                    CASE 
                        WHEN COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0) < 60 
                            THEN CONCAT(COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0), ' menit')
                        ELSE CONCAT(
                            FLOOR(COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0) / 60), ' jam ',
                            MOD(COALESCE(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk), 0), 60), ' menit'
                        )
                    END AS durasi
                  FROM users AS u
                  LEFT JOIN log_keluar_masuk_non_perizinan AS l ON l.user_id  = u.id
                  WHERE u.jabatan NOT IN ('SuperUser', 'Satpam')
                    AND u.id = :id
                  ORDER BY l.tanggal_keluar	 ASC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua hasil
    } 
    
    public function getUserById($id) {
        $query = "SELECT 
                    nama AS user_nama,
                    nip,
                    email,
                    jabatan
                  FROM users 
                  WHERE id = :id
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
