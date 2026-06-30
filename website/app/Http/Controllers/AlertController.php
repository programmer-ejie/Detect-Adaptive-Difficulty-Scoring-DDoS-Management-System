<?php

namespace App\Http\Controllers;

use App\Models\AttackEvent;
use App\Models\AppSetting;
use App\Models\SystemUptime;
use Illuminate\View\View;

class AlertController extends Controller
{
    public function index(): View
    {
        $settings = $this->settingsMap();
        
        // Get all alerts/events
        $alerts = AttackEvent::query()
            ->orderByDesc('occurred_at')
            ->get();
        
        // Stat counts
        $criticalCount = $alerts->where('severity', 'critical')->count();
        $highCount = $alerts->where('severity', 'high')->count();
        $acknowledgedCount = $alerts->where('status', 'acknowledged')->count();
        $resolvedCount = $alerts->where('status', 'resolved')->count();
        
        // Severity distribution for chart
        $criticalTotal = $alerts->where('severity', 'critical')->count();
        $highTotal = $alerts->where('severity', 'high')->count();
        $mediumTotal = $alerts->where('severity', 'medium')->count();
        $lowTotal = $alerts->where('severity', 'low')->count();
        $maxSeverity = max($criticalTotal, $highTotal, $mediumTotal, $lowTotal, 1);
        
        // Response metrics
        $avgResponseTime = $alerts->avg('sla_minutes') ?? 0;
        $avgTimeToResolve = $alerts->where('status', 'resolved')->avg('sla_minutes') ?? 0;
        $totalAlerts = $alerts->count();
        $resolvedAlerts = $alerts->where('status', 'resolved')->count();
        $responseRate = $totalAlerts > 0 ? round(($resolvedAlerts / $totalAlerts) * 100, 1) : 0;
        
        // False positive rate
        $falsePositives = $alerts->where('status', 'closed')->count() + $alerts->where('severity', 'low')->count();
        $falsePositiveRate = $totalAlerts > 0 ? round(($falsePositives / $totalAlerts) * 100, 1) : 0;
        
        $systemUptimeData = $this->getSystemUptime();
        
        return view('admin.alert', [
            'criticalCount' => $criticalCount,
            'highCount' => $highCount,
            'acknowledgedCount' => $acknowledgedCount,
            'resolvedCount' => $resolvedCount,
            'alerts' => $alerts,
            'criticalTotal' => $criticalTotal,
            'highTotal' => $highTotal,
            'mediumTotal' => $mediumTotal,
            'lowTotal' => $lowTotal,
            'maxSeverity' => $maxSeverity,
            'avgResponseTime' => $avgResponseTime,
            'avgTimeToResolve' => $avgTimeToResolve,
            'responseRate' => $responseRate,
            'falsePositiveRate' => $falsePositiveRate,
            'uptimeFormatted' => $systemUptimeData['formatted'],
            'uptimeCompact' => $systemUptimeData['compact'],
            'uptimeColor' => $systemUptimeData['color'],
            'uptimeStartedAt' => $systemUptimeData['started_at'],
            'uptimeSeconds' => $systemUptimeData['seconds'],
            'uptimeStatus' => $systemUptimeData['status'],
        ]);
    }

    private function settingsMap(): array
    {
        return AppSetting::query()->pluck('value', 'key')->all();
    }

    private function getSystemUptime(): array
    {
        $uptime = SystemUptime::first();
        
        if (!$uptime) {
            $uptime = SystemUptime::create([
                'started_at' => now(),
                'last_ping_at' => now(),
                'status' => 'running',
                'total_downtime_seconds' => 0,
                'downtime_events' => [],
            ]);
        }

        $uptime->last_ping_at = now();
        $uptime->save();

        return [
            'percentage' => $uptime->getUptimePercentage(),
            'formatted' => $uptime->getUptimeFormatted(),
            'compact' => $uptime->getUptimeCompact(),
            'short' => $uptime->getUptimeShort(),
            'with_icon' => $uptime->getUptimeWithIcon(),
            'color' => $uptime->getUptimeColor(),
            'progress' => $uptime->getUptimeProgress(),
            'started_at' => $uptime->started_at,
            'status' => $uptime->getStatusBadge(),
            'seconds' => $uptime->getUptimeSeconds(),
        ];
    }
}