<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\Auth\RegisterDto;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Регистрация
    public function register(RegisterDto $dto): JsonResponse
    {
        User::create([
            'email' => $dto->email,
            'name' => $dto->name,
            'password' => Hash::make($dto->password),
        ]);

        return response()->json(['success' => true], 201);
    }
}
