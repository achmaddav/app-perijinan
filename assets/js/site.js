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

      // Set link hapus dengan ID yang dipilih
      let btnHapus = document.getElementById("btnHapus");
      if (btnHapus)
        btnHapus.href =
          "/app-perijinan/hapus_cuti?id=" + encodeURIComponent(id);
    });
  }
});

// <!-- Dynamic Footer Year -->
document.getElementById("currentYear").textContent = new Date().getFullYear();
