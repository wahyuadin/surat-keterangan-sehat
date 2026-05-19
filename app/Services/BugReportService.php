<?php

namespace App\Services;

use App\Models\BugReport;
use App\Models\BugReportReply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BugReportService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $user = Auth::user();
            $data['pelapor'] = $user->nama ?? 'unknown';
            $data['user_id'] = $user->id ?? null;
            $data['status'] = 'open'; // Default tiket baru adalah open

            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('foto', 'public');
            }

            BugReport::create($data); // Gunakan standard eloquent

            DB::commit();
            toastify()->success('Tiket berhasil dibuat.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            DB::rollback(); // Rollback HARUS sebelum return
            // Log::error('Gagal tambah tiket: ' . $th->getMessage());
            toastify()->error('Terjadi kesalahan sistem.');
            return redirect()->back();
        }
    }

    public function tambahKomentar($request, $id)
    {
        DB::beginTransaction();
        try {
            $reply = BugReportReply::create([
                'bug_report_id' => $id,
                'user_id' => Auth::id(),
                'pesan' => $request->pesan,
            ]);

            DB::commit();

            // Jika dikirim via AJAX, berikan balasan JSON
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            toastify()->success('Pesan terkirim.');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim pesan'], 500);
            }

            toastify()->error('Gagal mengirim pesan.');
            return redirect()->back();
        }
    }

    public function ubahStatus($statusBaru, $id)
    {
        DB::beginTransaction();
        try {
            BugReport::editData($id, ['status' => $statusBaru]);
            DB::commit();
            toastify()->success("Status tiket diubah menjadi " . strtoupper($statusBaru));
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            toastify()->error('Gagal mengubah status.' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $user = Auth::user();
            $data['pelapor'] = $user->nama ?? 'unknown';
            $data['user_id'] = $user->id ?? null;
            BugReport::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            DB::rollback();
            return redirect()->back();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            BugReport::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->route('bug-report.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            DB::rollback();
            return redirect()->back();
        }
    }

    public function ambilKomentar($request, $id)
    {
        $query = BugReportReply::showDataByReportId($id);
        if ($request->has('last_id') && $request->last_id > 0) {
            $query->where('id', '>', $request->last_id);
        }

        $replies = $query->get()->map(function ($reply) {
            return [
                'id' => $reply->id,
                'user_id' => $reply->user_id,
                'nama' => $reply->user->nama ?? 'Unknown',
                'pesan' => nl2br(e($reply->pesan)),
                'waktu' => $reply->created_at->format('d M H:i')
            ];
        });

        return response()->json($replies);
    }
}
