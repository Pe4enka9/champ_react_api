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
    public function create(
        User     $user,
        BoardDto $dto,
    ): void
    {
        $board = Board::create([
            'name' => $dto->name,
            'owner_id' => $user->id,
        ]);

        $board->accesses()->create(['user_id' => $user->id]);
    }

    // Предоставить доступ к доске пользователю по email
    public function access(
        Board     $board,
        User      $user,
        AccessDto $dto,
    ): void
    {
        $this->checkOwner($board, $user);
        $user = User::where('email', $dto->email)->firstOrFail();
        $board->accesses()->create(['user_id' => $user->id]);
    }

    // Проверка на владельца доски
    private function checkOwner(
        Board $board,
        User  $user,
    ): void
    {
        if ($board->owner_id !== $user->id) {
            throw new AccessDeniedException();
        }
    }

    // Сделать доску публичной
    public function makePublic(
        Board $board,
        User  $user,
    ): string
    {
        $this->checkOwner($board, $user);
        $hash = Str::random(32);

        $board->update([
            'hash' => $hash,
            'is_public' => true,
        ]);

        return $hash;
    }

    // Сделать доску приватной
    public function makePrivate(
        Board $board,
        User  $user,
    ): void
    {
        $this->checkOwner($board, $user);

        $board->update([
            'hash' => null,
            'is_public' => false,
        ]);
    }

    // Проверка доступа
    public function checkAccess(
        Board $board,
        User  $user,
    ): void
    {
        if (!$board->accesses()->where('user_id', $user->id)->exists()) {
            throw new AccessDeniedException();
        }
    }

    // Генерации публичной ссылки
    public function generateLink(
        Board $board,
        User  $user,
    ): void
    {
        $this->checkOwner($board, $user);
        $publicLink = uniqid("board_{$board->id}_{$user->id}_");
        $board->update(['public_link' => $publicLink]);
    }
}
