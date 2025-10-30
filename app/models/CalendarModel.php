<?php
class CalendarModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateCalendar($startYear, $endYear)
    {
        $startDate = strtotime("$startYear-01-01");
        $endDate   = strtotime("$endYear-12-31");

        $sql = "INSERT IGNORE INTO calendar (tanggal, is_weekend, is_dayoff) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        while ($startDate <= $endDate) {
            $tanggal = date("Y-m-d", $startDate);
            $dayOfWeek = date("N", $startDate); // 1 = Senin, 7 = Minggu
            $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7) ? 1 : 0;

            $stmt->execute([$tanggal, $isWeekend, 0]);

            $startDate = strtotime("+1 day", $startDate);
        }

        return true;
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
