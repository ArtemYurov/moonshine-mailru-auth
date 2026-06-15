<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\Http\Integrations\MailRu\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class UserinfoRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $accessToken,
    ) {}

    public function resolveEndpoint(): string
    {
        $this->query()->add('access_token', $this->accessToken);

        return '/userinfo';
    }
}
