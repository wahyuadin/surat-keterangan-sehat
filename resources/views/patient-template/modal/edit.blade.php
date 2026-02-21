@foreach ($data as $dataEdit)
<div class="modal fade" id="editPatient-template{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPatient-template-officeLabel{{ $dataEdit->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('patient-template.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPatient-template-officeLabel{{ $dataEdit->id }}">Edit Data
                        Patient Template</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_patient-template" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="nama_patient-template" class="form-control" value="{{ $dataEdit->title ?? '-' }}" placeholder="Masukkan nama patient template" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required value="{{ $dataEdit->email ?? null }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Masukkan description">{{ $dataEdit->description ?? '-' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="clinic_id" class="form-label">Klinik <span class="text-danger">*</span></label>
                        <select name="clinic_id" class="form-select select2" required>
                            <option disabled>-- Pilih Klinik --</option>
                            @if (Auth::user()->role == '1')
                            @php
                            $clinicData = \App\Models\Clinic::where('id', Auth::user()->clinic_id)->get();
                            @endphp
                            @else
                            @php
                            $clinicData = \App\Models\Clinic::showData();
                            @endphp
                            @endif
                            @foreach ($clinicData as $clinic)
                            <option value="{{ $clinic->id }}" {{ $dataEdit->clinic_id == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->nama_klinik }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Is Active</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="is_active_1" value="1" {{ $dataEdit->is_active == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_1">Active</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="is_active_0" value="0" {{ $dataEdit->is_active == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_0">Inactive</label>
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
