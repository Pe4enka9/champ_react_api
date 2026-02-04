<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property string|null $hash
 * @property boolean $is_public
 * @property int $width
 * @property int $height
 *
 * @property-read User $owner
 * @property-read Collection<BoardAccess> $boardAccesses
 * @property-read Collection<BoardLike> $boardLikes
 * @property-read Collection<BoardObject> $boardObjects
 */
class Board extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function boardAccesses(): HasMany
    {
        return $this->hasMany(BoardAccess::class, 'board_id');
    }

    public function boardLikes(): HasMany
    {
        return $this->hasMany(BoardLike::class, 'board_id');
    }

    public function boardObjects(): HasMany
    {
        return $this->hasMany(BoardObject::class, 'board_id');
    }

    public function generateHash(): string
    {
        $hash = uniqid();
        $this->update(['hash' => $hash]);

        return $hash;
    }
}
