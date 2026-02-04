<?php

namespace App\Dtos\Auth;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class RegisterDto extends Data
{
    public function __construct(
        #[Email, Max(255), Unique(User::class)]
        public string $email,
        #[Max(255), Regex('/^[a-zA-Z]+$/')]
        public string $name,
        #[Max(255)]
        public string $password,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'password' => [Password::min(8)->numbers()->symbols()],
        ];
    }
}
