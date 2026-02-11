<?php

namespace App\Http\Resources;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Board
 */
class BoardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner' => new UserResource($this->owner),
            'hash' => $this->hash,
            'is_public' => $this->is_public,
            'width' => $this->width,
            'height' => $this->height,
            'likes' => $this->likes_count ?? 0,
            'liked_by_current_user' => (bool)$this->liked_by_current_user,
            'objects' => ObjectResource::collection($this->objects ?? collect()),
        ];
    }
}
