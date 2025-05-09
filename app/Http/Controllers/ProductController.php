<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all products with their related cart and cart items
        $products = Product::with(['cart', 'cart.cartItems'])->get();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
        ]);

        // Create a new product with the validated data
        $product = Product::create($request->all());
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the product by ID with their related cart and cart items
        $product = Product::with(['cart', 'cart.cartItems'])->findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
        ]);

        // Find the product by ID and update it with the validated data
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the product by ID and delete it
        $product = Product::findOrFail($id);
        $product->delete();

        // Return a response indicating success
        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
