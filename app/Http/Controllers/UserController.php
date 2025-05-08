<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all users with their related cart and cart items
        // $users = User::with(['cart', 'cart.cartItems'])->get();
        $users = User::with(['cart', 'cart.cartItems'])->get();
        // Return the users as a JSON response
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Create a new user with the validated data
        $user = User::create($request->all());
        // Return the created user as a JSON response
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the user by ID with their related cart and cart items
        $user = User::with(['cart', 'cart.cartItems'])->findOrFail($id);
        // Return the user as a JSON response
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);
        // Update the user with the validated data
        $user->update($request->all());
        // Return the updated user as a JSON response
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);
        // Delete the user
        $user->delete();
        // Return a success response
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
