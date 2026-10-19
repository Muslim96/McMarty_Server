# Restaurant Manager

## Запуск на Ubuntu
```bash
# Docker (один раз)
curl -fsSL https://get.docker.com | sh

cd /path/to/project
cp .env.example .env
docker compose up -d --build
docker compose logs -f app
```

## Обновление
```bash
git pull && docker compose exec app composer install --no-dev -o && docker compose restart app
```

## Роли
| Роль | Права |
|---|---|
| super_admin | всё, включая управление админами |
| admin | рестораны, сотрудники, штрафы (всё), причины, пользователи (кроме суперадминов) |
| manager | только свой ресторан: сотрудники (CRUD), назначение штрафов, просмотр |
| accountant | просмотр сотрудников и штрафов, отметка «оплачен» |

Права в `config/admin.php`.
