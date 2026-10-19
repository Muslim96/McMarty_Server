@extends('layouts.app')
@section('title', $fine->exists ? 'Редактирование штрафа' : 'Назначение штрафа')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $fine->exists ? route('fines.update', $fine) : route('fines.store') }}">
            @csrf @if ($fine->exists) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Сотрудник *</label>
                    <select name="employer_id" class="js-select @error('employer_id') is-invalid @enderror" required placeholder="Начните вводить фамилию...">
                        <option value="">Выберите...</option>
                        @foreach ($employers as $e)
                            <option value="{{ $e->id }}" @selected(old('employer_id', $fine->employer_id) == $e->id)>{{ $e->full_name }} - {{ $e->restaurant->name ?? $e->restaurant->slug }}</option>
                        @endforeach
                    </select>
                    @error('employer_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Причина</label>
                    <select name="fine_reason_id" id="reason" class="form-select">
                        <option value="">Без причины / другая</option>
                        @foreach ($reasons as $r)
                            <option value="{{ $r->id }}" data-amount="{{ $r->default_amount }}" @selected(old('fine_reason_id', $fine->fine_reason_id) == $r->id)>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Сумма, {{ config('admin.currency') }} *</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount', $fine->amount) }}" class="form-control @error('amount') is-invalid @enderror" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Дата *</label>
                    <input type="date" name="fined_at" value="{{ old('fined_at', optional($fine->fined_at)->format('Y-m-d')) }}" class="form-control @error('fined_at') is-invalid @enderror" required>
                    @error('fined_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Комментарий</label>
                    <textarea name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror" placeholder="Подробности нарушения">{{ old('comment', $fine->comment) }}</textarea>
                    @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <hr class="my-4">
            <button class="btn btn-primary">Сохранить</button>
            <a href="{{ route('fines.index') }}" class="btn btn-light">Отмена</a>
        </form>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('reason').addEventListener('change', e => {
        const a = e.target.selectedOptions[0]?.dataset.amount;
        if (a && parseFloat(a) > 0) document.getElementById('amount').value = a;
    });
</script>
@endpush
@endsection
