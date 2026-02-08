<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $board_id
 * @property int $user_id
 * @property boolean $can_edit
 */
class BoardAccess extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'can_edit' => 'boolean',
    ];
}
