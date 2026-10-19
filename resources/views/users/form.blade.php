@extends('layouts.app')
@section('title', $user->exists ? 'Редактирование пользователя' : 'Новый пользователь')
@section('content')
<div class="card" style="max-width:760px"><div class="card-body p-4">
    <form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
        @csrf @if ($user->exists) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Имя *</label>
                <input name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Пароль {{ $user->exists ? '(оставьте пустым, чтобы не менять)' : '*' }}</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" @required(! $user->exists)>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Роль *</label>
                <select name="role_id" id="role" class="form-select @error('role_id') is-invalid @enderror" required>
                    @foreach ($roles as $r)
                        <option value="{{ $r->id }}" data-slug="{{ $r->slug }}" @selected(old('role_id', $user->role_id) == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12" id="restaurant-box">
                <label class="form-label">Ресторан (для менеджера) *</label>
                <select name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror">
                    <option value="">Выберите...</option>
                    @foreach ($restaurants as $r)
                        <option value="{{ $r->id }}" @selected(old('restaurant_id', $user->restaurant_id) == $r->id)>{{ $r->name ?? $r->slug }}</option>
                    @endforeach
                </select>
                @error('restaurant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" @checked(old('is_active', $user->is_active ?? true))>
                    <label class="form-check-label" for="is_active">Аккаунт активен</label>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <button class="btn btn-primary">Сохранить</button>
        <a href="{{ route('users.index') }}" class="btn btn-light">Отмена</a>
    </form>
</div></div>
@push('scripts')
<script>
    const role = document.getElementById('role'), box = document.getElementById('restaurant-box');
    const sync = () => box.style.display = role.selectedOptions[0]?.dataset.slug === 'manager' ? '' : 'none';
    role.addEventListener('change', sync); sync();
</script>
@endpush
@endsection
