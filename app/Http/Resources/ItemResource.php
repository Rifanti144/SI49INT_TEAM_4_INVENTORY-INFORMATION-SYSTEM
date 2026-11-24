<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_name' => $this->item_name,
            'tech_spec' => $this->tech_spec,
            'unit_size' => $this->unit_size,
            'category' => $this->category,
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
