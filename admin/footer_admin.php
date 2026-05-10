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



    // Sidebar Toggle Logic for Mobile
    const openSidebarBtn = document.getElementById('openSidebar');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (openSidebarBtn && closeSidebarBtn && sidebarMenu && sidebarOverlay) {
        openSidebarBtn.addEventListener('click', function() {
            sidebarMenu.classList.add('show');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        const closeSidebar = function() {
            sidebarMenu.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = 'auto'; // Restore scrolling
        };

        closeSidebarBtn.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
});
</script>
</body>
</html>
