<?php

namespace App\Http\Controllers;

use App\Dtos\ObjectDto;
use App\Http\Resources\ObjectResource;
use App\Models\Board;
use App\Services\ObjectService;
use Illuminate\Http\JsonResponse;

class ObjectController extends Controller
{
    public function __construct(
        private ObjectService $objectService,
    )
    {
    }

    // Получение всех объектов доски
    public function index(Board $board): JsonResponse
    {
        return response()->json(ObjectResource::collection($board->objects));
    }

    // Обновление объектов доски
    public function update(ObjectDto $dto, Board $board): JsonResponse
    {
        $this->objectService->updateObjects($dto, $board);

        return response()->json(ObjectResource::collection($board->objects));
    }
}
