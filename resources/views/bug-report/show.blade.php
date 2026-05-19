@extends('template.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Kolom Kiri: Detail Tiket & Ubah Status -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h6 class="mb-0" style="color: white"><b>Detail Diskusi #{{ $bugReport->id }}</b></h6>
                </div>
                <div class="card-body mt-4">
                    <p class="mb-1 text-muted">Pelapor:</p>
                    <p class="fw-bold">{{ $bugReport->pelapor ?? 'Unknown' }}</p>

                    <p class="mb-1 text-muted">Tanggal Dibuat:</p>
                    <p class="fw-bold">{{ $bugReport->created_at->format('d M Y H:i') }}</p>

                    <p class="mb-1 text-muted">Status Saat Ini:</p>
                    <p>
                        @if($bugReport->status == 'open') <span class="badge bg-secondary">Open</span>
                        @elseif($bugReport->status == 'in_progress') <span class="badge bg-primary">In Progress</span>
                        @elseif($bugReport->status == 'resolved') <span class="badge bg-success">Resolved</span>
                        @elseif($bugReport->status == 'closed') <span class="badge bg-dark">Closed</span>
                        @endif
                    </p>

                    <hr>
                    <p class="mb-1 text-muted">Deskripsi Masalah:</p>
                    <p class="bg-light p-2 border rounded">{{ $bugReport->deskripsi }}</p>

                    @if($bugReport->foto)
                    <p class="mb-1 text-muted">Screenshot:</p>
                    <a href="{{ asset('storage/' . $bugReport->foto) }}" target="_blank">
                        <img src="{{ asset('storage/' . $bugReport->foto) }}" alt="Screenshot" class="img-fluid rounded border">
                    </a>
                    @endif

                    <!-- Form Ubah Status (Hanya untuk Admin/Role 2) -->
                    @if (Auth::user()->role == '2')
                    <hr>
                    <form action="{{ route('bug-report.update-status', $bugReport->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label for="status" class="form-label text-muted">Ubah Status Tiket:</label>
                        <div class="input-group">
                            <select name="status" class="form-select" required>
                                <option value="open" {{ $bugReport->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $bugReport->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $bugReport->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $bugReport->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>

       <!-- Kolom Kanan: History Percakapan (Chat) -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Diskusi Tiket</h6>
                    <a href="{{ route('bug-report.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
                </div>

                <!-- Chat Box -->
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-box">
                    @forelse ($bugReport->replies as $reply)
                        <div class="d-flex mb-3 {{ $reply->user_id == Auth::id() ? 'justify-content-end' : '' }}">
                            <div class="p-3 rounded border {{ $reply->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 75%;">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px; opacity: 0.8;">
                                    <span class="fw-bold">{{ $reply->user->nama ?? 'User' }}</span>
                                    <span class="ms-3">{{ $reply->created_at->format('d M H:i') }}</span>
                                </div>
                                <div>
                                    {!! nl2br(e($reply->pesan)) !!}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="no-chat-msg" class="text-center text-muted mt-5">
                            <i class='bx bx-message-square-dots fs-1'></i>
                            <p>Belum ada percakapan. Mulai diskusi di bawah.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Form Balas Pesan -->
                <div class="card-footer bg-light">
                    @if($bugReport->status == 'closed' || $bugReport->status == 'resolved')
                        <div class="alert alert-secondary mb-0 text-center">
                            Tiket ini telah ditutup. Anda tidak dapat membalas pesan.
                        </div>
                    @else
                        <!-- Tambahkan id="chat-form" pada form -->
                        <form id="chat-form" action="{{ route('bug-report.reply', $bugReport->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <textarea name="pesan" id="pesan-input" class="form-control" rows="2" placeholder="Ketik balasan Anda di sini..." required></textarea>
                                <button type="submit" id="btn-send" class="btn btn-primary px-4">Kirim <i class='bx bx-send'></i></button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const bugReportId = {{ $bugReport->id }};
    const currentUserId = {{ Auth::id() }};
    const chatBox = document.getElementById("chat-box");
    const chatForm = document.getElementById("chat-form");
    const pesanInput = document.getElementById("pesan-input");
    const btnSend = document.getElementById("btn-send");
    const noChatMsg = document.getElementById("no-chat-msg");

    // Simpan ID pesan terakhir yang diload
    let lastReplyId = {{ $bugReport->replies->last()->id ?? 0 }};

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Auto scroll ke bawah saat halaman pertama kali dibuka
    scrollToBottom();

    // Fungsi untuk menarik pesan baru dari server
    function fetchNewMessages() {
        fetch(`/report/bug-report/${bugReportId}/replies?last_id=${lastReplyId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                if(noChatMsg) noChatMsg.style.display = 'none'; // Sembunyikan text "belum ada pesan"

                data.forEach(reply => {
                    let isMe = reply.user_id === currentUserId;
                    let alignClass = isMe ? 'justify-content-end' : '';
                    let bgClass = isMe ? 'bg-primary text-white' : 'bg-light text-dark';

                    let chatHTML = `
                        <div class="d-flex mb-3 ${alignClass}">
                            <div class="p-3 rounded border ${bgClass}" style="max-width: 75%;">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px; opacity: 0.8;">
                                    <span class="fw-bold">${reply.nama}</span>
                                    <span class="ms-3">${reply.waktu}</span>
                                </div>
                                <div>${reply.pesan}</div>
                            </div>
                        </div>
                    `;

                    // Masukkan ke chatbox
                    chatBox.insertAdjacentHTML('beforeend', chatHTML);
                    lastReplyId = reply.id; // Update lastReplyId
                });

                scrollToBottom();
            }
        })
        .catch(error => console.error('Error fetching replies:', error));
    }

    // Jalankan pengecekan pesan baru setiap 3 detik (Polling)
    setInterval(fetchNewMessages, 3000);

    // Fungsi Mengirim Pesan menggunakan AJAX (Tanpa Reload)
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Ubah tombol jadi loading
            btnSend.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            btnSend.disabled = true;

            let formData = new FormData(chatForm);

            fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Memberitahu Laravel ini AJAX
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    pesanInput.value = ''; // Kosongkan input
                    fetchNewMessages(); // Langsung ambil pesan barunya
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Gagal mengirim pesan.");
            })
            .finally(() => {
                // Kembalikan tombol seperti semula
                btnSend.innerHTML = 'Kirim <i class="bx bx-send"></i>';
                btnSend.disabled = false;
            });
        });

        // Enter untuk mengirim pesan langsung (Opsional)
        pesanInput.addEventListener("keypress", function(e) {
            if(e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event("submit"));
            }
        });
    }
</script>
@endpush
@endsection
