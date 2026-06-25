@extends('admin.layout', ['page' => 'mitigation'])

@section('title', 'Mitigation')
@section('page-title', 'Mitigation')

@section('content')
  <div class="row">
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Blocked IPs</h6>
          <h4 class="mb-3">248 <span class="badge bg-light-primary border border-primary">Active</span></h4>
          <p class="mb-0 text-muted text-sm">Deny list entries</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Rate Limit Rules</h6>
          <h4 class="mb-3">12 <span class="badge bg-light-success border border-success">Enabled</span></h4>
          <p class="mb-0 text-muted text-sm">Applied to edge router</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">ACL Updates</h6>
          <h4 class="mb-3">6 <span class="badge bg-light-warning border border-warning">Pending</span></h4>
          <p class="mb-0 text-muted text-sm">Awaiting confirmation</p>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2 f-w-400 text-muted">Mitigation Mode</h6>
          <h4 class="mb-3">Auto <span class="badge bg-light-success border border-success">On</span></h4>
          <p class="mb-0 text-muted text-sm">Rules can be reviewed</p>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">Quick Mitigation</h5>
      <div class="card">
        <div class="card-body">
          <div class="d-grid gap-2">
            <button class="btn btn-danger"><i class="ti ti-ban me-2"></i>Block Suspicious IP</button>
            <button class="btn btn-primary"><i class="ti ti-adjustments me-2"></i>Enable Rate Limiting</button>
            <button class="btn btn-warning"><i class="ti ti-shield-check me-2"></i>Deploy ACL Update</button>
            <button class="btn btn-success"><i class="ti ti-refresh-alert me-2"></i>Recheck Router Status</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-8">
      <h5 class="mb-3">Mitigation Rules</h5>
      <div class="card tbl-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
              <thead>
                <tr>
                  <th>RULE</th>
                  <th>TARGET</th>
                  <th>ACTION</th>
                  <th>STATUS</th>
                  <th class="text-end">UPDATED</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Block UDP amplification source</td>
                  <td>10.23.1.18</td>
                  <td>Deny</td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-success f-10"></i>Active</span></td>
                  <td class="text-end">2 min ago</td>
                </tr>
                <tr>
                  <td>HTTP flood throttling</td>
                  <td>172.16.0.12</td>
                  <td>Rate limit</td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-warning f-10"></i>Review</span></td>
                  <td class="text-end">8 min ago</td>
                </tr>
                <tr>
                  <td>Temporary SYN ACL</td>
                  <td>172.16.0.18</td>
                  <td>ACL update</td>
                  <td><span class="d-flex align-items-center gap-2"><i class="fas fa-circle text-primary f-10"></i>Queued</span></td>
                  <td class="text-end">14 min ago</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
