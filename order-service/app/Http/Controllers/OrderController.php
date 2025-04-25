<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::query();

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $orders = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List of orders',
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|integer',
            'service_id' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        Log::info('Request data: ', $validated);

        try {
            $clientResponse = Http::get("http://localhost:8001/api/client-service/clients/{$validated['client_id']}");
            Log::info('ClientService response: ', [$clientResponse->status(), $clientResponse->body()]);

            if ($clientResponse->failed()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Client not found in ClientService'
                ], 404);
            }

            $serviceResponse = Http::get("http://localhost:8002/api/service-catalog/{$validated['service_id']}");
            Log::info('ServiceCatalogService response: ', [$serviceResponse->status(), $serviceResponse->body()]);

            if ($serviceResponse->failed()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Service not found in ServiceCatalogService'
                ], 404);
            }

            $order = Order::create($validated);
            Log::info('Order created: ', [$order]);

            return response()->json([
                'status' => 'success',
                'message' => 'Order created successfully',
                'data' => new OrderResource($order)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating order: ', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while creating order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|integer',
            'service_id' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        try {
            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Order not found',
                    'data' => null,
                ], 404);
            }

            Log::info('Before update:', $order->toArray());

            $order->update($validated);
            $order->refresh();

            Log::info('After update:', $order->toArray());

            return response()->json([
                'status' => 'success',
                'message' => 'Order updated successfully',
                'data' => new OrderResource($order),
            ]);

        } catch (\Exception $e) {
            Log::error('Update failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while updating order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
{
    $order = Order::find($id);

    if (!$order) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Order not found',
            'data' => null,
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Order found',
        'data' => new OrderResource($order),
    ]);
}


    public function destroy($id): JsonResponse
    {
        try {
            $order = Order::find($id);
            if (!$order) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Order not found',
                    'data' => null,
                ], 404);
            }
            $order->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Order deleted successfully',
                'data' => new OrderResource($order),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while deleting order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
