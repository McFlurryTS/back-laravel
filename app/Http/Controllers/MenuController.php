<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::all();
        return response()->json($menus);
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
        $validated = $request->validate([
            'category' => 'string',
            'name' => 'string|max:255',
            'description' => 'string',
            'weight' => 'numeric|between:0,999999.99',
            'calories' => 'integer',
            'caloriesPercentage' => 'integer|between:0,100',
            'proteins' => 'numeric|between:0,999.99',
            'proteinsPercentage' => 'integer|between:0,100',
            'carbohydrates' => 'numeric|between:0,999.99',
            'carbohydratesPercentage' => 'integer|between:0,100',
            'lipids' => 'numeric|between:0,999.99',
            'lipidsPercentage' => 'integer|between:0,100',
            'sodium' => 'numeric|between:0,999999.99',
            'sodiumPercentage' => 'integer|between:0,100',
            'image' => 'url',
            'country' => 'string|size:2',
            'hideExtraInfo' => 'boolean',
            'urlPdf' => 'nullable|url',
            'active' => 'boolean',
            'fiber' => 'numeric|between:0,999.99',
            'fiberPercentage' => 'integer|between:0,100',
            'saturatedFats' => 'numeric|between:0,999.99',
            'saturatedFatsPercentage' => 'integer|between:0,100',
            'transFats' => 'numeric|between:0,999.99',
            'transFatsPercentage' => 'integer|between:0,100',
            'allergens' => 'array',
            'allergens.*' => 'boolean',
            'sugarTotals' => 'numeric|between:0,999.99'
        ]);

        $menu = Menu::create($validated);
        return response()->json($menu, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category' => 'sometimes|string',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'weight' => 'sometimes|numeric|between:0,999999.99',
            'calories' => 'sometimes|integer',
            'caloriesPercentage' => 'sometimes|integer|between:0,100',
            'proteins' => 'sometimes|numeric|between:0,999.99',
            'proteinsPercentage' => 'sometimes|integer|between:0,100',
            'carbohydrates' => 'sometimes|numeric|between:0,999.99',
            'carbohydratesPercentage' => 'sometimes|integer|between:0,100',
            'lipids' => 'sometimes|numeric|between:0,999.99',
            'lipidsPercentage' => 'sometimes|integer|between:0,100',
            'sodium' => 'sometimes|numeric|between:0,999999.99',
            'sodiumPercentage' => 'sometimes|integer|between:0,100',
            'image' => 'sometimes|url',
            'country' => 'sometimes|string|size:2',
            'hideExtraInfo' => 'sometimes|boolean',
            'urlPdf' => 'nullable|url',
            'active' => 'sometimes|boolean',
            'fiber' => 'sometimes|numeric|between:0,999.99',
            'fiberPercentage' => 'sometimes|integer|between:0,100',
            'saturatedFats' => 'sometimes|numeric|between:0,999.99',
            'saturatedFatsPercentage' => 'sometimes|integer|between:0,100',
            'transFats' => 'sometimes|numeric|between:0,999.99',
            'transFatsPercentage' => 'sometimes|integer|between:0,100',
            'allergens' => 'sometimes|array',
            'allergens.*' => 'boolean',
            'sugarTotals' => 'sometimes|numeric|between:0,999.99'
        ]);

        $menu->update($validated);
        return response()->json($menu);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(null, 204);
    }
}
