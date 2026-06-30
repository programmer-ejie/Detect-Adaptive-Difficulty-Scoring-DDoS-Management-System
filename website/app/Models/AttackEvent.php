<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttackEvent extends Model
{
    protected $fillable = [
        'attack_type',
        'source_ip',
        'target_ip',
        'protocol',
        'severity',
        'status',
        'packets',
        'anomaly_score',
        'sla_minutes',
        'occurred_at',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'packets' => 'integer',
            'anomaly_score' => 'integer',
            'sla_minutes' => 'integer',
        ];
    }
}
