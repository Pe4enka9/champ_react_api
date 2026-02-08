<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 *
 * @property-read Collection<Board> $editableBoards
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

    public function editableBoards(): BelongsToMany
    {
        return $this->belongsToMany(Board::class, 'board_accesses')
            ->wherePivot('can_edit', true);
    }
}
