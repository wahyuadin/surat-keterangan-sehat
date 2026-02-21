<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerusahaanService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            $data['tarif'] = str_replace(',', '', $data['tarif']);
            $data['created_by'] = Auth::user()->id;
            Customer::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->route('perusahaan.index');
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
            $data['tarif'] = str_replace(',', '', $data['tarif']);
            Customer::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('perusahaan.index');
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
            Customer::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->route('perusahaan.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
