<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'phone' => 'required|string|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Berikan token untuk autentikasi
            $token = $user->createToken('API Token')->plainTextToken;

            return new JsonResponses(Response::HTTP_CREATED, 'User registered successfully!', $user, ['token' => $token]);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }


    public function login(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Cek apakah user ada berdasarkan email
            $user = User::where('email', $request->email)->first();

            // Jika user tidak ditemukan atau password salah
            if (!$user || !Hash::check($request->password, $user->password)) {
                return new JsonResponses(Response::HTTP_OK, 'Invalid credentials', null);
            }

            // Generate token untuk autentikasi
            $token = $user->createToken('API Token')->plainTextToken;

            return new JsonResponses(Response::HTTP_OK, 'Login successful!', $user, ['token' => $token]);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }


    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return new JsonResponses(Response::HTTP_OK, 'Logged out successfully', null);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_OK, 'Something went wrong', null, ['error' => $e->getMessage()]);
        }
    }
}
