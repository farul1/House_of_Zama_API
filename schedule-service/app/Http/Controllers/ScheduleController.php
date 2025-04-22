<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Resources\ScheduleResource;


class ScheduleController extends Controller
{
    public function index()
{
    $schedules = Schedule::all();
    return new ScheduleResource($schedules, 'Success', 'List of all schedules');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'order_id'   => 'required|string|max:255',
        'client_id'  => 'required|string|max:255',
        'fotografer' => 'required|string|max:255',
        'tempat'     => 'required|string|max:255',
        'waktu'      => 'required|date',
    ]);

    $schedule = Schedule::create($validated);
    return new ScheduleResource($schedule, 'Success', 'Schedule created successfully');
}

public function show($id)
{
    $schedule = Schedule::find($id);
    if (!$schedule) {
        return new ScheduleResource(null, 'Failed', 'Schedule not found');
    }

    return new ScheduleResource($schedule, 'Success', 'Schedule found');
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'order_id'   => 'required|string|max:255',
        'client_id'  => 'required|string|max:255',
        'fotografer' => 'required|string|max:255',
        'tempat'     => 'required|string|max:255',
        'waktu'      => 'required|date',
    ]);

    $schedule = Schedule::findOrFail($id);
    $schedule->update($validated);

    return new ScheduleResource($schedule, 'Success', 'Schedule updated successfully');
}

public function destroy($id)
{
    $schedule = Schedule::findOrFail($id);
    $schedule->delete();

    return new ScheduleResource(null, 'Success', 'Schedule deleted successfully');
}

}
