<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $focusedBy = User::find($this['focused_by']);

        return [
            'id' => $this['id'],
            'type' => $this['type'],
            'x' => $this['x'],
            'y' => $this['y'],
            'width' => $this['width'],
            'height' => $this['height'],
            'rotation' => $this['rotation'],
            'focused_by' => new UserResource($focusedBy),
        ];
    }
}
