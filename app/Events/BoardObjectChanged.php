<?php

namespace App\Events;

use App\Models\Board;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BoardObjectChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $boardId,
        public string $action, // create, update, delete, focus, blur
        public mixed  $data,
    )
    {
    }

    public function broadcastOn(): array
    {
        $board = Board::find($this->boardId);

        if ($board->is_public) {
            return [
                new Channel('public-board.' . $board->hash),
            ];
        }

        return [
            new PrivateChannel('board.' . $this->boardId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'board.object.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'data' => $this->data,
        ];
    }
}
