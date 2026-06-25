@extends('admin.layout', ['page' => 'analysis'])

@section('title', 'Analysis')
@section('page-title', 'Analysis')

@section('content')
  <div class="row">
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
      <h5 class="mb-3">Protocol Distribution</h5>
      <div class="card">
        <div class="card-body">
          <div id="protocol-chart"></div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-8">
      <h5 class="mb-3">Top Source IPs</h5>
      <div class="card tbl-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover table-borderless mb-0">
              <thead>
                <tr>
                  <th>SOURCE IP</th>
                  <th>PACKETS</th>
                  <th>PROTOCOL</th>
                  <th>ANOMALY SCORE</th>
                  <th>CLASSIFICATION</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>192.0.2.10</td>
                  <td>92,410</td>
                  <td>UDP</td>
                  <td>94%</td>
                  <td><span class="badge bg-light-danger border border-danger">Attack</span></td>
                </tr>
                <tr>
                  <td>203.0.113.44</td>
                  <td>78,120</td>
                  <td>HTTP</td>
                  <td>88%</td>
                  <td><span class="badge bg-light-danger border border-danger">Attack</span></td>
                </tr>
                <tr>
                  <td>198.51.100.77</td>
                  <td>41,980</td>
                  <td>TCP</td>
                  <td>62%</td>
                  <td><span class="badge bg-light-warning border border-warning">Suspicious</span></td>
                </tr>
                <tr>
                  <td>10.23.1.18</td>
                  <td>18,400</td>
                  <td>TCP</td>
                  <td>18%</td>
                  <td><span class="badge bg-light-success border border-success">Normal</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-4">
      <h5 class="mb-3">Model Signals</h5>
      <div class="card">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Packet rate spike<span class="h5 mb-0 text-danger">94%</span></a>
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Protocol imbalance<span class="h5 mb-0 text-warning">71%</span></a>
          <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">Router log warning<span class="h5 mb-0 text-primary">58%</span></a>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      new ApexCharts(document.querySelector('#analysis-chart'), {
        chart: { height: 450, type: 'area', toolbar: { show: false } },
        dataLabels: { enabled: false },
        colors: ['#1890ff', '#ff4d4f'],
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
        colors: ['#1890ff', '#17c671', '#faad14'],
        dataLabels: { enabled: false },
        legend: { position: 'bottom' }
      }).render();
    });
  </script>
@endpush
