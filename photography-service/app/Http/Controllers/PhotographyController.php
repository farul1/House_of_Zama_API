<?php

namespace App\Http\Controllers;

use App\Models\Photography;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Http\Resources\PhotographyResource;

class PhotographyController extends Controller
{

    public function index()
    {
        $data = Photography::all();
        return PhotographyResource::collection($data)->additional([
            'status' => 'success',
            'message' => 'List of all photography data',
        ]);
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'schedule_id' => 'required|integer',
        'foto' => 'required|string',
        'status' => 'required|string',
    ]);

    $client = new \GuzzleHttp\Client();
    $response = $client->get("http://localhost:9004/api/schedules/{$validated['schedule_id']}");

    if ($response->getStatusCode() !== 200) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Schedule not found',
        ], 404);
    }

    $photography = Photography::create($validated);
    return new PhotographyResource($photography, 'Success', 'Photography created successfully');
}
    public function show($id)
    {
        $photography = Photography::find($id);

        if ($photography) {
            return new PhotographyResource($photography, 'Success', 'Photography found');
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Photography not found',
            ], 404);
        }
    }


    public function update(Request $request, Photography $photography)
    {
        $validated = $request->validate([
            'foto' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        try {
            $photography->update($validated);
            return new PhotographyResource($photography, 'Success', 'Photography updated successfully');
        } catch (\Exception $e) {
            \Log::error("Error updating photography: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update photography.',
            ], 500);
        }
    }


    public function destroy(Photography $photography)
    {
        try {
            $photography->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Photography deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            \Log::error("Error deleting photography: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete photography.',
            ], 500);
        }
    }
}
