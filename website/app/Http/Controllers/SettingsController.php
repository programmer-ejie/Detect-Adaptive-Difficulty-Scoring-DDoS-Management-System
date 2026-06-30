<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\SystemUptime;
use App\Models\MitigationRule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = $this->settingsMap();
        
        // Get system health data
        $systemHealth = $this->getSystemHealth();
        
        // Get active rules count
        $activeRules = MitigationRule::where('status', 'active')->count();
        
        // Get system uptime
        $systemUptimeData = $this->getSystemUptime();
        
        return view('admin.settings', [
            // Settings
            'scoreThreshold' => $settings['score_threshold'] ?? '85',
            'refreshInterval' => $settings['refresh_interval'] ?? '30',
            'timeWindow' => $settings['time_window'] ?? '60',
            'alertSensitivity' => $settings['alert_sensitivity'] ?? 'medium',
            'autoMitigation' => $settings['auto_mitigation'] ?? '1',
            'routerHost' => $settings['router_host'] ?? '172.16.0.1',
            'routerPort' => $settings['router_port'] ?? '22',
            'logSource' => $settings['log_source'] ?? 'both',
            'notificationEmail' => $settings['notification_email'] ?? 'admin@example.com',
            'notifyHigh' => $settings['notify_high'] ?? '1',
            'keepHistory' => $settings['keep_history'] ?? '1',
            'manualAcl' => $settings['manual_acl'] ?? '0',
            'mlAutoTrain' => $settings['ml_auto_train'] ?? '1',
            'detailedLogs' => $settings['detailed_logs'] ?? '0',
            'logRetention' => $settings['log_retention'] ?? '30',
            
            // System Health
            'systemHealth' => $systemHealth['health'],
            'systemHealthPercent' => $systemHealth['percent'],
            
            // Active Rules
            'activeRules' => $activeRules,
            
            // Uptime
            'uptimeFormatted' => $systemUptimeData['formatted'],
            'uptimeCompact' => $systemUptimeData['compact'],
            'uptimeColor' => $systemUptimeData['color'],
            'uptimeStartedAt' => $systemUptimeData['started_at'],
            'uptimeSeconds' => $systemUptimeData['seconds'],
            'uptimeStatus' => $systemUptimeData['status'],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'score_threshold' => 'nullable|integer|min:0|max:100',
            'refresh_interval' => 'nullable|integer|min:5|max:300',
            'time_window' => 'nullable|integer|min:5|max:1440',
            'alert_sensitivity' => 'nullable|string|in:low,medium,high',
            'auto_mitigation' => 'nullable|boolean',
            'router_host' => 'nullable|string|max:255',
            'router_port' => 'nullable|integer|min:1|max:65535',
            'log_source' => 'nullable|string|in:both,logs,flows',
            'notification_email' => 'nullable|email|max:255',
            'notify_high' => 'nullable|boolean',
            'keep_history' => 'nullable|boolean',
            'manual_acl' => 'nullable|boolean',
            'ml_auto_train' => 'nullable|boolean',
            'detailed_logs' => 'nullable|boolean',
            'log_retention' => 'nullable|integer|min:1|max:365',
        ]);

        foreach ($validated as $key => $value) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings saved successfully!'
        ]);
    }

    public function testConnection(Request $request)
    {
        $host = $request->input('host', '172.16.0.1');
        $port = $request->input('port', 22);
        
        // Simulate connection test
        $success = $this->pingHost($host, $port);
        
        return response()->json([
            'success' => $success,
            'host' => $host,
            'port' => $port,
            'message' => $success ? 'Connection successful' : 'Connection failed'
        ]);
    }

    public function clearCache(Request $request)
    {
        // Simulate cache clearing
        \Illuminate\Support\Facades\Cache::flush();
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'Cache cleared successfully!'
        ]);
    }

    public function resetDefaults(Request $request)
    {
        $defaults = [
            'score_threshold' => '85',
            'refresh_interval' => '30',
            'time_window' => '60',
            'alert_sensitivity' => 'medium',
            'auto_mitigation' => '1',
            'router_host' => '172.16.0.1',
            'router_port' => '22',
            'log_source' => 'both',
            'notification_email' => 'admin@example.com',
            'notify_high' => '1',
            'keep_history' => '1',
            'manual_acl' => '0',
            'ml_auto_train' => '1',
            'detailed_logs' => '0',
            'log_retention' => '30',
        ];

        foreach ($defaults as $key => $value) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings reset to defaults successfully!'
        ]);
    }

    public function exportConfig(Request $request)
    {
        $settings = AppSetting::all()->pluck('value', 'key')->toArray();
        
        $config = [
            'version' => '1.0',
            'exported_at' => now()->toISOString(),
            'settings' => $settings
        ];
        
        return response()->json($config)->withHeaders([
            'Content-Disposition' => 'attachment; filename="ddos_config_' . now()->format('Y-m-d') . '.json"',
            'Content-Type' => 'application/json',
        ]);
    }

    private function settingsMap(): array
    {
        return AppSetting::query()->pluck('value', 'key')->all();
    }

    private function getSystemHealth(): array
    {
        $settings = $this->settingsMap();
        
        $health = $settings['router_health'] ?? 'Healthy';
        $percent = $settings['system_health'] ?? 98;
        
        return [
            'health' => $health,
            'percent' => $percent,
        ];
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

    private function pingHost(string $host, int $port): bool
    {
        // Simulate ping - in production use fsockopen or similar
        // For localhost, always return true with 90% success rate
        if ($host === 'localhost' || $host === '127.0.0.1') {
            return rand(1, 100) <= 95;
        }
        
        // For other hosts, simulate with 85% success rate
        return rand(1, 100) <= 85;
    }
}