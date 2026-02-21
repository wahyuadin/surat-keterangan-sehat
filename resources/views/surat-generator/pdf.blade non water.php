<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Sehat</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Tahoma, Arial, sans-serif !important;
            font-size: 12pt !important;
            line-height: 1.5 !important;
        }

        /* Menggunakan tabel untuk layout kop surat agar kompatibel dengan dompdf */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-cell {
            width: 115px;
            /* Lebar untuk logo + padding */
            padding-right: 15px;
        }

        .logo {
            width: 110px;
            height: auto;
        }

        .divider-cell {
            border-left: 2px solid #000;
            width: 1px;
            padding: 0;
        }

        .details-cell {
            padding-left: 15px;
        }

        .clinic-name {
            font-weight: bold;
            font-size: 16pt;
            line-height: 1.2;
            /* Mengurangi jarak baris */
        }

        .clinic-subtitle {
            font-weight: bold;
            font-size: 15pt;
            /* Disesuaikan sesuai permintaan */
            line-height: 1.2;
            /* Mengurangi jarak baris */
        }

        .address,
        .contact,
        .certificate {
            font-size: 11pt;
            line-height: 1.2;
        }

        /* CSS BARU UNTUK KOTAK KODE */
        .code-box-cell {
            width: 80px;
            /* Lebar sel untuk kotak kode */
            vertical-align: top;
            /* Posisikan di atas */
            text-align: right;
            /* Ratakan ke kanan */
        }

        .code-box {
            border: 1.5px solid #000;
            padding: 4px 8px;
            display: inline-block;
            /* Agar border pas dengan konten */
            font-weight: bold;
            font-size: 12pt;
        }

        /* AKHIR DARI CSS BARU */

        .header-line {
            border: 0;
            height: 1.5px;
            background-color: #000;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin: 0px 0;
        }

        .document-number {
            text-align: center;
            margin-bottom: 0px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table td {
            padding: 3px 24px;
            vertical-align: top;
        }

        .label {
            width: 4cm;
            white-space: nowrap;
        }

        .photo-cell {
            width: 5cm;
            text-align: center;
        }

        .photo {
            border: 1px solid #000;
            width: 4cm;
            /* Disesuaikan agar proporsional */
            height: auto;
        }

        .conclusion,
        .purpose {
            margin: 15px 0;
        }

        .signature {
            margin-top: 20px;
            width: 50%;
            /* Lebar blok tanda tangan */
            float: right;
            /* Posisikan blok di kanan */
            text-align: center;
            /* Ratakan teks di dalam blok ke tengah */
        }

        .signature-image {
            height: 100px;
            /* Sesuaikan tinggi gambar tanda tangan */
            margin: 5px 0;
        }

    </style>
</head>
<body>
    <!-- Kop surat diubah menjadi struktur tabel -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('assets/pdf/1.jpg') }}" alt="Logo Klinik" class="logo">
            </td>
            <td class="divider-cell" style="height: 80px;">&nbsp;</td> <!-- Memberi tinggi pada garis -->
            <td class="details-cell">
                <div class="clinic-name">KLINIK PRATAMA</div>
                <div class="clinic-subtitle">{{ Str::upper($data['paramedis']['clinic']['nama_klinik'] ?? 'NAYAKA HUSADA 07 CENGKARENG') }}</div>
                <div class="address">
                    {{ ucwords($data['paramedis']['clinic']['alamat']) }}
                </div>
                <div class="contact">Tlp. {{ $data['paramedis']['clinic']['tlpn'] ?? '' }} || Email : {{ $data['paramedis']['clinic']['email'] ?? '-' }}</div>
                <div class="certificate">No Sertifikat Standar : {{ $data['paramedis']['clinic']['no_serti'] ?? '-' }} <br> Website : https://nayakaerahusada.com</div>
            </td>
            <!-- SEL BARU UNTUK KOTAK KODE -->
            <td class="code-box-cell">
                <div class="code-box">F.b.5a</div>
            </td>
        </tr>
    </table>

    <hr class="header-line">

    <div class="title">SURAT KETERANGAN SEHAT</div>
    <div class="document-number">Nomor : {{ $data['no_transaksi']}}</div>

    <p>Yang bertanda tangan di bawah ini menerangkan bahwa :</p>

    <table class="form-table">
        <tr>
            <td class="label">Nama</td>
            <td>: {{ ucwords($data['patient']['nama_pasien']) }}</td>
            <td class="photo-cell" rowspan="10">
                <img src="{{ public_path('assets/registration/'.$data['foto']) }}" class="photo" alt="Foto Pasien" onerror="this.onerror=null;this.src='https://placehold.co/4x6/EFEFEF/AAAAAA&text=Foto';" style="border-radius:10px">
            </td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>:
                @if($data['patient']['jenis_kelamin'] == 1)
                Laki-laki
                @else
                Perempuan
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Tempat Tanggal Lahir</td>
            <td>: {{ ucwords($data['patient']['tempat_lahir'] ?? '') }}, {{ \Carbon\Carbon::parse($data['patient']['tgl_lahir'])->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Umur</td>
            @php
            $birthDate = \Carbon\Carbon::parse($data['patient']['tgl_lahir']);
            $now = \Carbon\Carbon::now();
            $diff = $birthDate->diff($now);
            @endphp

            <td>: {{ $diff->y }} Tahun {{ $diff->m }} Bulan</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td>: {{ $data['patient']['pekerjaan'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>: {{ $data['patient']['alamat'] }}</td>
        </tr>
        <tr>
            <td class="label">TB/BB</td>
            <td>: {{ $data['tinggi_badan'] }} Cm / {{ $data['berat_badan'] }} Kg</td>
        </tr>
        <tr>
            <td class="label">Tekanan Darah</td>
            <td>: {{ $data['tensi'] }} mmHg</td>
        </tr>
        <tr>
            <td class="label">Suhu</td>
            <td>: {{ $data['suhu'] }}°C </td>
        </tr>
        <tr>
            <td class="label">Saturasi</td>
            <td>: {{ $data['saturnasi'] }}%</td>
        </tr>
        <tr>
            <td class="label">Denyut Nadi</td>
            <td>: {{ $data['denyutnadi'] }} BPM</td>
        </tr>
        <tr>
            <td class="label">Golongan Darah</td>
            <td>:
                @if($data['gol_darah'] == 'A')
                A
                @elseif($data['gol_darah'] == 'B')
                B
                @elseif($data['gol_darah'] == 'O')
                O
                @elseif($data['gol_darah'] == 'AB')
                AB
                @else
                -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Buta Warna</td>
            <td>: @if($data['buta_warna'] == 0) Tidak @else Ya @endif</td>
        </tr>
        <tr>
            <td class="label">Pendengaran</td>
            <td>: @if($data['pendengaran'] == 1) Respon @else Tidak Respon @endif</td>
        </tr>
    </table>

    <div class="conclusion">
        Telah dilakukan pemeriksaan dan dinyatakan<strong>@if($data['status_kesehatan'] == 1) SEHAT. @else TIDAK SEHAT. @endif</strong>
    </div>

    <div class="purpose">
        Surat Keterangan Sehat ini dipergunakan untuk: <b>{{ ucwords($data['keperluan']) }}</b>
    </div>

    <div class="signature">
        {{ ucwords($data['paramedis']['clinic']['kota'] ?? 'Jakarta') }}, {{ \Carbon\Carbon::parse($data['tgl_transaksi'])->translatedFormat('d F Y') }}<br>
        Dokter pemeriksa,<br>
        <img src="{{ public_path('assets/ttd/' . $data['paramedis']['ttd'] ?? '-') }}" alt="Tanda Tangan" class="signature-image"><br>
        <b><u>{{ ucwords($data['paramedis']['nama']) }}</u></b>
        {{-- <b>{{ Str::upper($data['paramedis']['clinic']['nama_klinik']) }}</b> --}}
    </div>
</body>
</html>
