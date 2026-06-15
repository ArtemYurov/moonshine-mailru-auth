<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\MoonShine\Components;

use MoonShine\Core\Collections\Components;
use MoonShine\UI\Components\MoonShineComponent;
use MoonShine\UI\Fields\Switcher;

/**
 * Кнопка авторизации через Mail.ru на странице логина MoonShine.
 *
 * @method static static make()
 */
final class MailRuAuth extends MoonShineComponent
{
    protected string $view = 'mailru-auth::mail-ru-auth';

    protected iterable $components = [];

    public const string ROUTE_NAME = 'mailru.auth';

    public function __construct(string $name = 'default')
    {
        parent::__construct($name);

        $this->mergeAttribute('action', route(self::ROUTE_NAME));

        $this->components = Components::make([
            Switcher::make('Запомнить меня', 'remember')
                ->customWrapperAttributes(['style' => 'margin-top: 0.25rem;']),

            Switcher::make('Выбрать другого пользователя Mail.ru', 'prompt_force')
                ->customWrapperAttributes(['style' => 'margin-top: 0.25rem;']),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function viewData(): array
    {
        return [
            'components' => $this->components,
        ];
    }
}
