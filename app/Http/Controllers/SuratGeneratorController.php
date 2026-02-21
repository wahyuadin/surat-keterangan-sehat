<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Services\SuratGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SuratGeneratorController extends Controller
{
    protected $suratGenerator;

    public function __construct(SuratGeneratorService $suratGenerator)
    {
        $this->suratGenerator = $suratGenerator;
    }

    public function index()
    {
        return view('surat-generator.index');
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
            'patient_id' => [
                'required',
                Rule::unique('transaksis', 'patient_id')
                    ->where(
                        fn($query) =>
                        $query->whereDate('created_at', Carbon::today())
                            ->whereNull('deleted_at')
                    ),
            ],
        ], [
            'patient_id.unique' => 'Pasien sudah diinput hari ini, silakan cek kembali atau coba besok.'
        ]);

        return $this->suratGenerator->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->suratGenerator->pdf($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('surat-generator.modal.edit', ['surat' => Transaksi::where('id', $id)->with('patient.clinic', 'patient.customer', 'paramedis', 'agent')->first()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->suratGenerator->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->suratGenerator->hapus($id);
    }

    public function generateNoTransaksi($patient_id)
    {
        return $this->suratGenerator->generateNoTransaksi($patient_id);
    }

    public function resultData(Request $request)
    {
        return $this->suratGenerator->resultData($request);
    }

    public function suratBlangkoPdf()
    {
        return $this->suratGenerator->suratBlangkoPdf();
    }

    public function suratBlangkoDocx()
    {
        return $this->suratGenerator->suratBlangkoDocx();
    }
}
