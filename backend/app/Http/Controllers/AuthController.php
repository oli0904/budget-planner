<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $token = $user->createToken('budget-planner')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('budget-planner')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function deposit(Request $request) 
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $deposit = Deposit::create([
            'amount' => $validated['amount'],
        ]);
    
        return response()->json([
            'message' => 'Deposit successful',
            'deposit' => $deposit,
        ], 201);
    }
    public function deposits()
    {
        $deposits = Deposit::latest()->get();

        return response()->json([
            'deposits' => $deposits,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}