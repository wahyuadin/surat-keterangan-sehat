<div class="modal fade" id="addParamedis" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addparamedisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <form action="{{ route('paramedis.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addparamedisLabel">Tambah Data paramedis</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama paramedis -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama paramedis <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama Dokter/Perawat/Paramedis/Lainnya" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_paramedis" class="form-label">Asal Klinik <span class="text-danger">*</span></label>
                        @if(Auth::user()->role == '2')
                        <select name="clinic_id" required class="form-select select2">
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            @php
                            $klinik = \App\Models\Clinic::all();
                            @endphp
                            @foreach ($klinik as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_klinik }} - {{ $item->alamat }}</option>
                            @endforeach
                        </select>
                        @else
                        @php
                        $klinik = \App\Models\Clinic::where('id', Auth::user()->clinic_id)->first();
                        @endphp
                        <input type="text" value="{{ Str::upper($klinik->nama_klinik) }}" readonly class="form-control">
                        <input type="text" name="clinic_id" value="{{ $klinik->id }}" hidden class="form-control">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="bagian" class="form-label">Bagian <span class="text-danger">*</span></label>
                        <select name="bagian" required class="form-select select2" id="bagianSelect" onchange="toggleTtd()">
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            <option value="perawat">Perawat</option>
                            <option value="dokter">Dokter</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3" id="ttd_scan_container" style="display:none;">
                        <label for="bagian" class="form-label">TTD Scan</label>
                        <input type="file" class="form-control" name="ttd" id="ttd_scan" onchange="previewImage(this, 'ttd_preview')">
                        <img id="ttd_preview" src="#" alt="Preview TTD" style="max-width: 200px; margin-top: 10px; display: none;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = "block";
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "#";
            preview.style.display = "none";
        }
    }

    function toggleTtd() {
        var bagian = document.getElementById("bagianSelect").value;
        var ttdContainer = document.getElementById("ttd_scan_container");

        if (bagian === "dokter") {
            ttdContainer.style.display = "block";
        } else {
            ttdContainer.style.display = "none";
        }
    }

</script>
@endpush
