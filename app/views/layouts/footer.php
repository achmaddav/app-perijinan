<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Hapus adminlte.min.js jika sudah tidak digunakan -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- DataTables (Opsional) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    function submitFormPDF() {
        const form = document.getElementById('cetakForm');
        const formData = new FormData(form);

        const win = window.open('', '_blank'); // buka tab kosong

        fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(res => res.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                win.location.href = url;
            })
            .catch(err => {
                win.document.write("Gagal memuat PDF.");
                console.error(err);
            });
    }
</script>


<script>
    $(document).ready(function() {
        // Inisialisasi DataTables dengan pengaturan bahasa
        $('#data-table-perizinan').DataTable({
            responsive: true,
            language: {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data",
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    document.addEventListener("DOMContentLoaded", function() {
        let modalKonfirmasi = document.getElementById("modalKonfirmasi");
        if (modalKonfirmasi) {
            modalKonfirmasi.addEventListener("show.bs.modal", function(event) {
                let button = event.relatedTarget;
                if (!button) return;

                let id = button.getAttribute("data-id");
                let alasan = button.getAttribute("data-alasan");

                // Set teks alasan di modal
                let alasanElem = document.getElementById("alasanPerizinan");
                if (alasanElem) alasanElem.textContent = alasan;

                // Set link hapus dengan ID yang dipilih
                let btnHapus = document.getElementById("btnHapus");
                if (btnHapus) btnHapus.href = "/app-perijinan/hapus_perizinan?id=" + encodeURIComponent(id);
            });
        }

        let modalKonfirmasiCuti = document.getElementById("modalKonfirmasiCuti");
        if (modalKonfirmasiCuti) {
            modalKonfirmasiCuti.addEventListener("show.bs.modal", function(event) {
                let button = event.relatedTarget;
                if (!button) return;

                let id = button.getAttribute("data-id");
                let fromDate = button.getAttribute("data-fromDate");
                let tillDate = button.getAttribute("data-tillDate");

                // Set teks tanggal cuti di modal
                let leaveDateElem = document.getElementById("leaveDate");
                if (leaveDateElem) leaveDateElem.textContent = fromDate + " sampai tanggal " + tillDate;

                // Set link hapus dengan ID yang dipilih
                let btnHapus = document.getElementById("btnHapus");
                if (btnHapus) btnHapus.href = "/app-perijinan/hapus_cuti?id=" + encodeURIComponent(id);
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

<!-- Dynamic Footer Year -->
<script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>

</body>

</html>