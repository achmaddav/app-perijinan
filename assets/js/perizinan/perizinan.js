$(function () {
  // Init setiap kali modal dibuka 
  $('#modalTambahPerizinan').on('shown.bs.modal', function () {
    var $modal = $(this);
    var $select = $modal.find('#userDropdown');

    if ($select.length === 0) {
      console.warn('Element #userDropdown tidak ditemukan di dalam modal');
      return;
    }

    if ($select.hasClass('select2-hidden-accessible')) {
      try { $select.select2('destroy'); } catch (e) { /* ignore */ }
    }

    $select.select2({
      placeholder: "-- Pilih Karyawan --",
      allowClear: true,
      dropdownParent: $modal,   
      width: '100%'             
    });
  });

  // kalau modal sudah terbuka saat page load, init sekali
  if ($('#modalTambahPerizinan').hasClass('show')) {
    $('#modalTambahPerizinan').trigger('shown.bs.modal');
  }
});
