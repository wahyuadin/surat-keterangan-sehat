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
            'no_ktp' => 'No KTP sudah terdaftar di klinik ini.',
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
                    ->whereNull('deleted_at'),
            ],
        ], [
            'no_ktp.unique' => 'No KTP sudah terdaftar di klinik ini.',
        ]);

        return $this->patient->edit($id, $request);
    }

    public function destroy(string $id)
    {
        return $this->patient->hapus($id);
    }

    public function datatable()
    {
        return $this->patient->datatable();
    }

    public function downloadTemplate()
    {
        return $this->patient->generateTemplate();
    }

    public function previewImport(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $file = $request->file('file_excel');
            $path = $file->store('temp_imports', 'public');
            $data = $this->patient->parseForPreview($file);
            $data['temp_file_path'] = $path;

            if ($data['status'] === 'error') {
                return response()->json(['error' => $data['message']], 400);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal membaca file: ' . $e->getMessage()], 500);
        }
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'temp_file_path' => 'required|string',
            'mapping' => 'required|array',
        ]);

        try {
            $result = $this->patient->processMappedImport(
                $request->temp_file_path,
                $request->mapping
            );

            if ($result['status'] === 'error') {
                return response()->json(['error' => $result['message']], 400);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function downloadErrorLog($filename)
    {
        $path = storage_path('app/public/temp_errors/' . $filename);
        if (file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend(true);
        }
        return abort(404, 'File error log tidak ditemukan atau sudah kadaluarsa.');
    }
}
