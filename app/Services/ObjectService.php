<?php

namespace App\Services;

use App\Dtos\ObjectDto;
use App\Events\ObjectChanged;
use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Str;

class ObjectService
{
    public function __construct(
        private BoardService $boardService,
    )
    {
    }

    public function updateObjects(
        Board     $board,
        ObjectDto $dto,
        User      $user,
    ): void
    {
        $this->boardService->checkAccess($board, $user);
        $objects = $board->objects ?? [];

        if ($dto->deleted && $dto->id) {
            // Удаление объекта
            $objects = $this->delete($objects, $dto->id);
        } elseif ($dto->id) {
            // Обновление объекта
            $objects = $this->update($objects, $dto);
        } else {
            // Создание объекта
            $objects = $this->create($objects, $dto);
        }

        $board->update(['objects' => $objects]);

        event(new ObjectChanged($board->id, $board->objects));
    }

    // Удаление объекта
    private function delete(
        array  $objects,
        string $id,
    ): array
    {
        return array_values(
            array_filter($objects, fn($obj) => $obj['id'] !== $id)
        );
    }

    // Обновление объекта
    private function update(
        array     $objects,
        ObjectDto $dto,
    ): array
    {
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

        return $objects;
    }

    // Создание объекта
    private function create(
        array     $objects,
        ObjectDto $dto,
    ): array
    {
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

        return $objects;
    }
}
