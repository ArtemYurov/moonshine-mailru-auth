<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\MoonShine\Components;

use MoonShine\Core\Collections\Components;
use MoonShine\UI\Components\MoonShineComponent;

/**
 * Раскрывающийся блок «Вход для администраторов» на странице логина.
 * Оборачивает стандартную форму логина MoonShine.
 *
 * @method static static make(iterable $components)
 */
final class CollapsibleAdminLogin extends MoonShineComponent
{
    protected string $view = 'mailru-auth::collapsible-admin-login';

    public function __construct(
        protected iterable $components = [],
    ) {
        parent::__construct();
    }

    /**
     * @return array<string, mixed>
     */
    protected function viewData(): array
    {
        return [
            'components' => Components::make($this->components),
        ];
    }
}
