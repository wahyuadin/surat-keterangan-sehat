<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\BugReportReply;
use App\Services\BugReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BugReportController extends Controller
{
    protected $bug;

    public function __construct(BugReportService $bug)
    {
        $this->bug = $bug;
    }

    public function index()
    {
        if (Auth::user()->role != 2) {
            return view('bug-report.index', ['data' => BugReport::showData(Auth::user()->id)]);
        }
        return view('bug-report.index', ['data' => BugReport::showData()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required',
            'foto' => 'nullable|image|max:1024',
        ], [
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran file maksimal 1MB.',
        ]);
        return $this->bug->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bugReport = BugReport::with(['replies.user', 'user'])->findOrFail($id);
        return view('bug-report.show', compact('bugReport'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate(['pesan' => 'required']);
        return $this->bug->tambahKomentar($request, $id);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:open,in_progress,resolved,closed']);
        return $this->bug->ubahStatus($request->status, $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->bug->hapus($id);
    }

    public function getReplies(Request $request, $id)
    {
        return $this->bug->ambilKomentar($request, $id);
    }
}
