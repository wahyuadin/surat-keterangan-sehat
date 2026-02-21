<div class="modal fade" id="addPatient-template" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPatient-templateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('patient-template.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPatient-templateLabel">Tambah Data Patient Template</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama klinik -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan Title" required value="{{ old('title') }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Klinik <span class="text-danger">*</span></label>
                        @if(Auth::user()->role == '2')
                        <select name="clinic_id" required class="form-select select2">
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            @php
                            $klinik = \App\Models\Clinic::showData();
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

                    <!-- Alamat klinik -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Masukkan description">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Is Active</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_1">Active</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_0">Inactive</label>
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

@push('scripts')
<script>
    $('.select2').each(function() {
        $(this).select2({
            placeholder: "Cari atau pilih data..."
            , theme: "bootstrap-5"
            , allowClear: true
            , dropdownParent: $(this).closest('.modal')
        });
    });

</script>
@endpush
