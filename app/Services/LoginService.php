<?php

namespace App\Services;

use App\Dtos\Auth\LoginDto;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function __invoke(LoginDto $dto): string
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new AuthenticationException();
        }

        return $user->createToken('auth')->plainTextToken;
    }
}
