<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DataUserService;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    protected $dataUser;

    public function __construct(DataUserService $dataUser)
    {
        $this->dataUser = $dataUser;
    }


    public function index()
    {
        return view('user-data.index', ['data' => User::showData()]);
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
        $this->dataUser->create($request);
        return redirect()->back();
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
        $this->dataUser->update($request, $id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->dataUser->delete($id);
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $this->dataUser->bulkDelete($request);
        return redirect()->back();
    }

    public function importExcel(Request $request)
    {
        $this->dataUser->importExcel($request);
        return redirect()->back();
    }

    public function templateExcel()
    {
        return $this->dataUser->templateExcel();
    }
}
