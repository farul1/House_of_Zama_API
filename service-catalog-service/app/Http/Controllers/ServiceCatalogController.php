<?php

namespace App\Http\Controllers;

use App\Models\ServiceCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\ServiceCatalogResource;


class ServiceCatalogController extends Controller
{
    public function index()
{
    $services = ServiceCatalog::all();
    return new ServiceCatalogResource($services, 'Success', 'List of all services');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'nama_layanan' => 'required|string|max:255',
        'deskripsi'    => 'required|string',
        'harga'        => 'required|numeric',
        'kategori'     => 'required|string|max:255',
        'durasi'       => 'required|integer',
    ]);

    $service = ServiceCatalog::create($validated);
    return new ServiceCatalogResource($service, 'Success', 'Service created successfully');
}

public function show($id)
{
    $service = ServiceCatalog::find($id);
    if (!$service) {
        return new ServiceCatalogResource(null, 'Failed', 'Service not found');
    }

    return new ServiceCatalogResource($service, 'Success', 'Service found');
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama_layanan' => 'required|string|max:255',
        'deskripsi'    => 'required|string',
        'harga'        => 'required|numeric',
        'kategori'     => 'required|string|max:255',
        'durasi'       => 'required|integer',
    ]);

    $service = ServiceCatalog::findOrFail($id);
    $service->update($validated);

    return new ServiceCatalogResource($service, 'Success', 'Service updated successfully');
}

public function destroy($id)
{
    $service = ServiceCatalog::findOrFail($id);
    $service->delete();

    return new ServiceCatalogResource(null, 'Success', 'Service deleted successfully');
}

public function getClients()
{
    try {
        $response = Http::get('http://localhost:8001/api/clients');

        if ($response->successful()) {
            return response()->json($response->json(), 200);
        }

        return response()->json(['error' => 'Client service not available'], 500);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to connect to Client service'], 500);
    }
}

}
