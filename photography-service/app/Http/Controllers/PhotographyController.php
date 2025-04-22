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
    return new PhotographyResource($data, 'Success', 'List of all photography data');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'schedule_id' => 'required|exists:schedules,id',
        'foto' => 'required|string',
        'status' => 'required|string',
    ]);

    try {
        $client = new \GuzzleHttp\Client();
        $scheduleResponse = $client->get('http://localhost:8004/api/schedules/' . $validated['schedule_id']);

        if ($scheduleResponse->getStatusCode() != 200) {
            return new PhotographyResource(null, 'Failed', 'Schedule not found');
        }

        $photography = Photography::create($validated);
        return new PhotographyResource($photography, 'Success', 'Photography created successfully');

    } catch (RequestException $e) {
        return new PhotographyResource(null, 'Failed', 'Failed to connect to the schedule service.');
    } catch (\Exception $e) {
        return new PhotographyResource(null, 'Failed', 'Something went wrong, please try again later.');
    }
}

public function show($id)
{
    $photography = Photography::find($id);
    if ($photography) {
        return new PhotographyResource($photography, 'Success', 'Photography found');
    } else {
        return new PhotographyResource(null, 'Failed', 'Photography not found');
    }
}

public function update(Request $request, Photography $photography)
{
    $validated = $request->validate([
        'foto' => 'nullable|string',
        'status' => 'nullable|string',
    ]);

    $photography->update($validated);
    return new PhotographyResource($photography, 'Success', 'Photography updated successfully');
}

public function destroy(Photography $photography)
    {
        $photography->delete();
        return new PhotographyResource(null, 'Success', 'Photography deleted successfully');
    }

}
