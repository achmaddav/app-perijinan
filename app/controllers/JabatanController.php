<?php
require_once __DIR__ . '/../models/JabatanModel.php';

class JabatanController {
    private $model;

    public function __construct($db) {
        $this->model = new JabatanModel($db);
    }

    public function index() {
        $stmt = $this->model->getAll();
        include __DIR__ . '/../views/jabatan/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama']
            ];

            if ($this->model->insert($data)) {
                $_SESSION['success'] = "Jabatan berhasil ditambahkan.";
                header("Location: index.php?page=index_jabatan");
                exit;
            } else {
                $_SESSION['error'] = "Gagal menambahkan jabatan.";
            }
        }
        include __DIR__ . '/../views/jabatan/form.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        $data_jabatan = $this->model->getById($id);

        if (!$data_jabatan) {
            $_SESSION['error'] = "Data jabatan tidak ditemukan.";
            header("Location: index.php?page=index_jabatan");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'kode' => $_POST['kode'],
                'nama' => $_POST['nama']
            ];

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Jabatan berhasil diperbarui.";
                header("Location: index.php?page=index_jabatan");
                exit;
            } else {
                $_SESSION['error'] = "Gagal memperbarui jabatan.";
            }
        }

        include __DIR__ . '/../views/jabatan/form.php';
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($this->model->delete($id)) {
            $_SESSION['success'] = "Jabatan berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus jabatan.";
        }
        header("Location: index.php?page=index_jabatan");
        exit;
    }
}
