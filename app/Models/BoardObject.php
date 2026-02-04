<?php

namespace App\Models;

use App\Enums\ObjectTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $board_id
 * @property int $owner_id
 * @property ObjectTypeEnum $type
 * @property string $content
 * @property int $x
 * @property int $y
 * @property int|null $width
 * @property int|null $height
 * @property float $rotation
 * @property int $z_index
 * @property int $focused_by
 *
 * @property-read Board $board
 * @property-read User $owner
 */
class BoardObject extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'type' => ObjectTypeEnum::class,
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Проверка, находится ли объект в фокусе у другого пользователя
    public function isLockedByOtherUser(int $userId): bool
    {
        return $this->focused_by && $this->focused_by !== $userId;
    }

    // Захват фокуса
    public function focus(int $userId): bool
    {
        if ($this->isLockedByOtherUser($userId)) {
            return false;
        }

        $this->update(['focused_by' => $userId]);

        return true;
    }

    // Снятие фокуса
    public function blur(): void
    {
        $this->update(['focused_by' => null]);
    }
}
