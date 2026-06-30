<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitigationRule extends Model
{
    protected $fillable = [
        'name',
        'target_ip',
        'action',
        'status',
        'country',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
        ];
    }
}
