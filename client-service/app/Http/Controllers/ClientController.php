<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return new ClientResource($clients, 'Success', 'List of clients');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email|unique:clients',
            'telepon' => 'required',
            'perusahaan' => 'required',
            'lokasi' => 'required',
        ]);

        if ($validator->fails()) {
            return new ClientResource(null, 'Failed', $validator->errors());
        }

        $client = Client::create($request->all());
        return new ClientResource($client, 'Success', 'Client created successfully');
    }

    public function show($id)
    {
        $client = Client::find($id);
        if ($client) {
            return new ClientResource($client, 'Success', 'Client found');
        }
        return new ClientResource(null, 'Failed', 'Client not found');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email|unique:clients,email,' . $id,
            'telepon' => 'required',
            'perusahaan' => 'required',
            'lokasi' => 'required',
        ]);

        if ($validator->fails()) {
            return new ClientResource(null, 'Failed', $validator->errors());
        }

        $client = Client::find($id);
        if ($client) {
            $client->update($request->all());
            return new ClientResource($client, 'Success', 'Client updated successfully');
        }

        return new ClientResource(null, 'Failed', 'Client not found');
    }

    public function destroy($id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->delete();
            return new ClientResource($client, 'Success', 'Client deleted successfully');
        }

        return new ClientResource(null, 'Failed', 'Client not found');
    }

    public function getOrderHistory($clientId)
    {
        try {
            $url = config('services.order_service.url') . '/api/orders';

            $response = Http::get($url, [
                'client_id' => $clientId
            ]);

            if ($response->successful()) {
                return new ClientResource($response->json(), 'Success', 'Client order history found');
            }

            return new ClientResource(null, 'Failed', 'Failed to retrieve client order history');
        } catch (\Exception $e) {
            Log::error("Order Service Error: " . $e->getMessage());
            return new ClientResource(null, 'Failed', 'Error while contacting order service');
        }
    }

}
