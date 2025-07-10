<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginateFileCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_files' => $this->total(),
            'last_page' => $this->lastPage(),
            'files' => FileResource::collection($this->items())
        ];
    }
}
