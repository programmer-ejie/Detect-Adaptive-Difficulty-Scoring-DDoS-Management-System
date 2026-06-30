@extends('admin.layout', ['page' => 'dashboard'])

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
  <style>
    .stat-card {
      background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
      border: 1px solid rgba(0, 0, 0, 0.05);
      border-radius: 12px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      position: relative;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
      border-color: rgba(0, 0, 0, 0.1);
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #5864FF, #4356E6);
    }

    .stat-card.active-attacks::before {
      background: linear-gradient(90deg, #5864FF, #4356E6);
    }

    .stat-card.blocked-ips::before {
      background: linear-gradient(90deg, #5864FF, #4356E6);
    }

    .stat-card.uptime::before {
      background: linear-gradient(90deg, #5864FF, #4356E6);
    }

    .stat-card .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 12px;
      background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%);
      color: white;
    }

    .stat-card.active-attacks .stat-icon {
      background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%);
    }

    .stat-card.blocked-ips .stat-icon {
      background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%);
    }

    .stat-card.uptime .stat-icon {
      background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%);
    }

    .stat-label {
      font-size: 12px;
      font-weight: 600;
      color: #8c92a4;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 8px;
    }

    .stat-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 12px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 600;
    }

    .stat-description {
      font-size: 12px;
      color: #9ca3af;
      margin-top: 10px;
    }

    .severity-critical {
      background: #fee2e2;
      color: #991b1b;
    }

    .severity-high {
      background: #fecaca;
      color: #7f1d1d;
    }

    .severity-medium {
      background: #fef3c7;
      color: #92400e;
    }

    .severity-low {
      background: #dbeafe;
      color: #1e40af;
    }

    .threat-critical {
      color: #dc2626;
    }

    .threat-high {
      color: #f59e0b;
    }

    .threat-medium {
      color: #eab308;
    }

    .threat-low {
      color: #10b981;
    }
  </style>

  <div class="row">
    <!-- Network Status Card -->
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-wifi-2"></i></div>
          <div class="stat-label">Network Status</div>
          <div class="stat-value">{{ $routerHealth ?? 'Online' }}</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;">
            <i class="ti ti-check"></i> 
            {{ $routerHealth === 'Healthy' ? 'Stable' : 'Monitoring' }}
          </span>
          <div class="stat-description">{{ $routerHost ?? 'All routers responding' }}</div>
        </div>
      </div>
    </div>

    <!-- Active Attacks Card -->
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card active-attacks">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
          <div class="stat-label">Active Attacks</div>
          <div class="stat-value">{{ $activeAttacks ?? 0 }}</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;">
            <i class="ti ti-flame"></i> 
            {{ $activeAttacks > 0 ? 'Live' : 'None' }}
          </span>
          <div class="stat-description">{{ $activeAttacks > 0 ? $activeAttacks . ' active attacks' : 'No active attacks' }}</div>
        </div>
      </div>
    </div>

    <!-- Blocked IPs Card -->
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card blocked-ips">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-ban"></i></div>
          <div class="stat-label">Blocked IPs</div>
          <div class="stat-value">{{ number_format($blockedIps ?? 0) }}</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;">
            <i class="ti ti-new"></i> 
            {{ number_format($newBlocks ?? 0) }} new
          </span>
          <div class="stat-description">{{ ($newBlocks ?? 0) > 0 ? 'New blocks today' : 'No new blocks' }}</div>
        </div>
      </div>
    </div>

    
  <!-- System Uptime Card -->
<div class="col-md-6 col-xl-3">
    <div class="card stat-card uptime">
        <div class="card-body p-4">
            <div class="stat-icon"><i class="ti ti-clock"></i></div>
            <div class="stat-label">System Uptime</div>
            <div class="stat-value" id="uptime-display" style="color: {{ $uptimeColor ?? '#5864FF' }}; font-size: 32px; font-variant-numeric: tabular-nums;">
                {{ $uptimeFormatted ?? '00:00:00' }}
            </div>
            <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;">
                <i class="ti ti-check-circle"></i> Running
            </span>
            <div class="stat-description" id="uptime-started">
                {{ isset($uptimeStartedAt) ? $uptimeStartedAt->format('M d, H:i') : 'N/A' }}
            </div>
        </div>
    </div>
</div>

    <!-- Traffic Chart -->
    <div class="col-12">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
              <h5 class="mb-1" style="font-size: 16px; font-weight: 700; color: #1f2937;">Packets Per Second</h5>
              <p class="mb-0" style="font-size: 12px; color: #9ca3af;">Real-time traffic analysis</p>
            </div>
            <div class="btn-group" role="group">
              <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 6px; font-size: 11px; font-weight: 600; padding: 6px 12px; transition: all 0.2s;">15m</button>
              <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 6px; font-size: 11px; font-weight: 600; padding: 6px 12px; transition: all 0.2s; margin: 0 4px;">1h</button>
              <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 6px; font-size: 11px; font-weight: 600; padding: 6px 12px; transition: all 0.2s;">6h</button>
              <button class="btn btn-sm" style="border: 1px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 6px; font-size: 11px; font-weight: 600; padding: 6px 12px; transition: all 0.2s; margin-left: 4px;">24h</button>
            </div>
          </div>
          <div id="traffic-chart"></div>
        </div>
      </div>
    </div>

    <!-- Threat Level Card -->
    <div class="col-md-6 col-xl-3">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); overflow: hidden; height: 280px;">
        <div class="card-body p-4">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h5 style="font-size: 14px; font-weight: 700; color: #1f2937; margin: 0;">Threat Level</h5>
            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;"><i class="ti ti-alert-triangle"></i></div>
          </div>
          <div style="text-align: center; padding: 12px 0;">
            @php
              $threatColor = match($threatLevel ?? 'LOW') {
                'CRITICAL' => '#dc2626',
                'HIGH' => '#f59e0b',
                'MEDIUM' => '#eab308',
                default => '#10b981'
              };
            @endphp
            <div style="font-size: 32px; font-weight: 700; color: {{ $threatColor }}; margin-bottom: 8px;">
              {{ $threatLevel ?? 'LOW' }}
            </div>
            <div style="font-size: 12px; color: #9ca3af; margin-bottom: 16px;">
              {{ ($activeAttacks ?? 0) > 0 ? ($activeAttacks . ' active attacks detected') : 'No active threats' }}
            </div>
            <div style="padding-top: 8px; border-top: 1px solid #f3f4f6; text-align: left;">
              <div style="font-size: 11px; color: #6b7280; margin-top: 12px; line-height: 1.6;">
                <div>📊 Score: <span style="font-weight: 600;">{{ $threatScore ?? 0 }}/100</span></div>
                <div>⏱️ Status: <span style="font-weight: 600;">{{ ($activeAttacks ?? 0) > 0 ? 'Active' : 'Clear' }}</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mitigation Success Rate -->
    <div class="col-md-6 col-xl-3">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); overflow: hidden; height: 280px;">
        <div class="card-body p-4">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h5 style="font-size: 14px; font-weight: 700; color: #1f2937; margin: 0;">Mitigation Rate</h5>
            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #5864FF 0%, #4356E6 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;"><i class="ti ti-shield-check"></i></div>
          </div>
          <div style="text-align: center; padding: 12px 0;">
            <div style="font-size: 32px; font-weight: 700; color: #5864FF; margin-bottom: 8px;">
              {{ number_format($mitigationRate ?? 0, 1) }}%
            </div>
            <div style="font-size: 12px; color: #9ca3af; margin-bottom: 16px;">
              {{ ($mitigationRate ?? 0) > 80 ? 'Attacks successfully blocked' : 'Monitoring in progress' }}
            </div>
            <div style="padding-top: 8px; border-top: 1px solid #f3f4f6; text-align: left;">
              <div style="font-size: 11px; color: #6b7280; margin-top: 12px; line-height: 1.6;">
                <div>✓ Resolved: <span style="color: #5864FF; font-weight: 600;">{{ number_format($resolvedAlerts ?? 0) }}</span></div>
                <div>📊 Total: <span style="font-weight: 600;">{{ number_format($totalAlerts ?? 0) }}</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Attack Types Distribution -->
    <div class="col-md-6 col-xl-3">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); height: 280px;">
        <div class="card-body p-4">
          <h5 style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Attack Types</h5>
          <div id="attack-types-chart" style="margin-bottom: 12px;"></div>
          <div style="padding-top: 12px; border-top: 1px solid #f3f4f6; font-size: 11px; color: #6b7280;">
            <div style="line-height: 1.6;">
              <div>🔴 Top: <span style="font-weight: 600;">{{ $topAttackTypeLabel ?? 'No data' }} ({{ $topAttackTypePercent ?? 0 }}%)</span></div>
              <div>⏰ Period: <span style="font-weight: 600;">Last 24h</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bandwidth Usage -->
    <div class="col-md-6 col-xl-3">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); height: 280px;">
        <div class="card-body p-4">
          <h5 style="font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Bandwidth Usage</h5>
          <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <span style="font-size: 12px; color: #6b7280; font-weight: 600;">Current</span>
              <span style="font-size: 14px; font-weight: 700; color: #1f2937;">
                {{ number_format($currentBandwidth ?? 0, 1) }} Gbps
              </span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6; margin-bottom: 8px;">
              @php
                $bandwidthPercent = ($trafficThresholdLast ?? 1) > 0 ? min(100, (($currentBandwidth ?? 0) / ($trafficThresholdLast ?? 1)) * 100) : 0;
              @endphp
              <div class="progress-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ $bandwidthPercent }}%; border-radius: 3px;"></div>
            </div>
            <div style="font-size: 11px; color: #9ca3af; margin-bottom: 12px;">
              Threshold: {{ number_format($trafficThresholdLast ?? 0, 1) }} Gbps
            </div>
            <div style="padding-top: 8px; border-top: 1px solid #f3f4f6;">
              <div style="font-size: 11px; color: #6b7280; line-height: 1.6; margin-top: 8px;">
                <div>📊 Usage: <span style="font-weight: 600;">{{ number_format($bandwidthPercent, 0) }}%</span></div>
                <div>⚡ Status: <span style="font-weight: 600; color: {{ $bandwidthPercent > 80 ? '#dc2626' : ($bandwidthPercent > 60 ? '#f59e0b' : '#10b981') }};">
                  {{ $bandwidthPercent > 80 ? 'High' : ($bandwidthPercent > 60 ? 'Elevated' : 'Normal') }}
                </span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Blocked IPs -->
    <div class="col-md-12 col-xl-8">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
        <div class="card-body p-4">
          <h5 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Top Blocked IPs</h5>
          <div class="table-responsive">
            <table class="table" style="border: none; margin-bottom: 0;">
              <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase;">IP Address</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase;">Blocks</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase;">Country</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topBlockedIps as $ip)
                  <tr style="border-bottom: {{ !$loop->last ? '1px solid #f3f4f6' : 'none' }};">
                    <td style="padding: 12px 16px; font-family: monospace; color: #374151; font-size: 12px;">
                      {{ $ip->target_ip ?? 'No data' }}
                    </td>
                    <td style="padding: 12px 16px; color: #374151; font-weight: 600; font-size: 12px;">
                      {{ number_format($ip->blocks ?? 0) }}
                    </td>
                    <td style="padding: 12px 16px; color: #6b7280; font-size: 12px;">
                      {{ $ip->country ?? 'Unknown' }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" style="padding: 20px; text-align: center; color: #9ca3af; font-size: 13px;">
                      No blocked IPs recorded yet
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- System Health -->
   <div class="col-md-12 col-xl-4">
    <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
        <div class="card-body p-4">
            <h5 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 24px;">System Health</h5>
            
            <!-- Router Status -->
            <div style="margin-bottom: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span style="font-weight: 600; color: #374151; font-size: 14px;">Router</span>
                    </div>
                    <span class="badge" id="router-status" style="background: {{ ($routerHealth ?? 'Healthy') === 'Healthy' ? '#E6F0FF' : '#fee2e2' }}; color: {{ ($routerHealth ?? 'Healthy') === 'Healthy' ? '#5864FF' : '#dc2626' }}; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 4px;">
                        <i class="ti ti-{{ ($routerHealth ?? 'Healthy') === 'Healthy' ? 'check' : 'alert-triangle' }}"></i> 
                        {{ $routerHealth ?? 'Healthy' }}
                    </span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6;">
                    <div class="progress-bar" id="router-utilization-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ min(100, $routerUtilization ?? 0) }}%; border-radius: 3px; transition: width 0.5s ease;"></div>
                </div>
                <small style="color: #9ca3af; font-size: 11px; margin-top: 4px; display: block;">
                    <span id="router-utilization-text">{{ number_format($routerUtilization ?? 0, 0) }}%</span> utilization
                </small>
            </div>
            
            <!-- CPU Utilization -->
            <div style="margin-bottom: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-weight: 600; color: #374151; font-size: 14px;">CPU Utilization</span>
                    <span id="cpu-text" style="color: #6b7280; font-weight: 600; font-size: 13px;">{{ number_format($cpuUtilization ?? 0, 0) }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6;">
                    <div class="progress-bar" id="cpu-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ min(100, $cpuUtilization ?? 0) }}%; border-radius: 3px; transition: width 0.5s ease;"></div>
                </div>
            </div>
            
            <!-- Memory Usage -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-weight: 600; color: #374151; font-size: 14px;">Memory Usage</span>
                    <span id="memory-text" style="color: #6b7280; font-weight: 600; font-size: 13px;">{{ number_format($memoryUsage ?? 0, 0) }}%</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6;">
                    <div class="progress-bar" id="memory-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ min(100, $memoryUsage ?? 0) }}%; border-radius: 3px; transition: width 0.5s ease;"></div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Recent Alerts -->
    <div class="col-12">
      <div class="card" style="border: 1px solid rgba(0, 0, 0, 0.05); border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
        <div class="card-body p-4">
          <h5 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Recent Alerts</h5>
          <div class="table-responsive">
            <table class="table" style="border: none;">
              <thead>
                <tr style="border-bottom: 1px solid #e5e7eb;">
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">Attack Type</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">Time</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">Target IP</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Severity</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentAlerts as $alert)
                  @php
                    $severityClass = match(strtolower($alert->severity ?? 'low')) {
                      'critical' => 'severity-critical',
                      'high' => 'severity-high',
                      'medium' => 'severity-medium',
                      default => 'severity-low'
                    };
                  @endphp
                  <tr style="border-bottom: {{ !$loop->last ? '1px solid #f3f4f6' : 'none' }}; transition: background-color 0.2s;">
                    <td style="padding: 16px; color: #374151; font-weight: 500; font-size: 13px;">
                      {{ $alert->attack_type ?? 'No data' }}
                    </td>
                    <td style="padding: 16px; color: #9ca3af; font-size: 13px;">
                      {{ $alert->occurred_at ? $alert->occurred_at->diffForHumans() : 'N/A' }}
                    </td>
                    <td style="padding: 16px; font-family: monospace; color: #6b7280; font-size: 12px;">
                      {{ $alert->target_ip ?? '—' }}
                    </td>
                    <td style="padding: 16px;" align="center">
                      <span class="{{ $severityClass }}" style="padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; text-align: center;">
                        {{ ucfirst($alert->severity ?? 'Low') }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #9ca3af; font-size: 13px;">
                      No alerts recorded yet
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Get data from PHP
      const trafficData = @json($trafficSeries ?? []);
      const attackTypesData = @json($attackTypeValues ?? []);
      const attackTypesLabels = @json($attackTypeLabels ?? []);

      // Traffic Chart
      const trafficChartOptions = {
        chart: { 
          height: 450, 
          type: 'area', 
          toolbar: { show: false }, 
          zoom: { enabled: false } 
        },
        dataLabels: { enabled: false },
        colors: ['#17c671', '#ff4d4f', '#1890ff'],
        series: [
          { 
            name: 'Normal Traffic', 
            data: trafficData.normal && trafficData.normal.length > 0 ? trafficData.normal : Array(12).fill(0) 
          },
          { 
            name: 'Attack Traffic', 
            data: trafficData.attack && trafficData.attack.length > 0 ? trafficData.attack : Array(12).fill(0) 
          },
          { 
            name: 'Threshold', 
            data: trafficData.threshold && trafficData.threshold.length > 0 ? trafficData.threshold : Array(12).fill(0) 
          }
        ],
        stroke: { curve: 'smooth', width: [2, 2, 1.5] },
        xaxis: { 
          categories: trafficData.categories && trafficData.categories.length > 0 ? trafficData.categories : Array(12).fill('00:00') 
        },
        grid: { strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'right' }
      };

      const trafficChart = new ApexCharts(document.querySelector('#traffic-chart'), trafficChartOptions);
      trafficChart.render();

      // Attack Types Distribution Chart
      const attackTypesOptions = {
        chart: { 
          height: 150, 
          type: 'donut', 
          toolbar: { show: false } 
        },
        colors: ['#5864FF', '#4356E6', '#FF6B6B', '#FFA500', '#6D28D9'],
        series: attackTypesData && attackTypesData.length > 0 ? attackTypesData : [0],
        labels: attackTypesLabels && attackTypesLabels.length > 0 ? attackTypesLabels : ['No data'],
        legend: { show: false },
        plotOptions: { 
          pie: { 
            donut: { 
              size: '70%' 
            } 
          } 
        }
      };

      const attackTypesChart = new ApexCharts(document.querySelector('#attack-types-chart'), attackTypesOptions);
            attackTypesChart.render();
          });

          
      
          document.addEventListener('DOMContentLoaded', function () {
              const uptimeDisplay = document.getElementById('uptime-display');
              const uptimeStarted = document.getElementById('uptime-started');
              
              if (!uptimeDisplay) return;
              
              // Function to format seconds to HH:MM:SS
              function formatTime(seconds) {
                  const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
                  const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
                  const s = String(seconds % 60).padStart(2, '0');
                  return `${h}:${m}:${s}`;
              }
              
              // Function to fetch uptime from server
              function fetchUptime() {
                  fetch('/api/uptime')
                      .then(response => response.json())
                      .then(data => {
                          // Update the display with data from database
                          uptimeDisplay.textContent = data.formatted;
                          
                          // Update the started timestamp if available
                          if (uptimeStarted && data.started_at) {
                              uptimeStarted.textContent = data.started_at;
                          }
                      })
                      .catch(error => {
                          console.log('Uptime fetch error:', error);
                          // If fetch fails, increment locally as fallback
                          let currentText = uptimeDisplay.textContent;
                          if (currentText && currentText !== '00:00:00') {
                              const parts = currentText.split(':');
                              if (parts.length === 3) {
                                  let seconds = parseInt(parts[0]) * 3600 + 
                                              parseInt(parts[1]) * 60 + 
                                              parseInt(parts[2]) + 1;
                                  uptimeDisplay.textContent = formatTime(seconds);
                              }
                          }
                      });
              }
              
              // Fetch immediately on page load
              fetchUptime();
              
              // Then fetch every second
              const interval = setInterval(fetchUptime, 1000);
              
              // Clean up interval when page is hidden to save resources
              document.addEventListener('visibilitychange', function() {
                  if (document.hidden) {
                      clearInterval(interval);
                  }
              });
          });

          function updateSystemHealth() {
          fetch('/api/system-health')
              .then(response => response.json())
              .then(data => {
                  // Update Router
                  const routerStatus = document.getElementById('router-status');
                  if (routerStatus) {
                      const isHealthy = data.router_health === 'Healthy';
                      routerStatus.style.background = isHealthy ? '#E6F0FF' : '#fee2e2';
                      routerStatus.style.color = isHealthy ? '#5864FF' : '#dc2626';
                      routerStatus.innerHTML = `<i class="ti ti-${isHealthy ? 'check' : 'alert-triangle'}"></i> ${data.router_health}`;
                  }
                  
                  // Update Router Utilization
                  const routerBar = document.getElementById('router-utilization-bar');
                  const routerText = document.getElementById('router-utilization-text');
                  if (routerBar) {
                      const percent = Math.min(100, data.router_utilization || 0);
                      routerBar.style.width = percent + '%';
                      if (routerText) routerText.textContent = percent + '%';
                  }
                  
                  // Update CPU
                  const cpuBar = document.getElementById('cpu-bar');
                  const cpuText = document.getElementById('cpu-text');
                  if (cpuBar) {
                      const percent = Math.min(100, data.cpu_utilization || 0);
                      cpuBar.style.width = percent + '%';
                      if (cpuText) cpuText.textContent = percent + '%';
                  }
                  
                  // Update Memory
                  const memoryBar = document.getElementById('memory-bar');
                  const memoryText = document.getElementById('memory-text');
                  if (memoryBar) {
                      const percent = Math.min(100, data.memory_usage || 0);
                      memoryBar.style.width = percent + '%';
                      if (memoryText) memoryText.textContent = percent + '%';
                  }
              })
              .catch(error => console.log('System health fetch error:', error));
      }

      // Update system health every 5 seconds (less frequent than uptime)
      setInterval(updateSystemHealth, 5000);


  </script>
@endpush