<div class="modal fade" id="addTagihan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('tagihan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addTagihanLabel">Tambah Tagihan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Periode</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_mulai" class="form-label">Dari <span class="text-danger">*</span></label>
                            @php
                            $now = now();
                            $lastMonth = $now->copy()->subMonth();
                            $maxDate = $now->format('Y-m-d');
                            $minDate = $lastMonth->format('Y-m-d');
                            @endphp
                            <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control" value="{{ $minDate }}" min="{{ $minDate }}" max="{{ $maxDate }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_sampai" class="form-label">Sampai <span class="text-danger">*</span></label>
                            <input type="date" id="tgl_sampai" name="tgl_sampai" class="form-control" value="{{ now()->format('Y-m-d') }}" min="{{ $minDate }}" max="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <!-- Customer -->
                        <div class="col-md-6 mb-3">
                            <label for="customerSelect" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select id="customerSelect" name="customer_id" class="form-select select2" required>
                                <option value=""></option>
                                @php
                                if (Auth::user()->role == '0') {
                                $customer = \App\Models\Customer::where('id', Auth::user()->customer_id)->get();
                                } else {
                                $customer = \App\Models\Customer::all();
                                }
                                @endphp
                                @foreach ($customer as $customerItem)
                                <option value="{{ $customerItem->id }}">
                                    {{ Str::upper($customerItem->nama_perusahaan ?? '-') }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Klinik -->
                        <div class="col-md-6 mb-3">
                            <label for="klinikSelect" class="form-label">Klinik <span class="text-danger">*</span></label>
                            <select id="klinikSelect" name="clinic_id" class="form-select select2" required>
                                <option value=""></option>
                                @php
                                if (Auth::user()->role == '1') {
                                $clinic = \App\Models\Clinic::where('id', Auth::user()->clinic_id)->get();
                                } else {
                                $clinic = \App\Models\Clinic::all();
                                }
                                @endphp
                                @foreach ($clinic as $clinicItem)
                                <option value="{{ $clinicItem->id }}">
                                    {{ Str::upper($clinicItem->nama_klinik ?? '-') }} -
                                    {{ Str::upper($clinicItem->alamat ?? '-') }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Agent -->
                        <div class="col-md-12 mb-3">
                            <label for="agent_id" class="form-label">Agent</label>
                            <select class="form-select select2" id="agent_id" name="agent_id" disabled>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="nomor_tagihan">INVOICE</label>
                            <input type="text" id="nomor_tagihan" name="nomor_tagihan" class="form-control" readonly>
                        </div>
                    </div>

                    <!-- Tabel pasien -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="patientTable" style="display:none;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>No Transaksi</th>
                                    <th>Tgl Transaksi</th>
                                    <th>Nama Pasien</th>
                                    <th>No KTP</th>
                                    <th>Perusahaan</th>
                                    <th>Agent</th>
                                    <th>Tagihan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpan" class="btn btn-primary" disabled>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnSimpan = document.getElementById('btnSimpan');
        const table = document.getElementById('patientTable');
        const tbody = table.querySelector('tbody');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency'
                , currency: 'IDR'
                , minimumFractionDigits: 0
            }).format(angka);
        }

        function loadPatients() {
            let tgl_mulai = document.getElementById('tgl_mulai').value;
            let tgl_sampai = document.getElementById('tgl_sampai').value;
            let customer_id = document.getElementById('customerSelect').value;
            let clinic_id = document.getElementById('klinikSelect').value;
            let dataAgent = document.getElementById('agent_id').value;

            if (!tgl_mulai || !tgl_sampai || !customer_id || !clinic_id || dataAgent === "") {
                table.style.display = "none";
                tbody.innerHTML = "";
                btnSimpan.disabled = true;
                return;
            }

            // tampilkan tabel & loading
            table.style.display = "table";
            tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                            <span class="ms-2">Memuat data...</span>
                        </td>
                    </tr>`;
            btnSimpan.disabled = true;

            fetch(
                    `{{ route('tagihan.getPatients') }}?tgl_mulai=${tgl_mulai}&tgl_sampai=${tgl_sampai}&customer_id=${customer_id}&clinic_id=${clinic_id}&agent_id=${dataAgent}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = "";
                    if (data.length > 0) {
                        let total = 0;
                        data.forEach((item, index) => {
                            total += parseFloat(item.tagihan || 0);
                            tbody.innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.no_transaksi}</td>
                                    <td>${item.tgl_transaksi}</td>
                                    <td>${item.nama}</td>
                                    <td>${item.no_ktp}</td>
                                    <td>${item.perusahaan}</td>
                                    <td>${item.agent}</td>
                                    <td>${formatRupiah(item.tagihan)}</td>
                                </tr>`;
                        });

                        tbody.innerHTML += `
                                <tr class="fw-bold table-light">
                                    <td colspan="7" class="text-end">TOTAL</td>
                                    <td>${formatRupiah(total)}</td>
                                </tr>`;

                        btnSimpan.disabled = false;
                    } else {
                        tbody.innerHTML = `
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ditemukan</td>
                                </tr>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center text-danger">
                                    Terjadi kesalahan mengambil data
                                </td>
                            </tr>`;
                });
        }

        // event binding (cukup sekali)
        document.getElementById('tgl_mulai').addEventListener('change', loadPatients);
        document.getElementById('tgl_sampai').addEventListener('change', loadPatients);

        $('#customerSelect').on('change', function() {
            $('#agent_id').val("").trigger("change");
            loadPatients();
        });
        $('#klinikSelect').on('change', function() {
            $('#agent_id').val("").trigger("change");
            loadPatients();
        });
        $('#agent_id').on('change', loadPatients);
    });

</script>
@endpush
