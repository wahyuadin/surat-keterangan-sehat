<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PasienController extends Controller
{
    protected $patient;

    public function __construct(PatientService $patient)
    {
        $this->patient = $patient;
    }

    public function index()
    {
        return view('patient.index');
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
        $this->validate($request, [
            'no_ktp' => 'required|unique:patients,no_ktp,NULL,id,clinic_id,' . $request->clinic_id,
        ], [
            'no_ktp' => 'No KTP sudah terdaftar di klinik ini.'
        ]);
        return $this->patient->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Patient::showData($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return Provider::showData($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'no_ktp' => [
                'required',
                Rule::unique('patients', 'no_ktp')
                    ->ignore($id)
                    ->where('clinic_id', $request->clinic_id)
                    ->whereNull('deleted_at')
            ],
        ], [
            'no_ktp.unique' => 'No KTP sudah terdaftar di klinik ini.',
        ]);
        return $this->patient->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->patient->hapus($id);
    }

    public function datatable()
    {
        return $this->patient->datatable();
    }
}
