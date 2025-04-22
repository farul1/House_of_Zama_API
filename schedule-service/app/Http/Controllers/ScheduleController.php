<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Schedule::all();
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
        $order_id = $request->input('order_id');
        $client_id = $request->input('client_id');
        $fotografer = $request->input('fotografer');
        $tempat = $request->input('tempat');
        $waktu = $request->input('waktu');

        $schedule = Schedule::create([
            'order_id' => $order_id,
            'client_id' => $client_id,
            'fotografer' => $fotografer,
            'tempat' => $tempat,
            'waktu' => $waktu,
        ]);

        return response()->json($schedule, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Schedule::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
