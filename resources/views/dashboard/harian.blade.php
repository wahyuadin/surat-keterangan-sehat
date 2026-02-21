@extends('template.app')
@section('content')
<div class="container mt-4">
    <div class="w-full">
        <div class="bg-white p-4 rounded-xl shadow-lg">
            <h5 class="text-gray-800 text-xl font-semibold mb-4">
                Dashboard
            </h5>
            @if(Auth::user()->role != 0)
            <div class="mb-4">
                <label for="clinicFilter" class="block mb-1 font-semibold text-gray-700">Filter Berdasarkan Perusahaan:</label>
                <select id="clinicFilter" class="form-select select2 w-100 p-2 border border-gray-300 rounded-md">
                    <option value=""></option>
                </select>
            </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-3">
                {{-- Card for "Belum Dilayani" count --}}
                <div class="bg-red-600 text-white p-6 rounded-xl shadow flex flex-col justify-center items-center transition-transform duration-300 hover:scale-105">
                    <h6 class="text-lg font-semibold mb-2" style="color:white">Card 1</h6>
                    <h3 id="belumDiLayaniCount" class="text-4xl font-bold" style="color:white">...</h3>
                </div>
                {{-- Card for "Sudah Dilayani" count --}}
                <div class="bg-green-600 text-white p-6 rounded-xl shadow flex flex-col justify-center items-center transition-transform duration-300 hover:scale-105">
                    <h6 class="text-lg font-semibold mb-2" style="color:white">Card 2</h6>
                    <h3 id="sudahDiLayaniCount" class="text-4xl font-bold" style="color:white">...</h3>
                </div>
                {{-- Card for "Total Dilayani" count --}}
                <div class="bg-blue-600 text-white p-6 rounded-xl shadow flex flex-col justify-center items-center transition-transform duration-300 hover:scale-105">
                    <h6 class="text-lg font-semibold mb-2" style="color:white">Card 3</h6>
                    <h3 id="totalDiLayaniCount" class="text-4xl font-bold" style="color:white">...</h3>
                </div>
            </div>
        </div>
    </div>
    {{-- Table for visit records --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="bg-white p-6 rounded-xl shadow-lg lg:col-span-3">
            <h5 class="text-xl font-semibold mb-4 text-gray-800">[Belum Ada isi]</h5>
            <div class="overflow-x-auto">
                <table id="example" class="table table-striped table-bordered w-full min-w-max">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pasien ID</th>
                            <th>Nama Pasien</th>
                            <th>Provider</th>
                            <th>Tanggal Lahir</th>
                            <th>Usia</th>
                            <th>jenis Kelamin</th>
                            <th>Bagian/ Departement</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart Containers --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        {{-- Donut Chart --}}
        <div class="bg-white p-6 rounded-xl shadow-lg transform transition-transform duration-300 hover:scale-105">
            <h5 class="text-xl font-semibold mb-4 text-gray-800">[Belum Ada isi]</h5>
            <div class="relative" style="height:300px;">
                <canvas id="donutChart"></canvas>
            </div>
        </div>

        {{-- Line Chart --}}
        <div class="bg-white p-6 rounded-xl shadow-lg lg:col-span-2 transform transition-transform duration-300 hover:scale-105">
            <h5 class="text-xl font-semibold mb-4 text-gray-800">[Belum Ada isi]</h5>
            <div class="relative" style="height:300px;">
                <canvas id="lineChart"></canvas>
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
{{-- Moment.js CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
{{-- Datatable --}}
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.6/js/dataTables.bootstrap5.js"></script>
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let table;
    let donutChart = null;
    let lineChart = null;

    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
            , width: '100%'
        });

        table = new DataTable('#example', {
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
                url: '{{ route("server-site-harian") }}'
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
                    data: 'patient_name'
                }
                , {
                    data: 'clinic_name'
                }
                , {
                    data: 'birth_date'
                    , render: function(data) {
                        const dateOnly = data ? data.split(' ')[0] : '';
                        return dateOnly || '-';
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
                    data: 'sex'
                    , render: function(data) {
                        return data === 'M' ? 'Laki-laki' : (data === 'F' ? 'Perempuan' : '-');
                    }
                }
                , {
                    data: 'occupation'
                    , render: function(data) {
                        return data ? data.charAt(0).toUpperCase() + data.slice(1).toLowerCase() : 'Data Tidak Diinputkan';
                    }
                }
            ]
            , pageLength: 5
            , lengthMenu: [
                [5, 10, 50, -1]
                , [5, 10, 50, 'All']
            ]
        });

        // Event listener filter
        $('#clinicFilter').on('change', function() {
            table.ajax.reload();
            updateDashboardData(); // Panggil fungsi untuk update kartu dan grafik
        });

        // Panggil saat pertama kali halaman dimuat
        updateDashboardData();
    });

    function updateDashboardData() {
        const clinicId = $('#clinicFilter').val();
        $.ajax({
            url: '{{ route("ajax-harian-summary") }}'
            , method: 'GET'
            , data: {
                clinic_id: clinicId
            }
            , success: function(response) {
                // Update Card Counts
                $('#belumDiLayaniCount').text(response.belumDiLayani);
                $('#sudahDiLayaniCount').text(response.sudahDiLayani);
                $('#totalDiLayaniCount').text(response.totalDiLayani);

                // Update Donut Chart
                if (donutChart) {
                    donutChart.destroy();
                }
                const donutCtx = document.getElementById('donutChart').getContext('2d');
                donutChart = new Chart(donutCtx, {
                    type: 'pie'
                    , data: {
                        labels: response.occupation.label
                        , datasets: [{
                            data: response.occupation.total
                            , hoverOffset: 4
                        }]
                    }
                    , options: {
                        responsive: true
                        , maintainAspectRatio: false
                        , plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });

                // Update Line Chart
                if (lineChart) {
                    lineChart.destroy();
                }
                const lineCtx = document.getElementById('lineChart').getContext('2d');
                lineChart = new Chart(lineCtx, {
                    type: 'line'
                    , data: {
                        labels: response.icd.label
                        , datasets: [{
                            label: 'Total'
                            , data: response.icd.total
                            , borderColor: '#8884d8'
                            , backgroundColor: 'rgba(136, 132, 216, 0.2)'
                            , fill: true
                            , tension: 0.3
                        }]
                    }
                    , options: {
                        responsive: true
                        , maintainAspectRatio: false
                        , plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                        , scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    }

</script>
@endpush
@endsection
