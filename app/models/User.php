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
                    SUM(TIMESTAMPDIFF(MINUTE, l.tanggal_keluar, l.tanggal_masuk)) AS total_menit_keluar
                  FROM users AS u
                  LEFT JOIN perizinan AS p ON p.user_id = u.id
                  LEFT JOIN users AS approver ON p.approved_by = approver.id
                  LEFT JOIN log_keluar_masuk AS l ON l.perizinan_id = p.id
                  LEFT JOIN users AS scrty ON l.satpam_id = scrty.id
                  WHERE u.jabatan NOT IN ('SuperUser', 'Satpam')
                    AND p.status = 'Approved'
                    AND u.id = :id
                  GROUP BY u.id
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }        
}
?>
