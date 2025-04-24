<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;

class OrdenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'menu_id' => 'required|exists:menus,id',
        ]);

        $purchase = Purchase::create($validatedData);

        return response()->json([
            'message' => 'Purchase created successfully.',
            'purchase' => $purchase,
        ], 201);
    }
}