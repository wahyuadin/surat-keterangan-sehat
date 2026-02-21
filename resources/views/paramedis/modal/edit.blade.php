@foreach ($data as $dataEdit)
<div class="modal fade" id="editParamedis{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addparamedisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('paramedis.update', $dataEdit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editparamedisLabel{{ $dataEdit->id }}">Edit Data paramedis</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_paramedis" class="form-label">Nama paramedis <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ $dataEdit->nama }}" placeholder="Masukkan nama paramedis" required>
                    </div>

                    <div class="mb-3">
                        <label for="clinic_id" class="form-label">clinic Office <span class="text-danger">*</span></label>
                        <select name="clinic_id" required class="form-select select2">
                            <option disabled>== Pilih Salah Satu ==</option>
                            @if (Auth::user()->role == '1')
                            @php
                            $clinicData = \App\Models\Clinic::where('id', Auth::user()->clinic_id)->get();
                            @endphp
                            @else
                            @php
                            $clinicData = \App\Models\Clinic::all();
                            @endphp
                            @endif
                            @foreach ($clinicData as $clinic)
                            <option value="{{ $clinic->id }}" {{ $dataEdit->clinic_id == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->nama_klinik }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bagian" class="form-label">Bagian <span class="text-danger">*</span></label>
                        <select name="bagian" required class="form-select bagianSelectEdit" data-id="{{ $dataEdit->id }}">
                            <option disabled {{ empty($dataEdit->bagian) ? 'selected' : '' }}>== Pilih Salah Satu ==</option>
                            <option value="perawat" {{ $dataEdit->bagian == 'perawat' ? 'selected' : '' }}>Perawat</option>
                            <option value="dokter" {{ $dataEdit->bagian == 'dokter' ? 'selected' : '' }}>Dokter</option>
                            <option value="lainnya" {{ $dataEdit->bagian == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3 ttd_scan_container_edit" id="ttd_scan_container_edit{{ $dataEdit->id }}" style="display:none;">
                        <label class="form-label">TTD Scan</label>
                        <input type="file" class="form-control ttd_scan_input" data-preview="ttd_previewEdit{{ $dataEdit->id }}" name="ttd">
                        <img id="ttd_previewEdit{{ $dataEdit->id }}" src="{{ $dataEdit->ttd ? asset('storage/' . $dataEdit->ttd) : '' }}" alt="Preview TTD" style="max-width:200px; margin-top:10px; {{ $dataEdit->ttd ? '' : 'display:none;' }}">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image
    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("ttd_scan_input")) {
            const previewId = e.target.getAttribute("data-preview");
            const preview = document.getElementById(previewId);
            const file = e.target.files[0];

            if (file && preview) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    preview.src = reader.result;
                    preview.style.display = "block";
                }
                reader.readAsDataURL(file);
            } else if (preview) {
                preview.src = "";
                preview.style.display = "none";
            }
        }
    });

    // Toggle TTD container on bagian change
    function toggleTtdEdit(select) {
        const id = select.getAttribute("data-id");
        const ttdContainer = document.getElementById("ttd_scan_container_edit" + id);
        if (!ttdContainer) return;

        if (select.value === "dokter") {
            ttdContainer.style.display = "block";
        } else {
            ttdContainer.style.display = "none";
        }
    }

    // Pasang event listener ke semua select
    document.querySelectorAll(".bagianSelectEdit").forEach(function(select) {
        select.addEventListener("change", function() {
            toggleTtdEdit(this);
        });
        // set kondisi awal
        toggleTtdEdit(select);
    });

</script>
@endpush

@endforeach
