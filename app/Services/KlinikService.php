<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KlinikService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_method', '_token');
            $data['created_by'] = Auth::user()->id;
            Clinic::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->route('klinik.index');
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
            $data = $request->except('_method', '_token');
            $data['created_by'] = Auth::user()->id;
            Clinic::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('klinik.index');
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
            Clinic::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->route('klinik.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
