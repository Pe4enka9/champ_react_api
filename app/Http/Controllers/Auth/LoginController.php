<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\Auth\LoginDto;
use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private LoginService $loginService,
    )
    {
    }

    // Авторизация
    public function login(LoginDto $dto): JsonResponse
    {
        $token = ($this->loginService)($dto);

        return response()->json(['token' => $token]);
    }
}
