<?php

namespace App\Http\Controllers;

use App\Dtos\AccessDto;
use App\Dtos\BoardDto;
use App\Dtos\ObjectDto;
use App\Http\Resources\BoardResource;
use App\Http\Resources\ObjectResource;
use App\Models\Board;
use App\Services\BoardService;
use App\Services\ObjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function __construct(
        private BoardService  $boardService,
        private ObjectService $objectService,
    )
    {
    }

    // Все публичные доски
    public function index(): JsonResponse
    {
        $boards = Board::where('is_public', true)
            ->withCount('likes')
            ->get();

        return response()->json(BoardResource::collection($boards));
    }

    // Создание доски
    public function store(
        BoardDto $dto,
        Request  $request,
    ): JsonResponse
    {
        $this->boardService->create($request->user(), $dto);

        return response()->json(['success' => true], 201);
    }

    // Предоставить доступ к доске пользователю по email
    public function access(
        Board     $board,
        AccessDto $dto,
        Request   $request,
    ): JsonResponse
    {
        $this->boardService->access($board, $request->user(), $dto);

        return response()->json(['success' => true], 201);
    }

    // Сделать доску публичной
    public function makePublic(
        Board   $board,
        Request $request,
    ): JsonResponse
    {
        $hash = $this->boardService->makePublic($board, $request->user());

        return response()->json(['hash' => $hash]);
    }

    // Сделать доску приватной
    public function makePrivate(
        Board   $board,
        Request $request,
    ): JsonResponse
    {
        $this->boardService->makePrivate($board, $request->user());

        return response()->json(['success' => true]);
    }

    // Доступ к доске по hash
    public function showHash(Board $board): JsonResponse
    {
        $board->loadCount('likes');

        return response()->json(new BoardResource($board));
    }

    // Доступ к доске
    public function show(
        Board   $board,
        Request $request,
    ): JsonResponse
    {
        $this->boardService->checkAccess($board, $request->user());

        return response()->json(new BoardResource($board));
    }

    // Обновление объектов доски
    public function update(
        Board     $board,
        ObjectDto $dto,
        Request   $request,
    ): JsonResponse
    {
        $this->objectService->updateObjects($board, $request->user(), $dto);

        return response()->json(ObjectResource::collection($board->objects));
    }

    // Поставить лайк доске
    public function like(
        Board   $board,
        Request $request,
    ): JsonResponse
    {
        $board->likes()->create(['user_id' => $request->user()->id]);

        return response()->json(['success' => true]);
    }
}
