// site.js
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-delete").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();

            var id = this.getAttribute("data-id");
            var nama = this.getAttribute("data-nama");

            var namaEl = document.getElementById("namaDivisi");
            if (namaEl) namaEl.textContent = nama;

            document.getElementById("btnConfirmDelete").href = "/app-perijinan/delete_divisi?id=" + encodeURIComponent(id);

            var modal = new bootstrap.Modal(document.getElementById("confirmDeleteModal"));
            modal.show();
        });
    });
});
