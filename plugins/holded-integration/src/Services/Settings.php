<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Services;

class Settings extends \WC_Settings_API
{
    private static $instance = null;

    private function __construct()
    {
        $this->id = 'holdedwc-configpanel';
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getApiKey(): string
    {
        return get_option('holded_api_key', '');
    }

    public function setApiKey(string $value): bool
    {
        update_option('holded_api_key', $value);

        return true;
    }

    public function removeApiKey(): bool
    {
        delete_option('holded_api_key');

        return true;
    }

    public function getApiUrl(): ?string
    {
        if (getenv('HOLDED_DEBUG') == 1) {
            $url = get_option('holded_api_url', null);
            if ($url) {
                return $url;
            }

            $url = getenv('HOLDED_URL');
            if ($url) {
                return $url;
            }
        }

        return null;
    }

    public function setApiUrl(string $value): bool
    {
        if (getenv('HOLDED_DEBUG') == 1) {
            update_option('holded_api_url', $value);
        }

        return true;
    }
}
