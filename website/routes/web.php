<?php

use App\Helpers\SystemMetrics;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitigationController;
use App\Http\Controllers\SettingsController;
use App\Models\AppSetting;
use App\Models\SystemUptime;
use Illuminate\Support\Facades\Route;


// Home Route - Redirects to dashboard if authenticated, otherwise shows landing page
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : view('index');
})->name('home');

// Login Routes
Route::get('/login', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : view('index');
})->name('login');

Route::post('/login', [AuthController::class, 'store'])->name('login.perform');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis');
        Route::get('/alert', [AlertController::class, 'index'])->name('alert');
        Route::get('/mitigation', [MitigationController::class, 'index'])->name('mitigation');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

        // =============================================
        // Settings API Routes
        // =============================================
        Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-connection', [SettingsController::class, 'testConnection'])->name('settings.test-connection');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/reset-defaults', [SettingsController::class, 'resetDefaults'])->name('settings.reset-defaults');
        Route::get('/settings/export-config', [SettingsController::class, 'exportConfig'])->name('settings.export-config');
    });
});

// =============================================
// API Routes for Real-time Updates
// =============================================

// System Health API with REAL system metrics
Route::get('/api/system-health', function () {
    try {
        $metrics = SystemMetrics::getAllMetrics();
        
        return response()->json([
            'router_health' => $metrics['server_health'],
            'router_utilization' => round($metrics['cpu_utilization'], 0),
            'cpu_utilization' => round($metrics['cpu_utilization'], 0),
            'memory_usage' => round($metrics['memory_usage'], 0),
            'disk_usage' => round($metrics['disk_usage'], 0),
            'load_average' => $metrics['load_average'],
            'uptime_seconds' => $metrics['uptime_seconds'],
            'timestamp' => $metrics['timestamp'],
        ]);
    } catch (\Exception $e) {
        // Fallback to database values if system metrics fail
        $settings = AppSetting::query()->pluck('value', 'key')->all();
        
        return response()->json([
            'router_health' => $settings['router_health'] ?? 'Healthy',
            'router_utilization' => (int) ($settings['router_utilization'] ?? 92),
            'cpu_utilization' => (int) ($settings['cpu_utilization'] ?? 64),
            'memory_usage' => (int) ($settings['memory_usage'] ?? 51),
            'disk_usage' => 45,
            'load_average' => ['1min' => 0, '5min' => 0, '15min' => 0],
            'uptime_seconds' => 0,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
})->name('api.system-health');

// Uptime API with REAL system uptime
Route::get('/api/uptime', function () {
    $uptime = SystemUptime::first();
    $realUptime = SystemMetrics::getSystemUptime();
    
    // Use real uptime if available, fallback to database
    $uptimeSeconds = $realUptime > 0 ? $realUptime : ($uptime?->getUptimeSeconds() ?? 0);
    
    // Format time - Human readable
    $days = floor($uptimeSeconds / 86400);
    $hours = floor(($uptimeSeconds % 86400) / 3600);
    $minutes = floor(($uptimeSeconds % 3600) / 60);
    $secs = $uptimeSeconds % 60;
    
    $parts = [];
    if ($days > 0) $parts[] = $days . 'd';
    if ($hours > 0 || $days > 0) $parts[] = str_pad($hours, 2, '0', STR_PAD_LEFT) . 'h';
    if ($minutes > 0 || $hours > 0 || $days > 0) $parts[] = str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';
    $parts[] = str_pad($secs, 2, '0', STR_PAD_LEFT) . 's';
    $formatted = implode(' ', $parts);
    
    // Compact format for badge
    $compactParts = [];
    if ($days > 0) $compactParts[] = $days . 'd';
    if ($hours > 0) $compactParts[] = $hours . 'h';
    if ($minutes > 0) $compactParts[] = $minutes . 'm';
    $compact = !empty($compactParts) ? implode(' ', $compactParts) : $secs . 's';
    
    return response()->json([
        'seconds' => $uptimeSeconds,
        'formatted' => $formatted,
        'compact' => $compact,
        'started_at' => now()->subSeconds($uptimeSeconds)->format('M d, H:i:s'),
    ]);
})->name('api.uptime');