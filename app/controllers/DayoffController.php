<?php
require_once __DIR__ . '/../models/DayoffModel.php';

class DayoffController {
    private $conn;
    private $model;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->model = new DayoffModel($conn);
    }

    // List dayoff
    public function index() {
        $year = $_GET['year'] ?? null;
        $data = $this->model->getAll($year);
        include __DIR__ . '/../views/dayoff/index.php';
    }

    // Form tambah dayoff
    public function create() {
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'];
            $description = $_POST['description'];
            $success = $this->model->insert($tanggal, $description);
            $message = $success ? "Dayoff berhasil ditambahkan." : "Terjadi kesalahan.";
        }
        include __DIR__ . '/../views/dayoff/form.php';
    }

    // Form edit dayoff
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?page=dayoff");
            exit;
        }

        $dayoff = $this->model->getById($id);
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal = $_POST['tanggal'];
            $description = $_POST['description'];
            $success = $this->model->update($id, $tanggal, $description);
            $message = $success ? "Dayoff berhasil diperbarui." : "Terjadi kesalahan.";
            // Refresh data
            $dayoff = $this->model->getById($id);
        }

        include __DIR__ . '/../views/dayoff/form.php';
    }

    // Hapus dayoff
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        header("Location: index.php?page=dayoff");
        exit;
    }
}
?>
