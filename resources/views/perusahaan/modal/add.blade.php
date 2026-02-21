<div class="modal fade" id="addPerusahaan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPerusahaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('perusahaan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPerusahaanLabel">Tambah Data Perusahaan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama Perusahaan -->
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label">Kode Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="kode" id="kode" class="form-control" placeholder="Masukkan kode perusahaan" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" placeholder="Masukkan nama perusahaan" required>
                    </div>

                    <!-- Alamat Perusahaan -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat perusahaan (opsional)"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tarif" class="form-label">Tarif <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="tarif" id="tarif" class="form-control" placeholder="Masukkan tarif" required data-type="currency">
                        </div>
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
