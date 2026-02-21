<?php

namespace App\Http\Controllers;


use App\Models\Tagihan;
use App\Services\TagihanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    protected $tagihan;

    public function __construct(TagihanService $tagihan)
    {
        $this->tagihan = $tagihan;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Auth::user();
        if ($role->role == '0') {
            $data = Tagihan::tagihanByauditRole('customer_id', $role->customer_id);
        } elseif ($role->role == '1') {
            $data = Tagihan::tagihanByauditRole('clinic_id', $role->clinic_id);
        } else {
            $data = Tagihan::showData();
        }
        return view('tagihan.index', ['data' => $data]);
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
            'tgl_mulai'   => 'required|date',
            'tgl_sampai'  => 'required|date|after_or_equal:tgl_mulai',
            'customer_id' => 'required|exists:customers,id',
            'clinic_id'   => 'required|exists:clinics,id',
        ]);

        return $this->tagihan->tambah($request);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->tagihan->pdf($id);
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
        return $this->tagihan->bayar($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->tagihan->hapus($id);
    }

    public function getPatients(Request $request)
    {
        return $this->tagihan->getPasien($request);
    }

    public function generateNoTagihan($clinic_id)
    {
        return $this->tagihan->generateNoTagihan($clinic_id);
    }

    // public function getData(Request $request)
    // {
    //     return $this->tagihan->getData($request);
    // }
}
