<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\Http\Controllers;

use ArtemYurov\MailRuAuth\Http\Integrations\MailRu\MailRuConnector;
use ArtemYurov\MailRuAuth\Http\Integrations\MailRu\Requests\UserinfoRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MoonShine\Laravel\MoonShineAuth;
use MoonShine\Support\Enums\ToastType;

final class MailRuController
{
    /**
     * Редирект на страницу авторизации Mail.ru.
     */
    public function auth(Request $request): RedirectResponse
    {
        $remember = $request->boolean('remember');
        $promptForce = $request->boolean('prompt_force');

        session(['mailru_remember' => $remember]);

        $connector = new MailRuConnector();

        // https://id.vk.com/about/business/go/docs/ru/vkid/latest/oauth/oauth-mail/index
        // prompt_force=1 — запросить разрешение на доступ к аккаунту заново
        return redirect()->to(
            $connector->getAuthorizationUrl(
                additionalQueryParameters: ['prompt_force' => $promptForce],
            ),
        );
    }

    /**
     * Обработка callback от Mail.ru после авторизации.
     */
    public function callback(Request $request): RedirectResponse
    {
        $code = $request->input('code');
        $state = $request->input('state');

        if (!$code || !$state) {
            return $this->redirectWithError('Ошибка авторизации: отсутствует код подтверждения.');
        }

        $connector = new MailRuConnector();

        try {
            $authenticator = $connector->getAccessToken($code, $state);
        } catch (\Throwable $e) {
            Log::error('Mail.ru OAuth: ошибка обмена code на token', [
                'message' => $e->getMessage(),
            ]);

            return $this->redirectWithError('Ошибка авторизации: не удалось получить токен.');
        }

        try {
            $response = $connector->send(new UserinfoRequest($authenticator->getAccessToken()));
            $email = $response->json('email');
        } catch (\Throwable $e) {
            Log::error('Mail.ru OAuth: ошибка получения userinfo', [
                'message' => $e->getMessage(),
            ]);

            return $this->redirectWithError('Ошибка авторизации: не удалось получить информацию о пользователе.');
        }

        if (!$email) {
            return $this->redirectWithError('Ошибка авторизации: email не получен от Mail.ru.');
        }

        $userModel = $this->resolveUserModel();
        $emailField = (string) config('mailru-auth.email_field', 'email');

        $user = $userModel::query()->where($emailField, $email)->first();

        if ($user === null) {
            return $this->redirectWithError("Пользователь с email {$email} не найден в системе.");
        }

        $remember = (bool) session('mailru_remember', false);
        session()->forget('mailru_remember');

        MoonShineAuth::getGuard()->loginUsingId($user->getKey(), $remember);

        return redirect()->route(moonshineConfig()->getHomeRoute());
    }

    /**
     * @return class-string<Model>
     */
    private function resolveUserModel(): string
    {
        $model = (string) config('mailru-auth.user_model', '\\App\\Models\\User');

        if (!class_exists($model)) {
            throw new \RuntimeException(
                "MailRu Auth: модель пользователя {$model} не найдена. "
                . "Укажите корректный класс в config/mailru-auth.php => user_model."
            );
        }

        return $model;
    }

    private function redirectWithError(string $message): RedirectResponse
    {
        session()->flash('alert', $message);
        toast($message, ToastType::ERROR);

        return redirect(moonshineRouter()->to('login'));
    }
}
