<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\PerusahaanService;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    protected $perusahaan;

    public function __construct(PerusahaanService $perusahaan)
    {
        $this->perusahaan = $perusahaan;
    }

    public function index()
    {
        return view('perusahaan.index', ['data' => Customer::showData()]);
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
        return $this->perusahaan->tambah($request);
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
        return $this->perusahaan->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->perusahaan->hapus($id);
    }
}
