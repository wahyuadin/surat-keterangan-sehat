@extends('template.app')

@section('content')
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div class="container mt-4">
    <div class="card w-100">
        <form action="{{ route('surat.update' , $surat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-lg-4">
                    {{-- Kartu Foto dan Kamera --}}
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-3">Foto Pasien</h5>

                            {{-- Kamera & Preview --}}
                            <video id="video" width="100%" height="auto" autoplay class="rounded mb-2" style="display:none;"></video>
                            <img id="preview" src="{{ $surat->foto ? asset('storage/' . $surat->foto) : '' }}" alt="Foto Pasien" class="rounded mb-2" style="width: 100%; height: auto; aspect-ratio: 3/4; object-fit: cover; {{ $surat->foto ? '' : 'display:none;' }}">

                            {{-- Input base64 --}}
                            <input type="hidden" name="foto" id="gambarInput">

                            {{-- Input file (hanya untuk ambil base64, tidak dikirim langsung) --}}
                            <input type="file" id="fotoFile" accept="image/*" class="form-control mb-2" style="display:none;">

                            {{-- Tombol --}}
                            <div class="d-flex justify-content-center gap-2 mt-2">
                                <button type="button" id="startCamera" class="btn btn-outline-secondary btn-sm">
                                    <i class="bx bx-camera me-1"></i> Gunakan Kamera
                                </button>
                                <button type="button" id="uploadFile" class="btn btn-outline-info btn-sm">
                                    <i class="bx bx-upload me-1"></i> Upload File
                                </button>
                                <button type="button" id="snap" class="btn btn-primary btn-sm" style="display:none;">
                                    <i class="bx bxs-camera me-1"></i> Ambil
                                </button>
                                <button type="button" id="retake" class="btn btn-outline-warning btn-sm" style="display:none;">
                                    <i class="bx bx-refresh me-1"></i> Ulangi
                                </button>
                            </div>

                            <small class="form-text text-muted d-block mt-2">
                                Pilih "Gunakan Kamera" atau "Upload File" untuk mengganti foto.
                            </small>
                        </div>
                    </div>

                    {{-- Kartu Info Pasien (Read-Only) --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-user me-1"></i>
                                Info Pasien
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Nama</strong>
                                    <span>{{ Str::upper($surat->patient->nama_pasien ?? '-')  }}</span>
                                    {{-- <span>Wahyoo</span> --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">No. KTP</strong>
                                    <span>{{ Str::upper($surat->patient->no_ktp ?? '-') }}</span>
                                    {{-- <span>0828937</span> --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Tempat Lahir</strong>
                                    <span>{{ Str::upper($surat->patient->tempat_lahir ?? '-') }}</span>
                                    {{-- <span>rewer</span> --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Tgl. Lahir</strong>
                                    <span>{{ Str::upper($surat->patient->tgl_lahir ?? '-') }}</span>
                                    {{-- <span>342324</span> --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Umur</strong>
                                    <span>
                                        @php
                                        if ($surat->patient?->tgl_lahir) {
                                        $tanggal_lahir = new DateTime($surat->patient->tgl_lahir);
                                        $sekarang = new DateTime();
                                        $umur = $sekarang->diff($tanggal_lahir);

                                        $tahun = $umur->y;
                                        $bulan = $umur->m;

                                        echo $tahun . ' TAHUN ' . $bulan . ' BULAN';
                                        } else {
                                        echo '-';
                                        }
                                        @endphp
                                    </span>
                                    {{-- <span>243342</span> --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Telepon</strong>
                                    <span>{{ Str::upper($surat->patient->telp ?? '-') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Pekerjaan</strong>
                                    <span>{{ Str::upper($surat->patient->pekerjaan ?? '-') }}</span>
                                    {{-- <span>432342342</span> --}}
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <strong class="text-muted">Perusahaan</strong>
                                    <span>{{ Str::upper($surat->patient->customer->nama_perusahaan ?? '-') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-edit-alt me-1"></i>
                                Edit Data Surat
                            </h5>
                            <a href="{{ route('surat.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bx bx-left-arrow-alt me-1"></i> Kembali ke Daftar
                            </a>
                        </div>
                        <div class="card-body">
                            @include('alert')

                            <div class="row g-3">
                                <h6 class="border-bottom pb-2 mb-3">Data Administrasi</h6>
                                <div class="col-md-6">
                                    <label for="no_transaksi" class="form-label">No. Transaksi</label>
                                    <input type="text" class="form-control" value="{{ $surat->no_transaksi ?? null }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_transaksi" class="form-label">Tgl. Transaksi</label>
                                    <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" value="{{ $surat->tgl_transaksi ?? null }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="agent_id" class="form-label">Agent</label>
                                    @if (Auth::user()->role == '1')
                                    @php
                                    $agents = \App\Models\Agent::where('clinic_id', Auth::user()->clinic_id)->with('customer', 'clinic')->latest()->get();
                                    @endphp
                                    @else
                                    @php
                                    $agents = \App\Models\Agent::with('customer')->latest()->get();
                                    @endphp
                                    @endif
                                    <select class="form-select select2" id="agent_id" name="agent_id">
                                        <option value="">Pilih Agent</option>
                                        @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ $surat->agent_id == $agent->id ? 'selected' : '' }}>{{ Str::upper($agent->nama_agent) }} - [{{ $agent->customer->nama_perusahaan }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="paramedis" class="form-label">Paramedis <span class="text-danger">*</span></label>
                                    <select name="paramedis_id" class="form-select select2" required>
                                        <option selected disabled>== Pilih Salah Satu ==</option>
                                        @if(Auth::user()->role == '2')
                                        @php
                                        $paramedis = \App\Models\Paramedis::all();
                                        @endphp
                                        @else
                                        @php
                                        $paramedis = \App\Models\Paramedis::with('clinic')->where('clinic_id', Auth::user()->clinic_id)->get();
                                        @endphp
                                        @endif
                                        @foreach ($paramedis as $paramedItem)
                                        <option value="{{ $paramedItem->id }}" {{ $surat->paramedis_id == $paramedItem->id ? 'selected' : '' }}>{{ Str::upper($paramedItem->nama ?? '-')  }} - [{{ Str::upper($paramedItem->clinic->nama_klinik ?? '-') }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <h6 class="border-bottom pb-2 my-3">Hasil Pemeriksaan Fisik</h6>
                                <div class="col-md-4">
                                    <label for="tinggi_badan" class="form-label">Tinggi Badan</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="tinggi_badan" name="tinggi_badan" value="{{ $surat->tinggi_badan ?? '' }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="berat_badan" class="form-label">Berat Badan</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="berat_badan" name="berat_badan" value="{{ $surat->berat_badan ?? '' }}">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="tensi" class="form-label">Tensi</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="tensi" name="tensi" placeholder="120/80" value="{{ $surat->tensi ?? '' }}">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="suhu" class="form-label">Suhu</label>
                                    <div class="input-group">
                                        <input type="text" step="0.1" class="form-control" id="suhu" name="suhu" placeholder="36.5" value="{{ $surat->suhu ?? '' }}">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="saturnasi" class="form-label">Saturasi</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="saturnasi" name="saturnasi" placeholder="98" value="{{ $surat->saturnasi ?? '' }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="denyutnadi" class="form-label">Denyut Nadi</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="denyutnadi" name="denyutnadi" placeholder="80" value="{{ $surat->denyutnadi ?? '' }}">
                                        <span class="input-group-text">BPM</span>
                                    </div>
                                </div>

                                <h6 class="border-bottom pb-2 my-3">Status & Keperluan</h6>
                                <div class="col-md-6">
                                    <label for="gol_darah" class="form-label">Golongan Darah</label>
                                    <select class="form-select" id="gol_darah" name="gol_darah">
                                        <option value="-" {{ $surat->gol_darah == '-' ? 'selected' : '' }}>-</option>
                                        <option value="A" {{ $surat->gol_darah == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ $surat->gol_darah == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ $surat->gol_darah == 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ $surat->gol_darah == 'O' ? 'selected' : '' }}>O</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="buta_warna" class="form-label">Buta Warna</label>
                                    <select class="form-select" id="buta_warna" name="buta_warna">
                                        <option value="0" {{ $surat->buta_warna == 0 ? 'selected' : '' }}>Tidak</option>
                                        <option value="1" {{ $surat->buta_warna == 1 ? 'selected' : '' }}>Ya</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pendengaran" class="form-label">Pendengaran</label>
                                    <select class="form-select" id="pendengaran" name="pendengaran">
                                        <option value="1" {{ $surat->pendengaran == 1 ? 'selected' : '' }}>Normal</option>
                                        <option value="0" {{ $surat->pendengaran == 0 ? 'selected' : '' }}>Tidak Normal</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="status_kesehatan" class="form-label">Status Kesehatan</label>
                                    <select class="form-select" id="status_kesehatan" name="status_kesehatan">
                                        <option value="1" {{ $surat->status_kesehatan == 1 ? 'selected' : '' }}>Sehat</option>
                                        <option value="0" {{ $surat->status_kesehatan == 0 ? 'selected' : '' }}>Kurang Sehat</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="keperluan" class="form-label">Keperluan</label>
                                    <textarea class="form-control" id="keperluan" name="keperluan" rows="3">{{ $surat->keperluan ?? '' }}</textarea>
                                </div>

                                {{-- Status Pembayaran - Tampilkan jika user role adalah 2 (sesuai logika asli) --}}
                                @if(Auth::user()->role == 2)
                                <h6 class="border-bottom pb-2 my-3">Pembayaran</h6>
                                <div class="col-md-12">
                                    <label for="is_bayar" class="form-label">Status Pembayaran</label>
                                    <select class="form-select" id="is_bayar" name="is_bayar">
                                        <option value="0" {{ $surat->is_bayar == 0 ? 'selected' : '' }}>Menunggu Transaksi</option>
                                        <option value="1" {{ $surat->is_bayar == 1 ? 'selected' : '' }}>Lunas</option>
                                    </select>
                                </div>
                                <!--<div class="col-md-6">-->
                                <!--    <label for="tarif" class="form-label">Tarif</label>-->
                                <!--    <div class="input-group">-->
                                <!--        <span class="input-group-text">Rp</span>-->
                                <!--        <input type="number" class="form-control" id="tarif" name="tarif" value="{{ $surat->patient->customer->tarif ?? '' }}">-->
                                <!--    </div>-->
                                <!--</div>-->
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('style')
{{-- Select2 --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    /* Style untuk memastikan select2 di dalam form terlihat modern */
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 1.5 !important;
    }

</style>
@endpush

@push('scripts')
{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Inisialisasi Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Cari atau pilih data..."
            , theme: "bootstrap-5"
            , allowClear: true
        , });
    });

    const video = document.getElementById('video');
    const startCameraBtn = document.getElementById('startCamera');
    const snapBtn = document.getElementById('snap');
    const retakeBtn = document.getElementById('retake');
    const preview = document.getElementById('preview');
    const gambarInput = document.getElementById('gambarInput');
    const uploadFileBtn = document.getElementById('uploadFile');
    const fotoFile = document.getElementById('fotoFile');
    let stream = null;

    // 🔹 Start kamera
    startCameraBtn.addEventListener("click", () => {
        if (stream) return;
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(s => {
                stream = s;
                video.srcObject = stream;
                video.style.display = "block";
                preview.style.display = "none";
                startCameraBtn.style.display = "none";
                snapBtn.style.display = "inline-block";
                retakeBtn.style.display = "none";
            })
            .catch(err => {
                alert("Gagal mengakses kamera: " + err.message);
            });
    });

    // 🔹 Stop kamera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
        video.style.display = "none";
    }

    // 🔹 Ambil foto
    snapBtn.addEventListener("click", () => {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        const targetWidth = 600
            , targetHeight = 800;
        canvas.width = targetWidth;
        canvas.height = targetHeight;

        const vw = video.videoWidth
            , vh = video.videoHeight;
        const ratio = targetWidth / targetHeight;
        let sx, sy, sw, sh;

        if (vw / vh > ratio) {
            sh = vh;
            sw = vh * ratio;
            sx = (vw - sw) / 2;
            sy = 0;
        } else {
            sw = vw;
            sh = vw / ratio;
            sx = 0;
            sy = (vh - sh) / 2;
        }

        ctx.drawImage(video, sx, sy, sw, sh, 0, 0, targetWidth, targetHeight);
        const dataUrl = canvas.toDataURL("image/png");

        preview.src = dataUrl;
        preview.style.display = "block";
        gambarInput.value = dataUrl;

        stopCamera();
        snapBtn.style.display = "none";
        retakeBtn.style.display = "inline-block";
    });

    // 🔹 Upload manual
    uploadFileBtn.addEventListener('click', () => {
        stopCamera();
        fotoFile.click();
    });

    fotoFile.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
            gambarInput.value = e.target.result; // base64
        };
        reader.readAsDataURL(file);
    });

    // 🔹 Ulangi foto
    retakeBtn.addEventListener("click", () => {
        preview.style.display = "none";
        gambarInput.value = "";
        startCamera();
    });

</script>
@endpush
