<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "success"   => true,
            "data"      => [
                "permition" => false,
                "massage"   => 'authorized'
            ]
        ], 404);
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
            'nama_pasien'   => 'required|string|max:255',
            'no_ktp'        => 'required|string|max:20|unique:patients,no_ktp',
            'tempat_lahir'  => 'required|string|max:255',
            'tgl_lahir'     => 'required|date',
            'jenis_kelamin' => 'required|in:0,1',
            'customer_id'   => 'required|exists:customers,id',
            'pekerjaan'     => 'required|string|max:255',
            'alamat'        => 'required|string',
            'telp'          => 'required|string|max:20',
            'slug'          => 'required|string|exists:patient_templates,slug',
        ], [
            'nama_pasien.required'   => 'Nama pasien wajib diisi.',
            'no_ktp.required'        => 'Nomor KTP wajib diisi.',
            'no_ktp.unique'          => 'Nomor KTP sudah terdaftar.',
            'tempat_lahir.required'  => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required'     => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date'         => 'Tanggal lahir tidak valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'       => 'Jenis kelamin harus 0 (Laki-laki) atau 1 (Perempuan).',
            'customer_id.required'   => 'Customer wajib dipilih.',
            'customer_id.exists'     => 'Customer tidak ditemukan.',
            'pekerjaan.required'     => 'Pekerjaan wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'telp.required'          => 'Nomor telepon wajib diisi.',
            'slug.required'          => 'Slug form wajib diisi.',
            'slug.exists'            => 'Form tidak ditemukan atau tidak aktif.',
        ]);

        $template = PatientTemplate::where('slug', $request->slug)->first();
        $data = $request->except('_method', '_token', 'slug');
        $data['clinic_id'] = $template->clinic_id;

        DB::beginTransaction();
        try {
            Patient::tambahData($data);
            DB::commit();
            return view('form-public.success', ['slug' => $request->slug]);
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = PatientTemplate::where('slug', $id)->first();

        if (!$template || $template->slug !== $id || !$template->is_active) {
            return view('form-public.false', ['data' => $template]);
        }

        return view('form-public.index', ['data' => $template]);
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
        //
    }

    public function formCheck($data)
    {
        try {
            $validator = Validator::make(
                ['no_ktp' => $data],
                ['no_ktp' => 'required|exists:patients,no_ktp'],
                [
                    'no_ktp.required' => 'Nomor KTP wajib diisi.',
                    'no_ktp.exists' => 'Nomor KTP tidak ditemukan dalam database.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => "Validasi gagal",
                    "errors"  => $validator->errors(),
                ], 422);
            }

            return response()->json([
                "success" => true,
                "message" => "Data ditemukan",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Terjadi kesalahan server",
                "error"   => $e->getMessage()
            ], 500);
        }
    }
}
