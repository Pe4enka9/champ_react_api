<?php

namespace App\Http\Controllers;

use App\Dtos\CreateObjectDto;
use App\Dtos\UpdateObjectDto;
use App\Events\BoardObjectChanged;
use App\Http\Resources\BoardObjectResource;
use App\Models\Board;
use App\Models\BoardObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardObjectController extends Controller
{
    // Получение всех объектов доски
    public function index(Board $board): JsonResponse
    {
        $objects = $board->boardObjects()->get();

        return response()->json(BoardObjectResource::collection($objects));
    }

    // Создание объекта
    public function store(Request $request, CreateObjectDto $dto, Board $board): JsonResponse
    {
        $object = BoardObject::create([
            'board_id' => $board->id,
            'owner_id' => $request->user()->id,
            'type' => $dto->type,
            'content' => $dto->content,
            'x' => $dto->x,
            'y' => $dto->y,
            'rotation' => $dto->rotation,
            'z_index' => $dto->zIndex,
        ]);

        event(new BoardObjectChanged($board->id, 'create', $object));

        return response()->json([
            'success' => true,
            'object' => new BoardObjectResource($object)
        ], 201);
    }

    // Захват фокуса на объекте
    public function focus(Request $request, Board $board, BoardObject $object): JsonResponse
    {
        if (!$object->focus($request->user()->id)) {
            return response()->json([
                'message' => 'Object locked',
            ], 409);
        }

        event(new BoardObjectChanged($board->id, 'focus', [
            'object_id' => $object->id,
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
        ]));

        return response()->json(['success' => true]);
    }

    // Снятие фокуса
    public function blur(Request $request, Board $board, BoardObject $object): JsonResponse
    {
        if ($object->focused_by !== $request->user()->id) {
            return response()->json(['message' => 'Not your focus'], 403);
        }

        $object->blur();

        event(new BoardObjectChanged($board->id, 'blur', [
            'object_id' => $object->id,
            'user_id' => $request->user()->id,
        ]));

        return response()->json(['success' => true]);
    }

    // Обновление объекта
    public function update(Request $request, UpdateObjectDto $dto, Board $board, BoardObject $object): JsonResponse
    {
        if ($object->isLockedByOtherUser($request->user()->id)) {
            return response()->json([
                'message' => 'Object locked',
            ], 409);
        }

        $object->update([
            'content' => $dto->content ?? $object->content,
            'x' => $dto->x ?? $object->x,
            'y' => $dto->y ?? $object->y,
            'width' => $dto->width ?? $object->width,
            'height' => $dto->height ?? $object->height,
            'rotation' => $dto->rotation ?? $object->rotation,
            'z_index' => $dto->zIndex ?? $object->z_index,
        ]);

        event(new BoardObjectChanged($board->id, 'update', $object));

        return response()->json([
            'success' => true,
            'object' => new BoardObjectResource($object),
        ]);
    }

    // Удаление объекта
    public function destroy(Board $board, BoardObject $object): JsonResponse
    {
        $objectId = $object->id;
        $object->delete();

        event(new BoardObjectChanged($board->id, 'destroy', [
            'object_id' => $objectId,
        ]));

        return response()->json(['success' => true]);
    }
}
