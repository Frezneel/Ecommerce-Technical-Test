<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Cache data kategori selama 5 menit
        $categories = cache()->remember('category_list_all', 60*5, function () {
            return Category::where('is_deleted', false)->get()->toArray();
        });
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::where('is_deleted', false)->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $validate = $request->validated();
        $category = Category::create($validate);
        // Hapus cache kategori agar data terbaru ditampilkan
        cache()->forget('category_list_all');
        return response()->json($category, 201);
    }

    public function update(StoreCategoryRequest $request, $id)
    {
        $category = Category::where('is_deleted', false)->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validate = $request->validated();
        $category = $category->update($validate);

        // Hapus cache kategori agar data terbaru ditampilkan
        cache()->forget('category_list_all');
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::where('is_deleted', false)->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->update(['is_deleted' => true]);
        // Hapus cache kategori agar data terbaru ditampilkan
        cache()->forget('category_list_all');
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
