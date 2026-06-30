<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SystemUptime extends Model
{
    protected $fillable = [
        'started_at',
        'last_ping_at',
        'status',
        'total_downtime_seconds',
        'downtime_events',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'last_ping_at' => 'datetime',
            'downtime_events' => 'array',
        ];
    }

    public function getStatusBadge(): string
    {
        return match($this->status) {
            'running' => 'Running',
            'stopped' => 'Stopped',
            'maintenance' => 'Maintenance',
            default => 'Unknown'
        };
    }

    public function getUptimeSeconds(): int
    {
        if (!$this->started_at) {
            return 0;
        }

        $started = Carbon::parse($this->started_at);
        $now = Carbon::now();
        
        $diffInSeconds = $now->diffInSeconds($started);
        
        if ($diffInSeconds < 0) {
            return 0;
        }
        
        $downtimeSeconds = (int) ($this->total_downtime_seconds ?? 0);
        
        return (int) max(0, $diffInSeconds - $downtimeSeconds);
    }

    /**
     * Get formatted uptime - Human readable format
     * Examples:
     * - 5s (for < 1 minute)
     * - 2m 30s (for < 1 hour)
     * - 1h 23m 45s (for < 24 hours)
     * - 1d 5h 23m 45s (for >= 24 hours)
     */
    public function getUptimeFormatted(): string
    {
        $seconds = $this->getUptimeSeconds();
        
        if ($seconds === 0) {
            return '0s';
        }

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        $parts = [];
        
        if ($days > 0) {
            $parts[] = $days . 'd';
        }
        if ($hours > 0 || $days > 0) {
            $parts[] = str_pad($hours, 2, '0', STR_PAD_LEFT) . 'h';
        }
        if ($minutes > 0 || $hours > 0 || $days > 0) {
            $parts[] = str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';
        }
        $parts[] = str_pad($secs, 2, '0', STR_PAD_LEFT) . 's';

        return implode(' ', $parts);
    }

    /**
     * Get compact uptime format (for badges)
     */
    public function getUptimeCompact(): string
    {
        $seconds = $this->getUptimeSeconds();
        
        if ($seconds === 0) {
            return '0s';
        }

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        
        if ($days > 0) {
            $parts[] = $days . 'd';
        }
        if ($hours > 0 || $days > 0) {
            $parts[] = $hours . 'h';
        }
        if ($minutes > 0 || $hours > 0 || $days > 0) {
            $parts[] = $minutes . 'm';
        }

        return implode(' ', $parts);
    }

    public function getUptimePercentage(): float
    {
        if (!$this->started_at) {
            return 0;
        }

        $uptimeSeconds = $this->getUptimeSeconds();
        $downtimeSeconds = (int) ($this->total_downtime_seconds ?? 0);
        $totalSeconds = $uptimeSeconds + $downtimeSeconds;

        if ($totalSeconds === 0) {
            return 100;
        }

        return round(($uptimeSeconds / $totalSeconds) * 100, 2);
    }

    public function getUptimeShort(): string
    {
        $seconds = $this->getUptimeSeconds();
        
        if ($seconds === 0) return '0s';
        if ($seconds < 60) return $seconds . 's';
        if ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $secs = $seconds % 60;
            return $minutes . 'm ' . $secs . 's';
        }
        if ($seconds < 86400) {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return $hours . 'h ' . $minutes . 'm';
        }
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        return $days . 'd ' . $hours . 'h';
    }

    public function getUptimeWithIcon(): string
    {
        $seconds = $this->getUptimeSeconds();
        if ($seconds === 0) return '🔄 Just started';
        if ($seconds < 3600) return '⚡ ' . $this->getUptimeShort();
        if ($seconds < 86400) return '⏰ ' . $this->getUptimeShort();
        return '📅 ' . $this->getUptimeShort();
    }

    public function getUptimeColor(): string
    {
        $seconds = $this->getUptimeSeconds();
        $percentage = $this->getUptimePercentage();
        
        if ($percentage < 95) return '#dc2626';
        if ($percentage < 99) return '#f59e0b';
        if ($seconds < 3600) return '#6b7280';
        if ($seconds < 86400) return '#5864FF';
        return '#10b981';
    }

    public function getUptimeProgress(): int
    {
        $seconds = $this->getUptimeSeconds();
        $maxSeconds = 30 * 86400;
        return (int) round(min(100, ($seconds / $maxSeconds) * 100));
    }

    public function recordDowntime(int $durationSeconds): void
    {
        $events = $this->downtime_events ?? [];
        $events[] = [
            'started_at' => now()->subSeconds($durationSeconds)->toDateTimeString(),
            'ended_at' => now()->toDateTimeString(),
            'duration' => $durationSeconds,
        ];

        if (count($events) > 100) {
            $events = array_slice($events, -100);
        }

        $this->downtime_events = $events;
        $this->total_downtime_seconds = (int) ($this->total_downtime_seconds ?? 0) + $durationSeconds;
        $this->save();
    }
}