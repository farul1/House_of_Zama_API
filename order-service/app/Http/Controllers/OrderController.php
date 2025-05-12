<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendOrderConfirmationEmail;

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
        'client_id'   => 'required|integer',
        'service_id'  => 'required|string',
        'catatan'     => 'nullable|string',
    ]);

    Log::info('Incoming order request:', $validated);

    try {
        // Ambil data client
        $clientUrl = rtrim(env('CLIENT_SERVICE_URL'), '/') . "/api/client-service/clients/{$validated['client_id']}";
        $clientResponse = Http::get($clientUrl);

        if ($clientResponse->failed()) {
            Log::warning('Client not found', ['client_id' => $validated['client_id']]);
            return response()->json([
                'status'  => 'failed',
                'message' => 'Client not found in ClientService'
            ], 404);
        }

        // Ambil data service
        $serviceUrl = rtrim(env('SERVICE_CATALOG_URL'), '/') . "/api/service-catalog/{$validated['service_id']}";
        $serviceResponse = Http::get($serviceUrl);

        if ($serviceResponse->failed()) {
            Log::warning('Service not found', ['service_id' => $validated['service_id']]);
            return response()->json([
                'status'  => 'failed',
                'message' => 'Service not found in ServiceCatalogService'
            ], 404);
        }

        // Simpan order
        $order = Order::create($validated);
        Log::info('Order created successfully', ['order_id' => $order->id]);

        // Lampirkan data client
        $clientData = $clientResponse->json()['data'];
        $order->client = (object) $clientData;

        // Kirim email jika ada email client
        if (!empty($clientData['email'])) {
            dispatch(new SendOrderConfirmationEmail($order, $clientData['email']));
            Log::info("Email job dispatched to: {$clientData['email']}");
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Order created successfully',
            'data'    => new OrderResource($order),
        ], 201);

    } catch (\Throwable $e) {
        Log::error('Unexpected error occurred:', ['error' => $e->getMessage()]);
        return response()->json([
            'status'  => 'error',
            'message' => 'Unexpected error occurred while creating order',
            'error'   => $e->getMessage(),
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
