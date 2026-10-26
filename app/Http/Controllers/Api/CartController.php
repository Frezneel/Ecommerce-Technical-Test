<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function index()
    {
        $ownerId = request()->user()->id;
        try {
            $cartItems = Cart::with('product')->where('is_deleted', false)->where('user_id', $ownerId)->get();
            $data = CartResource::collection($cartItems);
            return response()->json(["data" => $data, "message" => "Cart items retrieved successfully"]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while fetching cart items'], 500);
        }
    }

    public function show($id)
    {
        $cartItem = Cart::with('product')->where('is_deleted', false)->find($id);
        $ownerId = request()->user()->id;
        if (!$cartItem || $cartItem->user_id !== $ownerId) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }
        return response()->json($cartItem);
    }

    public function store(StoreCartRequest $request)
    {
        $request->validated();
        $cartItem = Cart::create($request->all());
        $cartItem->load('product');
        return response()->json($cartItem, 201);
    }

}
