<div class="modal fade" id="addpatient" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addpatientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('patient.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addpatientLabel">Tambah Data Pasien</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pasien" class="form-control" placeholder="Masukkan Nama Pasien" required value="{{ old('nama_pasien') }}">
                    </div>
                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Klinik <span class="text-danger">*</span></label>
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
                        <input type="text" value="{{ Str::upper($klinik->nama_klinik ?? '-') }}" readonly class="form-control">
                        <input type="text" name="clinic_id" value="{{ $klinik->id ?? '-'}}" hidden class="form-control">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Customer <span class="text-danger">*</span></label>
                        <select name="customer_id" class="form-select select2" required>
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            @php
                            $clinic = \App\Models\Customer::all();
                            foreach ($clinic as $data) {
                            echo "<option value='" . $data->id . "'>" . $data->nama_perusahaan . '</option>';
                            }
                            @endphp
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            <option value="1">Laki Laki</option>
                            <option value="0">Perempuan</option>
                        </select>
                    </div>

                    <!-- Alamat klinik -->
                    <div class="mb-3">
                        <label for="no_ktp" class="form-label">No KTP</label>
                        <input type="number" name="no_ktp" required class="form-control" value="{{ old('no_ktp') }}" placeholder="Masukan No KTP">
                    </div>
                    <div class="mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" required class="form-control" value="{{ old('pekerjaan') }}" placeholder="Masukan Pekerjaan">
                    </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" required class="form-control" value="{{ old('tempat_lahir') }}" placeholder="Masukan Tempat Lahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" required class="form-control" value="{{ old('tgl_lahir') }}">
                    </div>
                    <div class="mb-3">
                        <label for="telp" class="form-label">No Telp</label>
                        <input type="number" name="telp" class="form-control" placeholder="Masukan No Telp" value="{{ old('telp') }}">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat pasien (opsional)">{{ old('alamat') }}</textarea>
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
