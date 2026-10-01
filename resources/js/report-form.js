import { Html5Qrcode } from 'html5-qrcode';

let html5QrcodeScanner = null;
let isScannerRunning = false;
const selectedPhotos = [];

// Toggle between Scanner and Manual modes
function toggleMode(mode) {
    const scannerView = document.getElementById('scanner-view');
    const manualView = document.getElementById('manual-view');
    const modeScanner = document.getElementById('mode-scanner');
    const modeManual = document.getElementById('mode-manual');
    const toggleBackground = document.getElementById('toggle-bg');

    if (mode === 'scanner') {
        scannerView.classList.remove('hidden');
        manualView.classList.add('hidden');
        toggleBackground.style.transform = 'translateX(0)';
        modeScanner.classList.remove('text-white');
        modeScanner.classList.add('text-primary');
        modeManual.classList.remove('text-primary');
        modeManual.classList.add('text-white');
        
        // Start scanner when switching to scanner mode
        setTimeout(() => startScanner(), 100);
    } else {
        manualView.classList.remove('hidden');
        scannerView.classList.add('hidden');
        toggleBackground.style.transform = 'translateX(100%)';
        modeManual.classList.remove('text-white');
        modeManual.classList.add('text-primary');
        modeScanner.classList.remove('text-primary');
        modeScanner.classList.add('text-white');
        
        // Stop scanner when switching to manual mode
        if (isScannerRunning) {
            stopScanner();
        }
    }
}

// QR Scanner Functions
function startScanner() {
    if (html5QrcodeScanner) {
        try {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner = null;
                initializeScanner();
            }).catch(() => {
                html5QrcodeScanner = null;
                initializeScanner();
            });
        } catch (e) {
            html5QrcodeScanner = null;
            initializeScanner();
        }
    } else {
        initializeScanner();
    }
}

function initializeScanner() {
    html5QrcodeScanner = new Html5Qrcode("qr-reader");

    const config = {
        fps: 15,
        aspectRatio: 1.0,
        disableFlip: false,
        rememberLastUsedCamera: true,
        showTorchButtonIfSupported: true
    };

    html5QrcodeScanner.start(
        { facingMode: "environment" },
        config,
        onQrCodeSuccess,
        onQrCodeError
    ).then(() => {
        isScannerRunning = true;
    }).catch(err => {
        console.error("Camera error:", err);
        let errorMsg = 'Tidak dapat mengakses kamera.';
        if (err && err.message) {
            if (err.message.includes('Permission') || err.message.includes('permission')) {
                errorMsg = 'Silakan berikan izin akses kamera di setting browser Anda.';
            } else if (err.message.includes('NotFound')) {
                errorMsg = 'Kamera tidak ditemukan di device Anda.';
            } else if (err.message.includes('NotAllowed')) {
                errorMsg = 'Akses kamera ditolak. Gunakan mode Input Manual sebagai alternatif.';
            } else if (err.message.includes('http')) {
                errorMsg = 'Kamera hanya bisa diakses melalui HTTPS atau localhost.';
            }
        }
        alert(errorMsg + '\n\nGunakan tab "Input Manual" untuk memilih unit secara manual.');
        isScannerRunning = false;
    });
}

function stopScanner() {
    if (html5QrcodeScanner && isScannerRunning) {
        html5QrcodeScanner.stop().then(() => {
            isScannerRunning = false;
        }).catch(err => {
            console.error("Error stopping scanner:", err);
            isScannerRunning = false;
        });
    }
}

function onQrCodeSuccess(decodedText, decodedResult) {
    console.log(`QR Code detected: ${decodedText}`);
    selectUnit(decodedText);
    stopScanner();
    // Switch to manual mode after successful scan
    toggleMode('manual');
}

function onQrCodeError(errorMessage) {
    // Silent - don't log errors during continuous scanning
}

// Load units based on selected company
function loadUnits(compId) {
    if (!compId) {
        document.getElementById('manual_unitId').innerHTML = '<option value="" disabled selected hidden>-- Pilih Unit --</option>';
        return;
    }

    fetch(`/api/companies/${compId}/units`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="" disabled selected hidden>-- Pilih Unit --</option>';
            if (data && data.length > 0) {
                data.forEach(unit => {
                    options += `<option value="${unit.unitId}">${unit.unitNumber} - ${unit.unitName}</option>`;
                });
            } else {
                options += '<option value="" disabled>Tidak ada unit tersedia</option>';
            }
            document.getElementById('manual_unitId').innerHTML = options;
        })
        .catch(error => {
            console.error('Error loading units:', error);
            alert('Gagal memuat data unit. Silakan coba lagi.');
        });
}

// Select unit
function selectUnit(unitId) {
    if (!unitId) {
        document.getElementById('unit-info').classList.add('hidden');
        document.getElementById('unitId').value = '';
        document.getElementById('compId').value = '';
        return;
    }

    fetch(`/api/units/${unitId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const unit = data.data;
                document.getElementById('unitId').value = unit.unitId;
                document.getElementById('compId').value = unit.compId;

                // Update info display
                document.getElementById('info-unit-number').textContent = unit.unitNumber;
                document.getElementById('info-unit-name').textContent = unit.unitName;
                document.getElementById('info-room-name').textContent = unit.room?.roomName || '-';
                document.getElementById('info-company-name').textContent = unit.company?.name || '-';

                document.getElementById('unit-info').classList.remove('hidden');

                // Set report date
                const now = new Date();
                document.getElementById('reportDate').value = now.toISOString();

                // Scroll to form
                document.querySelector('main').scrollIntoView({ behavior: 'smooth' });
            }
        })
        .catch(error => {
            console.error('Error fetching unit data:', error);
            alert('Gagal memuat data unit. Silakan coba lagi.');
        });
}

// File upload handling - keep files compact and display their thumbnails
function validatePhoto(file) {
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        alert(`File terlalu besar. Maksimal 5MB. File Anda: ${(file.size / 1024 / 1024).toFixed(2)}MB`);
        return false;
    }

    if (!['image/png', 'image/jpg', 'image/jpeg', 'image/webp'].includes(file.type)) {
        alert('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
        return false;
    }

    return true;
}

function syncPhotoInputs() {
    for (let index = 1; index <= 5; index++) {
        const input = document.getElementById(`photo-${index}`);
        const photoDisplay = document.querySelector(`.photo-display-${index}`);
        const fileInfo = document.getElementById(`file-info-${index}`);
        const photoPreview = document.getElementById(`photo-preview-${index}`);
        const removeButton = document.getElementById(`remove-photo-${index}`);
        const file = selectedPhotos[index - 1];
        const transfer = new DataTransfer();

        if (file) {
            transfer.items.add(file);
            const reader = new FileReader();
            reader.onload = (event) => {
                photoPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);

            photoDisplay.classList.add('hidden');
            fileInfo.classList.remove('hidden');
            removeButton.classList.remove('hidden');
            removeButton.classList.add('flex');
        } else {
            photoPreview.removeAttribute('src');
            photoDisplay.classList.remove('hidden');
            fileInfo.classList.add('hidden');
            removeButton.classList.add('hidden');
            removeButton.classList.remove('flex');
        }

        input.files = transfer.files;
    }
}

function addPhoto(file, index) {
    if (!file || !validatePhoto(file)) {
        syncPhotoInputs();
        return;
    }

    selectedPhotos[index - 1] = file;
    const compactedPhotos = selectedPhotos.filter(Boolean).slice(0, 5);
    selectedPhotos.splice(0, selectedPhotos.length, ...compactedPhotos);
    syncPhotoInputs();
}

function removePhoto(index) {
    selectedPhotos.splice(index - 1, 1);
    syncPhotoInputs();
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Initialize event listeners
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupEventListeners);
} else {
    setupEventListeners();
}

function setupEventListeners() {
    // Set report date on form load
    const now = new Date();
    document.getElementById('reportDate').value = now.toISOString();

    // Mode toggle buttons
    const modeScanner = document.getElementById('mode-scanner');
    const modeManual = document.getElementById('mode-manual');

    if (modeScanner) {
        modeScanner.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleMode('scanner');
        });
    }

    if (modeManual) {
        modeManual.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleMode('manual');
        });
    }

    // Photo input handlers
    for (let i = 1; i <= 5; i++) {
        const photoInput = document.getElementById(`photo-${i}`);
        if (photoInput) {
            photoInput.addEventListener('change', function () {
                addPhoto(this.files[0], i);
            });

            const removeButton = document.getElementById(`remove-photo-${i}`);
            removeButton.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                removePhoto(i);
            });

            // Drag and drop
            const dropZone = photoInput.closest('.photo-drop-zone');
            if (dropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, { passive: false });
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.add('border-primary', 'bg-primary/5');
                    }, { passive: true });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.remove('border-primary', 'bg-primary/5');
                    }, { passive: true });
                });

                dropZone.addEventListener('drop', (e) => {
                    addPhoto(e.dataTransfer.files[0], i);
                }, { passive: false });
            }
        }
    }

    // Form validation
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
        reportForm.addEventListener('submit', function (e) {
            if (!document.getElementById('unitId').value) {
                e.preventDefault();
                alert('Silakan pilih unit/barang terlebih dahulu');
                return false;
            }
        });
    }
}

// Export functions for global use
window.toggleMode = toggleMode;
window.loadUnits = loadUnits;
window.selectUnit = selectUnit;
