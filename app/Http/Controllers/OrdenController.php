<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrdenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user(); 

        $purchases = [];

        foreach ($validatedData['items'] as $item) {
            $purchases[] = Purchase::create([
                'user_id' => $user->id,
                'menu_id' => $item['menu_id'],
            ]);
        }

        // Increment user visits
        $user->increment('visits');

        return response()->json([
            'message' => 'Purchases created successfully.',
            'purchases' => $purchases,
        ], 201);
    }
}