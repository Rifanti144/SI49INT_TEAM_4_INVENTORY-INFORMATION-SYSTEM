<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'serial' => $this->serial,
            'type' => $this->type,
            'status' => $this->status,
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
