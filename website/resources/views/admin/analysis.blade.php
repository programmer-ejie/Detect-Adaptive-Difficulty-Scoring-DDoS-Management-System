@extends('admin.layout', ['page' => 'analysis'])

@section('title', 'Analysis')
@section('page-title', 'Analysis')

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

    .severity-critical { background: #fee2e2; color: #991b1b; }
    .severity-high { background: #fecaca; color: #7f1d1d; }
    .severity-medium { background: #fef3c7; color: #92400e; }
    .severity-low { background: #dbeafe; color: #1e40af; }
    .severity-normal { background: #dcfce7; color: #166534; }
  </style>

  <div class="row">
    <!-- Analysis Stat Cards -->
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-git-branch"></i></div>
          <div class="stat-label">Total Flows</div>
          <div class="stat-value">{{ number_format($totalFlows ?? 0) }}</div>
          <span class="stat-badge" style="background: {{ ($totalFlowsChange ?? 0) > 0 ? '#E6F0FF' : '#fee2e2' }}; color: {{ ($totalFlowsChange ?? 0) > 0 ? '#5864FF' : '#dc2626' }};">
            {{ ($totalFlowsChange ?? 0) > 0 ? '↑' : '↓' }} {{ abs($totalFlowsChange ?? 0) }}%
          </span>
          <div class="stat-description">Compared to previous period</div>
        </div>
      </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
          <div class="stat-label">Attack Flows</div>
          <div class="stat-value">{{ number_format($attackFlows ?? 0) }}</div>
          <span class="stat-badge" style="background: {{ ($attackFlows ?? 0) > 100 ? '#fee2e2' : '#fef3c7' }}; color: {{ ($attackFlows ?? 0) > 100 ? '#7f1d1d' : '#92400e' }};">
            {{ ($attackFlows ?? 0) > 100 ? 'Critical' : 'Elevated' }}
          </span>
          <div class="stat-description">{{ ($attackFlows ?? 0) > 0 ? 'Requires attention' : 'No attack flows' }}</div>
        </div>
      </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-ban"></i></div>
          <div class="stat-label">Anomalies</div>
          <div class="stat-value">{{ number_format($anomalies ?? 0) }}</div>
          <span class="stat-badge" style="background: {{ ($anomalies ?? 0) > 10 ? '#fef3c7' : '#dcfce7' }}; color: {{ ($anomalies ?? 0) > 10 ? '#92400e' : '#166534' }};">
            {{ ($anomalies ?? 0) > 10 ? 'Medium' : 'Low' }}
          </span>
          <div class="stat-description">{{ ($anomalies ?? 0) > 0 ? 'Flagged for review' : 'No anomalies' }}</div>
        </div>
      </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-clock"></i></div>
          <div class="stat-label">Detection Rate</div>
          <div class="stat-value">{{ number_format($detectionRate ?? 0, 1) }}%</div>
          <span class="stat-badge" style="background: {{ ($detectionRate ?? 0) > 95 ? '#dcfce7' : '#fef3c7' }}; color: {{ ($detectionRate ?? 0) > 95 ? '#166534' : '#92400e' }};">
            {{ ($detectionRate ?? 0) > 95 ? 'Excellent' : 'Needs Improvement' }}
          </span>
          <div class="stat-description">Last 24 hours</div>
        </div>
      </div>
    </div>

    <!-- Traffic Analysis Chart -->
    <div class="col-md-12 col-xl-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Traffic Analysis</h5>
        <span class="badge bg-light-primary border border-primary">Flow records</span>
      </div>
      <div class="card">
        <div class="card-body">
          <div id="analysis-chart"></div>
        </div>
      </div>
    </div>

    <!-- Response Metrics -->
    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">Response Metrics</h5>
      <div class="card">
        <div class="card-body">
          <div class="mb-3">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <h6 class="text-muted">Avg Response Time</h6>
              <span style="color: #5864FF; font-weight: 600;">{{ number_format($avgResponseTime ?? 0, 1) }}s</span>
            </div>
            <div style="height: 6px; background: #E6F0FF; border-radius: 3px; overflow: hidden;">
              @php $avgPercent = min(100, ($avgResponseTime ?? 0) / 10 * 100); @endphp
              <div style="height: 100%; background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ $avgPercent }}%;"></div>
            </div>
            <p class="text-muted text-sm mt-2 mb-0">{{ ($avgResponseTime ?? 0) < 5 ? 'Below SLA target' : 'Above SLA target' }}</p>
          </div>
          
          <div class="mb-3">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <h6 class="text-muted">Detection Success</h6>
              <span style="color: #5864FF; font-weight: 600;">{{ number_format($detectionSuccess ?? 0, 1) }}%</span>
            </div>
            <div style="height: 6px; background: #E6F0FF; border-radius: 3px; overflow: hidden;">
              <div style="height: 100%; background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ min(100, $detectionSuccess ?? 0) }}%;"></div>
            </div>
            <p class="text-muted text-sm mt-2 mb-0">{{ ($detectionSuccess ?? 0) > 95 ? 'Excellent accuracy' : 'Needs improvement' }}</p>
          </div>
          
          <div class="mb-3">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <h6 class="text-muted">Mean Time to Resolution</h6>
              <span style="color: #5864FF; font-weight: 600;">{{ number_format($meanTimeToResolution ?? 0, 1) }}m</span>
            </div>
            <div style="height: 6px; background: #E6F0FF; border-radius: 3px; overflow: hidden;">
              @php $mttrPercent = min(100, ($meanTimeToResolution ?? 0) / 10 * 100); @endphp
              <div style="height: 100%; background: linear-gradient(90deg, #5864FF, #4356E6); width: {{ $mttrPercent }}%;"></div>
            </div>
            <p class="text-muted text-sm mt-2 mb-0">{{ ($meanTimeToResolution ?? 0) < 10 ? 'Within response window' : 'Exceeds response window' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Top 5 Sources -->
    <div class="col-md-12 col-xl-8">
      <h5 class="mb-3">Top 5 Sources</h5>
      <div class="card tbl-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
              <thead>
                <tr>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">SOURCE IP</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">PACKETS</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">PROTOCOL</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">ANOMALY SCORE</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">CLASSIFICATION</th>
                </tr>
              </thead>
              <tbody>
                @forelse($topSources as $source)
                  <tr>
                    <td style="padding: 16px; font-family: monospace;">{{ $source->source_ip ?? 'Unknown' }}</td>
                    <td style="padding: 16px;">{{ number_format($source->count ?? 0) }}</td>
                    <td style="padding: 16px;">{{ $source->protocol ?? 'Unknown' }}</td>
                    <td style="padding: 16px;">
                      <span style="color: {{ ($source->max_score ?? 0) > 80 ? '#7f1d1d' : (($source->max_score ?? 0) > 50 ? '#b45309' : '#16a34a') }}; font-weight: 600;">
                        {{ number_format($source->max_score ?? 0, 0) }}%
                      </span>
                    </td>
                    <td style="padding: 16px; text-align: center;" align="center">
                      @php
                        $score = $source->max_score ?? 0;
                        $class = $score > 80 ? 'Attack' : ($score > 50 ? 'Suspicious' : 'Normal');
                        $bgColor = $score > 80 ? '#fee2e2' : ($score > 50 ? '#fef3c7' : '#dcfce7');
                        $textColor = $score > 80 ? '#7f1d1d' : ($score > 50 ? '#92400e' : '#166534');
                      @endphp
                      <span style="background: {{ $bgColor }}; color: {{ $textColor }}; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">
                        {{ $class }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: #9ca3af;">No data available</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Protocol Distribution -->
    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">Protocol Distribution</h5>
      <div class="card">
        <div class="card-body">
          <div id="protocol-chart"></div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-12">
      <h5 class="mb-3">Recent Activity</h5>
      <div class="card tbl-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0" id="recent-activity-table">
              <thead>
                <tr>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">TIMESTAMP</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">EVENT</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">SOURCE</th>
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">SEVERITY</th>
                </tr>
              </thead>
              <tbody id="activity-tbody">
                @forelse($recentActivities as $activity)
                  <tr class="activity-row">
                    <td style="padding: 16px;">{{ $activity->occurred_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
                    <td style="padding: 16px;">{{ $activity->attack_type ?? 'Unknown' }}</td>
                    <td style="padding: 16px; font-family: monospace;">{{ $activity->source_ip ?? 'Unknown' }}</td>
                    <td style="padding: 16px; text-align: center;" align="center">
                      @php
                        $severity = strtolower($activity->severity ?? 'low');
                        $severityClass = match($severity) {
                          'critical' => 'severity-critical',
                          'high' => 'severity-high',
                          'medium' => 'severity-medium',
                          'low' => 'severity-low',
                          default => 'severity-low'
                        };
                      @endphp
                      <span class="{{ $severityClass }}" style="padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">
                        {{ ucfirst($activity->severity ?? 'Low') }}
                      </span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" style="padding: 20px; text-align: center; color: #9ca3af;">No recent activity</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($recentActivities->count() > 5)
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <div style="font-size: 12px; color: #9ca3af;">
              Page <span id="current-page">1</span> of <span id="total-pages">2</span>
            </div>
            <div style="display: flex; gap: 8px;">
              <button id="prev-btn" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px; background: white; color: #374151; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Previous</button>
              <button id="next-btn" style="padding: 6px 12px; border: 1px solid #5864FF; border-radius: 4px; background: #5864FF; color: white; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Next</button>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Get data from PHP
      const trafficCategories = @json($trafficCategories ?? []);
      const normalFlows = @json($normalFlows ?? []);
      const suspiciousFlows = @json($suspiciousFlows ?? []);
      const protocolLabels = @json($protocolLabels ?? []);
      const protocolValues = @json($protocolValues ?? []);

      // Pagination for Recent Activity
      const rowsPerPage = 5;
      const rows = document.querySelectorAll('.activity-row');
      const totalRows = rows.length;
      const totalPages = Math.ceil(totalRows / rowsPerPage);
      let currentPage = 1;

      if (totalPages > 1) {
        function showPage(page) {
          const startIndex = (page - 1) * rowsPerPage;
          const endIndex = startIndex + rowsPerPage;

          rows.forEach((row, index) => {
            row.style.display = index >= startIndex && index < endIndex ? '' : 'none';
          });

          document.getElementById('current-page').textContent = page;
          document.getElementById('total-pages').textContent = totalPages;
          
          const prevBtn = document.getElementById('prev-btn');
          const nextBtn = document.getElementById('next-btn');
          
          prevBtn.disabled = page === 1;
          nextBtn.disabled = page === totalPages;
          prevBtn.style.opacity = page === 1 ? '0.5' : '1';
          prevBtn.style.cursor = page === 1 ? 'not-allowed' : 'pointer';
          nextBtn.style.opacity = page === totalPages ? '0.5' : '1';
          nextBtn.style.cursor = page === totalPages ? 'not-allowed' : 'pointer';
        }

        document.getElementById('prev-btn').addEventListener('click', function() {
          if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
          }
        });

        document.getElementById('next-btn').addEventListener('click', function() {
          if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
          }
        });

        showPage(currentPage);
      }

      // Traffic Analysis Chart
      new ApexCharts(document.querySelector('#analysis-chart'), {
        chart: { height: 450, type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        colors: ['#4356E6', '#ff4d4f'],
        series: [
          { name: 'Normal Flows', data: normalFlows.length > 0 ? normalFlows : Array(12).fill(0) },
          { name: 'Suspicious Flows', data: suspiciousFlows.length > 0 ? suspiciousFlows : Array(12).fill(0) }
        ],
        stroke: { curve: 'smooth', width: 2 },
        xaxis: { categories: trafficCategories.length > 0 ? trafficCategories : Array(12).fill('00:00') },
        grid: { strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'right' }
      }).render();

      // Protocol Distribution Chart
      new ApexCharts(document.querySelector('#protocol-chart'), {
        chart: { height: 320, type: 'donut', toolbar: { show: false } },
        series: protocolValues.length > 0 ? protocolValues : [0],
        labels: protocolLabels.length > 0 ? protocolLabels : ['No Data'],
        colors: ['#5864FF', '#4356E6', '#faad14', '#ff6b6b', '#6d28d9'],
        dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(1) + '%' } },
        legend: { position: 'bottom' },
        plotOptions: {
          pie: {
            donut: {
              size: '65%'
            }
          }
        }
      }).render();

      const protocolNote = document.createElement('p');
      protocolNote.style.marginTop = '12px';
      protocolNote.style.fontSize = '12px';
      protocolNote.style.color = '#9ca3af';
      protocolNote.style.textAlign = 'center';
      protocolNote.textContent = 'Based on network traffic analysis';
      document.querySelector('#protocol-chart').parentElement.appendChild(protocolNote);
    });
  </script>
@endpush