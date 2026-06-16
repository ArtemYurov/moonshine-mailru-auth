# moonshine-mailru-auth

OAuth-авторизация через Mail.ru для MoonShine 4.x.

На странице логина добавляется большая синяя кнопка **«Почта Mail.ru»**, а стандартная админ-форма прячется под раскрывающийся блок «Вход для администраторов» (автоматически раскрывается при ошибках валидации).

**Где регистрировать OAuth-приложение:**

- Кабинет: <https://o2.mail.ru/app/>
- Документация: <https://id.vk.com/about/business/go/docs/ru/vkid/latest/oauth/oauth-mail/index>

После 1 июля 2025 новые регистрации OAuth Mail доступны только через VK ID (см. документацию выше). Пакет работает с уже выданными `client_id`/`client_secret`, а также с новыми, выпущенными через VK ID — endpoint `o2.mail.ru` остаётся прежним.

## Возможности

- Кнопка `MailRuAuth` на странице логина — OAuth Authorization Code grant через Saloon
- `CollapsibleAdminLogin` — Alpine.js аккордеон для стандартной формы MoonShine
- Готовый `MailRuLoginPage` (можно extends'ить)
- Авто-резолв модели пользователя из MoonShine guard (можно переопределить)
- Конфигурируемое поле для поиска по email
- Нормальная обработка ошибок: `Log::error()` + flash-алерт через `<x-moonshine::alert>` + `toast()`
- Поддержка `prompt_force` (выбор другого аккаунта Mail.ru)

## Требования

- PHP 8.3+
- Laravel 11 / 12
- MoonShine 4.x
- Eloquent-модель пользователя с полем `email`

## Установка

### 1. Composer

```bash
composer require artemyurov/moonshine-mailru-auth
```

ServiceProvider регистрируется через Laravel package auto-discovery.

### 2. Опубликовать конфиг

```bash
php artisan vendor:publish --tag=mailru-auth-config
```

Это создаст `config/mailru-auth.php`. Если кастомизировать настройки не нужно — пропустить шаг, дефолты возьмутся из пакета.

### 3. .env

`MAILRU_CLIENT_ID` / `MAILRU_CLIENT_SECRET` берутся в кабинете <https://o2.mail.ru/app/>.

```env
MAILRU_CLIENT_ID=
MAILRU_CLIENT_SECRET=

# Опционально — по умолчанию APP_URL/mailru/callback
# MAILRU_REDIRECT_URI=
```

### 4. config/moonshine.php

Указать кастомную страницу логина:

```php
'pages' => [
    // ...
    'login' => \ArtemYurov\MailRuAuth\MoonShine\Pages\MailRuLoginPage::class,
],
```

### 5. OAuth-приложение

В настройках OAuth-приложения (<https://o2.mail.ru/app/>) указать redirect URI:

```
<APP_URL>/mailru/callback
```

Готово. Заходи на `/admin/login` — увидишь кнопку Mail.ru.

## Конфигурация

`config/mailru-auth.php`:

```php
return [
    'client_id'       => env('MAILRU_CLIENT_ID'),
    'client_secret'   => env('MAILRU_CLIENT_SECRET'),
    'redirect_uri'    => env('MAILRU_REDIRECT_URI', '<APP_URL>/mailru/callback'),

    // null = модель берётся из MoonShine guard (рекомендуется).
    // Явный класс — для не-Eloquent guard'а или поиска в отдельной таблице.
    'user_model'      => null,

    // Поле, по которому ищется пользователь
    'email_field'     => 'email',

    // Регистрировать роуты mailru.auth / mailru.callback автоматически
    'register_routes' => true,
];
```

### Модель пользователя

По умолчанию пакет берёт класс модели **из MoonShine guard** — той же, через
которую потом происходит `loginUsingId()`. Это исключает рассинхрон: если
пакет ищет email в одной таблице, а guard логинит по id в другой, можно
случайно залогинить чужого пользователя с совпавшим id.

Цепочка резолва:

```
config('mailru-auth.user_model')          // явный override
       ↓ null
MoonShineAuth::getProvider()->getModel()  // ← дефолт
       ↓
config('auth.guards.<moonshine guard>.provider')
       ↓
config('auth.providers.<provider>.model')
```

Если MoonShine guard использует не Eloquent-провайдер (JWT/Sanctum/API), пакет
выбросит `RuntimeException` с подсказкой указать `user_model` явно.

## Использование

### Готовая страница логина

Прописать в `config/moonshine.php`:

```php
'pages' => [
    'login' => \ArtemYurov\MailRuAuth\MoonShine\Pages\MailRuLoginPage::class,
],
```

### Своя страница логина

Унаследовать и добавить любые компоненты:

```php
namespace App\MoonShine\Pages;

use ArtemYurov\MailRuAuth\MoonShine\Pages\MailRuLoginPage;

final class LoginPage extends MailRuLoginPage
{
    protected function components(): iterable
    {
        return [
            // Можно добавить свои компоненты перед/после стандартных
            ...parent::components(),
        ];
    }
}
```

### Только кнопка Mail.ru, без аккордеона

В своём `LoginPage`:

```php
use ArtemYurov\MailRuAuth\MoonShine\Components\MailRuAuth;
use MoonShine\Laravel\Pages\LoginPage as BaseLoginPage;

final class LoginPage extends BaseLoginPage
{
    protected function components(): iterable
    {
        return [
            MailRuAuth::make(),
            ...parent::components(),
        ];
    }
}
```

### Кастомизация views

```bash
php artisan vendor:publish --tag=mailru-auth-views
```

Views скопируются в `resources/views/vendor/mailru-auth/`. Можно править стили кнопки, аккордеона.

## Как работает OAuth flow

1. На LoginPage отрисовывается кнопка «Почта Mail.ru» (`MailRuAuth`)
2. Submit → `POST /mailru/auth` → редирект на `https://o2.mail.ru/login` с client_id и scope `userinfo`
3. Callback `GET /mailru/callback?code=...&state=...` → обмен на access_token
4. Запрос `GET /userinfo` → email пользователя
5. Поиск по модели пользователя (override из конфига либо авто из MoonShine guard) через `config('mailru-auth.email_field')`
6. Логин через `MoonShineAuth::getGuard()->loginUsingId()`
7. Редирект на `moonshineConfig()->getHomeRoute()`

При ошибках на любом шаге — `Log::error()` + `session()->flash('alert', ...)` + `toast()`, редирект обратно на login. Алёрт отображается в блоке `mail-ru-auth.blade.php`. Аккордеон админ-формы автоматически раскрывается при `$errors->any()`.

## Лицензия

MIT
