@extends('layouts.app')
@section('title', $city->exists ? 'Редактирование города' : 'Новый город')
@section('content')
    <div class="card" style="max-width:560px"><div class="card-body p-4">
            <form method="POST" action="{{ $city->exists ? route('cities.update', $city) : route('cities.store') }}">
                @csrf @if ($city->exists) @method('PUT') @endif
                <div class="mb-4">
                    <label class="form-label">Название *</label>
                    <input name="name" value="{{ old('name', $city->name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary">Сохранить</button>
                <a href="{{ route('cities.index') }}" class="btn btn-light">Отмена</a>
            </form>
        </div></div>
@endsection
