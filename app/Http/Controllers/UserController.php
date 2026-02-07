<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Доски пользователя (с правом редактирования)
    public function boards(Request $request): JsonResponse
    {
        $editableBoards = $request->user()
            ->editableBoards()
            ->withCount('likes')
            ->get();

        return response()->json(BoardResource::collection($editableBoards));
    }
}
