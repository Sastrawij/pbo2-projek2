<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Servis Kendaraan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    {{-- Header --}}
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3 d-none d-md-block">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Sidebar --}}
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <i class="bi bi-wrench-adjustable-circle-fill fs-4 me-2"></i>
                <span class="brand-text fw-light">Servis Kendaraan</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">TRANSAKSI</li>
                    <li class="nav-item">
                        <a href="{{ route('servis.index') }}" class="nav-link {{ request()->routeIs('servis.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tools"></i><p>Servis</p>
                        </a>
                    </li>

                    <li class="nav-header">DATA MASTER</li>
                    <li class="nav-item">
                        <a href="{{ route('pelanggan.index') }}" class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i><p>Pelanggan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kendaraan.index') }}" class="nav-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-car-front"></i><p>Kendaraan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mekanik.index') }}" class="nav-link {{ request()->routeIs('mekanik.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-gear"></i><p>Mekanik</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('layanan.index') }}" class="nav-link {{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-check"></i><p>Layanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('suku-cadang.index') }}" class="nav-link {{ request()->routeIs('suku-cadang.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear-wide-connected"></i><p>Suku Cadang</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Konten --}}
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6"><h3 class="mb-0">@yield('title', 'Dashboard')</h3></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Data belum bisa disimpan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $pesan)
                                <li>{{ $pesan }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">PBO 2 - Proyek 2</div>
        <strong>Aplikasi Servis Kendaraan</strong>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>
