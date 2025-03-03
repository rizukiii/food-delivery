<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JsonResponses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function detail()
    {
        try {
            $user = User::find(auth()->user());
            $user->image = url('/') . Storage::url($user->image);

            return new JsonResponses(Response::HTTP_OK, 'Data User berhasil didapatkan!', $user);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, 'Ada kesalahan!', [$e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'nullable|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|numeric',
                'image' => 'nullable|image',
                'password' => 'nullable|string|confirmation'
            ]);

            $user = User::find(auth()->user());
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;

            if ($request->hasFile('image')) {
                if ($user->image && Storage::exists($user->image)) {
                    Storage::delete($user->image);
                }
                $user->image = $request->file('image')->store('user', 'public');
            }
            $user->save();

            return new JsonResponses(Response::HTTP_OK, "Data User berhasil di perbarui!", $user);
        } catch (\Exception $e) {
            return new JsonResponses(Response::HTTP_INTERNAL_SERVER_ERROR, "Ada kesalahan!", ['error' => $e->getMessage()]);
        }
    }
}
