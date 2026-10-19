<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Панель') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    @include('layouts.styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="brand"><i class="bi bi-egg-fried"></i><span>Менедж<b>мент</b></span></a>
    <nav class="nav flex-column">
        <div class="nav-title">Главное</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i>Дашборд</a>

        @canany(['restaurants.manage', 'employers.view'])
            <div class="nav-title">Команда</div>
        @endcanany
        @can('restaurants.manage')
            <a href="{{ route('restaurants.index') }}" class="nav-link {{ request()->routeIs('restaurants.*') ? 'active' : '' }}"><i class="bi bi-shop"></i>Рестораны</a>
        @endcan
        @can('employers.view')
            <a href="{{ route('employers.index') }}" class="nav-link {{ request()->routeIs('employers.*') ? 'active' : '' }}"><i class="bi bi-people"></i>Сотрудники</a>
        @endcan

        @canany(['fines.view', 'fine-reasons.manage'])
            <div class="nav-title">Дисциплина</div>
        @endcanany
        @can('fines.view')
            <a href="{{ route('fines.index') }}" class="nav-link {{ request()->routeIs('fines.*') ? 'active' : '' }}"><i class="bi bi-receipt-cutoff"></i>Штрафы</a>
        @endcan
        @can('fine-reasons.manage')
            <a href="{{ route('fine-reasons.index') }}" class="nav-link {{ request()->routeIs('fine-reasons.*') ? 'active' : '' }}"><i class="bi bi-list-check"></i>Причины штрафов</a>
        @endcan

        @can('users.manage')
            <div class="nav-title">Система</div>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-person-gear"></i>Пользователи</a>
        @endcan
    </nav>
    <div class="side-user">
        <div class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="flex-grow-1 text-truncate">
            <div class="fw-semibold text-white text-truncate">{{ auth()->user()->name }}</div>
            <small class="text-white-50">{{ auth()->user()->role?->name }}</small>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn btn-link text-white-50 p-0" title="Выйти"><i class="bi bi-box-arrow-right fs-5"></i></button>
        </form>
    </div>
</aside>
<div class="sidebar-backdrop" onclick="document.body.classList.remove('menu-open')"></div>

<main class="content">
    <header class="topbar">
        <button class="btn btn-light d-lg-none" onclick="document.body.classList.toggle('menu-open')"><i class="bi bi-list fs-5"></i></button>
        <div>
            <h1 class="page-title">@yield('title')</h1>
            @hasSection('subtitle')<div class="text-muted small">@yield('subtitle')</div>@endif
        </div>
        <div class="ms-auto d-flex gap-2">@yield('actions')</div>
    </header>

    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if (session($key))
            <div class="alert alert-{{ $type }} border-0 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-{{ $type === 'success' ? 'check-circle' : 'exclamation-triangle' }}"></i>{{ session($key) }}
            </div>
        @endif
    @endforeach

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    document.querySelectorAll('.js-select').forEach(el => new TomSelect(el, {allowEmptyOption: true}));
    document.querySelectorAll('form[data-confirm]').forEach(f => f.addEventListener('submit', e => {
        if (!confirm(f.dataset.confirm)) e.preventDefault();
    }));
</script>
@stack('scripts')
</body>
</html>
