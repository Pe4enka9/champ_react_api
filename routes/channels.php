<?php

use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('board.{boardId}', function (User $user, int $boardId) {
    $board = Board::findOrFail($boardId);

    if ($board->owner_id === $user->id) {
        return true;
    }

    return $board->boardAccesses()->where('user_id', $user->id)->exists();
});

Broadcast::channel('public-board.{hash}', function (?User $user, string $hash) {
    $board = Board::where('hash', $hash)
        ->where('is_public', true)
        ->firstOrFail();

    return $board !== null;
});
