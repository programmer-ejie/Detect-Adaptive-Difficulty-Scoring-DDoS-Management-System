<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\MitigationRule;
use App\Models\SystemUptime;
use Illuminate\View\View;

class MitigationController extends Controller
{
    public function index(): View
    {
        $settings = $this->settingsMap();
        
        // Get all mitigation rules
        $rules = MitigationRule::query()
            ->orderByDesc('applied_at')
            ->get();
        
        // Stat counts
        $blockedCount = $rules->where('status', 'active')->count();
        $rateLimitCount = $rules->where('action', 'rate_limit')->count();
        $aclCount = $rules->where('action', 'acl_update')->where('status', 'pending')->count();
        
        // Determine mitigation mode
        $mitigationMode = $settings['mitigation_mode'] ?? 'Auto';
        $mitigationStatus = $settings['mitigation_status'] ?? 'On';
        
        // Get unique IPs blocked
        $uniqueBlockedIps = $rules->where('status', 'active')->pluck('target_ip')->unique()->count();
        
        $systemUptimeData = $this->getSystemUptime();
        
        return view('admin.mitigation', [
            // Stat Cards
            'blockedCount' => $uniqueBlockedIps,
            'rateLimitCount' => $rateLimitCount,
            'aclCount' => $aclCount,
            'mitigationMode' => $mitigationMode,
            'mitigationStatus' => $mitigationStatus,
            
            // Rules
            'rules' => $rules,
            
            // Uptime
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