@extends('layouts.app')
@section('title', $employer->exists ? 'Редактирование сотрудника' : 'Новый сотрудник')
@section('content')
@php
    $f = fn ($k, $d = null) => old($k, $employer->{$k} instanceof \Carbon\Carbon ? $employer->{$k}->format('Y-m-d') : $employer->{$k});
    $inp = fn ($name, $label, $type = 'text', $req = false) => compact('name', 'label', 'type', 'req');
@endphp
<div class="card" style="max-width:900px">
    <div class="card-body p-4">
        <form method="POST" action="{{ $employer->exists ? route('employers.update', $employer) : route('employers.store') }}">
            @csrf @if ($employer->exists) @method('PUT') @endif
            <div class="row g-3">
                @foreach ([
                    ['surname', 'Фамилия', 'text', true, 4], ['name', 'Имя', 'text', true, 4], ['patronymic', 'Отчество', 'text', false, 4],
                    ['birth_date', 'Дата рождения', 'date', false, 4], ['phone', 'Телефон', 'text', false, 4], ['email', 'Email', 'email', false, 4],
                    ['position', 'Должность', 'text', false, 4], ['hired_at', 'Дата найма', 'date', false, 4],
                ] as [$name, $label, $type, $req, $col])
                    <div class="col-md-{{ $col }}">
                        <label class="form-label">{{ $label }}{{ $req ? ' *' : '' }}</label>
                        <input type="{{ $type }}" name="{{ $name }}" value="{{ $f($name) }}" class="form-control @error($name) is-invalid @enderror" @required($req)>
                        @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endforeach
                <div class="col-md-4">
                    <label class="form-label">Ресторан *</label>
                    <select name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror" required>
                        <option value="">Выберите...</option>
                        @foreach ($restaurants as $r)
                            <option value="{{ $r->id }}" @selected(old('restaurant_id', $employer->restaurant_id) == $r->id)>{{ $r->name ?? $r->slug }}</option>
                        @endforeach
                    </select>
                    @error('restaurant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" @checked(old('is_active', $employer->is_active ?? true))>
                        <label class="form-check-label" for="is_active">Работает в ресторане</label>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <button class="btn btn-primary">Сохранить</button>
            <a href="{{ $employer->exists ? route('employers.show', $employer) : route('employers.index') }}" class="btn btn-light">Отмена</a>
        </form>
    </div>
</div>
@endsection
