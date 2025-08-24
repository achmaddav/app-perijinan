<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByNip($nip) {
        $query = "
            SELECT 
                p.*, 
                j.kode AS kode_jabatan,
                d.nama AS divisi
            FROM {$this->table_name} p
            LEFT JOIN jabatan j ON p.jabatan_id = j.id
            LEFT JOIN divisitim d ON p.divisi_id = d.id
            WHERE p.nip = :nip
            AND p.IsActive = 1
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nip', $nip, PDO::PARAM_STR);
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
        $query = "SELECT p.id, p.nama, p.nip, p.email, CONCAT(j.nama, ' ', d.nama) AS jabatan
                FROM " . $this->table_name . " p
                LEFT JOIN jabatan j ON p.jabatan_id = j.id
                LEFT JOIN divisitim d ON p.divisi_id = d.id
                WHERE j.kode NOT IN ('KEP', 'SCT') 
                ORDER BY p.nama ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function findDetailUser($id) {
        $query = "SELECT 
                    u.nama AS user_nama,
                    u.nip,
                    u.email,
                    CONCAT(j.nama, ' ', d.nama) AS jabatan,
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
                  LEFT JOIN jabatan j 
                      ON u.jabatan_id = j.id
                  LEFT JOIN divisitim d 
                      ON u.divisi_id = d.id
                  LEFT JOIN log_keluar_masuk AS l 
                      ON l.perizinan_id = p.id
                  LEFT JOIN log_keluar_masuk_non_perizinan AS ln 
                      ON ln.user_id = u.id
                  WHERE j.kode NOT IN ('KEP', 'SCT')
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
                  LEFT JOIN jabatan j ON u.jabatan_id = j.id
                  WHERE j.kode NOT IN ('KEP', 'SCT')
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
                  LEFT JOIN jabatan j ON u.jabatan_id = j.id
                  LEFT JOIN log_keluar_masuk_non_perizinan AS l ON l.user_id  = u.id
                  WHERE j.kode NOT IN ('KEP', 'SCT')
                    AND u.id = :id
                  ORDER BY l.tanggal_keluar	 ASC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua hasil
    } 
    
    public function getUserById($id) {
        $query = "SELECT
                    u.id, 
                    u.nama AS user_nama,
                    u.nip,
                    u.email,
                    u.birth_of_date,
                    u.place_of_birth,
                    u.phone_number,
                    u.address,
                    u.tanggal_mulai_kerja,
                    CONCAT(COALESCE(j.nama, ''), ' ', COALESCE(d.nama, '')) AS jabatan
                  FROM users u
                  INNER JOIN jabatan j ON u.jabatan_id = j.id
                  LEFT JOIN divisitim d ON u.divisi_id = d.id  
                  WHERE u.id = :id
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUpdateUserById($id) {
        $query = "SELECT *
                  FROM users 
                  WHERE id = :id
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAtasanById($atasan_id) {
        $stmt = $this->conn->prepare("
            SELECT id, nama
            FROM users 
            WHERE id = :atasan_id
        ");
        $stmt->bindParam(':atasan_id', $atasan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKepalaBalaiById($id) {
        $stmt = $this->conn->prepare("
            SELECT id, nama
            FROM users 
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKetuaTimById($id) {
        $stmt = $this->conn->prepare("
            SELECT id, nama
            FROM users 
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllLeader() {
        $query = "SELECT u.id, u.nama 
                FROM users u
                INNER JOIN jabatan j ON u.jabatan_id = j.id
                WHERE j.Kode = :code";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':code' => 'KTA']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllHead() {
        $query = "SELECT u.id, u.nama 
                FROM users u
                INNER JOIN jabatan j ON u.jabatan_id = j.id
                WHERE j.Kode = :code";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':code' => 'KEP']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertUser($nama, $tanggal_lahir, $tempat_lahir, 
        $email, $phone_number, $alamat, $nip, $jenisJabatan, $timKerja,
        $ketua_timker, $kepala_balai, $tanggal_kerja) {
        try {
            // Hash password default di PHP, bukan di SQL
            $hashedPassword = password_hash("123456", PASSWORD_BCRYPT);

            $query = "INSERT INTO users (
                        nama, 
                        birth_of_date, 
                        place_of_birth, 
                        nip, 
                        email, 
                        phone_number, 
                        address, 
                        password, 
                        jabatan_id,
                        kepala_id,
                        atasan_id,
                        divisi_id,
                        tanggal_mulai_kerja,
                        IsActive,
                        created_at
                    ) VALUES (
                        :nama, 
                        :tanggal_lahir, 
                        :tempat_lahir, 
                        :nip,
                        :email, 
                        :phone_number, 
                        :alamat, 
                        :password, 
                        :jabatan_id,
                        :kepala_id,
                        :atasan_id,
                        :divisi_id,
                        :tanggal_mulai_kerja,
                        1,
                        NOW()
                    )";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ':nama' => $nama,
                ':tanggal_lahir' => $tanggal_lahir,
                ':tempat_lahir' => $tempat_lahir,
                ':nip' => $nip,
                ':email' => $email,
                ':phone_number' => $phone_number,
                ':alamat' => $alamat,
                ':password' => $hashedPassword, // password sudah di-hash
                ':jabatan_id' => $jenisJabatan,
                ':kepala_id' => !empty($kepala_balai) ? $kepala_balai : null,
                ':atasan_id' => !empty($ketua_timker) ? $ketua_timker : null,
                ':divisi_id' => $timKerja,
                ':tanggal_mulai_kerja' => $tanggal_kerja
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Insert User Error: " . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($id, $newHash)
    {
        $query = "
            UPDATE " . $this->table_name . "
            SET password = :password,
                updated_at = NOW()
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $newHash);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateUser($id, $nama, $tanggal_lahir, $tempat_lahir,
        $email, $phone_number, $alamat, $nip, $jenisJabatan,
        $timKerja, $ketua_timker, $kepala_balai, $tanggal_kerja)
    {
        try {
            $sql = "UPDATE users SET 
                        nama = :nama,
                        birth_of_date = :tanggal_lahir,
                        place_of_birth = :tempat_lahir,
                        email = :email,
                        phone_number = :phone_number,
                        address = :alamat,
                        nip = :nip,
                        jabatan_id = :jabatan_id,
                        divisi_id = :divisi_id,
                        atasan_id = :atasan_id,
                        kepala_id = :kepala_id,
                        tanggal_mulai_kerja = :tanggal_kerja,
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nama' => $nama,
                ':tanggal_lahir' => $tanggal_lahir,
                ':tempat_lahir' => $tempat_lahir,
                ':email' => $email,
                ':phone_number' => $phone_number,
                ':alamat' => $alamat,
                ':nip' => $nip,
                ':jabatan_id' => $jenisJabatan,
                ':divisi_id' => $timKerja,
                ':atasan_id' => !empty($ketua_timker) ? $ketua_timker : null,
                ':kepala_id' => !empty($kepala_balai) ? $kepala_balai : null,
                ':tanggal_kerja' => $tanggal_kerja
            ]);
        } catch (PDOException $e) {
            error_log("Update User Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateProfil($id, $nama, $tanggal_lahir, $tempat_lahir,
        $email, $phone_number, $alamat)
    {
        try {
            $sql = "UPDATE users SET 
                        nama = :nama,
                        birth_of_date = :tanggal_lahir,
                        place_of_birth = :tempat_lahir,
                        email = :email,
                        phone_number = :phone_number,
                        address = :alamat,
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':nama' => $nama,
                ':tanggal_lahir' => $tanggal_lahir,
                ':tempat_lahir' => $tempat_lahir,
                ':email' => $email,
                ':phone_number' => $phone_number,
                ':alamat' => $alamat
            ]);
        } catch (PDOException $e) {
            error_log("Update User Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
