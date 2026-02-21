@foreach ($data as $dataEdit)
<div class="modal fade" id="showTagihan{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="showTagihanLabel{{ $dataEdit->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem; max-height: 90vh;">
            <form action="" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-light border-0" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <div>
                        <h5 class="modal-title fw-bold" id="showTagihanLabel{{ $dataEdit->id }}">
                            Detail Tagihan
                        </h5>
                        <small class="text-muted">{{ $dataEdit->nomor_tagihan }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- ✅ Tambahkan max-height dan overflow untuk scroll -->
                <div class="modal-body p-4" style="max-height: calc(80vh - 100px); overflow-y: auto;">
                    <!-- Bagian Ringkasan Tagihan -->
                    <div class="card mb-4 border-light-subtle" style="border-radius: 0.75rem;">
                        <div class="card-body p-4">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-4">
                                    <small class="text-muted text-uppercase d-block mb-1">Periode</small>
                                    <h6 class="fw-semibold mb-0">
                                        <i class="bi bi-calendar-range me-2"></i>
                                        {{ \Carbon\Carbon::parse($dataEdit->dari)->isoFormat('D MMM Y') }}
                                        &mdash;
                                        {{ \Carbon\Carbon::parse($dataEdit->sampai)->isoFormat('D MMM Y') }}
                                    </h6>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted text-uppercase d-block mb-1">Kuantitas</small>
                                    <h6 class="fw-semibold mb-0">{{ $dataEdit->qty }} Pasien</h6>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted text-uppercase d-block mb-1">Harga Satuan</small>
                                    <h6 class="fw-semibold mb-0">Rp {{ number_format($dataEdit->satuan, 0, ',', '.') }}</h6>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted text-uppercase d-block mb-1">Status Transaksi</small>
                                    <h6 class="fw-semibold mb-0">
                                        @if ($dataEdit->status_tagihan)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Lunas</span>
                                        @else
                                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                        @endif
                                    </h6>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    <small class="text-muted text-uppercase d-block mb-1">Total Tagihan</small>
                                    <h5 class="fw-bolder text-primary mb-0">
                                        Rp {{ number_format($dataEdit->qty * $dataEdit->satuan, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Daftar Pasien -->
                    <h5 class="mb-3 fw-bold"><i class="bi bi-people-fill me-2"></i> Daftar Pasien</h5>
                    <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="exportTableToExcel('{{ $dataEdit->id }}')">
                        <i class="fas fa-file-excel me-2"></i>Download
                    </button>

                    <!-- ✅ Pastikan tabel punya batas tinggi dan bisa di-scroll -->
                    <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                        <table class="table table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px;">NO</th>
                                    <th scope="col">Nama Pasien</th>
                                    <th scope="col">No. KTP</th>
                                    <th scope="col">No. Transaksi</th>
                                    <th scope="col">Tanggal Transaksi</th>
                                    <th scope="col">Perusahaan</th>
                                    <th scope="col">Agent</th>
                                    <th scope="col" class="text-end">Tagihan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataEdit->pasien as $i => $pasien)
                                <?php $totalPasienTagihan = (int) ($dataEdit->satuan != 0 ? $pasien['tagihan'] : 0); ?>
                                <tr>
                                    <td class="text-center fw-semibold text-muted">{{ $i + 1 }}</td>
                                    <td>{{ Str::upper($pasien['nama']) }}</td>
                                    <td class="text-muted">{{ $pasien['no_ktp'] }}</td>
                                    <td class="text-muted">{{ $pasien['no_transaksi'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pasien['tgl_transaksi'])->isoFormat('D MMM Y') }}</td>
                                    <td>{{ Str::upper($pasien['perusahaan']) }}</td>
                                    <td>{{ Str::upper($pasien['agent'] ?? '-') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($totalPasienTagihan, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="7" class="text-end fw-bold">Total</th>
                                    <th class="text-end fw-bolder">
                                        Rp {{ number_format($dataEdit->qty * $dataEdit->satuan, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0" style="border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end w-100">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Tutup
                        </button>
                        <a href="{{ route('tagihan.show' , $dataEdit->id) }}" target="_blank" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i>Download Invoice
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(id) {
        // 🔍 Cari tabel berdasarkan ID modal
        const table = document.querySelector(`#showTagihan${id} table`);
        if (!table) {
            alert('Tabel tidak ditemukan!');
            return;
        }

        // 🧾 Ambil nomor tagihan untuk nama file
        const invoiceNumberElement = document.querySelector(`#showTagihan${id} small.text-muted`);
        const invoiceNumber = invoiceNumberElement ? invoiceNumberElement.innerText.trim() : 'Tagihan';

        // 📘 Buat workbook dan worksheet dari tabel HTML
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.table_to_sheet(table);
        XLSX.utils.book_append_sheet(wb, ws, 'Daftar Pasien');

        // 📏 Hitung auto-width setiap kolom
        if (ws['!ref']) {
            const range = XLSX.utils.decode_range(ws['!ref']);
            const colWidths = [];

            for (let C = range.s.c; C <= range.e.c; ++C) {
                let maxWidth = 10; // minimal lebar kolom

                for (let R = range.s.r; R <= range.e.r; ++R) {
                    const cellAddress = XLSX.utils.encode_cell({
                        r: R
                        , c: C
                    });
                    const cell = ws[cellAddress];

                    // Pastikan semua cell dikonversi ke text (type "s")
                    if (cell && cell.v != null) {
                        cell.t = 's'; // ✅ format teks
                        const cellValue = cell.v.toString();
                        maxWidth = Math.max(maxWidth, cellValue.length + 2);
                    }
                }

                colWidths.push({
                    wch: maxWidth
                });
            }

            ws['!cols'] = colWidths;
        }

        // 💾 Simpan file Excel (otomatis .xlsx)
        XLSX.writeFile(wb, `${invoiceNumber}.xlsx`);
    }

</script>
@endpush
