document.addEventListener("DOMContentLoaded", function() {
    const jabatanSelect = document.getElementById('jenisJabatan');
    const timKerjaSelect = document.getElementById('timKerja');
    const ketuaSelect = document.getElementById('ketua_timker');
    const kepalaSelect = document.getElementById('kepala_balai');

    jabatanSelect.addEventListener('change', function() {
        const selectedOption = jabatanSelect.options[jabatanSelect.selectedIndex];
        const kode = selectedOption.getAttribute('data-kode');

        if (kode === 'KTA') {
            // Disable hanya ketua tim
            ketuaSelect.disabled = true;
            ketuaSelect.value = ''; // reset value
            timKerjaSelect.disabled = false;
            kepalaSelect.disabled = false;
        } else if (kode === 'KEP') {
            // Disable tim kerja, ketua tim, dan kepala balai
            ketuaSelect.disabled = true;
            ketuaSelect.value = '';
            timKerjaSelect.disabled = true;
            timKerjaSelect.value = '';
            kepalaSelect.disabled = true;
            kepalaSelect.value = '';
        } else {
            // Semua enable
            ketuaSelect.disabled = false;
            timKerjaSelect.disabled = false;
            kepalaSelect.disabled = false;
        }
    });

    const importModal = document.getElementById('importModal');
    const importForm = document.getElementById("importForm");
    const fileInput = document.getElementById('file_excel');
    const loadingOverlay = document.getElementById("loadingOverlay");
    const importProgress = document.getElementById("importProgress");
    const progressPercentage = document.getElementById("progressPercentage");
    const statusText = document.getElementById("statusText");
    const timeElapsed = document.getElementById("timeElapsed");
    const cancelButton = document.getElementById("cancelButton");
    const importResultCard = document.getElementById("importResultCard");
    const importResultTitle = document.getElementById("importResultTitle");
    const importResultMessage = document.getElementById("importResultMessage");
    const successCountEl = document.getElementById("successCount");
    const errorCountEl = document.getElementById("errorCount");
    const errorListEl = document.getElementById("errorList");
    
    let progressInterval;
    let startTime;
    let importCancelled = false;
    let xhr = null;
    
    // Fungsi untuk membersihkan modal
    function resetModal() {
        // Reset form
        importForm.reset();
        
        // Sembunyikan hasil import
        importResultCard.style.display = 'none';
        errorListEl.innerHTML = '';
        
        // Reset progress bar
        importProgress.style.width = "0%";
        importProgress.setAttribute('aria-valuenow', 0);
        progressPercentage.textContent = "0%";
        
        // Sembunyikan loading overlay
        loadingOverlay.style.display = "none";
        
        // Hentikan interval jika masih berjalan
        if (progressInterval) {
            clearInterval(progressInterval);
        }
        
        // Reset status import
        importCancelled = false;
        xhr = null;
    }
    
    // Event listener ketika modal ditutup
    if (importModal) {
        importModal.addEventListener('hidden.bs.modal', function () {
            resetModal();
        });
    }

    // Fungsi untuk memformat waktu
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins} menit ${secs} detik`;
    }

    // Fungsi untuk memperbarui progress
    function updateProgress() {
        if (importCancelled) return;
        
        const currentTime = Math.floor((Date.now() - startTime) / 1000);
        timeElapsed.textContent = `Waktu: ${formatTime(currentTime)}`;
    }

    // Fungsi untuk menampilkan hasil import
    function showImportResult(response) {
        successCountEl.textContent = response.stats.success;
        errorCountEl.textContent = response.stats.error;
        importResultMessage.textContent = response.message;
        
        if (response.stats.error > 0 && response.errors) {
            errorListEl.innerHTML = '<h6 class="mt-3">Detail Kesalahan:</h6>';
            const errorList = document.createElement('ul');
            errorList.className = 'list-group';
            
            response.errors.slice(0, 5).forEach(error => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item list-group-item-danger';
                listItem.textContent = error;
                errorList.appendChild(listItem);
            });
            
            errorListEl.appendChild(errorList);
            
            if (response.stats.error > 5) {
                const moreErrors = document.createElement('p');
                moreErrors.className = 'text-muted mt-2';
                moreErrors.textContent = `Dan ${response.stats.error - 5} error lainnya...`;
                errorListEl.appendChild(moreErrors);
            }
        } else {
            errorListEl.innerHTML = '';
        }
        
        importResultCard.style.display = 'block';
        importResultCard.classList.add('success-animation');
    }

    // Event listener untuk form submission
    if (importForm) {
        importForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Sembunyikan hasil import sebelumnya
            importResultCard.style.display = 'none';
            errorListEl.innerHTML = '';
            
            // Validasi file terpilih
            if (!fileInput.value) {
                alert('Silakan pilih file Excel terlebih dahulu.');
                return;
            }
            
            // Tampilkan overlay
            loadingOverlay.style.display = "flex";
            
            // Reset progress
            importProgress.style.width = "0%";
            importProgress.setAttribute('aria-valuenow', 0);
            progressPercentage.textContent = "0%";
            statusText.textContent = "Memulai proses import...";
            importCancelled = false;
            
            // Mulai timer
            startTime = Date.now();
            progressInterval = setInterval(updateProgress, 1000);
            
            // Kirim form via AJAX
            const formData = new FormData(importForm);
            
            xhr = new XMLHttpRequest();
            
            // Event listener untuk progress upload
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 50; // 50% untuk upload
                    importProgress.style.width = percentComplete + "%";
                    importProgress.setAttribute('aria-valuenow', percentComplete);
                    progressPercentage.textContent = Math.round(percentComplete) + "%";
                    statusText.textContent = "Mengunggah file...";
                }
            });
            
            // Event listener untuk progress download (response)
            xhr.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = 50 + (e.loaded / e.total) * 50; // 50% untuk processing
                    importProgress.style.width = percentComplete + "%";
                    importProgress.setAttribute('aria-valuenow', percentComplete);
                    progressPercentage.textContent = Math.round(percentComplete) + "%";
                    statusText.textContent = "Memproses data...";
                }
            });
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    clearInterval(progressInterval);
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                importProgress.style.width = "100%";
                                importProgress.setAttribute('aria-valuenow', 100);
                                progressPercentage.textContent = "100%";
                                statusText.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-2"></i>Import berhasil!</span>';
                                
                                // Tampilkan hasil import
                                setTimeout(function() {
                                    loadingOverlay.style.display = "none";
                                    showImportResult(response);
                                    importResultTitle.innerHTML = '<i class="fas fa-check-circle me-2"></i>Import Berhasil!';
                                    importResultCard.classList.add('success-animation');
                                }, 1000);
                            } else {
                                statusText.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>' + response.message + '</span>';
                                importResultTitle.innerHTML = '<i class="fas fa-times-circle me-2"></i>Import Gagal';
                                importResultMessage.textContent = response.message;
                                importResultCard.style.display = 'block';
                                cancelButton.textContent = 'Tutup';
                            }
                        } catch (e) {
                            statusText.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan dalam memproses respons</span>';
                            cancelButton.textContent = 'Tutup';
                        }
                    } else {
                        statusText.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan server</span>';
                        cancelButton.textContent = 'Tutup';
                    }
                }
            };
            
            xhr.open('POST', importForm.action, true);
            xhr.send(formData);
        });
    }
    
    // Tombol batalkan
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            importCancelled = true;
            clearInterval(progressInterval);
            
            if (xhr) {
                xhr.abort();
            }
            
            loadingOverlay.style.display = "none";
        });
    }
});
