<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importar Storage si es necesario

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Leer el archivo ubicaciones.json
        $filePath = base_path('ubicaciones.json');
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'El archivo ubicaciones.json no existe.'], 404);
        }

        $locations = json_decode(file_get_contents($filePath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Error al decodificar el archivo JSON.'], 500);
        }

        return response()->json(['locations' => $locations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $location = Location::create($validated);
        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        return response()->json($location);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
        ]);

        $location->update($validated);
        return response()->json($location);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json(null, 204);
    }
}