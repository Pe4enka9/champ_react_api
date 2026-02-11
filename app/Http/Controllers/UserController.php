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
        $user = $request->user();

        $editableBoards = $user
            ->editableBoards()
            ->with('owner')
            ->withCount([
                'likes',
                'likes as liked_by_current_user' => fn($q) => $q->where('user_id', $user->id)
            ])
            ->get();

        return response()->json(BoardResource::collection($editableBoards));
    }
}
