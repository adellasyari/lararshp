<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="{{ asset('asset/img/AdminLTELogo.png') }}"
        alt="RSHP Logo"
        class="brand-image opacity-75 shadow"
      />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">RSHP</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      @php
        $isAdmin = auth()->check() && auth()->user()->roles()->where('nama_role','Administrator')->wherePivot('status',1)->exists();
        $isResepsionis = auth()->check() && auth()->user()->roles()->where('nama_role','Resepsionis')->wherePivot('status',1)->exists();
      @endphp
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="navigation"
        data-accordion="false"
      >
        @if($isAdmin)
        <li class="nav-item menu-open">
          <a href="{{ route('admin.dashboard') }}" class="nav-link active">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">MENU UTAMA</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-box-seam-fill"></i>
            <p>
              Master Data
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.jenis-hewan.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Jenis Hewan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.ras-hewan.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Ras Hewan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.kategori.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.kategori-klinis.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori Klinis</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-people-fill"></i>
            <p>
              Manajemen User
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.user.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>User</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.role.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Role</p>
              </a>
            </li>
            @if(Route::has('admin.dokter.index'))
            <li class="nav-item">
              <a href="{{ route('admin.dokter.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-md"></i>
                <p>Data Dokter</p>
              </a>
            </li>
            @endif
            @if(Route::has('admin.perawat.index'))
            <li class="nav-item">
              <a href="{{ route('admin.perawat.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user-nurse"></i>
                <p>Data Perawat</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-person-heart"></i>
            <p>
              Pemilik & Pet
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.pemilik.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Pemilik</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.pet.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Pet</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-calendar2-heart"></i>
            <p>
              Pelayanan
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.rekam-medis.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Rekam Medis</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.tindakan-terapi.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tindakan Terapi</p>
              </a>
            </li>
                <li class="nav-item">
                  <a href="{{ route('admin.temu-dokter.index') }}" class="nav-link">
                    <i class="nav-icon bi bi-circle"></i>
                    <p>Temu Dokter</p>
                  </a>
                </li>
          </ul>
        </li>
        @endif
        @if(auth()->check() && auth()->user()->roles()->where('nama_role','Perawat')->wherePivot('status',1)->exists())
        <li class="nav-item menu-open">
          <a href="{{ route('perawat.dashboard') }}" class="nav-link {{ request()->routeIs('perawat.dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">PERAWAT</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-heart-pulse"></i>
            <p>
              Perawat Menu
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('perawat.rekam-medis.index') }}" class="nav-link {{ request()->routeIs('perawat.rekam-medis.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Rekam Medis</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('perawat.profile') }}" class="nav-link {{ request()->routeIs('perawat.profile') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Profil Saya</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @if(auth()->check() && auth()->user()->roles()->where('nama_role','Dokter')->wherePivot('status',1)->exists())
        <li class="nav-item menu-open">
          <a href="{{ route('dokter.dashboard') }}" class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">DOKTER</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-heart-pulse"></i>
            <p>
              Dokter Menu
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('dokter.rekam-medis.index') }}" class="nav-link {{ request()->routeIs('dokter.rekam-medis.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Pemeriksaan Pasien</p>
              </a>
            </li>
            <!-- Dokter tidak memiliki akses untuk mengelola Tindakan Terapi; hanya memilih saat pemeriksaan -->
            <li class="nav-item">
              <a href="{{ route('dokter.create') }}" class="nav-link {{ request()->routeIs('dokter.create') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Profil Saya</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @if(auth()->check() && auth()->user()->roles()->where('nama_role','Resepsionis')->wherePivot('status',1)->exists())
        <li class="nav-item menu-open">
          <a href="{{ route('resepsionis.dashboard') }}" class="nav-link active">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">RESEPSIONIS</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-person-lines-fill"></i>
            <p>
              Resepsionis Menu
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('resepsionis.pemilik.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kelola Pemilik</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('resepsionis.hewan.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kelola Hewan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('resepsionis.temu-dokter.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Pendaftaran Dokter</p>
              </a>
            </li>
          </ul>
        </li>
        @endif
        @if(auth()->check() && auth()->user()->roles()->where('nama_role','Pemilik')->wherePivot('status',1)->exists())
        <li class="nav-item menu-open">
          <a href="{{ Route::has('pemilik.dashboard') ? route('pemilik.dashboard') : '#' }}" class="nav-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-header">PEMILIK</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-person-lines-fill"></i>
            <p>
              Pemilik Menu
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if(Route::has('pemilik.pet.index'))
            <li class="nav-item">
              <a href="{{ route('pemilik.pet.index') }}" class="nav-link {{ request()->routeIs('pemilik.pet.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Hewan Saya</p>
              </a>
            </li>
            @endif

            @if(Route::has('pemilik.temu-dokter.index'))
            <li class="nav-item">
              <a href="{{ route('pemilik.temu-dokter.index') }}" class="nav-link {{ request()->routeIs('pemilik.temu-dokter.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Jadwal Kunjungan</p>
              </a>
            </li>
            @elseif(Route::has('resepsionis.temu-dokter.index'))
            <li class="nav-item">
              <a href="{{ route('resepsionis.temu-dokter.index') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Jadwal Kunjungan</p>
              </a>
            </li>
            @endif

            @if(Route::has('pemilik.rekam-medis.index'))
            <li class="nav-item">
              <a href="{{ route('pemilik.rekam-medis.index') }}" class="nav-link {{ request()->routeIs('pemilik.rekam-medis.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Riwayat Berobat</p>
              </a>
            </li>
            @endif

            @if(Route::has('pemilik.profile'))
            <li class="nav-item">
              <a href="{{ route('pemilik.profile') }}" class="nav-link {{ request()->routeIs('pemilik.profile') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Profil Saya</p>
              </a>
            </li>
            @else
            <li class="nav-item">
              <a href="{{ route('pemilik.dashboard') }}" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Profil Saya</p>
              </a>
            </li>
            @endif
          </ul>
        </li>
        @endif
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->