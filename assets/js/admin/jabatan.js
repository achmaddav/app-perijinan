// site.js
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-delete").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();

            var id = this.getAttribute("data-id");
            var nama = this.getAttribute("data-nama");

            // Isi nama jabatan di modal
            var namaEl = document.getElementById("namaJabatan");
            if (namaEl) namaEl.textContent = nama;

            // Set link delete
            document.getElementById("btnConfirmDelete").href = "/app-perijinan/delete_jabatan?id=" + encodeURIComponent(id);

            // Tampilkan modal
            var modal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
            modal.show();
        });
    });
});
