<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Portfolio::all();
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
        $photography_id = $request->input('photography_id');
        $judul = $request->input('judul');
        $deskripsi = $request->input('deskripsi');

        // Menggunakan Guzzle untuk cek apakah photography_id valid
        $client = new Client();
        $photographyResponse = $client->get('http://localhost:8004/api/photographies/' . $photography_id);

        if ($photographyResponse->getStatusCode() != 200) {
            return response()->json(['error' => 'Photography not found'], 404);
        }

        $portfolio = Portfolio::create([
            'photography_id' => $photography_id,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
        ]);

        return response()->json($portfolio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Portfolio::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portfolio $portfolio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        //
    }
}
