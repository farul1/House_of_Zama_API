<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Resources\ScheduleResource;
use Illuminate\Support\Facades\Http;


class ScheduleController extends Controller
{

public function index()
{
    try {
        $schedules = Schedule::all();

        foreach ($schedules as $schedule) {
            $clientResponse = Http::get("http://localhost:9001/api/client-service/clients/{$schedule->client_id}");

            if ($clientResponse->successful()) {
                $schedule->client_data = $clientResponse->json();
            } else {
                $schedule->client_data = null;
            }
        }

        if ($schedules->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No schedules found',
                'data' => []
            ], 200);
        }

        return ScheduleResource::collection($schedules);
    } catch (\Exception $e) {
        \Log::error("Error fetching schedules in " . __METHOD__ . ": " . $e->getMessage(), [
            'stack' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching schedules',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'   => 'required|integer',
            'client_id'  => 'required|integer',
            'fotografer' => 'required|string|max:255',
            'tempat'     => 'required|string|max:255',
            'waktu'      => 'required|date',
        ]);

        try {
            $schedule = Schedule::create($validated);
            return new ScheduleResource($schedule, 'Success', 'Schedule created successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while creating schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'order_id'   => 'required|integer',
            'client_id'  => 'required|integer',
            'fotografer' => 'required|string|max:255',
            'tempat'     => 'required|string|max:255',
            'waktu'      => 'required|date',
        ]);

        try {
            $schedule = Schedule::find($id);

            if (!$schedule) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Schedule not found',
                ], 404);
            }

            $schedule->update($validated);

            return new ScheduleResource($schedule, 'Success', 'Schedule updated successfully');

        } catch (\Exception $e) {
            \Log::error("Error updating schedule: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while updating the schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
{
    try {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Schedule not found',
            ], 404);
        }

        $clientResponse = Http::get("http://localhost:9001/api/client-service/clients/{$schedule->client_id}");

        if ($clientResponse->successful()) {
            $schedule->client_data = $clientResponse->json();
        } else {
            $schedule->client_data = null;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Schedule found',
            'data' => $schedule,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while fetching the schedule',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            \Log::info("Attempting to delete schedule with ID: {$id}");
            $schedule = Schedule::find($id);

            if (!$schedule) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Schedule not found',
                ], 404);
            }

            $schedule->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Schedule deleted successfully',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            \Log::error("Error deleting schedule: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while deleting the schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
