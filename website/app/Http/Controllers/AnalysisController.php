<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\AttackEvent;
use App\Models\SystemUptime;
use App\Models\TrafficSample;
use Illuminate\View\View;

class AnalysisController extends Controller
{
    public function index(): View
    {
        $settings = $this->settingsMap();
        $events = AttackEvent::query()->orderByDesc('occurred_at')->get();
        $samples = TrafficSample::query()->orderBy('sample_at')->get();
        
        // Stat Cards Data
        $totalFlows = $samples->sum('normal_flows') + $samples->sum('suspicious_flows');
        $attackFlows = $samples->sum('suspicious_flows');
        $anomalies = $events->where('severity', '>=', 'medium')->count();
        $detectionRate = $totalFlows > 0 ? round(($attackFlows / $totalFlows) * 100, 2) : 0;
        
        // Response Metrics
        $avgResponseTime = $events->avg('sla_minutes') ?? 0;
        $detectionSuccess = $events->count() > 0 
            ? round(($events->where('status', 'resolved')->count() / $events->count()) * 100, 2) 
            : 0;
        $meanTimeToResolution = $events->where('status', 'resolved')->avg('sla_minutes') ?? 0;
        
        // Top Sources
        $topSources = AttackEvent::query()
            ->selectRaw('source_ip, COUNT(*) as count, MAX(anomaly_score) as max_score, protocol')
            ->groupBy('source_ip', 'protocol')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        
        // Protocol Distribution
        $protocolDistribution = AttackEvent::query()
            ->selectRaw('COALESCE(protocol, "Unknown") as protocol, COUNT(*) as count')
            ->groupBy('protocol')
            ->get();
        
        $protocolLabels = $protocolDistribution->pluck('protocol')->toArray();
        $protocolValues = $protocolDistribution->pluck('count')->toArray();
        
        if (empty($protocolLabels)) {
            $protocolLabels = ['TCP', 'UDP', 'ICMP'];
            $protocolValues = [0, 0, 0];
        }
        
        // Recent Activity
        $recentActivities = AttackEvent::query()
            ->orderByDesc('occurred_at')
            ->limit(10)
            ->get();
        
        // Traffic Chart
        $trafficCategories = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => $sample->sample_at?->format('H:i') ?? '00:00')->values()
            : collect(['01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00']);
        
        $normalFlows = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => (int) $sample->normal_flows)->values()
            : collect(array_fill(0, 12, 0));
        
        $suspiciousFlows = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => (int) $sample->suspicious_flows)->values()
            : collect(array_fill(0, 12, 0));
        
        $systemUptimeData = $this->getSystemUptime();
        
        return view('admin.analysis', [
            'totalFlows' => $totalFlows,
            'attackFlows' => $attackFlows,
            'anomalies' => $anomalies,
            'detectionRate' => $detectionRate,
            'avgResponseTime' => $avgResponseTime,
            'detectionSuccess' => $detectionSuccess,
            'meanTimeToResolution' => $meanTimeToResolution,
            'topSources' => $topSources,
            'protocolLabels' => $protocolLabels,
            'protocolValues' => $protocolValues,
            'recentActivities' => $recentActivities,
            'trafficCategories' => $trafficCategories,
            'normalFlows' => $normalFlows,
            'suspiciousFlows' => $suspiciousFlows,
            'uptimeFormatted' => $systemUptimeData['formatted'],
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