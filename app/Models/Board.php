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
 * @property array $objects
 *
 * @property-read User $owner
 * @property-read Collection<BoardAccess> $accesses
 * @property-read Collection<BoardLike> $likes
 */
class Board extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_public' => 'boolean',
        'objects' => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accesses(): HasMany
    {
        return $this->hasMany(BoardAccess::class, 'board_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BoardLike::class, 'board_id');
    }
}
