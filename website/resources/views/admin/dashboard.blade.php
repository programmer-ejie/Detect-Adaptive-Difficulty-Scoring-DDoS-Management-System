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
  </style>

  <div class="row">
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-wifi-2"></i></div>
          <div class="stat-label">Network Status</div>
          <div class="stat-value">Online</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;"><i class="ti ti-check"></i> Stable</span>
          <div class="stat-description">All routers responding</div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card active-attacks">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
          <div class="stat-label">Active Attacks</div>
          <div class="stat-value">14</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;"><i class="ti ti-flame"></i> Live</span>
          <div class="stat-description">2 high severity spikes</div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card blocked-ips">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-ban"></i></div>
          <div class="stat-label">Blocked IPs</div>
          <div class="stat-value">248</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;"><i class="ti ti-new"></i> 14 new</span>
          <div class="stat-description">Updated latest mitigation</div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card uptime">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-clock"></i></div>
          <div class="stat-label">System Uptime</div>
          <div class="stat-value">99.98%</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;"><i class="ti ti-check-circle"></i> 24/7</span>
          <div class="stat-description">Monitoring active</div>
        </div>
      </div>
    </div>


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
            <div style="font-size: 32px; font-weight: 700; color: #ff6b35; margin-bottom: 8px;">HIGH</div>
            <div style="font-size: 12px; color: #9ca3af; margin-bottom: 16px;">2 active attacks detected</div>
            <div style="padding-top: 8px; border-top: 1px solid #f3f4f6; text-align: left;">
              <div style="font-size: 11px; color: #6b7280; margin-top: 12px; line-height: 1.6;">
                <div>📈 Trend: <span style="color: #ff6b35; font-weight: 600;">Increasing</span></div>
                <div>⏱️ Last: <span style="font-weight: 600;">5 mins ago</span></div>
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
            <div style="font-size: 32px; font-weight: 700; color: #5864FF; margin-bottom: 8px;">98.7%</div>
            <div style="font-size: 12px; color: #9ca3af; margin-bottom: 16px;">Attacks successfully blocked</div>
            <div style="padding-top: 8px; border-top: 1px solid #f3f4f6; text-align: left;">
              <div style="font-size: 11px; color: #6b7280; margin-top: 12px; line-height: 1.6;">
                <div>✓ Today: <span style="color: #5864FF; font-weight: 600;">156 stopped</span></div>
                <div>📊 Avg: <span style="font-weight: 600;">99.2%</span></div>
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
            <div>🔴 Critical: <span style="font-weight: 600;">UDP (32%)</span></div>
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
              <span style="font-size: 14px; font-weight: 700; color: #1f2937;">7.2 Gbps</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6; margin-bottom: 8px;">
              <div class="progress-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: 72%; border-radius: 3px;"></div>
            </div>
            <div style="font-size: 11px; color: #9ca3af; margin-bottom: 12px;">Threshold: 10 Gbps</div>
            <div style="padding-top: 8px; border-top: 1px solid #f3f4f6;">
              <div style="font-size: 11px; color: #6b7280; line-height: 1.6; margin-top: 8px;">
                <div>↓ Min: <span style="font-weight: 600;">2.1 Gbps</span></div>
                <div>↑ Max: <span style="font-weight: 600;">8.9 Gbps</span></div>
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
          <h5 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 20px;">Top 4 Recent Blocked IPs</h5>
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
                <tr style="border-bottom: 1px solid #f3f4f6;">
                  <td style="padding: 12px 16px; font-family: monospace; color: #374151; font-size: 12px;">192.168.1.105</td>
                  <td style="padding: 12px 16px; color: #374151; font-weight: 600; font-size: 12px;">1,248</td>
                  <td style="padding: 12px 16px; color: #6b7280; font-size: 12px;">🇷🇺 Russia</td>
                </tr>
                <tr style="border-bottom: 1px solid #f3f4f6;">
                  <td style="padding: 12px 16px; font-family: monospace; color: #374151; font-size: 12px;">203.45.78.92</td>
                  <td style="padding: 12px 16px; color: #374151; font-weight: 600; font-size: 12px;">956</td>
                  <td style="padding: 12px 16px; color: #6b7280; font-size: 12px;">🇨🇳 China</td>
                </tr>
                <tr style="border-bottom: 1px solid #f3f4f6;">
                  <td style="padding: 12px 16px; font-family: monospace; color: #374151; font-size: 12px;">10.50.20.15</td>
                  <td style="padding: 12px 16px; color: #374151; font-weight: 600; font-size: 12px;">743</td>
                  <td style="padding: 12px 16px; color: #6b7280; font-size: 12px;">🇧🇷 Brazil</td>
                </tr>
                <tr>
                  <td style="padding: 12px 16px; font-family: monospace; color: #374151; font-size: 12px;">155.89.12.47</td>
                  <td style="padding: 12px 16px; color: #374151; font-weight: 600; font-size: 12px;">621</td>
                  <td style="padding: 12px 16px; color: #6b7280; font-size: 12px;">🇮🇳 India</td>
                </tr>
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
          
          <div style="margin-bottom: 24px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <span style="font-weight: 600; color: #374151; font-size: 14px;">Router A</span>
              </div>
              <span class="badge" style="background: #E6F0FF; color: #5864FF; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 4px;"><i class="ti ti-check"></i> Healthy</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6;">
              <div class="progress-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: 92%; border-radius: 3px;"></div>
            </div>
            <small style="color: #9ca3af; font-size: 11px; margin-top: 4px; display: block;">92% utilization</small>
          </div>
          
          <div style="margin-bottom: 24px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span style="font-weight: 600; color: #374151; font-size: 14px;">CPU Utilization</span>
              <span style="color: #6b7280; font-weight: 600; font-size: 13px;">64%</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6;">
              <div class="progress-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: 64%; border-radius: 3px;"></div>
            </div>
          </div>
          
          <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span style="font-weight: 600; color: #374151; font-size: 14px;">Memory Usage</span>
              <span style="color: #6b7280; font-weight: 600; font-size: 13px;">51%</span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 3px; background: #f3f4f6;">
              <div class="progress-bar" style="background: linear-gradient(90deg, #5864FF, #4356E6); width: 51%; border-radius: 3px;"></div>
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
                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;">
                  <td style="padding: 16px; color: #374151; font-weight: 500; font-size: 13px;">UDP amplification burst</td>
                  <td style="padding: 16px; color: #9ca3af; font-size: 13px;">2 min ago</td>
                  <td style="padding: 16px; font-family: monospace; color: #6b7280; font-size: 12px;">172.16.0.12</td>
                  <td style="padding: 16px;" align="center"><span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; text-align: center;">Critical</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;">
                  <td style="padding: 16px; color: #374151; font-weight: 500; font-size: 13px;">HTTP flood spike</td>
                  <td style="padding: 16px; color: #9ca3af; font-size: 13px;">7 min ago</td>
                  <td style="padding: 16px; font-family: monospace; color: #6b7280; font-size: 12px;">172.16.0.12</td>
                  <td style="padding: 16px;" align="center"><span style="background: #fecaca; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">High</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;">
                  <td style="padding: 16px; color: #374151; font-weight: 500; font-size: 13px;">Suspicious SYN scan</td>
                  <td style="padding: 16px; color: #9ca3af; font-size: 13px;">16 min ago</td>
                  <td style="padding: 16px; font-family: monospace; color: #6b7280; font-size: 12px;">172.16.0.18</td>
                  <td style="padding: 16px;" align="center"><span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; text-align: center;">Medium</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;">
                  <td style="padding: 16px; color: #374151; font-weight: 500; font-size: 13px;">DNS query flood</td>
                  <td style="padding: 16px; color: #9ca3af; font-size: 13px;">22 min ago</td>
                  <td style="padding: 16px; font-family: monospace; color: #6b7280; font-size: 12px;">172.16.0.25</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fecaca; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">High</span></td>
                </tr>
                <tr style="transition: background-color 0.2s;">
                  <td style="padding: 16px; color: #374151; font-weight: 500; font-size: 13px;">Port scanning detected</td>
                  <td style="padding: 16px; color: #9ca3af; font-size: 13px;">35 min ago</td>
                  <td style="padding: 16px; font-family: monospace; color: #6b7280; font-size: 12px;">172.16.0.33</td>
                  <td style="padding: 16px;" align="center"><span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; text-align: center;">Low</span></td>
                </tr>
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
      // Traffic Chart
      new ApexCharts(document.querySelector('#traffic-chart'), {
        chart: { height: 450, type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        colors: ['#17c671', '#ff4d4f', '#1890ff'],
        series: [
          { name: 'Normal Traffic', data: [120, 132, 128, 141, 155, 148, 160, 170, 163, 158, 166, 174] },
          { name: 'Attack Traffic', data: [12, 18, 24, 14, 32, 48, 46, 58, 42, 36, 28, 20] },
          { name: 'Threshold', data: [70, 70, 70, 70, 70, 70, 70, 70, 70, 70, 70, 70] }
        ],
        stroke: { curve: 'smooth', width: [2, 2, 1.5] },
        xaxis: { categories: ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'] },
        grid: { strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'right' }
      }).render();

      // Attack Types Distribution Chart
      new ApexCharts(document.querySelector('#attack-types-chart'), {
        chart: { height: 150, type: 'donut', toolbar: { show: false } },
        colors: ['#5864FF', '#4356E6', '#FF6B6B', '#FFA500', '#6D28D9'],
        series: [32, 28, 18, 15, 7],
        labels: ['UDP', 'HTTP', 'SYN', 'DNS', 'Others'],
        legend: { show: false },
        plotOptions: { pie: { donut: { size: '70%' } } }
      }).render();
    });
  </script>
@endpush
