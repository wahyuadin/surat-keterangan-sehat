<div class="modal fade" id="importData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('user-data.import-excel') }}" method="POST" class="needs-validation"
                enctype="multipart/form-data" novalidate>
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Import Data User || {{ config('app.name') }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label d-block">Seret & Letakkan File Anda di Sini</label>
                        <div id="dropArea" class="border border-3 border-dashed rounded p-5 text-center bg-light d-flex flex-column align-items-center justify-content-center"
                            style="min-height: 200px;">
                            <i class="bi bi-cloud-arrow-up-fill fs-huge text-primary mb-3"></i>
                            <p class="h5 text-muted mb-0">Seret & Letakkan file Excel (.xlsx, .xls) atau CSV di sini</p>
                            <p class="text-muted small">atau klik untuk memilih file</p>
                            <input class="form-control d-none" type="file" id="fileUpload" name="file" accept=".xlsx, .xls, .csv" required>
                            <div class="invalid-feedback">
                                Silakan seret atau pilih file untuk diimport.
                            </div>
                        </div>
                        <div id="fileNameDisplay" class="mt-3 text-center fw-bold fs-6"></div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="templateDownload" class="form-label">Download Template</label>
                        <p class="form-text">
                            Unduh template Excel kami untuk memastikan format data yang benar sebelum import.
                        </p>
                        <a href="{{ route('user-data.template-excel') }}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i> Unduh Template
                        </a>
                    </div>

                    <hr>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // --- JavaScript for Drag & Drop Functionality ---
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileUpload');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const form = document.querySelector('.needs-validation');

        // Custom CSS for larger icon
        const style = document.createElement('style');
        style.innerHTML = `
            .fs-huge {
                font-size: 5rem !important; /* Adjust as needed */
            }
            #dropArea.drag-over {
                border-color: var(--bs-primary) !important;
                background-color: var(--bs-primary-bg-subtle) !important; /* Lighter primary background */
            }
        `;
        document.head.appendChild(style);


        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop area when dragging over
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.add('drag-over'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.remove('drag-over'), false);
        });

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        // Handle file selection from input click (when clicking dropArea)
        dropArea.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                const allowedTypes = [
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                    'application/vnd.ms-excel',                                         // .xls
                    'text/csv'                                                          // .csv
                ];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                const isAllowedExtension = ['xlsx', 'xls', 'csv'].includes(fileExtension);

                if (allowedTypes.includes(file.type) || isAllowedExtension) {
                    fileInput.files = files; // Assign the dropped/selected file to the input
                    fileNameDisplay.textContent = `File terpilih: ${file.name}`;
                    fileNameDisplay.classList.remove('text-danger');
                    fileNameDisplay.classList.add('text-success');
                    fileInput.classList.remove('is-invalid'); // Clear validation feedback if valid
                    fileInput.setCustomValidity(''); // Clear custom validation message
                } else {
                    fileNameDisplay.textContent = `Error: File tidak valid. Hanya .xlsx, .xls, .csv yang diizinkan.`;
                    fileNameDisplay.classList.remove('text-success');
                    fileNameDisplay.classList.add('text-danger');
                    fileInput.value = ''; // Clear the input
                    fileInput.classList.add('is-invalid'); // Add validation feedback
                    fileInput.setCustomValidity('File yang diunggah tidak valid.'); // Set custom validation message
                }
            } else {
                fileNameDisplay.textContent = '';
                fileInput.value = '';
                fileInput.classList.add('is-invalid');
                fileInput.setCustomValidity('Silakan seret atau pilih file untuk diimport.');
            }
        }

        // --- Bootstrap Validation (existing) ---
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>