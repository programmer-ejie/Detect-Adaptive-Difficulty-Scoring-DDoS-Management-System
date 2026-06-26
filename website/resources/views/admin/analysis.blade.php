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
  </style>

  <div class="row">
    <!-- Analysis Stat Cards -->
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-git-branch"></i></div>
          <div class="stat-label">Total Flows</div>
          <div class="stat-value">24,580</div>
          <span class="stat-badge" style="background: #E6F0FF; color: #5864FF;">↑ 12%</span>
          <div class="stat-description">Compared to yesterday</div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
          <div class="stat-label">Attack Flows</div>
          <div class="stat-value">1,248</div>
          <span class="stat-badge" style="background: #fee2e2; color: #7f1d1d;">Critical</span>
          <div class="stat-description">Requires immediate action</div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-ban"></i></div>
          <div class="stat-label">Anomalies</div>
          <div class="stat-value">342</div>
          <span class="stat-badge" style="background: #fef3c7; color: #92400e;">Medium</span>
          <div class="stat-description">Flagged for review</div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card stat-card">
        <div class="card-body p-4">
          <div class="stat-icon"><i class="ti ti-clock"></i></div>
          <div class="stat-label">Detection Rate</div>
          <div class="stat-value">99.2%</div>
          <span class="stat-badge" style="background: #dcfce7; color: #166534;">Excellent</span>
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

    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">Response Metrics</h5>
      <div class="card">
        <div class="card-body">
          <div class="mb-3">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <h6 class="text-muted">Avg Response Time</h6>
              <span style="color: #5864FF; font-weight: 600;">2.3s</span>
            </div>
            <div style="height: 6px; background: #E6F0FF; border-radius: 3px; overflow: hidden;">
              <div style="height: 100%; background: linear-gradient(90deg, #5864FF, #4356E6); width: 92%;"></div>
            </div>
            <p class="text-muted text-sm mt-2 mb-0">Below SLA target</p>
          </div>
          <div class="mb-3">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <h6 class="text-muted">Detection Success</h6>
              <span style="color: #5864FF; font-weight: 600;">99.2%</span>
            </div>
            <div style="height: 6px; background: #E6F0FF; border-radius: 3px; overflow: hidden;">
              <div style="height: 100%; background: linear-gradient(90deg, #5864FF, #4356E6); width: 99.2%;"></div>
            </div>
            <p class="text-muted text-sm mt-2 mb-0">Excellent accuracy</p>
          </div>
          <div class="mb-3">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <h6 class="text-muted">Mean Time to Resolution</h6>
              <span style="color: #5864FF; font-weight: 600;">5.7m</span>
            </div>
            <div style="height: 6px; background: #E6F0FF; border-radius: 3px; overflow: hidden;">
              <div style="height: 100%; background: linear-gradient(90deg, #5864FF, #4356E6); width: 85%;"></div>
            </div>
            <p class="text-muted text-sm mt-2 mb-0">Within response window</p>
          </div>
        </div>
      </div>
    </div>

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
                <tr>
                  <td style="padding: 16px;">192.0.2.10</td>
                  <td style="padding: 16px;">92,410</td>
                  <td style="padding: 16px;">UDP</td>
                  <td style="padding: 16px;"><span style="color: #7f1d1d; font-weight: 600;">94%</span></td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Attack</span></td>
                </tr>
                <tr>
                  <td style="padding: 16px;">203.0.113.44</td>
                  <td style="padding: 16px;">78,120</td>
                  <td style="padding: 16px;">HTTP</td>
                  <td style="padding: 16px;"><span style="color: #7f1d1d; font-weight: 600;">88%</span></td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Attack</span></td>
                </tr>
                <tr>
                  <td style="padding: 16px;">198.51.100.77</td>
                  <td style="padding: 16px;">41,980</td>
                  <td style="padding: 16px;">TCP</td>
                  <td style="padding: 16px;"><span style="color: #b45309; font-weight: 600;">62%</span></td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Suspicious</span></td>
                </tr>
                <tr>
                  <td style="padding: 16px;">10.23.1.18</td>
                  <td style="padding: 16px;">18,400</td>
                  <td style="padding: 16px;">TCP</td>
                  <td style="padding: 16px;"><span style="color: #16a34a; font-weight: 600;">18%</span></td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Normal</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

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
                  <th style="padding: 12px 16px; font-weight: 600; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">IMPACT</th>
                </tr>
              </thead>
              <tbody id="activity-tbody">
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 14:23:45</td>
                  <td style="padding: 16px;">UDP Flood Detected</td>
                  <td style="padding: 16px;">192.0.2.10</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Critical</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 14:18:32</td>
                  <td style="padding: 16px;">HTTP Anomaly Flagged</td>
                  <td style="padding: 16px;">203.0.113.44</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fecaca; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">High</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 14:12:10</td>
                  <td style="padding: 16px;">Port Scan Detected</td>
                  <td style="padding: 16px;">198.51.100.77</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Medium</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 14:05:28</td>
                  <td style="padding: 16px;">TCP Connection Established</td>
                  <td style="padding: 16px;">10.23.1.18</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #dbeafe; color: #0c4a6e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Low</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 13:58:15</td>
                  <td style="padding: 16px;">DNS Query Flood</td>
                  <td style="padding: 16px;">155.89.12.47</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fecaca; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">High</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 13:45:42</td>
                  <td style="padding: 16px;">SYN Scan Attempt</td>
                  <td style="padding: 16px;">172.16.0.50</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Critical</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 13:32:20</td>
                  <td style="padding: 16px;">ICMP Request Flood</td>
                  <td style="padding: 16px;">210.45.30.88</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Medium</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 13:21:05</td>
                  <td style="padding: 16px;">Protocol Violation</td>
                  <td style="padding: 16px;">98.12.67.34</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #dbeafe; color: #0c4a6e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Low</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 13:08:33</td>
                  <td style="padding: 16px;">Traffic Spike Detected</td>
                  <td style="padding: 16px;">145.80.20.15</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Medium</span></td>
                </tr>
                <tr class="activity-row">
                  <td style="padding: 16px;">2026-06-26 12:55:47</td>
                  <td style="padding: 16px;">Router Configuration Change</td>
                  <td style="padding: 16px;">System</td>
                  <td style="padding: 16px; text-align: center;" align="center"><span style="background: #dbeafe; color: #0c4a6e; padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block;">Low</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <div style="font-size: 12px; color: #9ca3af;">
              Page <span id="current-page">1</span> of <span id="total-pages">2</span>
            </div>
            <div style="display: flex; gap: 8px;">
              <button id="prev-btn" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px; background: white; color: #374151; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Previous</button>
              <button id="next-btn" style="padding: 6px 12px; border: 1px solid #5864FF; border-radius: 4px; background: #5864FF; color: white; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Next</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Pagination for Recent Activity
      const rowsPerPage = 5;
      const rows = document.querySelectorAll('.activity-row');
      const totalRows = rows.length;
      const totalPages = Math.ceil(totalRows / rowsPerPage);
      let currentPage = 1;

      function showPage(page) {
        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        rows.forEach((row, index) => {
          row.style.display = index >= startIndex && index < endIndex ? '' : 'none';
        });

        document.getElementById('current-page').textContent = page;
        document.getElementById('total-pages').textContent = totalPages;
        document.getElementById('prev-btn').disabled = page === 1;
        document.getElementById('next-btn').disabled = page === totalPages;
        
        if (page === 1) {
          document.getElementById('prev-btn').style.opacity = '0.5';
          document.getElementById('prev-btn').style.cursor = 'not-allowed';
        } else {
          document.getElementById('prev-btn').style.opacity = '1';
          document.getElementById('prev-btn').style.cursor = 'pointer';
        }
        
        if (page === totalPages) {
          document.getElementById('next-btn').style.opacity = '0.5';
          document.getElementById('next-btn').style.cursor = 'not-allowed';
        } else {
          document.getElementById('next-btn').style.opacity = '1';
          document.getElementById('next-btn').style.cursor = 'pointer';
        }
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
      new ApexCharts(document.querySelector('#analysis-chart'), {
        chart: { height: 450, type: 'area', toolbar: { show: false } },
        dataLabels: { enabled: false },
        colors: ['#4356E6', '#ff4d4f'],
        series: [
          { name: 'Normal Flows', data: [31, 40, 28, 51, 42, 109, 100, 95, 88, 103, 110, 118] },
          { name: 'Suspicious Flows', data: [11, 32, 45, 32, 34, 52, 41, 70, 64, 58, 42, 35] }
        ],
        stroke: { curve: 'smooth', width: 2 },
        xaxis: { categories: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'] }
      }).render();

      new ApexCharts(document.querySelector('#protocol-chart'), {
        chart: { height: 320, type: 'donut' },
        series: [62, 28, 10],
        labels: ['TCP', 'UDP', 'ICMP'],
        colors: ['#5864FF', '#4356E6', '#faad14'],
        dataLabels: { enabled: true, formatter: function(val) { return val.toFixed(1) + '%' } },
        legend: { position: 'bottom' }
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
