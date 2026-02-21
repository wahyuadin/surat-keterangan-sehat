<div class="modal fade" id="addklinik" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addklinikLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('klinik.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addklinikLabel">Tambah Data klinik</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="nama_klinik" class="form-label">Nama klinik <span class="text-danger">*</span></label>
                        <input type="text" name="nama_klinik" id="nama_klinik" class="form-control" placeholder="Masukkan nama klinik" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Klinik <span class="text-danger">*</span></label>
                        <input type="text" name="kode" id="kode" class="form-control" value="{{ old('kode') }}" placeholder="Masukkan kode klinik" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_klinik" class="form-label">Branch Office <span class="text-danger">*</span></label>
                        <select name="branch_id" required class="form-select select2">
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            @php
                            $branch = \App\Models\Branch::all();
                            foreach ($branch as $item) {
                            echo "<option value='$item->id'>$item->nama_branch</option>";
                            }
                            @endphp
                        </select>
                    </div>

                    <!-- Alamat klinik -->
                    <div class="mb-3">
                        <label for="tlpn" class="form-label">No Telp</label>
                        <input type="number" name="tlpn" id="tlpn" class="form-control" placeholder="Masukkan No Telp" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_serti" class="form-label">No Sertifikat standart</label>
                        <input type="text" name="no_serti" id="no_serti" class="form-control" placeholder="Masukkan No Sertifikat Standart" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat klinik"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text" name="kota" id="kota" class="form-control" placeholder="Masukkan Kota" required value="{{ old('kota') }}">
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
