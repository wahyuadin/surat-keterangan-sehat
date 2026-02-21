<?php

namespace App\Services;

use App\Models\Paramedis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ParamedisService
{
    public function tambah($request)
    {
        $data = $request->except('_method', '_token');
        $data['created_by'] = Auth::user()->id;
        if ($request->hasFile('ttd')) {
            $file = $request->file('ttd');
            $path = $file->store('ttd', 'public');
            $data['ttd'] = $path;
        }
        DB::beginTransaction();
        try {
            Paramedis::tambahData($data);
            toastify()->success('Data Berhasil Ditambahkan.');
            DB::commit();
            return redirect()->route('paramedis.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function edit($id, $request)
    {
        $data = $request->except('_method', '_token');
        $data['created_by'] = Auth::user()->id;
        if ($request->hasFile('ttd')) {
            $file = $request->file('ttd');
            $path = $file->store('ttd', 'public');
            $data['ttd'] = $path;
        }
        DB::beginTransaction();
        try {
            Paramedis::editData($id, $data);
            toastify()->success('Data Berhasil diedit.');
            DB::commit();
            return redirect()->route('paramedis.index');
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
            Paramedis::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();
            return redirect()->route('paramedis.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
