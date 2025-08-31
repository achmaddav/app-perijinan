<?php
require_once __DIR__ . '/../models/CalendarModel.php';

class CalendarController {
    private $model;

    public function __construct($db) {
        $this->model = new CalendarModel($db);
    }

    public function index() {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        $data = $this->model->getCalendar($year);
        include __DIR__ . '/../views/calendar/index.php';
    }

    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startYear = (int)$_POST['start_year'];
            $endYear   = (int)$_POST['end_year'];

            $this->model->generateCalendar($startYear, $endYear);
            $message = "Calendar berhasil digenerate dari $startYear sampai $endYear";
            include __DIR__ . '/../views/calendar/generate.php';
        } else {
            include __DIR__ . '/../views/calendar/generate.php';
        }
    }

    public function syncDayoff() {
        $year = $_POST['year'] ?? date('Y');

        $affectedRows = $this->model->syncDayoffByYear($year);

        if ($affectedRows >= 0) {
            $_SESSION['success'] = "Synchronize Dayoff untuk tahun $year berhasil.";
        } else {
            $_SESSION['error'] = "Gagal melakukan synchronize dayoff.";
        }

        header("Location: index.php?page=calendar&year=" . $year);
        exit();
    }

    public function unsyncDayoff() {
        $year = $_POST['year'] ?? date('Y');

        $affectedRows = $this->model->unsyncDayoffByYear($year);

        if ($affectedRows >= 0) {
            $_SESSION['success'] = "Unsynchronize Dayoff untuk tahun $year berhasil.";
        } else {
            $_SESSION['error'] = "Gagal melakukan unsynchronize dayoff.";
        }

        header("Location: index.php?page=calendar&year=" . $year);
        exit();
    }
}
