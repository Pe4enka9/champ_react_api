<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $board_id
 * @property int $user_id
 * @property boolean $can_edit
 *
 * @property-read Board $board
 * @property-read User $user
 */
class BoardAccess extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'can_edit' => 'boolean',
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
