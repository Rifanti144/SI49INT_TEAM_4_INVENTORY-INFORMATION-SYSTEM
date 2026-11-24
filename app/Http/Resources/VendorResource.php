<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vendor_name' => $this->vendor_name,
            'office_address' => $this->office_address,
            'company_legality' => $this->company_legality,
            'pic_name' => $this->pic_name,
            'pic_contact' => $this->pic_contact,
            'created_by' => $this->user?->name,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
