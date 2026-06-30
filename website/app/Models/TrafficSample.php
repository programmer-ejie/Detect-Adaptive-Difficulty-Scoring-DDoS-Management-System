<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficSample extends Model
{
    protected $fillable = [
        'sample_at',
        'normal_flows',
        'suspicious_flows',
        'threshold',
    ];

    protected function casts(): array
    {
        return [
            'sample_at' => 'datetime',
            'normal_flows' => 'integer',
            'suspicious_flows' => 'integer',
            'threshold' => 'integer',
        ];
    }
}
