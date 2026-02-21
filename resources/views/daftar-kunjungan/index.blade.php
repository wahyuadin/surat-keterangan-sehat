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
                    <h5 class="card-title">Data Daftar Kunjungan</h5>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <div class="mt-3 mb-4">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-export'></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="printPDF()">Print PDF</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Export
                                Excel</a>
                        </li>
                    </ul>
                </div>
                <table id="example" class="table table-striped table-bordered w-100 mt-3">
                    @include('alert')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Pasien</th>
                            <th>Waktu Registrasi</th>
                            <th>Group Pasien</th>
                            <th>No BPJS</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Lahir</th>
                            <th>jenis Kelamin</th>
                            <th>Usia</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Kode ICD</th>
                            <th>Diagnosa</th>
                            <th>Bagian</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- @include('superadmin.absensi.add')
    @include('superadmin.absensi.edit') --}}
@push('style')
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
    new DataTable('#example', {
        serverSide: true
        , processing: true
        , language: {
            processing: "Tunggu Sebentar, Memerlukan Waktu Beberapa Detik..."
            , emptyTable: "Tidak ada data tersedia"
            , lengthMenu: "Tampilkan _MENU_ data"
            , zeroRecords: "Data tidak ditemukan"
            , info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            , infoEmpty: "Menampilkan 0 sampai 0 dari 0 data"
            , infoFiltered: "(disaring dari _MAX_ total data)"
            , search: "Cari:"
        }
        , ajax: {
            url: '{{ route("server-site-daftar-kunjungan") }}'
            , data: function(d) {
                d.clinic_id = $('#clinicFilter').val(); // Kirim nilai filter
            }
        }
        , columns: [{
                data: null
                , orderable: false
                , render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
            }
            , {
                data: 'patient_id'
            }
            , {
                data: 'registration_date'
            }
            , {
                data: 'client_name'
            }
            , {
                data: 'bpjs_no'
            }
            , {
                data: 'patient_name'
            }
            , {
                data: 'birth_date'
                , render: function(data) {
                    const dateOnly = data ? data.split(' ')[0] : '';
                    return dateOnly || '-';
                }
            }
            , {
                data: 'sex'
                , render: function(data) {
                    return data === 'M' ? 'Laki-laki' : (data === 'F' ? 'Perempuan' : '-');
                }
            }
            , {
                data: 'birth_date'
                , render: function(data) {
                    if (!data || !moment(data).isValid()) return '-';
                    const age = moment().diff(moment(data), 'years');
                    return age + ' Tahun';
                }
            }
            , {
                data: 'poly_name'
            }
            , {
                data: 'doctor_name'
            }
            , {
                data: 'kdicd'
            }
            , {
                data: 'diagnosa'
            }
            , {
                data: 'occupation'
                , render: function(data) {
                    return data ? data.charAt(0).toUpperCase() + data.slice(1).toLowerCase() : 'Data Tidak Diinputkan';
                }
            }
        ]
        , pageLength: 10
        , lengthMenu: [
            [10, 50, 100, 1000, 10000]
            , [10, 50, 100, 1000, 10000]
        ]
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
        let doc = new jsPDF();

        let title = "DATA DAFTAR KUNJUNGAN PT NAYAKA ERA HUSADA";
        let pageWidth = doc.internal.pageSize.width;
        let titleWidth = doc.getTextWidth(title);
        doc.text(title, (pageWidth - titleWidth) / 2, 10);

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 8) { // Hanya ambil kolom yang relevan
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari tabel DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 8) { // Ambil hanya kolom yang relevan
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
                fontSize: 8
                , textColor: [0, 0, 0]
            }
            , headStyles: {
                fillColor: [192, 192, 192]
                , textColor: [0, 0, 0]
            }
        , });

        doc.save("Dafta_Daftar_Kunjungan.pdf");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

    function exportExcel() {
        document.getElementById('loading-overlay').classList.remove('d-none');

        // Ambil header
        let headers = [];
        $('#example thead th').each(function(index) {
            if (index < 8) {
                headers.push($(this).text().trim());
            }
        });

        // Ambil data dari DOM
        let data = [];
        $('#example tbody tr:visible').each(function() {
            let rowData = [];
            $(this).find('td').each(function(index) {
                if (index < 8) {
                    let text = $(this).text().trim();
                    rowData.push(ucwordsJS(text));
                }
            });
            data.push(rowData);
        });

        let ws_data = [headers, ...data];
        let ws = XLSX.utils.aoa_to_sheet(ws_data);

        // Hitung lebar kolom
        let colWidths = ws_data[0].map((_, colIndex) => {
            let maxWidth = 10;
            ws_data.forEach(row => {
                let cell = row[colIndex];
                if (cell && cell.length > maxWidth) {
                    maxWidth = cell.length;
                }
            });
            return {
                wch: maxWidth + 2
            };
        });
        ws['!cols'] = colWidths;

        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Daftar Kunjungan");

        XLSX.writeFile(wb, "Daftar_Kunjungan.xlsx");
        document.getElementById('loading-overlay').classList.add('d-none');
    }

</script>
@endpush
@endsection
