<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'profile_pic_path' => $this->profile_pic_path,
            'files' => CurrentUserFilesResource::collection($this->whenLoaded('files')),
        ];
    }
}
