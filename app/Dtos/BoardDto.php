<?php

namespace App\Dtos;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class BoardDto extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,
    )
    {
    }
}
