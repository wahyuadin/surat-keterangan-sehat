<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Services\AgentService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    protected $agent;

    public function __construct(AgentService $agent)
    {
        $this->agent = $agent;
    }

    public function index()
    {
        return view('agent.index', ['data' => Agent::showData()]);
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
            'nama_agent'    => 'required|min:3|max:255',
            'customer_id'   => 'required|exists:customers,id',
        ]);

        return $this->agent->tambah($request);
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
        $this->validate($request, [
            'nama_agent'    => 'required|min:3|max:255',
            'customer_id'   => 'required|exists:customers,id',
        ]);

        return $this->agent->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->agent->hapus($id);
    }

    public function getAgent($customer_id, $clinic_id)
    {
        return $this->agent->getAgent($customer_id, $clinic_id);
    }
}
