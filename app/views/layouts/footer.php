<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Hapus adminlte.min.js jika sudah tidak digunakan -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- DataTables (Opsional) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables dengan pengaturan bahasa
        $('#data-table-perizinan').DataTable({
            responsive: true,
            language: {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data yang tersedia",
                "infoFiltered": "(disaring dari total _MAX_ data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Dynamic Footer Year -->
<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

<!-- Function: Show Cannot Delete Alert -->
<script>
    function showCannotDeleteAlert(status) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Bisa Dihapus!',
            text: 'Perizinan dengan status ' + status + ' tidak dapat dihapus.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }
</script>

<!-- Modal Konfirmasi Setup -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let modalKonfirmasi = document.getElementById("modalKonfirmasi");
        if (modalKonfirmasi) {
            modalKonfirmasi.addEventListener("show.bs.modal", function (event) {
                let button = event.relatedTarget;
                let id = button.getAttribute("data-id");
                let alasan = button.getAttribute("data-alasan");

                // Set teks alasan di modal
                document.getElementById("alasanPerizinan").textContent = alasan;

                // Set link hapus dengan ID yang dipilih
                document.getElementById("btnHapus").href = "index.php?page=hapus_perizinan&id=" + id;
            });
        }
    });
</script>

<!-- Footer -->
<footer class="text-center py-3 mt-4" style="background-color: #fff; border-top: 1px solid #eaeaea;">
    <p class="mb-0" style="color: #6c757d;">
        &copy; <span id="currentYear"></span> Sistem Perizinan. All Rights Reserved.
    </p>
</footer>

</body>
</html>
