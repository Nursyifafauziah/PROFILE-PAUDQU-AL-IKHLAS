        </div> <!-- End Content -->
    </div> <!-- End Row -->
</div> <!-- End Container Fluid -->

<!-- Global Crop Modal -->
<div class="modal fade" id="globalCropModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="globalCropModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="globalCropModalLabel">Sesuaikan Foto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="img-container" style="max-height: 60vh;">
          <img id="imageToCrop" src="" alt="Picture" style="max-width: 100%;">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary rounded-pill" id="btnCropSave">Pangkas & Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1100">
  <div id="pesanToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="7000">
    <div class="d-flex">
      <div class="toast-body fw-bold">
        <i class="fas fa-bell me-2 fs-5 align-middle"></i> Anda memiliki pesan baru masuk!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Cropper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let cropper;
    let currentFileInput;
    const imageToCrop = document.getElementById('imageToCrop');
    const cropModal = new bootstrap.Modal(document.getElementById('globalCropModal'));
    const btnCropSave = document.getElementById('btnCropSave');

    // Attach listener to all image file inputs
    const fileInputs = document.querySelectorAll('input[type="file"][accept^="image"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function (e) {
            const files = e.target.files;
            
            // Check if file is already cropped (we set a custom property to avoid infinite loop)
            if (this.dataset.cropped === "true") {
                this.dataset.cropped = "false";
                return;
            }

            if (files && files.length > 0) {
                const file = files[0];
                this.originalFile = file; // Save the original uncropped file
                const reader = new FileReader();
                currentFileInput = this; // Store reference to the input

                reader.onload = function (event) {
                    imageToCrop.src = event.target.result;
                    cropModal.show();
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Initialize Cropper when modal is shown
    document.getElementById('globalCropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: NaN, // Free crop by default
            viewMode: 1,
            autoCropArea: 1,
        });
    });

    // Destroy Cropper when modal is hidden
    document.getElementById('globalCropModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // If user canceled, we might want to clear the file input to allow re-selection
        // But for simplicity, we let the original file stay if they just cancel.
    });

    // Handle Crop & Save
    btnCropSave.addEventListener('click', function () {
        if (!cropper) return;

        cropper.getCroppedCanvas({
            maxWidth: 1920,
            maxHeight: 1080
        }).toBlob((blob) => {
            if (!blob) return;

            // Generate a filename
            const originalName = (currentFileInput && currentFileInput.files.length > 0) ? currentFileInput.files[0].name : 'cropped_image.jpg';
            const croppedFile = new File([blob], "cropped_" + originalName, { type: blob.type, lastModified: new Date().getTime() });

            // Create a new DataTransfer to replace the input files
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(croppedFile);

            // Set the new files
            if (currentFileInput) {
                currentFileInput.dataset.cropped = "true";
                currentFileInput.files = dataTransfer.files;

                // Update preview image if target is specified
                const previewId = currentFileInput.getAttribute('data-preview');
                if (previewId) {
                    const previewImg = document.getElementById(previewId);
                    if (previewImg) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                        };
                        reader.readAsDataURL(croppedFile);
                    }
                }
            }

            cropModal.hide();
        }, (currentFileInput && currentFileInput.files.length > 0) ? (currentFileInput.files[0].type || 'image/jpeg') : 'image/jpeg');
    });

    // Global function to trigger crop manually via button
    window.triggerCrop = function(previewId, inputId) {
        const input = document.getElementById(inputId);
        const previewImg = document.getElementById(previewId);
        
        if (!input || !previewImg) return;

        // If user already selected a file, load the original uncropped file
        if (input.originalFile) {
            const reader = new FileReader();
            currentFileInput = input;
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(input.originalFile);
        } else if (input.files && input.files.length > 0) {
            const file = input.files[0];
            const reader = new FileReader();
            currentFileInput = input;
            reader.onload = function(e) {
                imageToCrop.src = e.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(file);
        } else {
            // No file selected, but maybe there's an existing image on the server
            if (previewImg.src.includes('placeholder') || previewImg.src.includes('ui-avatars')) {
                alert('Silakan pilih foto baru terlebih dahulu sebelum melakukan pangkas (crop).');
                return;
            }

            // Fetch the existing image, convert to File, set to input, then crop
            fetch(previewImg.src)
                .then(res => res.blob())
                .then(blob => {
                    const filename = previewImg.src.split('/').pop() || 'existing_image.jpg';
                    const file = new File([blob], filename, { type: blob.type || "image/jpeg" });
                    
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    input.originalFile = file; // Save original for re-cropping
                    
                    currentFileInput = input;
                    imageToCrop.src = previewImg.src;
                    cropModal.show();
                })
                .catch(err => {
                    alert('Gagal memuat foto dari server untuk diedit. Pastikan Anda memilih file baru.');
                });
        }
    };

    // Notifikasi Pesan Masuk (Polling)
    let unreadCount = <?= isset($unread_count) ? (int)$unread_count : 0 ?>;
    const notifSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');

    setInterval(() => {
        fetch('cek_pesan_baru.php')
            .then(response => response.json())
            .then(data => {
                if (data.unread > unreadCount) {
                    // Ada pesan baru masuk!
                    unreadCount = data.unread;
                    const toastEl = document.getElementById('pesanToast');
                    if (toastEl) {
                        const toast = new bootstrap.Toast(toastEl);
                        toast.show();
                    }
                    notifSound.play().catch(e => console.log('Audio autoplay prevented'));
                    
                    // Update badge di sidebar
                    const badge = document.querySelector('.sidebar a[href="pesan.php"] .badge');
                    if (badge) {
                        badge.textContent = unreadCount;
                    } else {
                        const pesanLink = document.querySelector('.sidebar a[href="pesan.php"]');
                        if (pesanLink) {
                            pesanLink.innerHTML += ' <span class="badge bg-danger ms-2 rounded-pill">' + unreadCount + '</span>';
                        }
                    }

                } else {
                    // Update count jika pesan dibaca
                    unreadCount = data.unread;
                    
                    // Update badge di sidebar jika berkurang
                    const badge = document.querySelector('.sidebar a[href="pesan.php"] .badge');
                    if (badge && unreadCount === 0) {
                        badge.remove();
                    } else if (badge) {
                        badge.textContent = unreadCount;
                    }
                }
            })
            .catch(err => console.error('Error checking new messages:', err));
    }, 10000); // Cek setiap 10 detik
});
</script>
</body>
</html>
