@extends('layouts.app')
@section('title', 'Сотрудники')
@section('actions')
    @can('employers.manage')<a href="{{ route('employers.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Добавить</a>@endcan
@endsection
@section('content')
<div class="card">
    <div class="card-body border-bottom">
        <form class="row g-2">
            <div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="ФИО, телефон, email..."></div>
            @if ($restaurants->count() > 1)
                <div class="col-md-3"><select name="restaurant_id" class="form-select">
                    <option value="">Все рестораны</option>
                    @foreach ($restaurants as $r)<option value="{{ $r->id }}" @selected(request('restaurant_id') == $r->id)>{{ $r->name ?? $r->slug }}</option>@endforeach
                </select></div>
            @endif
            <div class="col-md-2"><select name="status" class="form-select">
                <option value="">Любой статус</option>
                <option value="1" @selected(request('status') === '1')>Работает</option>
                <option value="0" @selected(request('status') === '0')>Уволен</option>
            </select></div>
            <div class="col-auto"><button class="btn btn-light">Применить</button>
                <a href="{{ route('employers.index') }}" class="btn btn-link text-muted">Сбросить</a></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Сотрудник</th><th>Ресторан</th><th>Должность</th><th>Контакты</th><th>Долг по штрафам</th><th>Статус</th><th></th></tr></thead>
            <tbody>
            @forelse ($employers as $e)
                <tr>
                    <td><a href="{{ route('employers.show', $e) }}" class="d-flex align-items-center gap-3 text-decoration-none text-reset">
                        <div class="ava-sm">{{ $e->initials }}</div><span class="fw-semibold">{{ $e->full_name }}</span></a></td>
                    <td>{{ $e->restaurant->name ?? $e->restaurant->slug }}@if ($e->city)<div><small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $e->city->name }}</small></div>@endif</td>
                    <td>{{ $e->position ?? '-' }}</td>
                    <td><div>{{ $e->phone ?? '-' }}</div><small class="text-muted">{{ $e->email }}</small></td>
                    <td>@if ($e->active_fines_sum > 0)<span class="text-danger fw-semibold">@money($e->active_fines_sum)</span>@else<span class="text-muted">-</span>@endif</td>
                    <td><span class="badge text-bg-{{ $e->is_active ? 'success' : 'secondary' }}">{{ $e->is_active ? 'Работает' : 'Уволен' }}</span></td>
                    <td class="text-end text-nowrap">
                        @can('fines.create')<a href="{{ route('fines.create', ['employer_id' => $e->id]) }}" class="btn btn-sm btn-light text-warning" title="Назначить штраф"><i class="bi bi-receipt"></i></a>@endcan
                        @can('employers.manage')
                            <a href="{{ route('employers.edit', $e) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('employers.destroy', $e) }}" class="d-inline" data-confirm="Удалить сотрудника и все его штрафы?">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty"><i class="bi bi-people"></i>Сотрудники не найдены</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $employers->links() }}</div>
@endsection
