<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Resources\PortfolioResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PortfolioController extends Controller
{
    public function index()
{
    $portfolios = Portfolio::all();

    $photographyIds = $portfolios->pluck('photography_id')->unique();
    $photographies = [];

    try {
        $response = Http::get('http://localhost:9005/api/photographies', [
            'ids' => $photographyIds->toArray()
        ]);

        if ($response->successful()) {
            $photographies = $response->json();
        }
    } catch (\Exception $e) {
        Log::error('Error fetching photographies: ' . $e->getMessage());
    }

    $portfolios = $portfolios->map(function ($portfolio) use ($photographies) {
        $portfolio->photography = collect($photographies)->firstWhere('id', $portfolio->photography_id);
        return $portfolio;
    });

    return new PortfolioResource($portfolios, 'Success', 'List of all portfolio items');
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photography_id' => 'required|integer',
            'judul' => 'required|string',
            'deskripsi' => 'required|string',
            'order_id' => 'required|integer',
        ]);

        try {
            $client = new Client();
            $photographyResponse = $client->get('http://localhost:9005/api/photographies/' . $validated['photography_id']);

            if ($photographyResponse->getStatusCode() != 200) {
                return new PortfolioResource(null, 'Failed', 'Photography not found');
            }

            $orderResponse = Http::get('http://localhost:9003/api/orders/' . $validated['order_id']);
            if ($orderResponse->failed()) {
                return new PortfolioResource(null, 'Failed', 'Order not found or invalid');
            }

            $portfolio = Portfolio::create($validated);
            return new PortfolioResource($portfolio, 'Success', 'Portfolio created successfully');

        } catch (\Exception $e) {
            Log::error('Error in PortfolioController@store: ' . $e->getMessage());
            return new PortfolioResource(null, 'Failed', 'Something went wrong, please try again later.');
        }
    }
    public function show($id)
    {
        $portfolio = Portfolio::find($id);
        if (!$portfolio) {
            return new PortfolioResource(null, 'Failed', 'Portfolio not found');
        }

        // Ambil data fotografi dari Photography Service
        $photography = null;
        try {
            $response = Http::get('http://localhost:9005/api/photographies/' . $portfolio->photography_id);
            if ($response->successful()) {
                $photography = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Error fetching photography for portfolio: ' . $e->getMessage());
        }

        // Gabungkan data fotografi ke dalam portfolio
        $portfolio->photography = $photography;

        return new PortfolioResource($portfolio, 'Success', 'Portfolio found');
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'judul' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $portfolio->update($validated);
            return new PortfolioResource($portfolio, 'Success', 'Portfolio updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating portfolio: ' . $e->getMessage());
            return new PortfolioResource(null, 'Failed', 'Something went wrong, please try again later.');
        }
    }

    public function destroy(Portfolio $portfolio)
    {
        try {
            $portfolio->delete();
            return new PortfolioResource(null, 'Success', 'Portfolio deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting portfolio: ' . $e->getMessage());
            return new PortfolioResource(null, 'Failed', 'Something went wrong, please try again later.');
        }
    }
}
