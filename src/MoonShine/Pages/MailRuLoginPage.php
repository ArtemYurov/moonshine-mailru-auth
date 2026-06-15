<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\MoonShine\Pages;

use ArtemYurov\MailRuAuth\MoonShine\Components\CollapsibleAdminLogin;
use ArtemYurov\MailRuAuth\MoonShine\Components\MailRuAuth;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Core\Attributes\Layout;
use MoonShine\Laravel\Layouts\LoginLayout;
use MoonShine\Laravel\Pages\LoginPage as BaseLoginPage;
use MoonShine\MenuManager\Attributes\SkipMenu;
use MoonShine\UI\Components\Layout\Divider;

/**
 * Страница логина с кнопкой Mail.ru сверху и раскрывающейся стандартной формой админа снизу.
 *
 * Используется через config/moonshine.php:
 *   'pages' => [
 *       'login' => \ArtemYurov\MailRuAuth\MoonShine\Pages\MailRuLoginPage::class,
 *   ],
 *
 * Или extends в своём проекте, если нужно добавить ещё компонентов.
 */
#[SkipMenu]
#[Layout(LoginLayout::class)]
class MailRuLoginPage extends BaseLoginPage
{
    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        return [
            MailRuAuth::make(),
            Divider::make(),
            CollapsibleAdminLogin::make(parent::components()),
        ];
    }
}
