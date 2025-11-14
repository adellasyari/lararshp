@extends('layouts.lte.main')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!--begin::App Content-->
<div class="app-content">
  <div class="container-fluid">
    <!-- Summary cards -->
    <div class="row g-3 mb-4">
      <div class="col-lg-3 col-sm-6">
        <div class="card shadow-sm">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="icon bg-primary text-white rounded-circle p-3">
              <i class="bi bi-people-fill fs-4"></i>
            </div>
            <div>
              <h4 class="mb-0">{{ $stats['users'] ?? 0 }}</h4>
              <small class="text-muted">Total Users</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="card shadow-sm">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="icon bg-success text-white rounded-circle p-3">
              <i class="bi bi-journal-medical fs-4"></i>
            </div>
            <div>
              <h4 class="mb-0">{{ $stats['pets'] ?? 0 }}</h4>
              <small class="text-muted">Registered Pets</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="card shadow-sm">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="icon bg-warning text-dark rounded-circle p-3">
              <i class="bi bi-calendar-check fs-4"></i>
            </div>
            <div>
              <h4 class="mb-0">{{ $stats['appointments'] ?? 0 }}</h4>
              <small class="text-muted">Appointments</small>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="card shadow-sm">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="icon bg-danger text-white rounded-circle p-3">
              <i class="bi bi-file-medical fs-4"></i>
            </div>
            <div>
              <h4 class="mb-0">{{ $stats['records'] ?? 0 }}</h4>
              <small class="text-muted">Medical Records</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts & Recent -->
    <div class="row">
      <div class="col-lg-8">
        <div class="card mb-3">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Activity Overview</h5>
            <div class="text-muted"><small>Last 6 months</small></div>
          </div>
          <div class="card-body">
            <canvas id="overviewChart" style="height:320px;"></canvas>
          </div>
        </div>

        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Geography</h5>
            <div class="text-muted"><small>Visitors map</small></div>
          </div>
          <div class="card-body text-bg-primary position-relative" style="min-height:300px;">
            <div id="world-map" style="height:300px;"></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card mb-3">
          <div class="card-header">
            <h5 class="card-title mb-0">Quick Actions</h5>
          </div>
          <div class="card-body">
            <div class="d-grid gap-2">
              <a href="{{ route('admin.user.create') }}" class="btn btn-outline-primary">Tambah User</a>
              <a href="{{ route('admin.pet.create') }}" class="btn btn-outline-success">Tambah Pet</a>
              <a href="{{ route('admin.tindakan-terapi.create') }}" class="btn btn-outline-warning">Tambah Tindakan</a>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h5 class="card-title mb-0">Recent Users</h5></div>
          <div class="card-body p-0">
            <table class="table table-sm mb-0">
              <thead>
                <tr><th>Name</th><th class="text-end">Joined</th></tr>
              </thead>
              <tbody>
                @forelse($recentUsers ?? [] as $u)
                <tr>
                  <td>{{ $u->nama }}</td>
                  <td class="text-end">{{ optional($u->created_at)->format('d M') }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-center text-muted p-3">No recent users</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<!-- jsvectormap -->
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"></script>

<script>
// Overview Chart (line + area)
const ctx = document.getElementById('overviewChart').getContext('2d');
const overviewChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun'],
    datasets: [
      { label: 'Visits', data: [120,150,140,170,190,220], borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,0.08)', fill: true, tension: 0.3 },
      { label: 'New Users', data: [30,45,40,55,60,75], borderColor: '#198754', backgroundColor: 'rgba(25,135,84,0.06)', fill: true, tension: 0.3 }
    ]
  },
  options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
});

// World Map
new jsVectorMap({ selector: '#world-map', map: 'world', backgroundColor: 'transparent', regionStyle: { initial: { fill: 'rgba(255,255,255,0.08)', stroke: '#fff' } }, markers: [ { coords: [40.7128, -74.0060], name: 'New York' }, { coords: [51.5074, -0.1278], name: 'London' } ] });
</script>
@endsection