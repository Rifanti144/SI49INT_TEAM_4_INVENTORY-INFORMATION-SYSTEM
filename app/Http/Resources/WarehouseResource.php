<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'warehouse_name' => $this->warehouse_name,
            'aisle_number' => $this->aisle_number,
            'rack_code' => $this->rack_code,
            'physical_capacity' => $this->physical_capacity,
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
