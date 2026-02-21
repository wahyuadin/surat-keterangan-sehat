<div class="modal fade" id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editpatientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formEdit" method="POST">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editpatientLabel">Edit Data
                        Patient</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Nama Pasien <span class="text-danger">*</span></label>
                        <input type="text" id="nama_patient" name="nama_pasien" class="form-control" placeholder="Masukkan Nama Patient" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Klinik <span class="text-danger">*</span></label>
                        <select id="edit_clinic" name="clinic_id" class="form-select select2" required>
                            <option>== Pilih Salah Satu ==</option>
                            @foreach(\App\Models\Clinic::all() as $c)
                            <option value="{{ $c->id }}">{{ Str::upper($c->nama_klinik) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama_pasien" class="form-label">Customer <span class="text-danger">*</span></label>
                        <select id="edit_customer" name="customer_id" class="form-select select2" required>
                            <option disabled>== Pilih Salah Satu ==</option>
                            @foreach (\App\Models\Customer::all() as $customer)
                            <option value="{{ $customer->id }}">
                                {{ Str::upper($customer->nama_perusahaan ?? '-')  }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select id="edit_jenis_kelamin" name="jenis_kelamin" class="form-select select2" required>
                            <option disabled>== Pilih Salah Satu ==</option>
                            <option value="1">Laki Laki
                            </option>
                            <option value="0">Perempuan
                            </option>
                        </select>
                    </div>

                    <!-- Alamat klinik -->
                    <div class="mb-3">
                        <label for="no_ktp" class="form-label">No KTP</label>
                        <input type="number" id="no_ktp" name="no_ktp" required class="form-control" required placeholder="Masukan No KTP">
                    </div>
                    <div class="mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" required class="form-control" required placeholder="Masukan Pekerjaan">
                    </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" required class="form-control" required placeholder="Masukan Tempat Lahir">
                    </div>
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" required class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="telp" class="form-label">No Telp</label>
                        <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan No Telp" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat pasien (opsional)"></textarea>
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
