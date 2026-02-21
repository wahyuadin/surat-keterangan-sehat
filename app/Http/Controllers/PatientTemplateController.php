<?php

namespace App\Http\Controllers;

use App\Models\PatientTemplate;
use App\Services\PatientTemplateService;
use Illuminate\Http\Request;

class PatientTemplateController extends Controller
{

    protected $patientTemplate;

    public function __construct(PatientTemplateService $patientTemplate)
    {
        $this->patientTemplate = $patientTemplate;
    }

    public function index()
    {
        return view('patient-template.index', ['data' => PatientTemplate::showData()]);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        return $this->patientTemplate->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        return $this->patientTemplate->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->patientTemplate->hapus($id);
    }
}
