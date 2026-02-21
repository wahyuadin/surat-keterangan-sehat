@foreach ($data as $datas)
<div class="modal fade" id="editData{{ $datas->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('user-data.update', $datas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Data User || {{ config('app.name') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" class="modal-data-id" value="{{ $datas->id }}">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label">Nama User <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" value="{{ old('nama', $datas->nama) }}" required>
                    </div>

                    {{-- Username / Email --}}
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ old('username', $datas->username) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $datas->email) }}" required>
                        </div>
                    </div>

                    {{-- Role & Status --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option disabled selected>== Pilih Role ==</option>
                                <option value="0" {{ $datas->role == 0 ? 'selected' : '' }}>HRD</option>
                                <option value="1" {{ $datas->role == 1 ? 'selected' : '' }}>Admin Klinik</option>
                                <option value="2" {{ $datas->role == 2 ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Is Active</label>
                            <select name="is_active" class="form-select">
                                <option disabled selected>== Pilih Status ==</option>
                                <option value="1" {{ $datas->is_active == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $datas->is_active == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    {{-- Perusahaan / Klinik --}}
                    <div class="mb-3 mt-3">
                        <label class="form-label">Perusahaan</label>
                        <select name="customer_id" class="form-select">
                            <option disabled selected>== Pilih Salah Satu ==</option>
                            @foreach(App\Models\Customer::all() as $c)
                                <option value="{{ $c->id }}" {{ $datas->customer_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Klinik</label>
                        <select name="clinic_id" class="form-select">
                            <option disabled selected>== Pilih Salah Satu ==</option>
                            @foreach(App\Models\Clinic::all() as $c)
                                <option value="{{ $c->id }}" {{ $datas->clinic_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama_klinik }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Password --}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control password-input" id="password_edit{{ $datas->id }}" name="password" placeholder="Masukan Password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_edit{{ $datas->id }}', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                            <div class="invalid-feedback d-block" id="password_error_edit{{ $datas->id }}" style="display:none;"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Re-Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control repassword-input" id="repassword_edit{{ $datas->id }}" name="repassword" placeholder="Masukan ulang password">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('repassword_edit{{ $datas->id }}', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                            <div class="invalid-feedback d-block" id="password_confirmation_error_edit{{ $datas->id }}" style="display:none;"></div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3 mt-3">
                        <label class="form-label">Foto</label>
                        <div class="mt-2 mb-2">
                            <img id="preview_edit{{ $datas->id }}"
                                src="{{ $datas->avatar != 'default.png' ? asset('storage/'.$datas->avatar) : asset('assets/profile/default.png') }}"
                                alt="Preview Foto"
                                class="img-fluid"
                                style="max-width: 150px;">
                        </div>
                        <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImageEdit(this, 'preview_edit{{ $datas->id }}')">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
// ========== Preview Gambar ==========
function previewImageEdit(input, previewId) {
    const file = input.files ? input.files[0] : null;
    const preview = document.getElementById(previewId);
    if (!file || !preview) return;

    const reader = new FileReader();
    reader.onload = e => {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

// ========== Toggle Password ==========
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (!input) return;

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = "password";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

// ========== Validasi Password Dinamis ==========
document.addEventListener('input', function(e) {
    if (!e.target.matches('.password-input, .repassword-input')) return;

    const modal = e.target.closest('.modal');
    const id = modal.querySelector('.modal-data-id').value;
    const pw = document.getElementById('password_edit' + id).value;
    const rpw = document.getElementById('repassword_edit' + id).value;

    const pwErr = document.getElementById('password_error_edit' + id);
    const rpwErr = document.getElementById('password_confirmation_error_edit' + id);

    pwErr.style.display = 'none';
    rpwErr.style.display = 'none';

    const regex = /^[A-Z][A-Za-z0-9]{5,}$/;
    const hasNumber = /[0-9]/;

    let hasError = false;

    if (pw && (!regex.test(pw) || !hasNumber.test(pw))) {
        pwErr.textContent = "Password minimal 6 karakter, huruf besar di awal, dan ada angka.";
        pwErr.style.display = 'block';
        hasError = true;
    }

    if (rpw && pw && pw !== rpw) {
        rpwErr.textContent = "Password konfirmasi tidak sama.";
        rpwErr.style.display = 'block';
        hasError = true;
    }

    modal.querySelector('button[type="submit"]').disabled = hasError;
});

// ========== Reset Modal Saat Ditutup ==========
document.addEventListener('hidden.bs.modal', function(e) {
    const modal = e.target;
    if (!modal.classList.contains('modal')) return;

    modal.querySelectorAll('.password-input, .repassword-input').forEach(inp => inp.value = '');
    modal.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
});
</script>
@endpush
