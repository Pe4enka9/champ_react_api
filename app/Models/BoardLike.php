<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $board_id
 * @property int $user_id
 */
class BoardLike extends Model
{
    protected $guarded = ['id'];
}
