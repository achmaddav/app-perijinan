document.addEventListener("DOMContentLoaded", function() {
    const jabatanSelect = document.getElementById('jenisJabatan');
    const ketuaSelect = document.getElementById('ketua_timker');
    const timKerjaSelect = document.querySelector('select[name="timKerja"]');
    const kepalaSelect = document.querySelector('select[name="kepala_balai"]');

    // Pastikan elemen ada
    if (!jabatanSelect) return;

    function toggleFields() {
        const selectedOption = jabatanSelect.options[jabatanSelect.selectedIndex];
        const kode = selectedOption?.getAttribute('data-kode');

        // Pastikan elemen select tidak null sebelum diubah
        if (ketuaSelect) {
            ketuaSelect.disabled = (kode === 'KTA' || kode === 'KEP');
            if (ketuaSelect.disabled) ketuaSelect.value = '';
        }

        if (timKerjaSelect) {
            timKerjaSelect.disabled = (kode === 'KEP');
            if (timKerjaSelect.disabled) timKerjaSelect.value = '';
        }

        if (kepalaSelect) {
            kepalaSelect.disabled = (kode === 'KEP');
            if (kepalaSelect.disabled) kepalaSelect.value = '';
        }
    }

    // Jalankan saat load dan saat change
    toggleFields();
    jabatanSelect.addEventListener('change', toggleFields);
});
