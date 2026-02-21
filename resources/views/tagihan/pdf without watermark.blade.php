@php
// pastikan pasien selalu array
$pasienList = is_array($data->pasien) ? $data->pasien : json_decode($data->pasien, true);

// hitung total
$totalTagihan = collect($pasienList)->sum(fn($p) => (int) $p['tagihan']);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $data->nomor_tagihan }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header-table,
        .billing-table,
        .content-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 10px 0;
            vertical-align: top;
        }

        .company-details h2 {
            margin: 0 0 5px 0;
            /* Mengatur margin atas h2 menjadi 0 agar lebih sejajar */
            font-size: 20px;
            color: #000;
        }

        .company-details p {
            margin: 3px 0;
        }

        .invoice-details h1 {
            margin: 5px 0;
            margin-top: 10px;
            font-size: 35px;
            color: #000;
        }

        .invoice-details p {
            margin: 2px 0;
            font-size: 15px;
        }

        .billing-table p {
            margin: 2px 0;
        }

        .content-table th,
        .content-table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .content-table th {
            background: #f7f7f7;
            text-transform: uppercase;
            font-size: 11px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-table {
            width: 40%;
            margin-left: 60%;
            margin-top: 20px;
        }

        .totals-table td {
            padding: 6px;
        }

        .totals-table .label {
            font-weight: bold;
            text-align: right;
            width: 70%;
        }

        .total-row {
            background: #f7f7f7;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #777;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        img.logo {
            width: 200px;
            margin-bottom: 5px;
        }

    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="padding-right: 15px; vertical-align: middle;">
                            <img class="logo" src="{{ public_path('assets/pdf/1.jpg') }}" alt="Logo" style="width: 100px; display: block;">
                        </td>
                        <td style="border-left: 1px solid #000; padding-right: 15px;">
                            &nbsp; </td>
                        <td style="vertical-align: middle;">
                            <h2 style="margin: 0 0 0px 0;">KLINIK PRATAMA</h2>
                            <h2 style="margin: 0 0 5px 0;">{{ strtoupper($data->clinic->nama_klinik) }}</h2>
                            <p style="margin: 3px 0;">{{ $data->clinic->alamat }}, {{ $data->clinic->kota }}</p>
                            <p style="margin: 3px 0;">Telp: {{ $data->clinic->tlpn }} | Email: {{ $data->clinic->email }}</p>
                            <p style="margin: 0px 0;">No. Sertifikat: {{ $data->clinic->no_serti }}</p>
                        </td>
                    </tr>
                </table>
            </td>

            <td class="invoice-details" style="width: 45%; text-align:right; vertical-align: top;">
                <h1>INVOICE</h1>
                <p>{{ $data->nomor_tagihan }}</p>
                {{-- <p>{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p> --}}
            </td>
        </tr>
    </table>

    <table class="billing-table" style="margin:20px 0;">
        <tr>
            <td style="width:50%;">
                <p><strong>Kepada:</strong></p>
                <p><strong>{{ $data->customer->nama_perusahaan }}</strong></p>
                <p>{{ $data->customer->alamat }}</p>
            </td>
            <td style="width:50%; text-align:right; vertical-align: top;">
                <p><strong>Periode Tagihan:</strong></p>
                <p>{{ \Carbon\Carbon::parse($data->dari)->isoFormat('D MMMM Y') }}
                    &mdash;
                    {{ \Carbon\Carbon::parse($data->sampai)->isoFormat('D MMMM Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">No</th>
                <th style="width:30%;">Layanan</th>
                <th style="width:25%;">QTY</th>
                <th style="width:20%;">Satuan</th>
                <th style="width:20%; text-align: right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>Surat Keterangan Sehat</td>
                <td>{{ $data->qty }}</td>
                <td>Rp. {{ number_format($data->satuan,0,',','.') }}</td>
                <td class="text-right">Rp. {{ number_format($data->satuan * $data->qty,0,',','.') }}</td>
            </tr>
            @foreach ($pasienList as $i => $p)
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td class="label">Subtotal</td>
            <td class="text-right">Rp {{ number_format($totalTagihan,0,',','.') }}</td>
        </tr>
        <tr class="total-row">
            <td class="label">Total</td>
            <td class="text-right">Rp {{ number_format($totalTagihan,0,',','.') }}</td>
        </tr>
    </table>

    <div style="width: 40%; margin-left: 60%; margin-top: 60px; text-align: center;">
        <p>{{ $data->clinic->kota }}, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        <img src="{{ public_path('assets/profile/' . Auth::user()->avatar) }}" alt="" style="width: 150px">
        <p><strong>( {{ Str::upper(Auth::user()->nama) ?? 'Nama Kasir Anda' }} )</strong></p>
    </div>
</body>
</html>
