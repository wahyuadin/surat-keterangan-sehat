@foreach ($data as $dataEdit)
<div class="modal fade" id="editklinik{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addklinikLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('klinik.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editKlinikLabel{{ $dataEdit->id }}">Edit Data Klinik</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_klinik" class="form-label">Nama Klinik <span class="text-danger">*</span></label>
                        <input type="text" name="nama_klinik" id="nama_klinik" class="form-control" value="{{ $dataEdit->nama_klinik }}" placeholder="Masukkan nama klinik" required>
                    </div>
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Klinik <span class="text-danger">*</span></label>
                        <input type="text" name="kode" id="kode" class="form-control" value="{{ $dataEdit->kode }}" placeholder="Masukkan kode klinik" required>
                    </div>

                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Branch Office <span class="text-danger">*</span></label>
                        <select name="branch_id" required class="form-select">
                            <option disabled>== Pilih Salah Satu ==</option>
                            @foreach(\App\Models\Branch::all() as $branch)
                            <option value="{{ $branch->id }}" {{ $dataEdit->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_branch }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tlpn" class="form-label">No Telp</label>
                        <input type="number" name="tlpn" id="tlpn" class="form-control" value="{{ $dataEdit->tlpn }}" placeholder="Masukkan No Telp" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $dataEdit->email }}" placeholder="Masukkan Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_serti" class="form-label">No Sertifikat standart</label>
                        <input type="text" name="no_serti" id="no_serti" class="form-control" value="{{ $dataEdit->no_serti }}" placeholder="Masukkan No Sertifikat Standart" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat klinik (opsional)">{{ $dataEdit->alamat }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text" name="kota" id="kota" class="form-control" placeholder="Masukkan Kota" required value="{{ $dataEdit->kota }}">
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
@endforeach
