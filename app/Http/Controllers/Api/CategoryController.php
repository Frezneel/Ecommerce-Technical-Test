<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::where('is_deleted', false)->all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = \App\Models\Category::where('is_deleted', false)->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = \App\Models\Category::create($request->all());
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::where('is_deleted', false)->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::where('is_deleted', false)->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->update(['is_deleted' => true]);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
