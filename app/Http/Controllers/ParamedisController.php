<?php

namespace App\Http\Controllers;

use App\Models\Paramedis;
use App\Services\ParamedisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParamedisController extends Controller
{
    protected $paramedis;

    public function __construct(ParamedisService $paramedis)
    {
        $this->paramedis = $paramedis;
    }

    public function index()
    {
        $paramedis = Auth::user()->role == '2' ? null : Auth::user()->clinic_id;
        return view('paramedis.index', [
            'data' => Paramedis::showData($paramedis),
        ]);
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
            'nama'          => 'unique:paramedis,nama',
            'clinic_id'     => 'exists:clinics,id'
        ], [
            'nama' => 'Nama Sudah Ada.'
        ]);
        return $this->paramedis->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Paramedis::showData($id);
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
        return $this->paramedis->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->paramedis->hapus($id);
    }
}
