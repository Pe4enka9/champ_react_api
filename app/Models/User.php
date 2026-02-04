<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 *
 * @property-read Collection<Board> $boards
 * @property-read Collection<BoardAccess> $boardAccesses
 * @property-read Collection<Board> $editableBoards
 * @property-read Collection<BoardLike> $boardLikes
 * @property-read Collection<BoardObject> $boardObjects
 */
class User extends Authenticatable
{
    use HasApiTokens;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class, 'owner_id');
    }

    public function boardAccesses(): HasMany
    {
        return $this->hasMany(BoardAccess::class, 'user_id');
    }

    public function boardLikes(): HasMany
    {
        return $this->hasMany(BoardLike::class, 'user_id');
    }

    public function boardObjects(): HasMany
    {
        return $this->hasMany(BoardObject::class, 'board_id');
    }

    public function editableBoards(): BelongsToMany
    {
        return $this->belongsToMany(Board::class, 'board_accesses')
            ->wherePivot('can_edit', true);
    }
}
