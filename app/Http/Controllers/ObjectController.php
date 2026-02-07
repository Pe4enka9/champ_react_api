<?php

namespace App\Http\Controllers;

use App\Dtos\ObjectDto;
use App\Events\ObjectChanged;
use App\Http\Resources\ObjectResource;
use App\Models\Board;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ObjectController extends Controller
{
    // Получение всех объектов доски
    public function index(Board $board): JsonResponse
    {
        return response()->json(ObjectResource::collection($board->objects));
    }

    // Обновление объектов доски
    public function update(ObjectDto $dto, Board $board): JsonResponse
    {
        $objects = $board->objects ?? [];

        if ($dto->deleted && $dto->id) {
            // Удаление объекта
            $objects = array_values(
                array_filter($objects, fn($obj) => $obj['id'] !== $dto->id)
            );
        } elseif ($dto->id) {
            // Обновление объекта
            foreach ($objects as $key => $obj) {
                if ($obj['id'] === $dto->id) {
                    $objects[$key] = [
                        'id' => $dto->id,
                        'type' => $dto->type,
                        'x' => $dto->x,
                        'y' => $dto->y,
                        'width' => $dto->width ?? ($obj['width'] ?? 100),
                        'height' => $dto->height ?? ($obj['height'] ?? 100),
                        'rotation' => $dto->rotation ?? ($obj['rotation'] ?? 0),
                        'focused_by' => $dto->focused_by,
                    ];
                    break;
                }
            }
        } else {
            // Создание объекта
            $objects[] = [
                'id' => $dto->id ?? Str::uuid()->toString(),
                'type' => $dto->type,
                'x' => $dto->x,
                'y' => $dto->y,
                'width' => $dto->width ?? 100,
                'height' => $dto->height ?? 100,
                'rotation' => $dto->rotation ?? 0,
                'focused_by' => null,
            ];
        }

        $board->update(['objects' => $objects]);

        event(new ObjectChanged($board->id, $board->objects));

        return response()->json(ObjectResource::collection($board->objects));
    }
}
