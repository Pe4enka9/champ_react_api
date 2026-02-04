<?php

namespace App\Dtos\Auth;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class LoginDto extends Data
{
    public function __construct(
        #[Email]
        public string $email,
        public string $password,
    )
    {
    }
}
