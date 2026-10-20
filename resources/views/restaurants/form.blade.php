@extends('layouts.app')
@section('title', $restaurant->exists ? 'Редактирование ресторана' : 'Новый ресторан')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data"
              action="{{ $restaurant->exists ? route('restaurants.update', $restaurant) : route('restaurants.store') }}">
            @csrf @if ($restaurant->exists) @method('PUT') @endif
            <div class="mb-3">
                <label class="form-label">Название *</label>
                <input name="name" value="{{ old('name', $restaurant->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input name="slug" value="{{ old('slug', $restaurant->slug) }}" class="form-control @error('slug') is-invalid @enderror" placeholder="Сгенерируется из названия">
                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Города, где работает ресторан</label>
                <select name="city_ids[]" multiple class="js-select" placeholder="Выберите города...">
                    @foreach ($cities as $c)
                        <option value="{{ $c->id }}" @selected(in_array($c->id, old('city_ids', $restaurant->cities->pluck('id')->all())))>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('city_ids.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label">Логотип</label>
                @if ($restaurant->logo_url)<div class="mb-2"><img src="{{ $restaurant->logo_url }}" class="logo-sm" style="width:64px;height:64px" alt=""></div>@endif
                <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary">Сохранить</button>
            <a href="{{ route('restaurants.index') }}" class="btn btn-light">Отмена</a>
        </form>
    </div>
</div>
@endsection
