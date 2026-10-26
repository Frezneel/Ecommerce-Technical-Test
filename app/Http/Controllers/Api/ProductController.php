<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        // Cache data produk selama 5 menit
        $products = Cache::remember('product_list_all', 60*5, function () {
            return Product::with('category')->where('is_deleted', false)->get()->toArray();
        });
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

        // Hapus cache produk agar data terbaru ditampilkan
        cache()->forget('product_list_all');

        return response()->json([
            "message" => "Product created successfully",
            "product" => $product
        ], 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::where('is_deleted', false)->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $validate = $request->validated();
        $product = $product->update($validate);
        if ($request->has('category_id')) {
            $product->category()->sync($request->input('category_id'));
        }
        // Hapus cache produk agar data terbaru ditampilkan
        cache()->forget('product_list_all');

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::where('is_deleted', false)->find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->update(['is_deleted' => true, 'deleted_at' => now()]);

        // Hapus cache produk agar data terbaru ditampilkan
        cache()->forget('product_list_all');

        return response()->json(['message' => 'Product deleted successfully']);
    }


}
