@extends('template.app')
@section('content')
    <div id="loading-overlay" class="d-none"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">
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
                        <h5 class="card-title">Data Tagihan</h5>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <div class="mt-3 mb-4">
                        @if (Auth::user()->role != 0)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addTagihan">
                                <i class='bx bx-plus'></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
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
                                <th>No Tagihan</th>
                                <th>Perusahaan</th>
                                <th>Agent</th>
                                <th>Klinik</th>
                                <th>QTY</th>
                                <th>Total</th>
                                <th>Status Tagihan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $dataItem)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::upper($dataItem->nomor_tagihan ?? '-') }}</td>
                                    <td>{{ Str::upper($dataItem->customer->nama_perusahaan ?? '-') }}</td>
                                    <td>{{ Str::upper(data_get($dataItem, 'pasien.0.agent', '-')) }}</td>
                                    <td>{{ Str::upper($dataItem->clinic->nama_klinik ?? '-') }}</td>
                                    <td>{{ $dataItem->qty ?? '-' }}</td>
                                    <td>Rp. {{ number_format($dataItem->qty * $dataItem->satuan, 0, ',', '.') ?? '-' }}</td>
                                    <td>
                                        @if ($dataItem->status_tagihan == true)
                                            <span class="badge bg-success">LUNAS</span>
                                        @else
                                            <span class="badge bg-warning text-dark">MENUNGGU PEMBAYARAN</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-primary rounded bx bx-detail"
                                                data-bs-toggle="modal" data-bs-target="#showTagihan{{ $dataItem->id }}">
                                            </button>
                                            @if (Auth::user()->role != '0')
                                                @if ($dataItem->status_tagihan == false)
                                                    <button type="button"
                                                        class="btn btn-sm btn-success rounded bx bx-money"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#bayarTagihan{{ $dataItem->id }}">
                                                    </button>
                                                @endif
                                                {{-- <a href="{{ route('tagihan.show' , $dataItem->id) }}" target="_blank" class="btn btn-sm btn-warning rounded bx bxs-file-pdf">
                                    </a> --}}
                                                <button type="button" class="btn btn-sm btn-danger rounded bx bx-trash"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteTagihan{{ $dataItem->id }}">
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('tagihan.modal.add')
    @include('tagihan.modal.show')
    @include('tagihan.modal.delete')
    @include('tagihan.modal.bayar')
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
                processing: true,
                lengthMenu: [
                    [10, 50, 100, -1],
                    [10, 50, 100, 'All']
                ],
                pageLength: 10
            });
            $('.select2').each(function() {
                $(this).select2({
                    placeholder: "Cari atau pilih peserta...",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $(this).closest('.modal')
                });
            });

            // Generate nomor transaksi berdasarkan patient_id
            $('#patient_id').on('change', function() {
                let patientId = $(this).val();

                if (patientId) {
                    $.ajax({
                        url: '/generate-no-transaksi/patient/' + patientId,
                        type: 'GET',
                        success: function(response) {
                            $('#no_transaksi').val(response.no_transaksi);
                        },
                        error: function() {
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
                        url: '{{ route("generate.no.tagihan", ":clinic_id") }}'.replace(':clinic_id', clinicId),
                        type: 'GET',
                        success: function(response) {
                            $('#nomor_tagihan').val(response.no_tagihan);
                        },
                        error: function() {
                            $('#nomor_tagihan').val('');
                            alert('Gagal generate nomor tagihan');
                        }
                    });
                }
            });

            $('#customerSelect, #klinikSelect').on('change', function() {
                let customerId = $('#customerSelect').val();
                let clinicId = $('#klinikSelect').val();

                if (customerId && clinicId) {
                    $.ajax({
                        url: '{{ route("get-agent", [":customer_id", ":clinic_id"]) }}'
    .replace(':customer_id', customerId)
    .replace(':clinic_id', clinicId),
                        type: 'GET',
                        success: function(response) {
                            console.log("Agent Response:", response);

                            let $agentSelect = $('#agent_id');

                            let optionsHtml = `
                    <option value="">Pilih agent</option>
                    <optgroup label="Other">
                        <option value="all">Semua Agent</option>
                        <option value="without">Tanpa Agent</option>
                    </optgroup>
                    <optgroup label="Agent">
                `;

                            if (response.length > 0) {
                                response.forEach(function(agent) {
                                    let namaAgent = (agent.nama_agent || '').toUpperCase();
                                    let namaCustomer = agent.customer?.nama_perusahaan || '';
                                    optionsHtml +=
                                        `<option value="${agent.id}">${namaAgent}${namaCustomer ? ' - ' + namaCustomer : ''}</option>`;
                                });
                            } else {
                                optionsHtml += `<option disabled>Tidak ada agent</option>`;
                            }

                            optionsHtml += `</optgroup>`;

                            // aktifkan select & isi ulang
                            $agentSelect.prop('disabled', false).html(optionsHtml);

                            // reset pilihan + refresh select2
                            $agentSelect.val("").trigger("change.select2");
                        },
                        error: function() {
                            // kalau error, disable lagi
                            $('#agent_id').prop('disabled', true).empty().append(
                                    '<option value="">Pilih agent</option>')
                                .trigger('change.select2');
                        }
                    });
                } else {
                    // reset agent kalau customer/clinic kosong
                    $('#agent_id').prop('disabled', true).empty().append('<option value="">Pilih agent</option>')
                        .trigger('change.select2');
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
                    head: [headers],
                    body: data,
                    startY: 20,
                    theme: "striped",
                    styles: {
                        fontSize: 5.4,
                        textColor: [0, 0, 0]
                    },
                    headStyles: {
                        fillColor: [192, 192, 192],
                        textColor: [0, 0, 0]
                    },
                });

                let now = new Date();
                let dateString = moment(now).format('YYYYMMDDHHmmss');
                doc.save("Pelayanan_klinik_" + dateString + ".pdf");
                document.getElementById('loading-overlay').classList.add('d-none');
            }

            function exportExcel() {
                document.getElementById('loading-overlay').classList.remove('d-none');

                // Ambil header
                let headers = [];
                $('#example thead th').each(function(index) {
                    if (index < 22) {
                        headers.push($(this).text().trim());
                    }
                });

                // Ambil data dari DOM
                let data = [];
                $('#example tbody tr:visible').each(function() {
                    let rowData = [];
                    $(this).find('td').each(function(index) {
                        if (index < 22) {
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
                XLSX.utils.book_append_sheet(wb, ws, "MASTER");

                let now = new Date();
                let dateString = moment(now).format('YYYYMMDDHHmmss');
                XLSX.writeFile(wb, "Pelayanan_klinik_" + dateString + ".xlsx");
                document.getElementById('loading-overlay').classList.add('d-none');
            }
        </script>
    @endpush
@endsection
