@extends('form-public.template.app')
@section('header-form', $data->title ?? 'Formulir Input Patient')
@section('description-form', $data->description ?? 'PT. Nayaka Era Husada')
@section('email-form', $data->email ?? 'pusat@nayakaerahusada.com')
@section('nama-klinik', $data->clinic->nama_klinik ?? ' NAYAKA HUSADA')


@section('content-form')
<div class="bg-white rounded-xl shadow-sm p-6 border-l-[6px] border-red-500 mb-5">
    <p class="text-lg font-semibold text-red-600">Form sudah kadaluarsa</p>
    <p class="text-gray-600 mt-2">
        Form ini tidak dapat diakses karena sudah tidak aktif atau masa berlakunya habis.
    </p>
</div>
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
@endpush
@endsection
