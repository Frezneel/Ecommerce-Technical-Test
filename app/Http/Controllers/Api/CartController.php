<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function index()
    {
        $ownerId = request()->user()->id;
        try {
            $cartItems = Cart::with('product')->where('user_id', $ownerId)->get();
            $data = CartResource::collection($cartItems);
            return response()->json(["data" => $data, "message" => "Cart items retrieved successfully"]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred while fetching cart items'], 500);
        }
    }

    public function show($id)
    {
        $ownerId = request()->user()->id;
        $cartItem = Cart::with('product')->find($id);
        //validasi agar tidak diisi orang lain
        if ($ownerId !== (int) $cartItem['user_id']) {
            return response()->json(['Message' => "Cannot show a cart to another user"]);
        }
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
            return response()->json(['Message' => "Cannot add a cart to another user"]);
        }
        $validate_stock = $this->validateStock((int)$validate['product_id'], (int)$validate['quantity']);
        if(!$validate_stock){
            return response()->json(['Message' => "Products cannot be added to cart, Exceed quantity"]);
        }
        //validasi hanya update jika sudah ada
        $existingCart = Cart::where('is_deleted', false)
                        ->where('user_id', $validate['user_id'])
                        ->where('product_id', $validate['product_id'])
                        ->first();

        //validasi Cart yang dimasukkan sudah ada maka ditambahkan
        if ($existingCart){
            $newQuantity = $existingCart->quantity + ($validate['quantity'] ?? 1);
            $validate_stock = $this->validateStock((int)$validate['product_id'], $newQuantity);
            if(!$validate_stock){
                return response()->json(['Message' => "Products cannot be added to cart, Exceed quantity"]);
            }
            $existingCart->update(['quantity' => $newQuantity]);
            $cartItem = $existingCart;
        }else{
            $cartItem = Cart::create($validate);
        }

        $cartItem->load('product');
        return response()->json($cartItem, 201);
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $validate = $request->validated();
        $ownerId = request()->user()->id;
        if ($ownerId !== (int) $validate['user_id']) {
            return response()->json(['Message'=> "Cannot add a cart to another user"]);
        }
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['Message' => 'Category not found'], 404);
        }
        $validate_stock = $this->validateStock((int)$validate['product_id'], (int)$validate['quantity']);
        if(!$validate_stock){
            return response()->json(["Message"=> "Products cannot be added to cart, Exceed quantity"]);
        }

        $cart->update($validate);
        $cart->load('product');

        return response()->json($cart);
    }

    public function destroy($id)
    {
        $cart = Cart::find($id);
        $ownerId = request()->user()->id;
        if ($ownerId !== (int) $cart->user_id) {
            return response()->json(["Message"=> "Cannot delete a cart another user"]);
        }
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $cart->delete();
        return response()->json(['message' => 'Cart deleted successfully']);
    }

    private function validateStock(int $productId, int $quantityRequest):bool
    {
        // Validasi jika quantity dicart lebih dari stock product
        $validate_stock = Product::find($productId);
        if ($validate_stock->quantity >= $quantityRequest){
            return true;
        }
        return false;
    }

}
