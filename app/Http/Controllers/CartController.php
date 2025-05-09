<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all carts with their related cart items
        $carts = Cart::with(['cartItems'])->get();
        // Return the carts as a JSON response
        return CartResource::collection($carts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
        ]);

        // Create a new cart with the validated data
        $cart = Cart::create($request->all());
        // Return the created cart as a JSON response
        return new CartResource($cart);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the product by ID with their related cart and cart items
        $product = Cart::with(['cart', 'cart.cartItems'])->findOrFail($id);
        return new CartResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'total_price' => 'sometimes|required|numeric',
        ]);

        // Find the cart by ID
        $cart = Cart::findOrFail($id);
        // Update the cart with the validated data
        $cart->update($request->all());
        // Return the updated cart as a JSON response
        return new CartResource($cart);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the cart by ID
        $cart = Cart::findOrFail($id);
        // Delete the cart
        $cart->delete();
        // Return a success response
        return response()->json(['message' => 'Cart deleted successfully'], 200);
    }
}
