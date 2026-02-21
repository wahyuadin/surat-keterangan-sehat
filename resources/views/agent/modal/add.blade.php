<div class="modal fade" id="addagent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addagentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('agent.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addagentLabel">Tambah Data Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="nama_agent" class="form-label">Nama Agent <span class="text-danger">*</span></label>
                        <input type="text" name="nama_agent" class="form-control" placeholder="Masukkan Nama Pasien" required value="{{ old('nama_agent') }}">
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
                        <input type="text" value="{{ Str::upper($klinik->nama_klinik) }}" readonly class="form-control">
                        <input type="text" name="clinic_id" value="{{ $klinik->id }}" hidden class="form-control">
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Asal Perusahaan <span class="text-danger">*</span></label>
                        <select name="customer_id" required class="form-select select2">
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            @php
                            $customer = \App\Models\Customer::all();
                            @endphp
                            @foreach ($customer as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_perusahaan }} - {{ $item->alamat }}</option>
                            @endforeach
                        </select>
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
