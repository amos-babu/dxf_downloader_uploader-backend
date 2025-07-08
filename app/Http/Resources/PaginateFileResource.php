<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginateFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'perPage' => $this->perPage,
            'currentPage' => $this->currentPage,
            'totalFiles' => $this->total,
            'lastPage' => $this->lastPage,
            'files' => $this->items
        ];
    }
}
