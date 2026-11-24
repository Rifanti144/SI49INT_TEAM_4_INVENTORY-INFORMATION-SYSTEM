<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use BelongsToUser;

    public const TYPES = ['forklift', 'trolley', 'pickup'];

    public const STATUSES = ['available', 'maintenance'];

    protected $fillable = ['serial', 'type', 'status'];
}
