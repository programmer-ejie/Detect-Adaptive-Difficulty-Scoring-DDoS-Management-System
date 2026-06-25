@extends('admin.layout', ['page' => 'settings'])

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
  <div class="row">
    <div class="col-md-12 col-xl-6">
      <h5 class="mb-3">Detection Settings</h5>
      <div class="card">
        <div class="card-body">
          <div class="form-group mb-3">
            <label class="form-label">Attack score threshold</label>
            <input type="number" class="form-control" value="85">
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Auto-refresh interval</label>
            <select class="form-select">
              <option selected>5 seconds</option>
              <option>10 seconds</option>
              <option>30 seconds</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Time window</label>
            <select class="form-select">
              <option selected>15 minutes</option>
              <option>1 hour</option>
              <option>6 hours</option>
              <option>24 hours</option>
            </select>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input input-primary" type="checkbox" id="autoMitigation" checked>
            <label class="form-check-label" for="autoMitigation">Enable automatic mitigation when score is critical</label>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-6">
      <h5 class="mb-3">Router Connection</h5>
      <div class="card">
        <div class="card-body">
          <div class="form-group mb-3">
            <label class="form-label">Router host</label>
            <input type="text" class="form-control" value="172.16.0.1">
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Log source</label>
            <select class="form-select">
              <option selected>Router logs and flow records</option>
              <option>Router logs only</option>
              <option>Flow records only</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label class="form-label">Notification email</label>
            <input type="email" class="form-control" value="keanosy0319@gmail.com">
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="ti ti-device-floppy me-2"></i>Save Settings</button>
            <button class="btn btn-light-primary"><i class="ti ti-plug-connected me-2"></i>Test Connection</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <h5 class="mb-3">System Preferences</h5>
      <div class="card">
        <div class="list-group list-group-flush">
          <label class="list-group-item d-flex align-items-center justify-content-between">
            <span>
              <span class="d-block fw-semibold">Notify on high severity attacks</span>
              <span class="text-muted">Send alert when attacks are classified as high or critical.</span>
            </span>
            <input class="form-check-input input-primary" type="checkbox" checked>
          </label>
          <label class="list-group-item d-flex align-items-center justify-content-between">
            <span>
              <span class="d-block fw-semibold">Keep mitigation history</span>
              <span class="text-muted">Store applied rule records for later review.</span>
            </span>
            <input class="form-check-input input-primary" type="checkbox" checked>
          </label>
          <label class="list-group-item d-flex align-items-center justify-content-between">
            <span>
              <span class="d-block fw-semibold">Manual approval for ACL updates</span>
              <span class="text-muted">Queue firewall rule changes before deployment.</span>
            </span>
            <input class="form-check-input input-primary" type="checkbox">
          </label>
        </div>
      </div>
    </div>
  </div>
@endsection
