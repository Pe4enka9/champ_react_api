<?php

namespace App\Dtos;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class UpdateObjectDto extends Data
{
    public function __construct(
        public ?string $content,
        #[Min(0), Max(1600)]
        public ?int   $x,
        #[Min(0), Max(900)]
        public ?int   $y,
        #[Min(0)]
        public ?int   $width,
        #[Min(0)]
        public ?int   $height,
        #[Min(-360), Max(360)]
        public ?float $rotation,
        #[Min(0)]
        public ?int   $zIndex,
    )
    {
    }
}
