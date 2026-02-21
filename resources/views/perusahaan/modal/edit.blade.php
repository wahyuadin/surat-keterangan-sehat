@foreach ($data as $dataEdit)
<div class="modal fade" id="editPerusahaan{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addperusahaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('perusahaan.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editperusahaanLabel{{ $dataEdit->id }}">Edit Data perusahaan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label">Nama perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" value="{{ $dataEdit->nama_perusahaan }}" placeholder="Masukkan nama perusahaan" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat perusahaan (opsional)">{{ $dataEdit->alamat }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tarif" class="form-label">Tarif <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="tarif" id="tarif" class="form-control" value="{{ $dataEdit->tarif }}" placeholder="Masukkan tarif" required data-type="currency">
                        </div>
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
