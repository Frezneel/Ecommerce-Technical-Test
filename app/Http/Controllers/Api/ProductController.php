<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all()->where('is_deleted', false);
        return response()->json($products);
    }

    public function show($id)
    {
        $product_id = Product::with('category')->where('is_deleted', false)->find($id);
        if (!$product_id) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product = ProductResource::make($product_id);
        return response()->json($product);
    }

    public function store(StoreProductRequest $request)
    {
        $validateData = $request->validated();
        $product = Product::create($validateData);

        if ($request->has('category_id')) {
            $product->category()->attach($request->input('category_id'));
        }

        return response()->json([
            "message" => "Product created successfully",
            "product" => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('is_deleted', false)->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->update($request->all());
        if ($request->has('category_id')) {
            $product->category()->sync($request->input('category_id'));
        }

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::where('is_deleted', false)->find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update(['is_deleted' => true, 'deleted_at' => now()]);

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
