@extends('layouts.app')
@section('title', 'Штрафы')
@section('actions')
    @can('fines.create')<a href="{{ route('fines.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Назначить штраф</a>@endcan
@endsection
@section('content')
<div class="row g-3 mb-3">
    @foreach (\App\Models\Fine::STATUSES as $key => $s)
        <div class="col-md-4"><div class="card stat">
            <div class="icon bg-{{ $s['color'] }}-subtle text-{{ $s['color'] }}-emphasis"><i class="bi bi-{{ ['active' => 'hourglass-split', 'paid' => 'check2-circle', 'cancelled' => 'x-circle'][$key] }}"></i></div>
            <div><div class="text-muted small">{{ $s['label'] }}</div><div class="val">@money($totals[$key] ?? 0)</div></div>
        </div></div>
    @endforeach
</div>
<div class="card">
    <div class="card-body border-bottom">
        <form class="row g-2">
            <div class="col-md-3"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Сотрудник..."></div>
            @if ($restaurants->count() > 1)
                <div class="col-md-2"><select name="restaurant_id" class="form-select"><option value="">Все рестораны</option>
                    @foreach ($restaurants as $r)<option value="{{ $r->id }}" @selected(request('restaurant_id') == $r->id)>{{ $r->name ?? $r->slug }}</option>@endforeach
                </select></div>
            @endif
            <div class="col-md-2"><select name="status" class="form-select"><option value="">Любой статус</option>
                @foreach (\App\Models\Fine::STATUSES as $k => $s)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $s['label'] }}</option>@endforeach
            </select></div>
            <div class="col-md-2"><input type="date" name="from" value="{{ request('from') }}" class="form-control" title="С даты"></div>
            <div class="col-md-2"><input type="date" name="to" value="{{ request('to') }}" class="form-control" title="По дату"></div>
            <div class="col-auto"><button class="btn btn-light">Применить</button><a href="{{ route('fines.index') }}" class="btn btn-link text-muted">Сбросить</a></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Дата</th><th>Сотрудник</th><th>Причина</th><th>Сумма</th><th>Статус</th><th>Назначил</th><th></th></tr></thead>
            <tbody>
            @forelse ($fines as $fine)
                <tr>
                    <td class="text-nowrap">{{ $fine->fined_at->format('d.m.Y') }}</td>
                    <td><a href="{{ route('employers.show', $fine->employer_id) }}" class="fw-semibold text-decoration-none">{{ $fine->employer->full_name }}</a>
                        <div><small class="text-muted">{{ $fine->employer->restaurant->name ?? $fine->employer->restaurant->slug }}</small></div></td>
                    <td><div>{{ $fine->reason?->name ?? 'Без причины' }}</div>@if ($fine->comment)<small class="text-muted">{{ \Illuminate\Support\Str::limit($fine->comment, 60) }}</small>@endif</td>
                    <td class="fw-semibold text-nowrap">@money($fine->amount)</td>
                    <td><span class="badge text-bg-{{ $fine->status_color }}">{{ $fine->status_label }}</span>
                        @if ($fine->paid_at)<div><small class="text-muted">{{ $fine->paid_at->format('d.m.Y') }}</small></div>@endif</td>
                    <td class="text-muted small">{{ $fine->issuer?->name ?? '-' }}</td>
                    <td class="text-end text-nowrap">@include('fines._actions', ['fine' => $fine])</td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty"><i class="bi bi-receipt"></i>Штрафов не найдено</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $fines->links() }}</div>
@endsection
