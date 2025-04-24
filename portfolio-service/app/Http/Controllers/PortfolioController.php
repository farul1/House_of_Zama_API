<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\PortfolioResource;

class PortfolioController extends Controller
{
    public function index()
    {
        $data = Portfolio::with('photography')->get();
        return new PortfolioResource($data, 'Success', 'List of all portfolio items');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photography_id' => 'required|exists:photographies,id',
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
        ]);

        try {
            $client = new Client();
            $photographyResponse = $client->get('http://localhost:8005/api/photographies/' . $validated['photography_id']);

            if ($photographyResponse->getStatusCode() != 200) {
                return new PortfolioResource(null, 'Failed', 'Photography not found');
            }

            $portfolio = Portfolio::create($validated);
            return new PortfolioResource($portfolio, 'Success', 'Portfolio created successfully');

        } catch (\Exception $e) {
            return new PortfolioResource(null, 'Failed', 'Something went wrong, please try again later.');
        }
    }

    public function show($id)
    {
        $portfolio = Portfolio::with('photography')->find($id);
        if (!$portfolio) {
            return new PortfolioResource(null, 'Failed', 'Portfolio not found');
        }

        return new PortfolioResource($portfolio, 'Success', 'Portfolio found');
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $portfolio->update($validated);
        return new PortfolioResource($portfolio, 'Success', 'Portfolio updated successfully');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();
        return new PortfolioResource(null, 'Success', 'Portfolio deleted successfully');
    }
}
