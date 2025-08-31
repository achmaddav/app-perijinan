<?php
class CalendarModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateCalendar($startYear, $endYear) {
        // Buat tanggal dari startYear sampai endYear
        $startDate = "$startYear-01-01";
        $endDate   = "$endYear-12-31";

        $query = "
            INSERT IGNORE INTO calendar (tanggal, is_weekend, is_dayoff)
            SELECT d,
                   CASE WHEN DAYOFWEEK(d) IN (1,7) THEN 1 ELSE 0 END,
                   0
            FROM (
                WITH RECURSIVE dates(d) AS (
                    SELECT DATE(:startDate)
                    UNION ALL
                    SELECT DATE_ADD(d, INTERVAL 1 DAY) FROM dates WHERE d < :endDate
                )
                SELECT d FROM dates
            ) AS x;
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':startDate', $startDate);
        $stmt->bindValue(':endDate', $endDate);
        return $stmt->execute();
    }

    public function getCalendar($year) {
        $stmt = $this->conn->prepare("SELECT * FROM calendar WHERE YEAR(tanggal) = :year ORDER BY tanggal");
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function syncDayoffByYear($year)
    {
        $sql = "
            UPDATE calendar c
            LEFT JOIN dayoff d 
                ON d.tanggal = c.tanggal
            SET c.is_dayoff = CASE 
                WHEN d.tanggal IS NOT NULL THEN 1 
                ELSE 0 
            END
            WHERE YEAR(c.tanggal) = :year
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        // PDO tidak ada affected_rows, pakainya rowCount()
        $affectedRows = $stmt->rowCount();

        return $affectedRows;
    }

    public function unsyncDayoffByYear($year)
    {
        $sql = "
            UPDATE calendar 
            SET is_dayoff = 0
            WHERE YEAR(tanggal) = :year
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':year' => $year]);

        return $stmt->rowCount();
    }

}
