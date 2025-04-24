<?php

namespace App\Http\Controllers;

use App\Models\ServiceCatalog;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceCatalogResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class ServiceCatalogController extends Controller
{
    public function index()
    {
        $services = ServiceCatalog::all();
        return ServiceCatalogResource::collection($services)
            ->additional([
                'status'  => 'Success',
                'message' => 'List of all services'
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'    => 'required|string|max:255|unique:service_catalogs,service_id',
            'nama_layanan'  => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'harga'         => 'required|numeric',
            'kategori'      => 'required|string|max:255',
            'durasi'        => 'required|integer',
        ]);

        try {
            $service = ServiceCatalog::create($validated);
            return new ServiceCatalogResource($service);
        } catch (\Exception $e) {
            Log::error('Service creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'Failed',
                'message' => 'Service creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($service_id)
    {
        $service = ServiceCatalog::where('service_id', $service_id)->first();
        if (!$service) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Service not found'
            ], 404);
        }

        return new ServiceCatalogResource($service);
    }
    public function update(Request $request, $service_id)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'nama_layanan'  => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'harga'         => 'required|numeric',
            'kategori'      => 'required|string|max:255',
            'durasi'        => 'required|integer',
        ]);

        // Cari layanan berdasarkan service_id
        $service = ServiceCatalog::where('service_id', $service_id)->first();

        // Jika service tidak ditemukan, kembalikan pesan error
        if (!$service) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Service not found'
            ], 404);
        }

        // Update data layanan dengan data yang sudah divalidasi
        $service->update($validated);

        // Kembalikan response dengan status sukses
        return response()->json([
            'data' => new ServiceCatalogResource($service),
            'status' => 'Success',
            'message' => 'Service updated successfully'
        ], 200);
    }



    public function destroy($service_id)
    {
        $service = ServiceCatalog::where('service_id', $service_id)->first();

        if (!$service) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Service not found'
            ], 404);
        }

        $service->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Service deleted successfully'
        ], 200);
    }

    public function getClients()
    {
        try {
            $response = Http::get('http://localhost:8001/api/clients');

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            }

            Log::error('Client Service Error: ' . $response->body());
            return response()->json(['error' => 'Client service not available'], 500);
        } catch (\Exception $e) {
            Log::error('Connection to Client Service failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to connect to Client service'], 500);
        }
    }
}
