<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photography;
use GuzzleHttp\Client;

class PhotographyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Photography::all();
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
        $schedule_id = $request->input('schedule_id');
        $foto = $request->input('foto');
        $status = $request->input('status');

        $client = new Client();
        $scheduleResponse = $client->get('http://localhost:8003/api/schedules/' . $schedule_id);

        if ($scheduleResponse->getStatusCode() != 200) {
            return response()->json(['error' => 'Schedule not found'], 404);
        }

        $photography = Photography::create([
            'schedule_id' => $schedule_id,
            'foto' => $foto,
            'status' => $status,
        ]);

        return response()->json($photography, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Photography::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photography $photography)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photography $photography)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photography $photography)
    {
        //
    }
}
