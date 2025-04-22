<?php

namespace App\Http\Controllers;

use App\Models\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServiceCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceCatalog::all();
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
            'nama_layanan' => 'required',
            'deskripsi'    => 'required',
            'harga'        => 'required|numeric',
            'kategori'     => 'required',
            'durasi'       => 'required|integer',
            ]);

        return ServiceCatalog::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return ServiceCatalog::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceCatalog $serviceCatalog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $service = ServiceCatalog::findOrFail($id);
        $service->update($request->all());

        return $service;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return ServiceCatalog::destroy($id);
    }

    public function getClients()
    {
        $response = Http::get('http://localhost:8001/api/clients');
        return $response->json();
    }

}
