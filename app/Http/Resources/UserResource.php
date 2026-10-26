<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            $this->when($request->user()->tokenCan('SYSTEM:MANAGE_USERS'), function () {
                return [
                    'permissions' => $this->whenLoaded('permissions'),
                ];
            }),
            'role' => $this->whenLoaded('role'),
            'permissions' => $this->whenLoaded('permissions'),
        ];
    }
}
