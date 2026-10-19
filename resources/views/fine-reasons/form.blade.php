@extends('layouts.app')
@section('title', $reason->exists ? 'Редактирование причины' : 'Новая причина')
@section('content')
<div class="card" style="max-width:620px"><div class="card-body p-4">
    <form method="POST" action="{{ $reason->exists ? route('fine-reasons.update', $reason) : route('fine-reasons.store') }}">
        @csrf @if ($reason->exists) @method('PUT') @endif
        <div class="mb-3">
            <label class="form-label">Название *</label>
            <input name="name" value="{{ old('name', $reason->name) }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Сумма по умолчанию, {{ config('admin.currency') }}</label>
            <input type="number" step="0.01" min="0" name="default_amount" value="{{ old('default_amount', $reason->default_amount) }}" class="form-control @error('default_amount') is-invalid @enderror">
            @error('default_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-check form-switch mb-4">
            <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" @checked(old('is_active', $reason->is_active ?? true))>
            <label class="form-check-label" for="is_active">Активна (доступна при назначении штрафа)</label>
        </div>
        <button class="btn btn-primary">Сохранить</button>
        <a href="{{ route('fine-reasons.index') }}" class="btn btn-light">Отмена</a>
    </form>
</div></div>
@endsection
