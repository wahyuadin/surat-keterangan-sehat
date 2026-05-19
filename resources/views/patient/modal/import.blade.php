<!-- Modal Import Modern -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <!-- Header Modal -->
            <div class="modal-header border-0 pb-0 justify-content-center position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mt-4">
                    <i class='bx bx-cloud-upload fs-1'></i>
                    <h4 class="modal-title fw-bold" id="importModalLabel">Import Data Pasien</h4>
                    <p class="text-muted small mb-0">Unggah file Excel Anda untuk menambahkan data pasien secara massal.</p>
                </div>
            </div>

            <form id="formUploadExcel" enctype="multipart/form-data">
                <div class="modal-body px-4 pt-4 pb-3">

                    <!-- Area Upload Custom (Sudah diperbaiki) -->
                    <div class="upload-dropzone mb-4" id="dropzoneArea">
                        <input type="file" id="file_excel" name="file_excel" accept=".xlsx, .xls, .csv" required>

                        <div class="icon-upload-wrapper">
                            <i class='bx bx-spreadsheet fs-2'></i>
                        </div>
                        <h6 class="fw-semibold mb-1 text-dark">Klik atau Seret file ke sini</h6>
                        <p class="text-muted small mb-0">Mendukung file .xlsx, .xls, atau .csv</p>

                        <!-- Tempat menampilkan nama file yang diunggah -->
                        <div id="fileNameDisplay" class="mt-3 text-primary fw-bold small d-none"></div>
                    </div>

                    <!-- Area Download Template -->
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-4 bg-white border border-light-subtle shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3">
                                <i class='bx bx-file-blank text-primary fs-4'></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 0.9rem;">Format Standar</h6>
                                <small class="text-muted">Unduh template agar sesuai database</small>
                            </div>
                        </div>
                        <a href="{{ route('patient.template') }}" id="btnDownloadTemplate" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium" style="z-index: 11;">
                            <i class='bx bx-download'></i> Unduh
                        </a>
                    </div>

                </div>

                <!-- Footer Modal -->
                <div class="modal-footer border-0 px-4 pb-4 pt-2 justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4 text-muted fw-medium" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium d-flex align-items-center" id="btnPreviewImport">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="previewImportModal" tabindex="-1" aria-labelledby="previewImportModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <!-- PERBAIKAN: Tag form digabungkan dengan class modal-content -->
        <form id="formProsesImport" class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">

            <div class="modal-header border-0 pb-0 mt-2 px-4">
                <h5 class="modal-title fw-bold" id="previewImportModalLabel">Preview & Mapping Kolom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-4">
                <p>Cocokkan kolom dari file Excel Anda (baris abu-abu) dengan kolom di Database pada menu *dropdown*. Pastikan kolom wajib seperti <strong>Nama Pasien</strong> dan <strong>No KTP</strong> terpilih.</p>

                <!-- Tempat Tabel Preview Dirender oleh JS -->
                <div class="table-responsive border rounded-3">
                    <table class="table table-bordered table-hover mb-0 align-middle" id="tablePreview">
                        <thead class="table-light">
                            <tr id="rowExcelHeaders">
                                <!-- Header Excel masuk sini -->
                            </tr>
                            <tr id="rowMappingSelects">
                                <!-- Dropdown Mapping masuk sini -->
                            </tr>
                        </thead>
                        <tbody id="bodyPreviewData">
                            <!-- Data Excel 5 baris pertama masuk sini -->
                        </tbody>
                    </table>
                </div>

                <!-- Input hidden untuk menampung path file yang akan di proses pada tahap final -->
                <input type="hidden" id="temp_file_path" name="temp_file_path">
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-between">
                <button type="button" class="btn btn-light text-muted px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success px-4 rounded-pill d-flex align-items-center" id="btnProsesImport">
                    <i class='bx bx-save me-2'></i> Proses Import Sekarang
                </button>
            </div>

        </form>

    </div>
</div>

@push('style')
<style>
    /* Styling khusus untuk area upload agar elegan dan modern */
    .upload-dropzone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 1rem;
        background-color: #f8fafc;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease-in-out;
    }

    .upload-dropzone:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .upload-dropzone input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }

    .icon-upload-wrapper {
        width: 64px;
        height: 64px;
        background-color: #eff6ff;
        color: #3b82f6;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $('#btnDownloadTemplate').on('click', function(e) {
        e.preventDefault();

        let btn = $(this);
        let url = btn.attr('href');
        let originalHtml = btn.html();

        btn.html(`<i class='bx bx-loader-alt bx-spin'></i> Menyiapkan...`);
        btn.addClass('disabled').css('pointer-events', 'none');

        setTimeout(function() {
            window.location.href = url;
            setTimeout(function() {
                btn.html(originalHtml);
                btn.removeClass('disabled').css('pointer-events', 'auto');
            }, 1000);

        }, 1000);
    });
    function notify(message, status) {
        let style = {};

        if (status === "success") {
            style = {
                color: "#fff",
                background: "linear-gradient(to right, #00b09b, #96c93d)",
                borderRadius: "0.5rem",
            };
        } else if (status === "warning") {
            style = {
                color: "#fff",
                background: "#f59e0b",
                borderRadius: "0.5rem",
                boxShadow: "0 0 10px rgba(245, 158, 11, 0.5)",
            };
        } else if (status === "error") {
            style = {
                color: "#fff",
                background: "#d63939",
                borderRadius: "0.5rem",
                boxShadow: "0 0 10px rgba(214, 57, 57, 0.5)",
            };
        }

        Toastify({
            text: message,
            duration: 1500,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: style,
        }).showToast();
    }
    // Script interaksi file yang sudah disesuaikan dengan ID dropzoneArea
    document.getElementById('file_excel').addEventListener('change', function(e) {
        let fileName = e.target.files[0] ? e.target.files[0].name : '';
        let display = document.getElementById('fileNameDisplay');
        let dropzone = document.getElementById('dropzoneArea');

        if(fileName) {
            display.innerHTML = "<i class='bx bx-check-circle'></i> File terpilih: " + fileName;
            display.classList.remove('d-none');
            // Menambahkan efek visual saat file dipilih
            dropzone.style.borderColor = '#3b82f6';
            dropzone.style.backgroundColor = '#eff6ff';
        } else {
            display.classList.add('d-none');
            // Mengembalikan efek visual saat file batal dipilih
            dropzone.style.borderColor = '#cbd5e1';
            dropzone.style.backgroundColor = '#f8fafc';
        }
    });
     $("#formUploadExcel").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btnPreview = $("#btnPreviewImport");
            let originalBtnText = btnPreview.html();
            btnPreview.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...`).prop('disabled', true);
            document.getElementById('loading-overlay').classList.remove('d-none');

            $.ajax({
                url: "{{ route('patient.preview') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(res) {
                    document.getElementById('loading-overlay').classList.add('d-none');
                    btnPreview.html(originalBtnText).prop('disabled', false);

                    if (res.status === 'success') {
                        // Sembunyikan modal upload, tampilkan modal preview
                        $("#importModal").modal("hide");

                        // Render Header Excel
                        let thHeaders = '';
                        res.headers.forEach(header => {
                            thHeaders += `<th class="text-center bg-secondary bg-opacity-10 text-dark py-3">${header}</th>`;
                        });
                        $("#rowExcelHeaders").html(thHeaders);
                        let tdSelects = '';
                        res.headers.forEach((header, index) => {
                            let options = '';

                            // Tambahkan opsi default kosong
                            options += `<option value="">-- Pilih Kolom --</option>`;

                            for (let key in res.db_columns) {
                                if (key === '') continue;
                                let headerClean = header.toLowerCase().trim();
                                let keyClean = key.toLowerCase().trim();
                                let labelClean = res.db_columns[key].toLowerCase().trim();

                                let selected = (headerClean === keyClean || labelClean.includes(headerClean) || headerClean.includes(labelClean)) ? 'selected' : '';

                                options += `<option value="${key}" ${selected}>${res.db_columns[key]}</option>`;
                            }
                            tdSelects += `
                                <td class="bg-light p-2">
                                    <select name="mapping[${index}]" class="form-select form-select-sm border-primary shadow-sm" required>
                                        ${options}
                                    </select>
                                </td>
                            `;
                        });
                        $("#rowMappingSelects").html(tdSelects);

                        let trData = '';
                        res.preview_data.forEach(row => {
                            trData += '<tr>';
                            row.forEach(cell => {
                                trData += `<td class="text-muted small">${cell !== null ? cell : '-'}</td>`;
                            });
                            trData += '</tr>';
                        });
                        $("#bodyPreviewData").html(trData);
                        $("#temp_file_path").val(res.temp_file_path);
                        $("#previewImportModal").modal("show");
                    }
                },
                error: function(err) {
                    document.getElementById('loading-overlay').classList.add('d-none');
                    btnPreview.html(originalBtnText).prop('disabled', false);

                    let errorMsg = 'Terjadi kesalahan sistem.';
                    if (err.responseJSON) {
                        if (err.responseJSON.errors) {
                            errorMsg = Object.values(err.responseJSON.errors)[0][0];
                        } else if (err.responseJSON.message) {
                            errorMsg = err.responseJSON.message;
                        } else if (err.responseJSON.error) {
                            errorMsg = err.responseJSON.error;
                        }
                    }
                    notify("Gagal memproses file: " + errorMsg, "error");
                }
            });
        });

        $("#formProsesImport").submit(function(e) {
            e.preventDefault();

            let btnSubmit = $("#btnProsesImport");
            let originalBtnText = btnSubmit.html();

            btnSubmit.html(`<i class='bx bx-loader-alt bx-spin me-2'></i> Memproses Data...`).prop('disabled', true);
            document.getElementById('loading-overlay').classList.remove('d-none');

            $.ajax({
                url: "{{ route('patient.import.process') }}",
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function(res) {
                    document.getElementById('loading-overlay').classList.add('d-none');
                    $("#previewImportModal").modal("hide");

                    // Evaluasi respon dari server
                    if (res.status === 'warning' && res.error_url) {
                        notify(res.message + " Mengunduh rincian log error otomatis...", "warning");
                        let a = document.createElement('a');
                        a.href = res.error_url;
                        a.download = res.error_file;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    } else {
                        notify(res.message, "success");
                    }

                    console.log(res);
                    btnSubmit.html(originalBtnText).prop('disabled', false);

                    // Perbaikan kondisi reload: Jika table tidak ada, reload halaman
                    if (typeof table !== 'undefined') {
                        table.ajax.reload(null, false);
                    } else {
                        location.reload();
                    }
                },
                error: function(err) {
                    document.getElementById('loading-overlay').classList.add('d-none');
                    btnSubmit.html(originalBtnText).prop('disabled', false);
                    let errMsg = err.responseJSON ? (err.responseJSON.error || err.responseJSON.message) : 'Terjadi kesalahan sistem.';
                    notify("Gagal mengimport data: " + errMsg, "error");
                }
            });
        });
</script>
@endpush
