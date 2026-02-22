@extends('template.app')
@section('content')
<div id="loading-overlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!-- Start of Selection -->
<div class="container mt-4">
    <div class="card w-100">
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-8">
                    <h5 class="card-title">Data Surat</h5>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <div class="mt-3 mb-4">
                    @if(Auth::user()->role != 0)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addsuratGenerator">
                        <i class='bx bx-plus'></i>
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-export'></i>
                    </button>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->role != '0')
                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">
                                Cetak Blangko
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('surat-blangko-pdf') }}" target="_blank">Format.pdf</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('surat-blangko-word') }}" target="_blank">Format.docx (MS WORD)</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="printPDF()">Export PDF</a>
                        </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Export Excel</a>
                        </li>
                    </ul>
                </div>

                @include('alert')
                <div class="row mb-3">
                    <h5>Periode</h5>
                    <div class="col-md-4">
                        <label class="form-label">Dari</label>
                        <input type="date" class="form-control" id="dari" value="{{ date('Y-m-d', strtotime('-1 week')) }}" min="{{ date('Y-m-d', strtotime('-1 month')) }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sampai</label>
                        <input type="date" id="sampai" class="form-control" placeholder="Sampai Tanggal" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Agent</label>
                        <select id="agent_id" class="form-select">
                            <option selected disabled>== Pilih Salah Satu ==</option>
                            @if (Auth::user()->role == '1')
                            @php
                            $agent = \App\Models\Agent::with('customer', 'clinic')->where('clinic_id' , Auth::user()->clinic_id)->get();
                            @endphp
                            @elseif(Auth::user()->role == '2')
                            @php
                            $agent = \App\Models\Agent::with('customer', 'clinic')->latest()->get();
                            @endphp
                            @else
                            @php
                            $agent = \App\Models\Agent::with('customer', 'clinic')->where('customer_id' , Auth::user()->customer_id)->get();
                            @endphp
                            @endif
                            <option value="all">All</option>
                            <option value="without">Tanpa Agent</option>
                            @foreach ($agent as $agentItem )
                            <option value="{{ $agentItem->id }}">{{ Str::upper($agentItem->nama_agent ?? '-') }} - [{{ $agentItem->customer->nama_perusahaan ?? '-' }}]</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table id="example" class="table table-striped table-bordered w-100 mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Transaksi</th>
                            <th>No Transaksi</th>
                            <th>Agent</th>
                            <th>No KTP</th>
                            <th>Nama Pasien</th>
                            <th>Tempat Lahir</th>
                            <th>Tgl Lahir</th>
                            <th>Umur</th>
                            <th>No Telp</th>
                            <th>Pekerjaan</th>
                            {{-- ===== --}}
                            <th>Klinik</th>
                            <th>Tenaga Medis</th>
                            <th>Tinggi Badan</th>
                            <th>Berat Badan</th>
                            <th>Tekanan Darah</th>
                            <th>Suhu</th>
                            <th>Saturasi</th>
                            <th>Denyut Nadi</th>
                            <th>Gol Darah</th>
                            <th>Buta Warna</th>
                            <th>Pendengaran</th>
                            <th>Status Kesehatan</th>
                            <th>Keperluan</th>
                            <th>Foto</th>
                            <th>Asal Perusahaan</th>
                            <th>Status Transaksi</th>
                            @if (Auth::user()->role == '2')
                            <th>Tarif</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@if(Auth::user()->role != 0)
@include('surat-generator.modal.add')
{{-- @include('surat-generator.modal.edit') --}}
@include('surat-generator.modal.delete')
@include('surat-generator.modal.addPatient')
@include('surat-generator.modal.addAgent')
@endif
@push('style')
<style>
    .dropdown-menu .dropdown-submenu {
        position: relative;
    }

    .dropdown-menu .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }

    /* Responsive styles for mobile */
    @media (max-width: 767.98px) {
        .dropdown-menu .dropdown-submenu .dropdown-menu {
            position: static;
            /* Let it flow in the document */
            left: auto;
            /* Reset horizontal position */
            top: auto;
            /* Reset vertical position */
            width: 100%;
            /* Take full width of the parent dropdown */
            margin-top: 0;
            box-shadow: none;
            /* Remove floating shadow effect */
            border: none;
            /* Remove border */
            background-color: transparent;
            /* Optional: remove background to blend in */
        }

        /* Add some indentation to distinguish submenu items on mobile */
        .dropdown-menu .dropdown-submenu .dropdown-menu .dropdown-item {
            padding-left: 2.5rem;
        }
    }

</style>
<style>
    /* 🔹 Mirror tampilan video saja (bukan hasil foto) */
    #video {
        transform: scaleX(-1);
        -webkit-transform: scaleX(-1);
    }

    /* 🔹 Foto hasil tangkapan normal (tidak mirror) */
    #preview {
        transform: scaleX(1);
        -webkit-transform: scaleX(1);
    }

</style>

{{-- datatable --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
@endpush

@push('scripts')
{{-- Moment.js for date handling --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>

{{-- Library untuk Export Excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

{{-- Library untuk Export PDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.dropdown-menu .dropdown-toggle').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let parent = this.closest('.dropdown-submenu');
                if (!parent) return;

                let submenu = parent.querySelector('.dropdown-menu');
                if (!submenu) return;

                // Hide other open submenus
                parent.closest('.dropdown-menu').querySelectorAll('.dropdown-menu.show').forEach(function(openSubmenu) {
                    if (openSubmenu !== submenu) {
                        openSubmenu.classList.remove('show');
                    }
                });

                submenu.classList.toggle('show');
            });
        });

        // Hide submenus when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.dropdown-toggle')) {
                document.querySelectorAll('.dropdown-menu .dropdown-menu.show').forEach(function(submenu) {
                    submenu.classList.remove('show');
                });
            }
        });
    });
    let userRole = "{{ Auth::user()->role }}";
    let clinicName = "{{ Str::upper(\App\Models\Clinic::where('id', Auth::user()->clinic_id)->first()->nama_klinik ?? 'KLINIK PRATAMA NAYAKA HUSADA') }}";
    let perusahaanName = "{{ Str::upper(\App\Models\Customer::where('id', Auth::user()->customer_id)->first()->nama_perusahaan ?? '-') }}";

    let columns = [{
        data: 'DT_RowIndex'
        , name: 'DT_RowIndex'
        , orderable: false
        , searchable: false
    }, {
        data: 'tgl_transaksi'
        , name: 'tgl_transaksi'
    }, {
        data: 'no_transaksi'
        , name: 'no_transaksi'
    }, {
        data: 'agent.nama_agent'
        , name: 'agent.nama_agent'
        , render: data => data ? data.toUpperCase() : '-'
    }, {
        data: 'patient.no_ktp'
        , name: 'patient.no_ktp'
    }, {
        data: 'patient.nama_pasien'
        , name: 'patient.nama_pasien'
        , render: data => data ? data.toUpperCase() : '-'
    }, {
        data: 'patient.tempat_lahir'
        , name: 'patient.tempat_lahir'
        , render: data => data ? data.toUpperCase() : '-'
    }, {
        data: 'patient.tgl_lahir'
        , name: 'patient.tgl_lahir'
    }, {
        data: 'umur'
        , name: 'patient.tgl_lahir'
        , render: (data, type, row) => {

            // Cek apakah patient atau tgl_lahir null
            if (!row.patient || !row.patient.tgl_lahir) {
                return "-";
            }

            const birthDate = moment(row.patient.tgl_lahir);

            // Cek apakah tanggal valid
            if (!birthDate.isValid()) {
                return "-";
            }

            const now = moment();
            const age = now.diff(birthDate, 'years');

            return age + " Tahun";
        }
    }, {
        data: 'patient.telp'
        , name: 'patient.telp'
        , render: (data, type, row) => {
            if (!data) return '-';
            let phone = data.replace(/^0/, "62");
            let message = encodeURIComponent("Berikut kami kirimkan Surat Keterangan Sehat dari Klinik Nayaka Husada. \n \nTerimakasih 🙏");
            return `
                <a href="https://api.whatsapp.com/send?phone=${phone}&text=${message}"
                   target="_blank"
                   class="btn btn-info btn-sm rounded">${phone}</a>`;
        }
    }, {
        data: 'patient.pekerjaan'
        , name: 'patient.pekerjaan'
        , render: data => data ? data.toUpperCase() : ''
    }, {
        data: 'patient.clinic.nama_klinik'
        , name: 'patient.clinic.nama_klinik'
        , render: data => data ? data.toUpperCase() : '-'
    }, {
        data: 'paramedis'
        , name: 'paramedis'
        , render: data => data ? data.toUpperCase() : ''
    }, {
        data: 'tinggi_badan'
        , name: 'tinggi_badan'
        , render: data => data + " cm"
    }, {
        data: 'berat_badan'
        , name: 'berat_badan'
        , render: data => data + " kg"
    }, {
        data: 'tensi'
        , name: 'tensi'
        , render: data => data + " mmHg"
    }, {
        data: 'suhu'
        , name: 'suhu'
        , render: data => data + " °C"
    }, {
        data: 'saturnasi'
        , name: 'saturnasi'
        , render: data => data + " %"
    }, {
        data: 'denyutnadi'
        , name: 'denyutnadi'
        , render: data => data + " BPM"
    }, {
        data: 'gol_darah'
        , name: 'gol_darah'
    }, {
        data: 'buta_warna'
        , name: 'buta_warna'
        , render: data => data == 1 ? 'YA' : (data == 0 ? 'TIDAK' : '-')
    }, {
        data: 'pendengaran'
        , name: 'pendengaran'
        , render: data => data == 1 ? 'RESPON' : (data == 0 ? 'KURANG RESPON' : '-')
    }, {
        data: 'status_kesehatan'
        , name: 'status_kesehatan'
        , render: data => data == 1 ? 'SEHAT' : (data == 0 ? 'KURANG SEHAT' : '-')
    }, {
        data: 'keperluan'
        , name: 'keperluan'
        , render: data => data ? data.toUpperCase() : '-'
    }, {
        data: 'foto'
        , name: 'foto'
        , orderable: false
        , searchable: false
        , render: data => {
            if (!data) return '-';
            const url = `{{ asset('storage') }}/${data}`;
            return `<a href="${url}" target="_blank">
                    <img src="${url}" width="50" class="rounded"/>
                </a>`;
        }
    }, {
        data: 'patient.customer.nama_perusahaan'
        , name: 'patient.customer.nama_perusahaan'
        , render: data => data ? data.toUpperCase() : ''
    }, {
        data: 'is_bayar'
        , name: 'is_bayar'
        , orderable: false
        , searchable: false
        , render: data => {
            if (data == 0) {
                return '<span class="badge bg-danger">MENUNGGU TRANSAKSI</span>';
            } else if (data == 1) {
                return '<span class="badge bg-success">LUNAS</span>';
            } else {
                return '-';
            }
        }
    }, {
        data: 'action'
        , name: 'action'
        , orderable: false
        , searchable: false
        , render: (data, type, row) => {
            let url = "{{ route('surat.show', ':id') }}".replace(':id', row.id);
            let buttons = `
                <div class="d-flex gap-1">
                   <a href="${url}" target="_blank"
                        class="btn btn-sm btn-warning rounded bx bxs-file-pdf"
                        data-id="${row.id}">
                    </a>
            `;
            if (userRole != '0') {
                let editUrl = "{{ route('surat.edit', ':id') }}";
                editUrl = editUrl.replace(':id', row.id);
                buttons += `<a href="${editUrl}" class="btn btn-sm btn-primary rounded bx bx-edit" title="Edit"></a>`;
            }
            if (userRole == '2') {
                buttons += `
                    <button type="button" class="btn btn-sm btn-danger rounded bx bx-trash btn-delete"
                            data-id="${row.id}"
                            data-no="${row.no_transaksi}"
                            data-nama="${row.patient?.nama_pasien ?? '-'}"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal"></button>
                `;
            }
            buttons += `</div>`;
            return buttons;
        }
    }];
    if (userRole == '2') {
        columns.splice(columns.length - 1, 0, {
            data: 'patient.customer.tarif'
            , name: 'patient.customer.tarif'
            , render: data => data ? 'Rp ' + parseInt(data).toLocaleString('id-ID') : ''
        });
    }

    let table = $('#example').DataTable({
        processing: true
        , serverSide: true
        , ajax: {
            url: "{{ route('surat.data') }}"
            , data: d => {
                d.dari = $('#dari').val();
                d.sampai = $('#sampai').val();
                d.agent_id = $('#agent_id').val();
            }
        }
        , columns: columns
    });

    // reload otomatis kalau tanggal berubah
    $('#dari, #sampai, #clinic_id, #agent_id').on('change', () => table.ajax.reload());
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        let no = $(this).data('no');
        let nama = $(this).data('nama');

        // teks konfirmasi
        $('#deleteText').html(`
            Anda akan menghapus data:<br>
            <strong class="text-dark">${no} - ${nama}</strong>.
            <br>Tindakan ini tidak dapat dibatalkan.
        `);
        let url = "{{ route('surat.destroy', ':id') }}".replace(':id', id);

        $('#deleteForm').attr('action', url);
    });

    $('.select2').each(function() {
        $(this).select2({
            placeholder: "Cari atau pilih data..."
            , theme: "bootstrap-5"
            , allowClear: true
            , dropdownParent: $(this).closest('.modal')
        });
    });

    $('.agent').select2({
        placeholder: "Pilih Agent"
        , theme: "bootstrap-5"
    , })

    // Generate nomor transaksi berdasarkan patient_id
    $('#patient_id').on('change', function() {
        let patientId = $(this).val();

        if (patientId) {
            $.ajax({
                url: "{{ route('surat.generateNo', ':id') }}".replace(':id', patientId)
                , type: 'GET'
                , success: function(response) {
                    $('#no_transaksi').val(response.no_transaksi);
                }
                , error: function() {
                    $('#no_transaksi').val('');
                    alert('Gagal generate nomor transaksi');
                }
            });
        }
    });

    // Generate nomor tagihan berdasarkan clinic_id
    $('#klinikSelect').on('change', function() {
        let clinicId = $(this).val();
        if (clinicId) {
            $.ajax({
                url: '/generate-no-tagihan/' + clinicId
                , type: 'GET'
                , beforeSend: function() {
                    $('#invoice_number').val('Loading...');
                }
                , success: function(response) {
                    $('#invoice_number').val(response.no_tagihan);
                }
                , error: function() {
                    $('#invoice_number').val('');
                    alert('Gagal generate nomor tagihan, halaman akan direfresh.');
                    location.reload();
                }
                , complete: function() {
                    if ($('#invoice_number').val() === 'Loading...') {
                        $('#invoice_number').val('');
                    }
                }
            });
        }
    });

    function ucwordsJS(str) {
        return str
            .replace(/_/g, ' ') // ganti underscore jadi spasi
            .toLowerCase()
            .replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
    }

    function printPDF() {
        document.getElementById('loading-overlay').classList.remove('d-none');

        const {
            jsPDF
        } = window.jspdf;
        let doc = new jsPDF({
            orientation: 'landscape'
        });

        let title = "Pelayanan Klinik Nayaka Husada";
        let pageWidth = doc.internal.pageSize.width;
        let titleWidth = doc.getTextWidth(title);
        doc.text(title, (pageWidth - titleWidth) / 2, 10);

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 22) { // Hanya ambil kolom yang relevan
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari tabel DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 22) { // Ambil hanya kolom yang relevan
                    let text = $(this).text().trim();
                    rowData.push(ucwordsJS(text));

                }
            });
            data.push(rowData);
        });

        // Buat tabel PDF
        doc.autoTable({
            head: [headers]
            , body: data
            , startY: 20
            , theme: "striped"
            , styles: {
                fontSize: 5.4
                , textColor: [0, 0, 0]
            }
            , headStyles: {
                fillColor: [192, 192, 192]
                , textColor: [0, 0, 0]
            }
        , });

        let now = new Date();
        let dateString = moment(now).format('YYYYMMDDHHmmss');
        doc.save("Pelayanan_klinik_" + dateString + ".pdf");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

    function exportExcel() {
        let roleUser = `{{ Auth::user()->role }}`;

        let overlay = document.getElementById('loading-overlay');
        if (overlay) overlay.classList.remove('d-none');

        // === Ambil header dari tabel ===
        let headers = [];
        let maxIndex = (userRole == 0) ? 27 : 28;

        $('#example thead th').each(function(index) {
            if (index < maxIndex) {
                headers.push($(this).text().trim());
            }
        });

        // === Ambil data ===
        let data = [];
        let totalTarif = 0;
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            let maxIndex = (userRole == 0) ? 27 : 28; // kalau role 0 -> 27, selain itu -> 28

            $(this).find('td').each(function(index) {
                if (index < maxIndex) {
                    let text = '';
                    if ($(this).find('.badge').length) {
                        text = $(this).find('.badge').text().trim();
                    } else if ($(this).find('img').length) {
                        text = $(this).find('img').attr('src');
                    } else {
                        text = $(this).text().trim();
                    }
                    rowData.push(text);
                }
            });
            let tarifText = rowData[27] || "0";
            let tarifNumber = parseInt(tarifText.replace(/[^0-9]/g, "")) || 0;
            totalTarif += tarifNumber;

            data.push(rowData);
        });

        // === Ambil periode & perusahaan ===
        let dari = $('#dari').val() ? moment($('#dari').val()).format('DD/MM/YYYY') : "-";
        let sampai = $('#sampai').val() ? moment($('#sampai').val()).format('DD/MM/YYYY') : "-";
        let periode = `PERIODE : ${dari} s/d ${sampai}`;
        let perusahaanText = `PERUSAHAAN : ${perusahaanName}`;

        // === Header laporan ===
        let ws_data = [
            ["DAFTAR KUNJUNGAN (SURAT KETERANGAN SEHAT)"]
            , [clinicName || 'KLINIK PRATAMA NAYAKA HUSADA']
            , [periode]
            , [perusahaanText]
            , []
        ];

        // === Header grouping ===
        let groupingRow = new Array(headers.length).fill("");
        groupingRow[0] = "Data Diri"; // kolom 0–11
        groupingRow[12] = "Hasil Pemeriksaan"; // kolom 12–22

        ws_data.push(groupingRow);
        ws_data.push(headers);
        ws_data = [...ws_data, ...data];

        if (roleUser == '2') {
            let totalRow = new Array(headers.length).fill("");
            totalRow[headers.length - 2] = "TOTAL";
            totalRow[headers.length - 1] = "Rp " + totalTarif.toLocaleString("id-ID");
            ws_data.push(totalRow);
        }

        let ws = XLSX.utils.aoa_to_sheet(ws_data);

        // === Merge cell ===
        ws['!merges'] = [{
            s: {
                r: 0
                , c: 0
            }
            , e: {
                r: 0
                , c: headers.length - 1
            }
        }, {
            s: {
                r: 1
                , c: 0
            }
            , e: {
                r: 1
                , c: headers.length - 1
            }
        }, {
            s: {
                r: 2
                , c: 0
            }
            , e: {
                r: 2
                , c: headers.length - 1
            }
        }, {
            s: {
                r: 3
                , c: 0
            }
            , e: {
                r: 3
                , c: headers.length - 1
            }
        }, {
            s: {
                r: 5
                , c: 0
            }
            , e: {
                r: 5
                , c: 11
            }
        }, {
            s: {
                r: 5
                , c: 12
            }
            , e: {
                r: 5
                , c: 26
            }
        }, ];

        // === Auto width ===
        let colWidths = [];
        headers.forEach((_, colIndex) => {
            let maxWidth = 10;
            ws_data.forEach(row => {
                let cell = row[colIndex];
                if (cell != null) {
                    let len = cell.toString().length;
                    if (len > maxWidth) maxWidth = len;
                }
            });
            colWidths.push({
                wch: maxWidth + 2
            });
        });
        ws['!cols'] = colWidths;

        // === Styling ===
        let range = XLSX.utils.decode_range(ws['!ref']);
        for (let R = range.s.r; R <= range.e.r; ++R) {
            for (let C = range.s.c; C <= range.e.c; ++C) {
                let cellAddr = XLSX.utils.encode_cell({
                    r: R
                    , c: C
                });
                if (!ws[cellAddr]) continue;
                ws[cellAddr].s = {
                    font: {
                        bold: (R <= 6)
                    }
                    , alignment: {
                        horizontal: "center"
                        , vertical: "center"
                        , wrapText: true
                    }
                };
            }
        }

        // === Buat file ===
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "MASTER");
        let now = new Date();
        let dateString = moment(now).format('YYYYMMDDHHmmss');
        XLSX.writeFile(wb, "Pelayanan_klinik_" + dateString + ".xlsx");

        if (overlay) overlay.classList.add('d-none');
    }

</script>

<script>
    const video = document.getElementById('video');
    const snap = document.getElementById('snap');
    const retake = document.getElementById('retake');
    const preview = document.getElementById('preview');
    const gambarInput = document.getElementById('gambarInput');
    const btnSimpan = document.getElementById('btnSimpan');
    let stream = null;

    // 🔹 Nyalakan kamera
    async function startCamera() {
        if (stream) return;
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "user"
                }
            });
            video.srcObject = stream;
            video.style.display = "block";
            preview.style.display = "none";
            snap.style.display = "inline-block";
            retake.style.display = "none";
            btnSimpan.disabled = true;
        } catch (err) {
            console.error("Gagal akses kamera:", err);
            alert("Tidak dapat mengakses kamera. Pastikan izin kamera aktif atau pilih upload manual.");
            switchToUpload(); // fallback otomatis ke upload
        }
    }

    // 🔹 Matikan kamera
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
        video.style.display = "none";
    }

    // 🔹 Modal buka/tutup
    const modalEl = document.getElementById('addsuratGenerator');
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', startCamera);
        modalEl.addEventListener('hidden.bs.modal', stopCamera);
    }

    // 🔹 Ambil foto
    snap ? .addEventListener("click", () => {
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");

        const targetWidth = 600;
        const targetHeight = 800;
        canvas.width = targetWidth;
        canvas.height = targetHeight;

        const videoWidth = video.videoWidth;
        const videoHeight = video.videoHeight;
        const targetRatio = targetWidth / targetHeight;

        let sx, sy, sWidth, sHeight;
        if (videoWidth / videoHeight > targetRatio) {
            sHeight = videoHeight;
            sWidth = videoHeight * targetRatio;
            sx = (videoWidth - sWidth) / 2;
            sy = 0;
        } else {
            sWidth = videoWidth;
            sHeight = videoWidth / targetRatio;
            sx = 0;
            sy = (videoHeight - sHeight) / 2;
        }

        // mirror
        context.translate(targetWidth, 0);
        context.scale(-1, 1);
        context.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, targetWidth, targetHeight);

        const dataUrl = canvas.toDataURL("image/png");
        preview.src = dataUrl;
        preview.style.display = "block";
        gambarInput.value = dataUrl;
        btnSimpan.disabled = false;

        stopCamera();
        retake.style.display = "inline-block";
        snap.style.display = "none";
    });

    // 🔹 Ambil ulang foto
    retake ? .addEventListener("click", () => {
        preview.style.display = "none";
        gambarInput.value = "";
        btnSimpan.disabled = true;
        startCamera();
    });

    // === 🔹 Pilihan Kamera / Upload ===
    const btnUseCamera = document.getElementById('btnUseCamera');
    const btnUseUpload = document.getElementById('btnUseUpload');
    const cameraSection = document.getElementById('cameraSection');
    const uploadSection = document.getElementById('uploadSection');
    const uploadFoto = document.getElementById('uploadFoto');
    const previewUpload = document.getElementById('previewUpload');

    function switchToCamera() {
        btnUseCamera ? .classList.add('active');
        btnUseUpload ? .classList.remove('active');
        cameraSection.style.display = 'block';
        uploadSection.style.display = 'none';
        gambarInput.value = '';
        previewUpload.style.display = 'none';
        btnSimpan.disabled = true;
        startCamera();
    }

    function switchToUpload() {
        btnUseUpload ? .classList.add('active');
        btnUseCamera ? .classList.remove('active');
        uploadSection.style.display = 'block';
        cameraSection.style.display = 'none';
        stopCamera();
        gambarInput.value = '';
        preview.style.display = 'none';
        btnSimpan.disabled = true;
    }

    btnUseCamera ? .addEventListener('click', switchToCamera);
    btnUseUpload ? .addEventListener('click', switchToUpload);

    // 🔹 Upload Foto Manual
    uploadFoto ? .addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewUpload.src = e.target.result;
                previewUpload.style.display = 'block';
                gambarInput.value = e.target.result;
                btnSimpan.disabled = false;
            };
            reader.readAsDataURL(file);
        }
    });

</script>

@endpush
@endsection
