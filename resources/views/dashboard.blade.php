@extends('layouts.app')
@section('title', 'Дашборд')
@section('subtitle', 'Добро пожаловать, ' . auth()->user()->name)

@section('content')
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['Рестораны', $stats['restaurants'], 'bi-shop', '#eef2ff', '#4f46e5'],
            ['Активные сотрудники', $stats['employers'], 'bi-people', '#ecfdf5', '#059669'],
            ['Неоплаченные штрафы', null, 'bi-hourglass-split', '#fffbeb', '#d97706', $stats['active_fines']],
            ['Штрафы за месяц', null, 'bi-receipt', '#fef2f2', '#dc2626', $stats['month_fines']],
        ];
    @endphp
    @foreach ($cards as $c)
        <div class="col-sm-6 col-xl-3">
            <div class="card stat">
                <div class="icon" style="background:{{ $c[3] }};color:{{ $c[4] }}"><i class="bi {{ $c[2] }}"></i></div>
                <div>
                    <div class="text-muted small">{{ $c[0] }}</div>
                    <div class="val">@if (isset($c[5])) @money($c[5]) @else {{ $c[1] }} @endif</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">Последние штрафы
                @can('fines.view')<a href="{{ route('fines.index') }}" class="btn btn-sm btn-light">Все</a>@endcan
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Сотрудник</th><th>Причина</th><th>Сумма</th><th>Статус</th></tr></thead>
                    <tbody>
                    @forelse ($recent as $f)
                        <tr>
                            <td><div class="fw-semibold">{{ $f->employer->full_name }}</div><small class="text-muted">{{ $f->employer->restaurant->name }}</small></td>
                            <td>{{ $f->reason?->name ?? '-' }}</td>
                            <td class="fw-semibold">@money($f->amount)</td>
                            <td><span class="badge text-bg-{{ $f->status_color }}">{{ $f->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty"><i class="bi bi-emoji-smile"></i>Штрафов пока нет</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">Рестораны по штату</div>
            <ul class="list-group list-group-flush">
                @forelse ($restaurants as $r)
                    <li class="list-group-item d-flex align-items-center gap-3 py-3">
                        @if ($r->logo_url)<img src="{{ $r->logo_url }}" class="logo-sm" alt="">@else<div class="logo-sm"><i class="bi bi-shop"></i></div>@endif
                        <div class="flex-grow-1 fw-semibold">{{ $r->name ?? $r->slug }}</div>
                        <span class="badge text-bg-light">{{ $r->active_employers_count }}</span>
                    </li>
                @empty
                    <li class="list-group-item"><div class="empty">Нет ресторанов</div></li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
