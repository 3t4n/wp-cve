<?php

declare(strict_types=1);

namespace ADEV_EmailValidation;

defined('ABSPATH') or die('Nope nope nope...');

class EmailDataProvider implements EmailDataProviderInterface
{
    const EMAIL_PROVIDERS = __DIR__ . '/data/email-providers.php';
    const TOP_LEVEL_DOMAINS = __DIR__ . '/data/top-level-domains.php';
    const DISPOSABLE_EMAIL_PROVIDERS = __DIR__ . '/data/disposable-email-providers.php';
    const ROLE_BASED_EMAIL_PREFIXES = __DIR__ . '/data/role-based-email-prefixes.php';

    /**
     * {@inheritdoc}
     */
    public function getEmailProviders()
    {
        return include self::EMAIL_PROVIDERS;
    }

    /**
     * {@inheritdoc}
     */
    public function getTopLevelDomains()
    {
        return include self::TOP_LEVEL_DOMAINS;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisposableEmailProviders()
    {
        return include self::DISPOSABLE_EMAIL_PROVIDERS;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoleEmailPrefixes()
    {
        return include self::ROLE_BASED_EMAIL_PREFIXES;
    }
}
