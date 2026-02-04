<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\Auth\LoginDto;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Авторизация
    public function login(LoginDto $dto): JsonResponse
    {
        $user = User::where('email', $dto->email)->firstOrFail();

        if (!Hash::check($dto->password, $user->password)) {
            return response()->json(status: 401);
        }

        return response()->json([
            'token' => $user->createToken('auth')->plainTextToken,
        ]);
    }
}
