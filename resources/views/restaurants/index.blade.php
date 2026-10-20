@extends('layouts.app')
@section('title', 'Рестораны')
@section('actions')
    <a href="{{ route('restaurants.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Добавить</a>
@endsection
@section('content')
<div class="card">
    <div class="card-body border-bottom">
        <form class="row g-2"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Поиск по названию..."></div>
            <div class="col-auto"><button class="btn btn-light">Найти</button></div></form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Ресторан</th><th>Slug</th><th>Города</th><th>Сотрудников</th><th class="text-end">Действия</th></tr></thead>
            <tbody>
            @forelse ($restaurants as $r)
                <tr>
                    <td><div class="d-flex align-items-center gap-3">
                        @if ($r->logo_url)<img src="{{ $r->logo_url }}" class="logo-sm" alt="">@else<div class="logo-sm"><i class="bi bi-shop"></i></div>@endif
                        <span class="fw-semibold">{{ $r->name ?? '-' }}</span></div></td>
                    <td><code>{{ $r->slug }}</code></td>
                    <td>@forelse ($r->cities as $c)<span class="badge text-bg-light me-1">{{ $c->name }}</span>@empty<span class="text-muted">—</span>@endforelse</td>
                    <td><a href="{{ route('employers.index', ['restaurant_id' => $r->id]) }}" class="badge text-bg-light text-decoration-none">{{ $r->employers_count }}</a></td>
                    <td class="text-end">
                        <a href="{{ route('restaurants.edit', $r) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('restaurants.destroy', $r) }}" class="d-inline" data-confirm="Удалить ресторан вместе со всеми сотрудниками?">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty"><i class="bi bi-shop"></i>Ресторанов нет</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $restaurants->links() }}</div>
@endsection
