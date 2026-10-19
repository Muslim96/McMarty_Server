@extends('layouts.app')
@section('title', $employer->full_name)
@section('subtitle', $employer->restaurant->name ?? $employer->restaurant->slug)
@section('actions')
    <a href="{{ route('employers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i>Назад</a>
    @can('fines.create')<a href="{{ route('fines.create', ['employer_id' => $employer->id]) }}" class="btn btn-warning"><i class="bi bi-receipt me-1"></i>Назначить штраф</a>@endcan
    @can('employers.manage')<a href="{{ route('employers.edit', $employer) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Изменить</a>@endcan
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card"><div class="card-body p-4">
            <div class="text-center mb-3">
                <div class="ava-sm mx-auto mb-2" style="width:72px;height:72px;font-size:1.5rem">{{ $employer->initials }}</div>
                <div class="fw-bold fs-5">{{ $employer->full_name }}</div>
                <div class="text-muted">{{ $employer->position ?? 'Должность не указана' }}</div>
                <span class="badge text-bg-{{ $employer->is_active ? 'success' : 'secondary' }} mt-2">{{ $employer->is_active ? 'Работает' : 'Уволен' }}</span>
            </div>
            <dl class="row mb-0 small">
                <dt class="col-5 text-muted fw-normal">Телефон</dt><dd class="col-7">{{ $employer->phone ?? '-' }}</dd>
                <dt class="col-5 text-muted fw-normal">Email</dt><dd class="col-7 text-break">{{ $employer->email ?? '-' }}</dd>
                <dt class="col-5 text-muted fw-normal">Дата рождения</dt><dd class="col-7">{{ $employer->birth_date?->format('d.m.Y') ?? '-' }}</dd>
                <dt class="col-5 text-muted fw-normal">Принят</dt><dd class="col-7">{{ $employer->hired_at?->format('d.m.Y') ?? '-' }}</dd>
            </dl>
        </div></div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">История штрафов
                <span class="text-muted fw-normal">К оплате: <b class="text-danger">@money($fines->where('status', 'active')->sum('amount'))</b></span></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Дата</th><th>Причина</th><th>Сумма</th><th>Статус</th><th></th></tr></thead>
                    <tbody>
                    @forelse ($fines as $fine)
                        <tr>
                            <td>{{ $fine->fined_at->format('d.m.Y') }}</td>
                            <td><div>{{ $fine->reason?->name ?? 'Без причины' }}</div>
                                @if ($fine->comment)<small class="text-muted">{{ $fine->comment }}</small>@endif
                                <div><small class="text-muted">Назначил: {{ $fine->issuer?->name ?? '-' }}</small></div></td>
                            <td class="fw-semibold">@money($fine->amount)</td>
                            <td><span class="badge text-bg-{{ $fine->status_color }}">{{ $fine->status_label }}</span></td>
                            <td class="text-end text-nowrap">@include('fines._actions', ['fine' => $fine])</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="empty"><i class="bi bi-emoji-smile"></i>Штрафов не было</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
