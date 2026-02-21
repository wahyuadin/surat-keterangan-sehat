@extends('form-public.template.app')
@section('header-form', $data->title ?? 'Formulir Publik')
@section('description-form', $data->description ?? 'description-form')
@section('email-form', $data->email ?? 'pusat@nayakaerahusada.com')
@section('nama-klinik', $data->clinic->nama_klinik ?? 'KLINIK NAYAKA HUSADA')

@section('content-form')
<div id="card-nik" class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md mb-5">
    @include('alert')
    <label for="nik" class="block text-base font-medium text-gray-800 mb-4">
        Masukan NIK <span class="text-red-500">*</span>
    </label>
    <div class="relative group">
        <input type="number" id="nik" required class="peer w-full border-b-2 border-gray-200 bg-transparent pt-1 pb-2 text-gray-800 focus:outline-none focus:border-brand-green transition-colors" placeholder="NIK Anda">
        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green transition-all duration-300 peer-focus:w-full"></div>
    </div>
    <!-- tempat pesan error -->
    <p id="nik-error" class="text-green-500 text-sm mt-2 hidden"></p>
    <button id="btn-check-nik" class="mt-4 px-4 py-2 bg-brand-blue text-white rounded-md hover:bg-brand-blue-dark transition-colors">Check</button>
</div>

<form id="form-lanjutan" action="{{ route('form.store') }}" method="POST" class="space-y-5 hidden">
    @csrf
    <input type="text" value="{{ $data->slug }}" name="slug" hidden>
    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Nama Lengkap Sesuai KTP <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <input type="text" name="nama_pasien" required class="peer w-full border-b-2 border-gray-200 bg-transparent pt-1 pb-2 text-gray-800 focus:outline-none focus:border-brand-green transition-colors" placeholder="Masukan Nama Lengkap Anda">
            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green transition-all duration-300 peer-focus:w-full"></div>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Masukan NIK KTP <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <input type="number" name="no_ktp" required class="peer w-full border-b-2 border-gray-200 bg-transparent pt-1 pb-2 text-gray-800 focus:outline-none focus:border-brand-green transition-colors" placeholder="Masukan Nama Lengkap Anda">
            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green transition-all duration-300 peer-focus:w-full"></div>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Tempat Lahir <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <input type="text" name="tempat_lahir" required class="peer w-full border-b-2 border-gray-200 bg-transparent pt-1 pb-2 text-gray-800 focus:outline-none focus:border-brand-green transition-colors" placeholder="Masukan Tempat Lahir Anda">
            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green transition-all duration-300 peer-focus:w-full"></div>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Tanggal Lahir <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <input type="text" id="tgl_lahir" name="tgl_lahir" class="peer w-full border-b-2 border-gray-200 bg-transparent pt-6 pb-2 text-gray-800
                            focus:outline-none focus:border-brand-green transition-all" placeholder="Masukan Tanggal Lahir Anda" required>

            <label for="tgl_lahir" class="absolute left-0 top-1 text-gray-500 text-sm transition-all
                            peer-focus:text-brand-green peer-focus:-translate-y-4 peer-focus:text-xs
                            peer-[&:not(:placeholder-shown)]:-translate-y-4
                            peer-[&:not(:placeholder-shown)]:text-xs">
                Tanggal Lahir
            </label>

            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green
                                transition-all duration-300 peer-focus:w-full"></div>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Pekerjaan <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <input type="text" name="pekerjaan" required class="peer w-full border-b-2 border-gray-200 bg-transparent pt-1 pb-2 text-gray-800 focus:outline-none focus:border-brand-green transition-colors" placeholder="Masukan Pekerjaan Lahir Anda">
            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green transition-all duration-300 peer-focus:w-full"></div>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Asal Perusahaan <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            @php
            $perusahaan = App\Models\Customer::select('id', 'nama_perusahaan')->get();
            @endphp
            <select id="select-perusahaan" name="customer_id"
                class="peer w-full pt-1 pb-2 text-gray-800 bg-transparent" required>
                <option value="" disabled selected>-- Pilih Perusahaan --</option>
                @foreach($perusahaan as $per)
                <option value="{{ $per->id }}">{{ Str::upper($per->nama_perusahaan) }}</option>
                @endforeach
            </select>
        
            <!-- underline animasi -->
            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green 
                transition-all duration-300 peer-focus:w-full"></div>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label class="block text-base font-medium text-gray-800 mb-4">
            Jenis Kelamin <span class="text-red-500">*</span>
        </label>
        <div class="space-y-2">
            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer group transition-all has-[:checked]:border-brand-green has-[:checked]:bg-brand-green/5 has-[:checked]:shadow-sm">
                <div class="flex items-center h-5">
                    <input type="radio" name="jenis_kelamin" value="1" class="w-5 h-5 text-brand-green border-gray-300 focus:ring-brand-green focus:ring-2 transition-all">
                </div>
                <div class="ml-3 text-sm leading-6">
                    <span class="font-medium text-gray-900 group-has-[:checked]:text-brand-blue">Laki Laki</span>
                </div>
            </label>
            <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer group transition-all has-[:checked]:border-brand-green has-[:checked]:bg-brand-green/5 has-[:checked]:shadow-sm">
                <div class="flex items-center h-5">
                    <input type="radio" name="jenis_kelamin" value="0" class="w-5 h-5 text-brand-green border-gray-300 focus:ring-brand-green focus:ring-2 transition-all">
                </div>
                <div class="ml-3 text-sm leading-6">
                    <span class="font-medium text-gray-900 group-has-[:checked]:text-brand-blue">Perempuan</span>
                </div>
            </label>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label for="email" class="block text-base font-medium text-gray-800 mb-4">
            Alamat <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <textarea id="alamat" name="alamat" required rows="3" class="peer w-full border-b-2 border-gray-200 bg-transparent pt-4 pb-2 text-gray-800
                            focus:outline-none focus:border-brand-green transition-colors resize-none" placeholder="Masukan Alamat Anda"></textarea>
        </div>
    </div>

    <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-transparent focus-within:border-brand-blue relative transition-all hover:shadow-md">
        <label for="email" class="block text-base font-medium text-gray-800 mb-4">
            Telp <span class="text-red-500">*</span>
        </label>
        <div class="relative group">
            <input type="number" id="telp" name="telp" required class="peer w-full border-b-2 border-gray-200 bg-transparent pt-1 pb-2 text-gray-800 focus:outline-none focus:border-brand-green transition-colors" placeholder="Masukan No Telp Anda (WhatsApps)">
            <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-green transition-all duration-300 peer-focus:w-full"></div>
        </div>
        <p id="telp-error" class="text-red-500 text-sm mt-2 hidden"></p>
    </div>

    <div class="flex items-center justify-between mt-8 px-1">
        <button id="btn-submit-form" type="submit" class="group relative inline-flex items-center justify-start overflow-hidden rounded-lg bg-brand-green px-8 py-3 font-medium transition-all hover:bg-white hover:text-brand-green hover:shadow-lg active:scale-95">
            <span class="absolute inset-0 rounded-lg border-2 border-brand-green"></span>
            <span class="absolute inset-0 flex justify-center items-center w-full h-full text-white transition-all duration-300 transform group-hover:translate-x-full ease">Kirim Data</span>
            <span class="relative invisible">Kirim Data</span>
            <span class="absolute inset-0 flex justify-center items-center w-full h-full text-brand-green transition-all duration-300 transform -translate-x-full group-hover:translate-x-0 ease">Kirim Data</span>
        </button>

        <button type="reset" class="text-gray-500 font-medium hover:text-brand-blue text-sm transition-colors py-2 px-4 rounded hover:bg-gray-100">
            Kosongkan Formulir
        </button>
    </div>

</form>
@push('style-form')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: {
                        green: '#28b531', // Warna Utama (Hijau)
                        yellow: '#f3ab01', // Warna Aksen (Kuning/Oranye)
                        blue: '#070782', // Warna Gelap/Teks (Biru Tua)
                    }
                }
                , fontFamily: {
                    sans: ['Inter', 'sans-serif']
                , }
                , boxShadow: {
                    'card': '0 1px 3px 0 rgba(60,64,67, 0.3), 0 4px 8px 3px rgba(60,64,67, 0.15)'
                }
            }
        }
    }

</script>
<style>
    body {
        background-color: #f0f2f5;
        /* Background abu-abu lembut */
    }

    /* Transisi halus untuk kartu */
    .question-card {
        transition: all 0.3s ease;
    }

</style>
<style>
/* Styling input agar sama dengan input Tailwind */
.ts-wrapper.single .ts-control {
    border: none !important;
    border-bottom: 2px solid #e5e7eb !important; /* gray-200 */
    border-radius: 0 !important;
    padding-left: 0 !important;
    background: transparent !important;
    font-size: 1rem;
    color: #1f2937; /* text-gray-800 */
    min-height: 38px;
    box-shadow: none !important;
}

/* Saat fokus */
.ts-wrapper.single .ts-control:focus,
.ts-wrapper.single .ts-control.input-active {
    border-bottom-color: #28b531 !important; /* brand-green */
    box-shadow: none !important;
}

/* Placeholder style */
.ts-control::placeholder {
    color: #9ca3af; /* text-gray-400 */
}

/* Dropdown menu */
.ts-dropdown {
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
    background: white;
}

.ts-dropdown .option {
    padding: 10px 12px;
    font-size: 0.95rem;
}

.ts-dropdown .option.active {
    background: #28b53120; /* hijau soft */
    color: #28b531;
}

.ts-dropdown .option.selected {
    background: #28b53130;
    font-weight: 600;
}
</style>

@endpush
@push('scripts-form')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const telpInput = document.getElementById('telp');
    const telpError = document.getElementById('telp-error');
    const submitBtn = document.getElementById('btn-submit-form');

    // Disable submit button pada awal
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    
    new TomSelect("#select-perusahaan", {
        placeholder: "-- Pilih Perusahaan --",
        maxOptions: 100,
        controlInput: "<input>",
    });
    
    telpInput.addEventListener('input', function () {
        let value = telpInput.value.replace(/\D/g, '');

        if (value.length < 10) {
            telpError.textContent = "Nomor telp minimal 10 digit.";
            telpError.classList.remove('hidden');

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            telpError.classList.add('hidden');

            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
});
</script>
<script>
    flatpickr("#tgl_lahir", {
        dateFormat: "Y-m-d"
        , maxDate: "today"
        , altInput: true
        , altFormat: "d F Y"
        , allowInput: true
    , });
    document.getElementById('btn-check-nik').addEventListener('click', function() {

        let nik = document.getElementById('nik').value;
        let errorText = document.getElementById('nik-error');
        let formLanjutan = document.getElementById('form-lanjutan');
        let cardNik = document.getElementById('card-nik');

        // Clear error
        errorText.classList.add('hidden');
        errorText.innerHTML = '';

        if (!nik) {
            errorText.innerHTML = 'NIK wajib diisi.';
            errorText.classList.remove('hidden');
            return;
        }

        fetch(`/form-check/${nik}`)
            .then(response => response.json())
            .then(res => {
                if (!res.success) {
                    if (res.message === "Validasi gagal") {
                        formLanjutan.classList.remove('hidden');
                        cardNik.classList.add('hidden');
                    }
                } else {
                    errorText.innerHTML = 'Data dengan NIK tersebut sudah terdaftar. Silahkan lanjutkan ke pelayanan.';
                    errorText.classList.remove('hidden');
                }

            })
            .catch(err => {
                errorText.innerHTML = "Terjadi kesalahan! Coba lagi.";
                errorText.classList.remove('hidden');
            });

    });

</script>
@endpush
@endsection
