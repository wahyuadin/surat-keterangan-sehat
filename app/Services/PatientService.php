<?php

namespace App\Services;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            $data['created_by'] = Auth::user()->id;
            Patient::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->back();
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            $data['created_by'] = Auth::user()->id;
            Patient::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->back();
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            Patient::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->back();
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function datatable()
    {
        $query = Patient::with(['clinic', 'customer'])->select('patients.*');
        return datatables()->eloquent($query)
            ->addIndexColumn()
            ->addColumn('nama_patient', fn($row) => strtoupper($row->nama_pasien ?? '-'))
            ->addColumn('nama_clinic', fn($row) => strtoupper($row->clinic->nama_klinik ?? '-'))
            ->addColumn('nama_customer', fn($row) => strtoupper($row->customer->nama_perusahaan ?? '-'))
            ->addColumn('pekerjaan', fn($row) => strtoupper($row->pekerjaan ?? '-'))
            ->addColumn('no_ktp', fn($row) => strtoupper($row->no_ktp ?? '-'))
            ->addColumn('lahir', fn($row) => strtoupper($row->tgl_lahir . ', ' . $row->tempat_lahir ?? '-'))
            ->addColumn('jenis_kelamin', fn($row) => strtoupper($row->jenis_kelamin ?? '-'))
            ->addColumn('telp', fn($row) => strtoupper($row->telp ?? '-'))
            ->addColumn('alamat', fn($row) => strtoupper($row->alamat ?? '-'))
            ->addColumn('updated_by', function ($row) {
                $userName = '-';
                if ($row->created_by) {
                    $user = \App\Models\User::where('id', $row->created_by)->first();
                    if ($user) {
                        $userName = strtoupper($user->nama);
                    }
                }
                return $userName;
            })
            ->addColumn('updated_at', fn($row) => $row->updated_at->format('Y-m-d H:i'))
            ->addColumn('action', function ($row) {
                return '
                 <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>
                 </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
