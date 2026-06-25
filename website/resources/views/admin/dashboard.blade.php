@extends('admin.layout', ['page' => 'dashboard'])

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
  <div class="row">
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Network Status</h6>
          <h4 class="mb-3">Online <span class="badge bg-light-success border border-success"><i class="ti ti-check"></i> Stable</span></h4>
          <p class="mb-0 text-muted text-sm">All routers responding</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Active Attacks</h6>
          <h4 class="mb-3">14 <span class="badge bg-light-danger border border-danger"><i class="ti ti-alert-triangle"></i> Live</span></h4>
          <p class="mb-0 text-muted text-sm">2 high severity spikes</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Blocked IPs</h6>
          <h4 class="mb-3">248 <span class="badge bg-light-warning border border-warning"><i class="ti ti-ban"></i> 14 new</span></h4>
          <p class="mb-0 text-muted text-sm">Updated from latest mitigation</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">System Uptime</h6>
          <h4 class="mb-3">99.98% <span class="badge bg-light-primary border border-primary"><i class="ti ti-wave-sine"></i> 24/7</span></h4>
          <p class="mb-0 text-muted text-sm">Monitoring active</p>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Packets Per Second</h5>
        <div class="btn-group">
          <button class="btn btn-outline-primary btn-sm active">15m</button>
          <button class="btn btn-outline-primary btn-sm">1h</button>
          <button class="btn btn-outline-primary btn-sm">6h</button>
          <button class="btn btn-outline-primary btn-sm">24h</button>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div id="traffic-chart"></div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">System Health</h5>
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span class="fw-semibold">Router A</span>
            <span class="badge bg-light-success border border-success">Healthy</span>
          </div>
          <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar bg-success" style="width: 92%"></div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="fw-semibold">CPU Utilization</span>
            <span class="text-muted">64%</span>
          </div>
          <div class="progress mb-3" style="height: 10px;">
            <div class="progress-bar bg-primary" style="width: 64%"></div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="fw-semibold">Memory Usage</span>
            <span class="text-muted">51%</span>
          </div>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-warning" style="width: 51%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-8">
      <h5 class="mb-3">Recent Alerts</h5>
      <div class="card tbl-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
              <thead>
                <tr>
                  <th>ATTACK TYPE</th>
                  <th>TIME</th>
                  <th>TARGET IP</th>
                  <th>SEVERITY</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>UDP amplification burst</td>
                  <td>2 min ago</td>
                  <td>172.16.0.12</td>
                  <td><span class="badge bg-light-danger border border-danger">Critical</span></td>
                </tr>
                <tr>
                  <td>HTTP flood spike</td>
                  <td>7 min ago</td>
                  <td>172.16.0.12</td>
                  <td><span class="badge bg-light-danger border border-danger">High</span></td>
                </tr>
                <tr>
                  <td>Suspicious SYN scan</td>
                  <td>16 min ago</td>
                  <td>172.16.0.18</td>
                  <td><span class="badge bg-light-warning border border-warning">Medium</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">Quick Actions</h5>
      <div class="card">
        <div class="list-group list-group-flush">
          <a href="{{ route('admin.mitigation') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Block Suspicious IP<span class="avtar avtar-xs bg-light-danger text-danger"><i class="ti ti-ban"></i></span></a>
          <a href="{{ route('admin.mitigation') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Enable Rate Limiting<span class="avtar avtar-xs bg-light-primary text-primary"><i class="ti ti-adjustments"></i></span></a>
          <a href="{{ route('admin.analysis') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Open Analysis<span class="avtar avtar-xs bg-light-success text-success"><i class="ti ti-chart-line"></i></span></a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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
    });
  </script>
@endpush
