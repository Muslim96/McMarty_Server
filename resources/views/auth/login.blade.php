<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; min-height:100vh; display:grid; place-items:center; background:linear-gradient(135deg,#111827,#312e81 60%,#4f46e5); padding:16px; }
        .box { width:100%; max-width:410px; background:#fff; border-radius:20px; padding:36px; box-shadow:0 30px 60px rgba(0,0,0,.35); }
        .logo { width:56px; height:56px; border-radius:16px; background:#4f46e5; color:#fff; display:grid; place-items:center; font-size:1.7rem; margin:0 auto 14px; }
        .form-control { border-radius:10px; padding:.7rem .9rem; }
        .btn-primary { background:#4f46e5; border-color:#4f46e5; border-radius:10px; padding:.7rem; font-weight:600; }
    </style>
</head>
<body>
<div class="box">
    <div class="logo"><i class="bi bi-egg-fried"></i></div>
    <h4 class="text-center fw-bold mb-1">Staff<span style="color:#4f46e5">Admin</span></h4>
    <p class="text-center text-muted mb-4">Войдите в панель управления</p>
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Запомнить меня</label>
        </div>
        <button class="btn btn-primary w-100">Войти</button>
    </form>
</div>
</body>
</html>
