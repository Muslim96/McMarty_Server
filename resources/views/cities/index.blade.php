@extends('layouts.app')
@section('title', 'Города')
@section('actions')<a href="{{ route('cities.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Добавить</a>@endsection
@section('content')
    <div class="card">
        <div class="card-body border-bottom">
            <form class="row g-2"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Поиск по названию..."></div>
                <div class="col-auto"><button class="btn btn-light">Найти</button></div></form>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Город</th><th>Ресторанов</th><th>Сотрудников</th><th class="text-end">Действия</th></tr></thead>
                <tbody>
                @forelse ($cities as $c)
                    <tr>
                        <td class="fw-semibold"><i class="bi bi-geo-alt text-primary me-2"></i>{{ $c->name }}</td>
                        <td>{{ $c->restaurants_count }}</td>
                        <td>{{ $c->employers_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('cities.edit', $c) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('cities.destroy', $c) }}" class="d-inline" data-confirm="Удалить город? Он будет убран у ресторанов и сотрудников.">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty"><i class="bi bi-geo-alt"></i>Городов пока нет</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $cities->links() }}</div>
@endsection
