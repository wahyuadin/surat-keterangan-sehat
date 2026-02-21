<div class="modal fade" id="modalDelete" tabindex="-1"
    aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-danger mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold">Apakah Anda Yakin?</h4>
                <p class="text-muted">
                    Data yang dihapus tidak dapat dikembalikan.
                    <input type="hidden" id="delete_id">
                </p>

                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <button id="btnDeleteConfirm" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
