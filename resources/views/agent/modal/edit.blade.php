@foreach ($data as $dataEdit)
<div class="modal fade" id="editagent{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editagentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('agent.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editagentLabel{{ $dataEdit->id }}">Edit Data Agent</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_agent" class="form-label">Nama Agent <span class="text-danger">*</span></label>
                        <input type="text" name="nama_agent" class="form-control" placeholder="Masukkan Nama Agent" required value="{{ $dataEdit->nama_agent }}">
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
                            <option value="{{ $item->id }}" {{ $dataEdit->clinic_id == $item->id ? 'selected' : '' }}>{{ $item->nama_klinik }} - {{ $item->alamat }}</option>
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

                    @if(Auth::user()->role != '0')
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Asal Perusahaan <span class="text-danger">*</span></label>
                        <select name="customer_id" required class="form-select select2">
                            <option disabled>== Pilih Salah Satu ==</option>
                            @foreach (\App\Models\Customer::all() as $customer)
                            <option value="{{ $customer->id }}" {{ $dataEdit->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->nama_perusahaan }} - {{ $customer->alamat }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
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
