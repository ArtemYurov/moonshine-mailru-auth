<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\Http\Integrations\MailRu;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

/**
 * OAuth-коннектор для Mail.ru (Authorization Code grant).
 *
 * Кабинет приложений: https://oauth.mail.ru/app/  (=https://o2.mail.ru/app/)
 * Документация:       https://id.vk.com/about/business/go/docs/ru/vkid/latest/oauth/oauth-mail/index
 */
final class MailRuConnector extends Connector
{
    use AuthorizationCodeGrant;
    use AcceptsJson;

    /**
     * Базовый URL API Mail.ru OAuth.
     */
    public function resolveBaseUrl(): string
    {
        return 'https://o2.mail.ru';
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId((string) config('mailru-auth.client_id'))
            ->setClientSecret((string) config('mailru-auth.client_secret'))
            ->setDefaultScopes(['userinfo'])
            ->setRedirectUri((string) config('mailru-auth.redirect_uri'))
            ->setAuthorizeEndpoint('/login')
            ->setTokenEndpoint('/token');
    }
}
