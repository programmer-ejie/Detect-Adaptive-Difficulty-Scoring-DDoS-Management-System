@extends('admin.layout', ['page' => 'alert'])

@section('title', 'Alerts')
@section('page-title', 'Alerts')

@section('content')
  <div class="row">
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Critical Alerts</h6>
          <h4 class="mb-3">3 <span class="badge bg-light-danger border border-danger">Needs review</span></h4>
          <p class="mb-0 text-muted text-sm">Highest risk incidents</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">High Severity</h6>
          <h4 class="mb-3">7 <span class="badge bg-light-warning border border-warning">Active</span></h4>
          <p class="mb-0 text-muted text-sm">Traffic spikes under watch</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Acknowledged</h6>
          <h4 class="mb-3">18 <span class="badge bg-light-success border border-success">Tracked</span></h4>
          <p class="mb-0 text-muted text-sm">Assigned to operator</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">False Positives</h6>
          <h4 class="mb-3">2 <span class="badge bg-light-primary border border-primary">Low</span></h4>
          <p class="mb-0 text-muted text-sm">Marked safe today</p>
        </div>
      </div>
    </div>

    <div class="col-12">
      <h5 class="mb-3">Detected Attack Alerts</h5>
      <div class="card tbl-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
              <thead>
                <tr>
                  <th>TIME</th>
                  <th>TYPE</th>
                  <th>SOURCE IP</th>
                  <th>TARGET IP</th>
                  <th>SEVERITY</th>
                  <th>STATUS</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>2 min ago</td>
                  <td>UDP Amplification</td>
                  <td>10.23.1.18</td>
                  <td>172.16.0.12</td>
                  <td><span class="badge bg-light-danger border border-danger">Critical</span></td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-danger f-10"></i>Open</span></td>
                </tr>
                <tr>
                  <td>7 min ago</td>
                  <td>HTTP Flood</td>
                  <td>203.0.113.44</td>
                  <td>172.16.0.12</td>
                  <td><span class="badge bg-light-danger border border-danger">High</span></td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-warning f-10"></i>Investigating</span></td>
                </tr>
                <tr>
                  <td>16 min ago</td>
                  <td>SYN Scan</td>
                  <td>198.51.100.77</td>
                  <td>172.16.0.18</td>
                  <td><span class="badge bg-light-warning border border-warning">Medium</span></td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-success f-10"></i>Acknowledged</span></td>
                </tr>
                <tr>
                  <td>31 min ago</td>
                  <td>Low-rate Probing</td>
                  <td>192.0.2.88</td>
                  <td>172.16.0.12</td>
                  <td><span class="badge bg-light-success border border-success">Low</span></td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-success f-10"></i>Resolved</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
