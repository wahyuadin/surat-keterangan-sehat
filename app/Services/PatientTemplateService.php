<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\PatientTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientTemplateService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            $data['created_by'] = auth()->user()->nama;
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $slug = '';
            for ($i = 0; $i < 50; $i++) {
                $slug .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $data['slug'] = $slug;
            PatientTemplate::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');
            return redirect()->route('patient-template.index');
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
            $data['created_by'] = Auth::user()->nama;
            PatientTemplate::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');
            return redirect()->route('patient-template.index');
        } catch (\Throwable $th) {
            dd($th);
            toastify()->error('Error, ' . $th);
            DB::rollback();
            return redirect()->back();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            PatientTemplate::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->route('patient-template.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }
}
