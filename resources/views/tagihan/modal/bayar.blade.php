@foreach ($data as $dataBayar)
<div class="modal fade" id="bayarTagihan{{ $dataBayar->id }}" tabindex="-1" aria-labelledby="bayar{{ $dataBayar->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-question-circle-fill text-primary mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold">Konfirmasi Pembayaran</h4>
                <p class="text-muted">
                    Apakah Anda yakin ingin menandai tagihan:<br>
                    <strong class="text-dark">
                        {{ $dataBayar->nomor_tagihan ?? '' }}
                    </strong>
                    <br>sebagai lunas?
                </p>

                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    {{-- Pastikan route ini sesuai dengan controller Anda untuk memproses pembayaran --}}
                    <form action="{{ route('tagihan.update', $dataBayar->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT') {{-- Atau PUT, sesuai kebutuhan update status --}}
                        <button type="submit" class="btn btn-primary">Ya, Konfirmasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
