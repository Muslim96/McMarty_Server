@extends('layouts.app')
@section('title', 'Пользователи')
@section('subtitle', 'Доступ к панели управления')
@section('actions')<a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i>Добавить</a>@endsection
@section('content')
<div class="card">
    <div class="card-body border-bottom">
        <form class="row g-2"><div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Имя или email..."></div>
            <div class="col-auto"><button class="btn btn-light">Найти</button></div></form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Пользователь</th><th>Роль</th><th>Ресторан</th><th>Статус</th><th></th></tr></thead>
            <tbody>
            @foreach ($users as $u)
                <tr>
                    <td><div class="d-flex align-items-center gap-3"><div class="ava-sm">{{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}</div>
                        <div><div class="fw-semibold">{{ $u->name }}</div><small class="text-muted">{{ $u->email }}</small></div></div></td>
                    <td><span class="badge text-bg-primary-subtle text-primary-emphasis">{{ $u->role?->name }}</span></td>
                    <td>{{ $u->restaurant?->name ?? ($u->restaurant ? $u->restaurant->slug : '-') }}</td>
                    <td><span class="badge text-bg-{{ $u->is_active ? 'success' : 'secondary' }}">{{ $u->is_active ? 'Активен' : 'Отключён' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                        @if ($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $u) }}" class="d-inline" data-confirm="Удалить пользователя?">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
