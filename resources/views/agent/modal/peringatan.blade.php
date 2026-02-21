<!-- Modal Peringatan Modern -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <!-- Header -->
      <div class="modal-header bg-primary text-dark rounded-top-2 border-0">
        <h5 class="modal-title fw-bold mb-3" id="alertModalLabel" style="color: white;">⚠️ Peringatan</h5>
        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
      </div>

      <!-- Body -->
    <div class="modal-body text-center py-2">
        <h3 class="mb-0 p-3 fs-4" style="line-height: 1.5;">
            Pastikan sebelum memasukkan data <b>Agent</b>, lakukan pengecekan terlebih dahulu apakah data tersebut sudah ada atau belum dengan menggunakan tombol pencarian. Duplikasi data bisa menimbulkan masalah pada data.
        </h3>
    </div>

      <!-- Footer -->
    <div class="modal-footer justify-content-center border-0 pb-4 gap-3">
      <button type="button" class="btn btn-outline-warning btn-lg px-4 rounded-pill d-flex align-items-center gap-2" data-bs-dismiss="modal">
        <i class="bi bi-check-circle-fill"></i>
        Mengerti
      </button>
    </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        alertModal.show(); // muncul otomatis saat halaman load
    });
</script>
@endpush
