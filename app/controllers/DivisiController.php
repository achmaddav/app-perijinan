<?php
require_once __DIR__ . '/../models/DivisiModel.php';

class DivisiController {
    private $model;

    public function __construct($db) {
        $this->model = new DivisiModel($db);
    }

    // === INDEX: Tampilkan semua divisi ===
    public function index() {
        $stmt = $this->model->getAllDivisi();
        include __DIR__ . '/../views/divisi/index.php';
    }

    // === CREATE: Tambah divisi baru ===
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama']
            ];

            if ($this->model->insert($data)) {
                $_SESSION['success'] = "Divisi berhasil ditambahkan.";
                header("Location: index.php?page=index_divisi");
                exit;
            } else {
                $_SESSION['error'] = "Gagal menambahkan divisi.";
            }
        }
        include __DIR__ . '/../views/divisi/form.php';
    }

    // === EDIT: Update divisi ===
    public function edit() {
        $id = $_GET['id'] ?? null;
        $data_divisi = $this->model->getDivisiById($id);

        if (!$data_divisi) {
            $_SESSION['error'] = "Data divisi tidak ditemukan.";
            header("Location: index.php?page=index_divisi");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama']
            ];

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Divisi berhasil diperbarui.";
                header("Location: index.php?page=index_divisi");
                exit;
            } else {
                $_SESSION['error'] = "Gagal memperbarui divisi.";
            }
        }

        include __DIR__ . '/../views/divisi/form.php';
    }

    // === DELETE: Hapus divisi ===
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($this->model->deleteDivisi($id)) {
            $_SESSION['success'] = "Divisi berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus divisi.";
        }
        header("Location: index.php?page=index_divisi");
        exit;
    }
}
