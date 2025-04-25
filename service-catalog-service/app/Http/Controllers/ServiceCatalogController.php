<?php

namespace App\Http\Controllers;

use App\Models\ServiceCatalog;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceCatalogResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Exception;

class ServiceCatalogController extends Controller
{

    public function index(): JsonResponse
    {
        $services = ServiceCatalog::all();
        return response()->json([
            'status'  => 'Success',
            'message' => 'List of all services',
            'data'    => ServiceCatalogResource::collection($services),
        ]);
    }


    public function store(Request $request): JsonResponse
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
            return response()->json([
                'status'  => 'Success',
                'message' => 'Service created successfully',
                'data'    => new ServiceCatalogResource($service),
            ], 201);
        } catch (Exception $e) {
            Log::error('Service creation failed: ' . $e->getMessage());
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Service creation failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function show($service_id): JsonResponse
    {
        $service = ServiceCatalog::findOrFail($service_id);

        return response()->json([
            'status'  => 'Success',
            'message' => 'Service found',
            'data'    => new ServiceCatalogResource($service),
        ]);
    }


    public function update(Request $request, $service_id): JsonResponse
    {
        $validated = $request->validate([
            'nama_layanan'  => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'harga'         => 'required|numeric',
            'kategori'      => 'required|string|max:255',
            'durasi'        => 'required|integer',
        ]);

        try {
            $service = ServiceCatalog::findOrFail($service_id);

            $service->update($validated);

            return response()->json([
                'status'  => 'Success',
                'message' => 'Service updated successfully',
                'data'    => new ServiceCatalogResource($service),
            ], 200);
        } catch (Exception $e) {
            Log::error('Service update failed: ' . $e->getMessage());
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Service update failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($service_id): JsonResponse
    {
        try {
            $service = ServiceCatalog::findOrFail($service_id);

            $service->delete();

            return response()->json([
                'status'  => 'Success',
                'message' => 'Service deleted successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error('Service delete failed: ' . $e->getMessage());
            return response()->json([
                'status'  => 'Failed',
                'message' => 'Service delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
