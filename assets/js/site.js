function submitFormPDF(id) {
  const formData = new FormData(); // ✅ kosong, lalu append manual
  formData.append("id", id);

  fetch("?page=cetak_cuti", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.blob())
    .then((blob) => {
      const url = URL.createObjectURL(blob);
      window.open(url, "_blank");
    })
    .catch((err) => console.error(err));
}


// Tutup otomatis setelah 3 detik (3000 ms)
setTimeout(() => {
  let alerts = document.querySelectorAll(".auto-dismiss");
  alerts.forEach((alert) => {
    let bsAlert = new bootstrap.Alert(alert);
    bsAlert.close();
  });
}, 3000);

$(document).ready(function () {
  // Inisialisasi DataTables dengan pengaturan bahasa
  var table = $("#data-table-perizinan").DataTable({
    responsive: true,
    language: {
      lengthMenu: "Tampilkan _MENU_ data per halaman",
      zeroRecords: "Tidak ada data",
      info: "Menampilkan halaman _PAGE_ dari _PAGES_",
      infoEmpty: "Tidak ada data yang tersedia",
      infoFiltered: "(disaring dari total _MAX_ data)",
      search: "Cari:",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Berikutnya",
        previous: "Sebelumnya",
      },
    },
  });

  $('#calendarTable').DataTable({
      "pageLength": 10,
      "lengthMenu": [10, 25, 50, 100],
      "ordering": true,
      "searching": true,
      "info": true,
      "autoWidth": false
  });

  $('#table-dayoff').DataTable({
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ baris",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": 3 } 
        ]
    });
});

document.addEventListener("DOMContentLoaded", function () {
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

// <!-- Function: Show Cannot Delete Alert -->

function showCannotDeleteAlert(status) {
  Swal.fire({
    icon: "warning",
    title: "Tidak Bisa Dihapus!",
    text: "Perizinan dengan status " + status + " tidak dapat dihapus.",
    confirmButtonColor: "#3085d6",
    confirmButtonText: "OK",
  });
}

// <!-- Modal Konfirmasi Setup -->

document.addEventListener("DOMContentLoaded", function () {
  let modalKonfirmasi = document.getElementById("modalKonfirmasi");
  if (modalKonfirmasi) {
    modalKonfirmasi.addEventListener("show.bs.modal", function (event) {
      let button = event.relatedTarget;
      if (!button) return;

      let id = button.getAttribute("data-id");
      let alasan = button.getAttribute("data-alasan");

      // Set teks alasan di modal
      let alasanElem = document.getElementById("alasanPerizinan");
      if (alasanElem) alasanElem.textContent = alasan;

      // Set link hapus dengan ID yang dipilih
      let btnHapus = document.getElementById("btnHapus");
      if (btnHapus)
        btnHapus.href =
          "/app-perijinan/hapus_perizinan?id=" + encodeURIComponent(id);
    });
  }

  let modalKonfirmasiCuti = document.getElementById("modalKonfirmasiCuti");
  if (modalKonfirmasiCuti) {
    modalKonfirmasiCuti.addEventListener("show.bs.modal", function (event) {
      let button = event.relatedTarget;
      if (!button) return;

      let id = button.getAttribute("data-id");
      let fromDate = button.getAttribute("data-fromDate");
      let tillDate = button.getAttribute("data-tillDate");

      // Set teks tanggal cuti di modal
      let leaveDateElem = document.getElementById("leaveDate");
      if (leaveDateElem)
        leaveDateElem.textContent = fromDate + " sampai tanggal " + tillDate;

      let btnHapus = document.getElementById("btnHapus");
      if (btnHapus)
        btnHapus.href =
          "/app-perijinan/hapus_cuti?id=" + encodeURIComponent(id);
    });
  }


  // === Modal Reset Password ===

  let modalKonfirmasiResetPassword = document.getElementById("modalResetPassword");
  if (modalKonfirmasiResetPassword) {
    modalKonfirmasiResetPassword.addEventListener("show.bs.modal", function (event) {
      let button = event.relatedTarget;
      if (!button) return;

      let id = button.getAttribute("data-id");
      let nama = button.getAttribute("data-nama");

      // Set nama user ke modal
      let namaElem = document.getElementById("resetUserName");
      if (namaElem) namaElem.textContent = nama;

      // Set link reset dengan ID user
      let btnReset = document.getElementById("btnReset");
      if (btnReset) {
        btnReset.href = "/app-perijinan/user_reset_password?id=" + encodeURIComponent(id);
      }
    });
  }

  // === Modal Nonaktifkan Pegawai ===
  let modalNonaktifkan = document.getElementById("modalNonaktifkan");
  if (modalNonaktifkan) {
      modalNonaktifkan.addEventListener("show.bs.modal", function (event) {
          let button = event.relatedTarget;
          if (!button) return;

          let id = button.getAttribute("data-id");
          let nama = button.getAttribute("data-nama");

          // Set nama user ke modal
          let namaElem = document.getElementById("nonaktifUserName");
          if (namaElem) {
              namaElem.textContent = nama || "(Tidak ada nama)";
          }

          // Set link nonaktifkan
          let btnNonaktifkan = document.getElementById("btnNonaktifkan");
          if (btnNonaktifkan) {
              btnNonaktifkan.href = "/app-perijinan/user_nonaktifkan?id=" + encodeURIComponent(id);
          }
      });
  }


});

// <!-- Dynamic Footer Year -->
document.getElementById("currentYear").textContent = new Date().getFullYear();
