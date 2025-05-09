<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all cart items with their related cart and product
        $cartItems = CartItem::with(['cart', 'product'])->get();
        // Return the cart items as a JSON response
        return CartItemResource::collection($cartItems);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create a new cart item with the validated data
        $cartItem = CartItem::create($request->all());
        // Return the created cart item as a JSON response
        return new CartItemResource($cartItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the cart item by ID with their related cart and product
        $cartItem = CartItem::with(['cart', 'product'])->findOrFail($id);
        // Return the cart item as a JSON response
        return new CartItemResource($cartItem);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'cart_id' => 'sometimes|required|exists:carts,id',
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        // Find the cart item by ID
        $cartItem = CartItem::findOrFail($id);
        // Update the cart item with the validated data
        $cartItem->update($request->all());
        // Return the updated cart item as a JSON response
        return new CartItemResource($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the cart item by ID
        $cartItem = CartItem::findOrFail($id);
        // Delete the cart item
        $cartItem->delete();
        // Return a success response
        return response()->json(['message' => 'Cart item deleted successfully'], 200);
    }
}
