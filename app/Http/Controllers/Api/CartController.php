<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
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
        $validate = $request->validated();
        $ownerId = request()->user()->id;
        //validasi agar tidak diisi orang lain
        if ($ownerId !== (int) $validate['user_id']) {
            return response()->json("Not add cart to another user ". $ownerId . ' + ' . $validate['user_id']);
        }
        //validasi hanya update jika sudah ada
        $existingCart = Cart::where('is_deleted', false)
                        ->where('user_id', $validate['user_id'])
                        ->where('product_id', $validate['product_id'])
                        ->first();
        if ($existingCart){
            $newQuantity = $existingCart->quantity + ($validate['quantity'] ?? 1);
            $existingCart->update(['quantity' => $newQuantity]);
            $cartItem = $existingCart;
        }else{
            $cartItem = Cart::create($existingCart);
        }
        $cartItem->load('product');
        return response()->json($cartItem, 201);
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $cart = Cart::where('is_deleted', false)->find($id);
        if (!$cart) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $validate = $request->validated();
        $cart->update($validate);
        $cart->load('product');

        return response()->json($cart);
    }

    public function destroy($id)
    {
        $cart = Cart::where('is_deleted', false)->find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $cart->update(['is_deleted' => true]);
        return response()->json(['message' => 'Cart deleted successfully']);
    }

}
