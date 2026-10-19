@extends('layouts.app')
@section('title', 'Причины штрафов')
@section('subtitle', 'Справочник типовых нарушений и сумм по умолчанию')
@section('actions')<a href="{{ route('fine-reasons.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Добавить</a>@endsection
@section('content')
<div class="card"><div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead><tr><th>Название</th><th>Сумма по умолчанию</th><th>Использована</th><th>Статус</th><th></th></tr></thead>
        <tbody>
        @forelse ($reasons as $r)
            <tr>
                <td class="fw-semibold">{{ $r->name }}</td>
                <td>{{ $r->default_amount > 0 ? '' : '-' }}@if ($r->default_amount > 0)@money($r->default_amount)@endif</td>
                <td>{{ $r->fines_count }}</td>
                <td><span class="badge text-bg-{{ $r->is_active ? 'success' : 'secondary' }}">{{ $r->is_active ? 'Активна' : 'Скрыта' }}</span></td>
                <td class="text-end">
                    <a href="{{ route('fine-reasons.edit', $r) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                    <form method="POST" action="{{ route('fine-reasons.destroy', $r) }}" class="d-inline" data-confirm="Удалить причину? В старых штрафах причина станет пустой.">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5"><div class="empty"><i class="bi bi-list-check"></i>Справочник пуст</div></td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
@endsection
