<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\AttackEvent;
use App\Models\MitigationRule;
use App\Models\SystemUptime;
use App\Models\TrafficSample;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $settings = $this->settingsMap();
        $events = AttackEvent::query()->orderByDesc('occurred_at')->get();
        $samples = TrafficSample::query()->orderBy('sample_at')->get();
        $rules = MitigationRule::query()->orderByDesc('applied_at')->get();

        $activeAttacks = $events->whereIn('status', ['open', 'investigating'])->count();
        $blockedIps = MitigationRule::query()->where('status', 'active')->distinct('target_ip')->count('target_ip');
        $newBlocks = MitigationRule::query()->where('status', 'active')->where('applied_at', '>=', now()->subDay())->count();
        $resolvedAlerts = $events->where('status', 'resolved')->count();
        $totalAlerts = max($events->count(), 1);
        $mitigationRate = round(($resolvedAlerts / $totalAlerts) * 100, 1);

        $topBlockedIps = MitigationRule::query()
            ->selectRaw('target_ip, COALESCE(country, "Unknown") as country, COUNT(*) as blocks')
            ->groupBy('target_ip', 'country')
            ->orderByDesc('blocks')
            ->limit(4)
            ->get();

        if ($topBlockedIps->isEmpty()) {
            $topBlockedIps = collect([
                (object) ['target_ip' => 'No data', 'country' => 'Unknown', 'blocks' => 0],
                (object) ['target_ip' => 'No data', 'country' => 'Unknown', 'blocks' => 0],
                (object) ['target_ip' => 'No data', 'country' => 'Unknown', 'blocks' => 0],
                (object) ['target_ip' => 'No data', 'country' => 'Unknown', 'blocks' => 0],
            ]);
        }

        $recentAlerts = $events->take(5);
        if ($recentAlerts->isEmpty()) {
            $recentAlerts = collect([
                (object) [
                    'attack_type' => 'No alerts yet',
                    'occurred_at' => null,
                    'target_ip' => '—',
                    'severity' => 'low',
                ],
            ]);
        }

        $trafficCategories = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => $sample->sample_at?->format('H:i') ?? '00:00')->values()
            : collect(['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00']);

        $trafficNormal = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => (int) $sample->normal_flows)->values()
            : collect(array_fill(0, 12, 0));

        $trafficAttack = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => (int) $sample->suspicious_flows)->values()
            : collect(array_fill(0, 12, 0));

        $trafficThreshold = $samples->isNotEmpty()
            ? $samples->map(fn ($sample) => (int) $sample->threshold)->values()
            : collect(array_fill(0, 12, 0));

        $attackTypes = $events->groupBy('attack_type')->map->count()->sortDesc();
        $attackTypeLabels = $attackTypes->keys()->values();
        $attackTypeValues = $attackTypes->values();
        if ($attackTypeLabels->isEmpty()) {
            $attackTypeLabels = collect(['No data']);
            $attackTypeValues = collect([0]);
        }

        $topAttackTypeLabel = $attackTypeLabels->first() ?? 'No data';
        $topAttackTypePercent = $attackTypeValues->sum() > 0
            ? (int) round((($attackTypeValues->first() ?? 0) / $attackTypeValues->sum()) * 100)
            : 0;

        $lastSample = $samples->last();
        $currentBandwidth = (float) (($lastSample?->normal_flows ?? 0) + ($lastSample?->suspicious_flows ?? 0));
        $trafficThresholdLast = (float) ($trafficThreshold->last() ?? 0);

        $threatScore = (int) ($events->max('anomaly_score') ?? 0);
        $threatLevel = match (true) {
            $threatScore >= 85 => 'CRITICAL',
            $threatScore >= 70 => 'HIGH',
            $threatScore >= 45 => 'MEDIUM',
            default => 'LOW',
        };

        $routerHealth = $settings['router_health'] ?? 'Healthy';
        $routerHost = $settings['router_host'] ?? 'N/A';
        $routerUtilization = (int) ($settings['router_utilization'] ?? 0);
        $cpuUtilization = (int) ($settings['cpu_utilization'] ?? 0);
        $memoryUsage = (int) ($settings['memory_usage'] ?? 0);

        // ========== GET UPTIME FROM DATABASE ==========
        $systemUptimeData = $this->getSystemUptime();
        // ==============================================

        return view('admin.dashboard', [
            'activeAttacks' => $activeAttacks,
            'blockedIps' => $blockedIps,
            'newBlocks' => $newBlocks,
            'mitigationRate' => $mitigationRate,
            'trafficSeries' => [
                'categories' => $trafficCategories,
                'normal' => $trafficNormal,
                'attack' => $trafficAttack,
                'threshold' => $trafficThreshold,
            ],
            'topBlockedIps' => $topBlockedIps,
            'recentAlerts' => $recentAlerts,
            'topAttackTypeLabel' => $topAttackTypeLabel,
            'topAttackTypePercent' => $topAttackTypePercent,
            'currentBandwidth' => $currentBandwidth,
            'trafficThresholdLast' => $trafficThresholdLast,
            'threatLevel' => $threatLevel,
            'routerHealth' => $routerHealth,
            'routerHost' => $routerHost,
            'routerUtilization' => $routerUtilization,
            'cpuUtilization' => $cpuUtilization,
            'memoryUsage' => $memoryUsage,
            // Uptime data
            'uptimePercentage' => $systemUptimeData['percentage'],
            'uptimeFormatted' => $systemUptimeData['formatted'],
            'uptimeShort' => $systemUptimeData['short'],
            'uptimeWithIcon' => $systemUptimeData['with_icon'],
            'uptimeColor' => $systemUptimeData['color'],
            'uptimeProgress' => $systemUptimeData['progress'],
            'uptimeStartedAt' => $systemUptimeData['started_at'],
            'uptimeStatus' => $systemUptimeData['status'],
            'threatScore' => $threatScore,
            'resolvedAlerts' => $resolvedAlerts,
            'totalAlerts' => $totalAlerts,
            'attackTypeLabels' => $attackTypeLabels,
            'attackTypeValues' => $attackTypeValues,
        ]);
    }

    private function settingsMap(): array
    {
        return AppSetting::query()->pluck('value', 'key')->all();
    }

    /**
     * Get system uptime from database
     */
    private function getSystemUptime(): array
    {
        // Get or create uptime record
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

        // Update last ping
        $uptime->last_ping_at = now();
        $uptime->save();

        // Simulate occasional small downtimes for realism (local only)
        if (app()->environment('local')) {
            $this->simulateDowntime($uptime);
        }

        return [
            'percentage' => $uptime->getUptimePercentage(),
            'formatted' => $uptime->getUptimeFormatted(),
            'short' => $uptime->getUptimeShort(),
            'with_icon' => $uptime->getUptimeWithIcon(),
            'color' => $uptime->getUptimeColor(),
            'progress' => $uptime->getUptimeProgress(),
            'started_at' => $uptime->started_at,
            'status' => $uptime->getStatusBadge(),
        ];
    }

    /**
     * Simulate small downtimes for local development
     */
    private function simulateDowntime(SystemUptime $uptime): void
    {
        // 1% chance per request to simulate a small downtime
        if (mt_rand(1, 100) === 1) {
            $duration = mt_rand(1, 3);
            $uptime->recordDowntime($duration);
        }
    }
}