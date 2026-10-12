# ЛР4 — API-тесты на Laravel 8

## Безопасность

В репозитории НЕ хранятся секреты. Токены Sanctum не коммитятся.
Переменные auth_token задаются локально в Bruno.
Файлы результатов прогона (bruno/results*.json) — в .gitignore.

Назначение проекта: **ЛР4 — REST API + автотесты**. Приложение реализует API регистрации/аутентификации
через **Laravel Sanctum (Bearer-токены)**, отдаёт JSON-ресурсы и содержит набор feature-тестов PHPUnit,
проверяющих контракт API (коды ответов, JSON-схему, валидацию), плюс UI-форму регистрации из ЛР3.

## Стек

| Компонент | Версия |
|---|---|
| PHP | 7.4 |
| Laravel | 8 (`laravel/framework ^8.75`) |
| Laravel Sanctum | 2.x (`laravel/sanctum ^2.11`) |
| PHPUnit | 9 (`phpunit/phpunit ^9.5.10`) |
| БД (прод) | MySQL (`.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=laravel`) |
| БД (тесты) | SQLite `:memory:` (задано в `phpunit.xml`) |

## Endpoints API (5 основных)

| # | Метод | URL | Middleware | Описание | Успешный ответ |
|---|---|---|---|---|---|
| 1 | POST | `/api/register` | — | Регистрация: `name`, `email`, `password`, `password_confirmation` | `201` `{user, token}` |
| 2 | POST | `/api/login` | — | Вход: `email`, `password` | `200` `{user, token}` |
| 3 | POST | `/api/logout` | `auth:sanctum` | Выход: удаляет текущий токен (`Authorization: Bearer …`) | `200` `{message}` |
| 4 | GET | `/api/profile` | `auth:sanctum` | Профиль текущего пользователя (UserResource) | `200` `{data:{id,name,email,created_at}}` |
| 5 | GET | `/api/users/{id}` | `auth:sanctum` | Пользователь по идентификатору | `200` `{data:{…}}`, `404` если нет |

Коды ошибок: `401` — нет/неверный токен или неверные учётные данные, `422` — ошибка валидации (`errors`),
`404` — несуществующий эндпоинт или ресурс.

Дополнительно (вспомогательные роуты `routes/web.php`): `GET /api-status` (JSON-статус) и
`GET /ui/register.html` (отдаёт файл формы).

## Запуск через OpenServer

1. Домен OpenServer: **test4** — корень домена указывает на каталог проекта `domains/test4`,
   поэтому все URL Laravel содержат префикс `/public/` (публичная папка проекта).
   Базовый URL API: **`http://test4/public`** — именно он задан в `bruno/environments/Local.bru`
   (переменная `base_url`) и в `postman/collection.json`.
   Если в настройках OpenServer указать корнем папку `public`, префикс `/public/` станет не нужен,
   но тогда `base_url` в Bruno/Postman надо поменять на `http://test4`.
2. Запустите Apache/Nginx + MySQL в OpenServer.
3. `.env` уже настроен: `DB_CONNECTION=mysql`, `DB_DATABASE=laravel`. При необходимости выполните
   `php artisan migrate` (вручную, вне автогенерации) — созданы миграции `users` и `personal_access_tokens`.
4. Откройте `http://test4/public/` — главная страница, `http://test4/public/ui/register.html` — форма регистрации,
   `http://test4/public/api-status` — JSON-статус API.

## Запуск тестов

```bash
php artisan test
```

Или напрямую:

```bash
vendor/bin/phpunit
```

Тесты используют SQLite in-memory (`phpunit.xml`: `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`)
и трейт `RefreshDatabase`, поэтому реальная MySQL-база не затрагивается.

Основной набор ЛР4 — **17 тестов**: `tests/Feature/ApiTest.php` (16 тестов: успешная регистрация,
невалидный email, дубликат email, успешный вход, неверный пароль, доступ к `/api/profile` без токена
и с невалидным токеном, пустое тело регистрации, проверка JSON-схемы ответа, несуществующий эндпоинт
+ 6 boundary-тестов границ валидации: пароль 7/8/64/65 символов и `name` 255/256 символов)
и `tests/Feature/ApiUiBundleTest.php` (1 тест: связка «создание через API + открытие UI»).

Вместе со стандартными `tests/Feature/ExampleTest.php` (`GET /` → `200`) и `tests/Unit/ExampleTest.php`
PHPUnit запускает всего **19 тестов**.

## UI

Статическая форма регистрации (перенесена из ЛР3) лежит в `public/ui/`:

- `public/ui/register.html`
- `public/ui/style.css`
- `public/ui/script.js`

Открывается по адресу `http://test4/public/ui/register.html` (роут отдаёт файл через `response()->file()`).

## CI (GitHub Actions)

`.github/workflows/ci.yml` запускает шаг **«Run API tests (PHPUnit)»** → `php artisan test --testsuite=Feature`
при `push`/`pull_request` в ветки `main`/`master`:
PHP 7.4, расширения `mbstring, pdo, pdo_sqlite, sqlite3`, `composer install`, `php artisan key:generate`.

## Автоматизация API-тестов (PHPUnit)

Для автоматизации API выбран **PHPUnit** — штатный инструмент Laravel 8 для тестирования.

**Почему PHPUnit:**

- встроен в Laravel 8, не требует дополнительных пакетов;
- выполняет HTTP-запросы к API через `$this->postJson()`, `$this->getJson()`;
- проверяет коды ответов (`assertStatus`), JSON-структуру (`assertJsonStructure`), ошибки валидации (`assertJsonValidationErrors`);
- запускается командой `php artisan test`;
- интегрируется в CI (GitHub Actions) без дополнительной настройки.

**Что покрыто (19 тестов):**

`tests/Feature/ApiTest.php` — 16 тестов:

- Регистрация: успешная, невалидный email, дубликат email, пустое тело;
- Логин: успешный, неверный пароль;
- Профиль: без токена, с невалидным токеном, схема ответа;
- Пользователь по id;
- Несуществующий endpoint (404);
- Boundary: пароль 7/8/64/65 символов, name 255/256 символов.

`tests/Feature/ApiUiBundleTest.php` — 1 тест:

- Связка «создание через API + открытие UI».

`tests/Feature/ExampleTest.php` и `tests/Unit/ExampleTest.php` — 2 стандартных теста Laravel.

**Запуск:**

```bash
php artisan test
# или только API-тесты:
php artisan test --testsuite=Feature
```

**В CI:** шаг «Run API tests (PHPUnit)» запускает `php artisan test --testsuite=Feature` при push/PR.

## Postman

`postman/collection.json` — Collection v2.1 с **11 запросами**; переменная `base_url = http://test4/public`.
Токен для запросов с `Authorization: Bearer {{auth_token}}` берётся из ответа `POST /api/login`
(поле `token` сохраняется в переменную `auth_token`). Запрос 11 — `POST /api/logout` (выход, `200 {"message":"Logged out"}`);
после него токен перестаёт действовать, поэтому выполняйте его последним.

## Структура проекта

```
app/
  Http/
    Controllers/
      Api/              — AuthController, ProfileController
    Kernel.php          — middleware-группы (в api включён EnsureFrontendRequestsAreStateful)
    Requests/           — RegisterRequest, LoginRequest
    Resources/          — UserResource
  Models/               — User (HasApiTokens)
database/
  factories/            — UserFactory
  migrations/           — users, personal_access_tokens
public/
  ui/                   — register.html, style.css, script.js
routes/
  api.php               — /register, /login, /logout, /profile, /users/{id}
  web.php               — /, /api-status, /ui/register.html
tests/
  Feature/              — ApiTest, ApiUiBundleTest, ExampleTest
  Unit/                 — ExampleTest
postman/collection.json — 11 запросов для ручной проверки API
.github/workflows/ci.yml — CI: php artisan test
phpunit.xml             — тестовое окружение (sqlite :memory:)
```
