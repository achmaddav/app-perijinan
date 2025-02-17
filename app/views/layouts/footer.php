<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- DataTables (Opsional) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        $('#riwayatTable').DataTable({
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

<!-- Footer -->
<footer class="text-center py-3 mt-4" style="background-color: white; border-top: 1px solid #eaeaea;">
    <p class="mb-0" style="color: #6c757d;">
        &copy; <span id="currentYear"></span> Sistem Perizinan. All Rights Reserved.
    </p>
</footer>

<script>
    // Mengatur Tahun Secara Dinamis
    const year = new Date().getFullYear();
    document.getElementById('currentYear').textContent = year;
</script>

</body>
</html>
