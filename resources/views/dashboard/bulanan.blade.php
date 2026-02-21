@extends('template.app')
@section('content')
<div class="container mt-4">
    <div class="w-full">
        <div class="bg-white p-4 rounded-xl shadow-lg">
            <h5 class="text-gray-800 text-xl font-semibold mb-4">
                Data Per Bulan Maret 2023
                {{-- Data Per Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }} --}}
            </h5>
            <div class="row mb-4">
                {{-- Filter Dari Tanggal --}}
                <div class="col-md-{{ Auth::user()->role != 0 ? '3' : '4' }} mb-2">
                    <label for="startDate" class="form-label fw-semibold text-secondary">Dari Tanggal:</label>
                    {{-- <input type="date" id="startDate" name="start_date" class="form-control" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"> --}}
                    <input type="date" id="startDate" name="start_date" class="form-control" value="2023-03-01">
                </div>

                {{-- Filter Hingga Tanggal --}}
                <div class="col-md-{{ Auth::user()->role != 0 ? '3' : '4' }} mb-2">
                    <label for="endDate" class="form-label fw-semibold text-secondary">Hingga Tanggal:</label>
                    {{-- <input type="date" id="endDate" name="end_date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" min="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"> --}}
                    <input type="date" id="endDate" name="end_date" class="form-control" value="2023-03-31">
                </div>

                @if(Auth::user()->role != 0)
                <div class="col-md-3 mb-2">
                    <label for="clinicFilter" class="form-label fw-semibold text-secondary">Filter Berdasarkan Klinik:</label>
                    <select id="clinicFilter" name="clinic_id" class="form-select select2">
                        @php
                        $clinics = \App\Models\Provider::all();
                        function branchName($id) {
                        return \App\Models\BranchOffice::where('branch_id', $id)->value('branch_name');
                        }
                        @endphp
                        <option value="">Semua Klinik</option>
                        @foreach ($clinics as $clinic)
                        <option value="{{ $clinic->clinic_id }}">
                            [{{ branchName($clinic->branch_id ?? 'NONE') }}] {{ $clinic->clinic_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-10 col-md-3 d-flex flex-row align-items-end gap-2 mt-md-4 mb-2 justify-content-between justify-content-md-end">
                    <button id="filterButton" class="btn btn-primary w-100 w-md-auto">
                        Preview <i class="fa fa-arrow-right"></i>
                    </button>
                    <button id="refreshAllTabs" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fa fa-sync"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Table for visit records --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="bg-white p-6 rounded-xl shadow-lg lg:col-span-3">
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-diagnosis-tab" data-bs-toggle="pill" data-bs-target="#pills-diagnosis" type="button" role="tab" aria-controls="pills-diagnosis" aria-selected="true">Top 10 Diagnosis</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-visits-division-tab" data-bs-toggle="pill" data-bs-target="#pills-visits-division" type="button" role="tab" aria-controls="pills-visits-division" aria-selected="false">Top 5 Kunjungan Perbagian</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-individual-visits-tab" data-bs-toggle="pill" data-bs-target="#pills-individual-visits" type="button" role="tab" aria-controls="pills-individual-visits" aria-selected="false">Top 10 Kunjungan Perorangan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-total-visits-tab" data-bs-toggle="pill" data-bs-target="#pills-total-visits" type="button" role="tab" aria-controls="pills-total-visits" aria-selected="false">Jumlah Kunjungan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-sick-notes-tab" data-bs-toggle="pill" data-bs-target="#pills-sick-notes" type="button" role="tab" aria-controls="pills-sick-notes" aria-selected="false">Jumlah Surat Sakit</button>
                </li>
            </ul>
            <hr class="my-4 border-gray-300">
            <div class="tab-content" id="pills-tabContent">
                {{-- Top 10 Diagnosis --}}
                <div class="tab-pane fade show active" id="pills-diagnosis" role="tabpanel" aria-labelledby="pills-diagnosis-tab">
                    <div class="row">
                        <div class="col-md-4"> {{-- Kolom untuk Tabel (5/12 lebar) --}}
                            <h6 class="text-gray-700 text-lg font-semibold mb-3">Tabel Top 10 Diagnosis</h6>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-hover table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Klinik</th>
                                            <th scope="col">Kode ICD</th>
                                            <th scope="col">Diagnosis</th>
                                            <th scope="col">Jumlah Kasus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 mt-4"> {{-- Kolom untuk Chart (responsive: full on mobile, 8/12 on md+) --}}
                            <h6 class="text-gray-700 text-lg font-semibold mb-3">Grafik Top 10 Diagnosis</h6>
                            <div id="chart-diagnosis" class="w-100" style="min-height: 300px; height: 45vw; max-height: 450px;"></div>
                        </div>
                    </div>
                </div>

                {{-- Top 5 Kunjungan Perbagian --}}
                <div class="tab-pane fade" id="pills-visits-division" role="tabpanel" aria-labelledby="pills-visits-division-tab">
                    <div class="row"> {{-- Menggunakan Bootstrap Grid Row --}}
                        {{-- Tombol Export --}}
                        <div class="mb-3 d-flex gap-2">
                            <a href="{{ route('export.bulanan.top5kunjunganperbagian') }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fa fa-file-excel"></i> Export Excel
                            </a>
                            {{-- <button class="btn btn-danger btn-sm">
                                <i class="fa fa-file-pdf"></i> Export PDF
                            </button> --}}
                        </div>
                        <div class="col-md-4"> {{-- Kolom untuk Tabel (5/12 lebar) --}}
                            <h6 class="text-gray-700 text-lg font-semibold mb-3">Tabel Top 5 Kunjungan Perbagian</h6>
                            <div class="table-responsive">
                                <table id="tabeltop5kunjperbagian" class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bagian</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 mt-4"> {{-- Kolom untuk Chart (responsive: full on mobile, 8/12 on md+) --}}
                            <h6 class="text-gray-700 text-lg font-semibold mb-3">Grafik Top 5 Kunjungan Perbagian</h6>
                            <div id="chart-visits-division" class="w-100"></div>
                        </div>
                    </div>
                </div>

                {{-- Top 10 Kunjungan Perorangan --}}
                <div class="tab-pane fade" id="pills-individual-visits" role="tabpanel" aria-labelledby="pills-individual-visits-tab">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 d-flex gap-2">
                                <button class="btn btn-success btn-sm">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </button>
                            </div>
                            <h6 class="text-gray-700 text-lg font-semibold mb-3">Tabel Top 10 Kunjungan Perorangan</h6>
                            <div class="table-responsive">
                                <table id="tabeltop10kunjperorangan" class="table table-striped table-hover table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Pasien</th>
                                            <th>Nama</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 mt-4">
                            <h6 class="text-gray-700 text-lg font-semibold mb-3 mt-3">Grafik Top 10 Kunjungan Perorangan</h6>
                            <div id="chart-individual-visits"></div>
                        </div>
                    </div>
                </div>
                {{-- Jumlah Kunjungan --}}
                <div class="tab-pane fade" id="pills-total-visits" role="tabpanel" aria-labelledby="pills-total-visits-tab">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="mb-3 d-flex gap-2">
                                <button class="btn btn-success btn-sm">
                                    <i class="fa fa-file-excel"></i> Export Excel
                                </button>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-file-pdf"></i> Export PDF
                                </button>
                            </div>
                            <h6 class="text-gray-700 text-lg font-semibold mb-3">Grafik Jumlah Kunjungan</h6>
                            <div id="chart-total-visits"></div>
                        </div>
                        <div class="col-12 col-md-4 mt-4 mt-md-0">
                            <h6 class="text-gray-700 text-lg font-semibold mb-3 mt-5">Ringkasan Jumlah Kunjungan</h6>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Kunjungan
                                    <span class="badge bg-primary rounded-pill" id="totalVisitsThisMonth">-</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Rata-rata Kunjungan
                                    <span class="badge bg-info rounded-pill" id="avgVisitsPerDay">-</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Hari Tertinggi
                                    <span class="badge bg-success rounded-pill" id="maxVisitsDay">-</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Hari Terendah
                                    <span class="badge bg-danger rounded-pill" id="minVisitsDay">-</span>
                                </li>
                            </ul>
                            <div class="alert alert-info small mb-0">
                                {{-- <i class="fa fa-info-circle"></i> --}}
                                <span id="ket"></span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Jumlah Surat Sakit --}}
                <div class="tab-pane fade" id="pills-sick-notes" role="tabpanel" aria-labelledby="pills-sick-notes-tab">
                    <div class="mb-3 d-flex justify-content-center gap-2">
                        <button class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel"></i> Export Excel
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> Export PDF
                        </button>
                    </div>
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary shadow-lg">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-primary mb-2" style="font-size: 17px"><b>Jumlah Surat Sakit </b></h6>
                                    <span class="display-5 fw-bold" id="JumlahPesertaSakit" style="font-size: 50px"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
{{-- datatable --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
{{-- Tailwind CSS CDN --}}
<script src="https://cdn.tailwindcss.com"></script>
@endpush

@push('scripts')
<!-- Moment.js & DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
            , width: '100%'
        });

        let tabLoaded = {
            top10diagnosis: false
            , visitsdivision: false
            , individualvisits: false
            , totalvisits: false
            , sicknotes: false
        };

        // Filter button
        $('#filterButton').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

            // Reset semua tab agar reload saat dibuka kembali
            tabLoaded = {
                top10diagnosis: false
                , visitsdivision: false
                , individualvisits: false
                , totalvisits: false
                , sicknotes: false
            };

            // Hanya load ulang tab aktif saat ini
            updateDashboardData().always(() => {
                const activeKey = getActiveTabKey();
                tabLoaded[activeKey] = true;
                $btn.prop('disabled', false).html('Preview <i class="fa fa-arrow-right"></i>');
            });
        });

        // Tambahan tombol refresh semua tab
        $('#refreshAllTabs').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Refreshing...');
            tabLoaded = {
                top10diagnosis: false
                , visitsdivision: false
                , individualvisits: false
                , totalvisits: false
                , sicknotes: false
            };

            updateDashboardData().always(() => {
                loadTop10Diagnosis().then(() => tabLoaded.top10diagnosis = true);
                loadVisitsDivision().then(() => tabLoaded.visitsdivision = true);
                loadIndividualVisits().then(() => tabLoaded.individualvisits = true);
                loadTotalVisits().then(() => tabLoaded.totalvisits = true);
                loadSickNotes().then(() => tabLoaded.sicknotes = true);
                $btn.prop('disabled', false).html('<i class="fa fa-sync"></i>');
            });
        });

        // Tab switching (load jika belum pernah)
        $('#pills-total-visits-tab').on('click', () => !tabLoaded.totalvisits && loadTotalVisits().then(() => tabLoaded.totalvisits = true));
        $('#pills-visits-division-tab').on('click', () => !tabLoaded.visitsdivision && loadVisitsDivision().then(() => tabLoaded.visitsdivision = true));
        $('#pills-individual-visits-tab').on('click', () => !tabLoaded.individualvisits && loadIndividualVisits().then(() => tabLoaded.individualvisits = true));
        $('#pills-sick-notes-tab').on('click', () => !tabLoaded.sicknotes && loadSickNotes().then(() => tabLoaded.sicknotes = true));

        // Load tab default
        loadTop10Diagnosis().then(() => tabLoaded.top10diagnosis = true);
    });

    function getActiveTabKey() {
        const id = $('#dashboardTabs .nav-link.active').attr('id');
        return {
            'pills-total-visits-tab': 'totalvisits'
            , 'pills-visits-division-tab': 'visitsdivision'
            , 'pills-individual-visits-tab': 'individualvisits'
            , 'pills-sick-notes-tab': 'sicknotes'
        } [id] || 'top10diagnosis';
    }

    function getFilters() {
        return {
            start_date: $('#startDate').val()
            , end_date: $('#endDate').val()
            , clinic_id: $('#clinicFilter').val()
        };
    }

    function updateDashboardData() {
        switch (getActiveTabKey()) {
            case 'totalvisits':
                return loadTotalVisits();
            case 'visitsdivision':
                return loadVisitsDivision();
            case 'individualvisits':
                return loadIndividualVisits();
            case 'sicknotes':
                return loadSickNotes();
            default:
                return loadTop10Diagnosis();
        }
    }

    function loadTop10Diagnosis() {
        const filters = getFilters();
        $('#chart-diagnosis').html(spinnerDiv(300));
        return $.ajax({
            url: '{{ route("top10diagnosis") }}'
            , method: 'GET'
            , data: filters
            , success: function(response) {
                const data = response.top10diagnosis;
                const $table = $('#datatable');
                if ($.fn.DataTable.isDataTable($table)) $table.DataTable().destroy();
                const $tbody = $table.find('tbody').empty();

                data.tabel.forEach((item, i) => {
                    $tbody.append(`
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${item.klinik}</td>
                            <td>${item.kode_icd}</td>
                            <td>${item.nama_icd}</td>
                            <td>${item.total}</td>
                        </tr>`);
                });

                $table.DataTable({
                    responsive: true
                    , order: []
                    , columnDefs: [{
                        targets: 0
                        , className: 'text-center'
                    }]
                    , pageLength: 2
                    , lengthMenu: [
                        [2, 5, 10, 50, 100, -1]
                        , [2, 5, 10, 50, 100, 'All']
                    ]
                });

                if (window.chartDiagnosis) window.chartDiagnosis.destroy();
                $('#chart-diagnosis').empty();
                window.chartDiagnosis = new ApexCharts(document.querySelector("#chart-diagnosis"), {
                    chart: {
                        type: 'area'
                        , height: 500
                    }
                    , series: [{
                        name: 'Jumlah Kasus'
                        , data: data.total
                    }]
                    , xaxis: {
                        categories: data.label
                    }
                    , stroke: {
                        curve: 'smooth'
                        , width: 3
                    }
                    , markers: {
                        size: 2
                    }
                    , tooltip: {
                        shared: true
                        , intersect: false
                    }
                });
                window.chartDiagnosis.render();
            }
        });
    }

    function loadVisitsDivision() {
        const filters = getFilters();
        const $table = $('#tabeltop5kunjperbagian');
        if ($.fn.DataTable.isDataTable($table)) $table.DataTable().destroy();
        $table.find('tbody').html(spinnerRow(3));
        $('#chart-visits-division').html(spinnerDiv(300));

        return $.ajax({
            url: '{{ route("top5kunjunganperbagian") }}'
            , method: 'GET'
            , data: filters
            , success: function(res) {
                const data = res.top5kunjunganperbagian;
                const $tbody = $table.find('tbody').empty();

                data.tabel.forEach((item, i) => {
                    $tbody.append(`<tr><td class="text-center">${i + 1}</td><td>${item.occupation}</td><td>${item.Total}</td></tr>`);
                });

                $table.DataTable({
                    responsive: true
                    , order: []
                    , columnDefs: [{
                        targets: 0
                        , className: 'text-center'
                    }]
                    , pageLength: 5
                    , lengthMenu: [
                        [2, 5, 10, 50, 100, -1]
                        , [2, 5, 10, 50, 100, 'All']
                    ]
                });

                if (window.chartVisitsDivision) window.chartVisitsDivision.destroy();
                $('#chart-visits-division').empty();
                window.chartVisitsDivision = new ApexCharts(document.querySelector("#chart-visits-division"), {
                    chart: {
                        type: 'pie'
                        , height: 400
                    }
                    , series: data.total
                    , labels: data.label
                });
                window.chartVisitsDivision.render();
            }
        });
    }

    function loadIndividualVisits() {
        const filters = getFilters();
        const $table = $('#tabeltop10kunjperorangan');
        if ($.fn.DataTable.isDataTable($table)) $table.DataTable().destroy();
        $table.find('tbody').html(spinnerRow(6));
        $('#chart-individual-visits').html(spinnerDiv(300));

        return $.ajax({
            url: '{{ route("top10kunjunganperorangan") }}'
            , method: 'GET'
            , data: filters
            , success: function(res) {
                const data = res.top10kunjunganperorangan;
                const $tbody = $table.find('tbody').empty();

                data.tabel.forEach((item, i) => {
                    $tbody.append(`
                        <tr>
                            <td class="text-center">${i + 1}</td>
                            <td>${item.NoPasien}</td>
                            <td>${item.Nama}</td>
                            <td>${item.TglLahir}</td>
                            <td>${item.JenisKelamin === 'F' ? 'Perempuan' : 'Laki-laki'}</td>
                            <td>${item.JumlahKunjungan}</td>
                        </tr>`);
                });

                $table.DataTable({
                    responsive: true
                    , order: []
                    , columnDefs: [{
                        targets: 0
                        , className: 'text-center'
                    }]
                    , pageLength: 5
                    , lengthMenu: [
                        [2, 5, 10, 50, 100, -1]
                        , [2, 5, 10, 50, 100, 'All']
                    ]
                });

                if (window.chartIndividualVisits) window.chartIndividualVisits.destroy();
                $('#chart-individual-visits').empty()
                window.chartIndividualVisits = new ApexCharts(document.querySelector("#chart-individual-visits"), {
                    chart: {
                        type: 'bar'
                        , height: 500
                    }
                    , series: [{
                        name: 'Jumlah Kunjungan'
                        , data: data.total
                    }]
                    , xaxis: {
                        categories: data.label
                    }
                });
                window.chartIndividualVisits.render();
            }
        });
    }

    function loadTotalVisits() {
        const filters = getFilters();
        $('#chart-total-visits').html(spinnerDiv(300));

        return $.ajax({
            url: '{{ route("jumlah-kunjungan") }}'
            , method: 'GET'
            , data: filters
            , success: function(res) {
                if (!res || !res.data || res.data.length === 0) {
                    $('#chart-total-visits').html('<div class="text-center py-5 text-muted">Tidak ada data kunjungan.</div>');
                    $('#totalVisitsThisMonth, #avgVisitsPerDay, #maxVisitsDay, #minVisitsDay, #ket').text('-');
                    return;
                }

                $('#totalVisitsThisMonth').text(res.Total_Kunjungan.toLocaleString());
                $('#avgVisitsPerDay').text(parseFloat(res.rata_rata).toFixed(1));
                $('#ket').html(`<i class="fa fa-info-circle"></i> Data dari ${res.filter_date[0]} hingga ${res.filter_date[1]}`);
                $('#maxVisitsDay').text(`${formatDate(res.tertinggi.tanggal)} (${res.tertinggi.jumlah})`);
                $('#minVisitsDay').text(`${formatDate(res.terendah.tanggal)} (${res.terendah.jumlah})`);

                if (window.chartTotalVisits) window.chartTotalVisits.destroy();
                $('#chart-total-visits').empty();
                window.chartTotalVisits = new ApexCharts(document.querySelector("#chart-total-visits"), {
                    chart: {
                        type: 'bar'
                        , height: 500
                    }
                    , series: [{
                        data: res.data
                    }]
                    , xaxis: {
                        categories: res.label
                    }
                });
                window.chartTotalVisits.render();
            }
            , error: function() {
                $('#chart-total-visits').html('<div class="text-danger text-center py-5">Terjadi kesalahan saat mengambil data.</div>');
            }
        });
    }

    function loadSickNotes() {
        const filters = getFilters();
        $('#JumlahPesertaSakit').html(spinnerDiv(60));
        return $.ajax({
            url: '{{ route("jumlah-surat-sakit") }}'
            , method: 'GET'
            , data: filters
            , success: function(res) {
                $('#JumlahPesertaSakit').text(res.jumlahsuratsakit);
            }
        });
    }

    // Utilities
    function spinnerRow(colspan) {
        return `<tr><td colspan="${colspan}" class="text-center">${spinnerDiv(60)}</td></tr>`;
    }

    function spinnerDiv(height = 60) {
        return `<div class="d-flex justify-content-center align-items-center" style="height:${height}px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`;
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit'
            , month: 'short'
            , year: 'numeric'
        });
    }

</script>
@endpush
@endsection
