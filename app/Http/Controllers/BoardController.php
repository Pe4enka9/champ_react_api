<?php

namespace App\Http\Controllers;

use App\Dtos\BoardAccessDto;
use App\Dtos\BoardDto;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\BoardAccess;
use App\Models\BoardLike;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    // Все публичные доски
    public function index(): JsonResponse
    {
        $boards = Board::where('is_public', true)
            ->withCount('boardLikes')
            ->get();

        return response()->json(BoardResource::collection($boards));
    }

    // Доски пользователя (с правом редактирования)
    public function userBoards(Request $request): JsonResponse
    {
        $editableBoards = $request->user()->editableBoards()
            ->withCount('boardLikes')
            ->get();

        return response()->json(BoardResource::collection($editableBoards));
    }

    // Создание доски
    public function store(Request $request, BoardDto $dto): JsonResponse
    {
        $user = $request->user();

        $board = Board::create([
            'name' => $dto->name,
            'owner_id' => $user->id,
        ]);

        BoardAccess::create([
            'board_id' => $board->id,
            'user_id' => $user->id,
        ]);

        return response()->json(['success' => true], 201);
    }

    // Предоставить доступ к доске пользователю по email
    public function storeAccess(BoardAccessDto $dto, Board $board): JsonResponse
    {
        $user = User::where('email', $dto->email)->firstOrFail();

        BoardAccess::create([
            'board_id' => $board->id,
            'user_id' => $user->id,
        ]);

        return response()->json(['success' => true], 201);
    }

    // Сделать доску публичной
    public function makePublic(Request $request, Board $board): JsonResponse
    {
        if (!$this->checkOwner($board, $request->user())) {
            return response()->json(['message' => 'Access is not allowed.'], 403);
        }

        $hash = $board->generateHash();
        $board->update(['is_public' => true]);

        return response()->json(['hash' => $hash]);
    }

    // Сделать доску приватной
    public function makePrivate(Request $request, Board $board): JsonResponse
    {
        if (!$this->checkOwner($board, $request->user())) {
            return response()->json(['message' => 'Access is not allowed.'], 403);
        }

        $board->update([
            'is_public' => false,
            'hash' => null,
        ]);

        return response()->json(['success' => true]);
    }

    // Проверка на владельца доски
    private function checkOwner(Board $board, User $user): bool
    {
        return $board->owner_id === $user->id;
    }

    // Доступ к доске
    public function show(Board $board): JsonResponse
    {
        $board->loadCount('boardLikes');

        return response()->json(new BoardResource($board));
    }

    // Поставить лайк доске
    public function like(Request $request, Board $board): JsonResponse
    {
        BoardLike::create([
            'board_id' => $board->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['success' => true]);
    }
}
