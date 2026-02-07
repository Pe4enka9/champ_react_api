<?php

namespace App\Services;

use App\Dtos\AccessDto;
use App\Dtos\BoardDto;
use App\Models\Board;
use App\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class BoardService
{
    // Создание доски
    public function create(User $user, BoardDto $dto): void
    {
        $board = Board::create([
            'name' => $dto->name,
            'owner_id' => $user->id,
        ]);

        $board->accesses()->create(['user_id' => $user->id]);
    }

    // Предоставить доступ к доске пользователю по email
    public function access(AccessDto $dto, Board $board): void
    {
        $user = User::where('email', $dto->email)->firstOrFail();
        $board->accesses()->create(['user_id' => $user->id]);
    }

    // Проверка на владельца доски
    private function checkOwner(User $user, Board $board): void
    {
        if ($board->owner_id !== $user->id) {
            throw new AccessDeniedException();
        }
    }

    // Сделать доску публичной
    public function makePublic(User $user, Board $board): string
    {
        $this->checkOwner($user, $board);
        $hash = Str::random(32);

        $board->update([
            'hash' => $hash,
            'is_public' => true,
        ]);

        return $hash;
    }

    // Сделать доску приватной
    public function makePrivate(User $user, Board $board): void
    {
        $this->checkOwner($user, $board);

        $board->update([
            'hash' => null,
            'is_public' => false,
        ]);
    }
}
