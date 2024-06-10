<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        try {
            $items = Item::with('user')->where('returned', 0)->get();

            return response()->json([
                'success' => true,
                'message' => 'Success getting Items',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting Items data',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function history()
    {
        try {
            $items = Item::with('user')->where('returned', 1)->get();

            return response()->json([
                'success' => true,
                'message' => 'Success getting Items History',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting Items History',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:lost,found',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
            ]);

            $path = $request->file('image') ? $request->file('image')->store('public/items') : null;

            $item = Item::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $path,
                'returned' => 0,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success storing Item data',
                'data' => $item
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when storing Item data',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $item = Item::with('user')->findOrfail($id);

            return response()->json([
                'success' => true,
                'message' => 'Success getting Item data',
                'data' => $item
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when getting Item data',
                'errors' => $e->getMessage()
            ], 400);
        }
    }

    public function return($id)
    {
        try {
            $item = Item::findOrfail($id);
            $item->update([
                'returned' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Success returning Item',
                'data' => $item
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error when returning Item',
                'errors' => $e->getMessage()
            ], 400);
        }
    }
}
