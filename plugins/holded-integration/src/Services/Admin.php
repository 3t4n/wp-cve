<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Services;

use Holded\SDK\Holded as HoldedSDK;
use Holded\Woocommerce\Endpoints\EndpointsLoader;
use Holded\Woocommerce\Hooks\HooksLoader;
use Holded\Woocommerce\Loggers\WoocommerceLogger;
use Holded\Woocommerce\Views\ConfigPanel;

class Admin
{
    public function load(): void
    {
        add_action('plugins_loaded', [$this, 'woocommerceDependencies']);
    }

    public function woocommerceDependencies(): void
    {
        if (class_exists('\WC_Integration')) {
            (new EndpointsLoader())->load();
            $holdedSDK = new HoldedSDK((Settings::getInstance())->getApiKey(), new WoocommerceLogger(), Settings::getInstance()->getApiUrl());
            (new HooksLoader($holdedSDK))->load();

            add_filter('woocommerce_integrations', [$this, 'addConfigPanel']);
        } else {
            add_action('admin_notices', [$this, 'woocommerceIsRequired']);
        }
    }

    /**
     * @param string[] $integrations
     *
     * @return string[]
     */
    public function addConfigPanel(array $integrations): array
    {
        $integrations[] = ConfigPanel::class;

        return $integrations;
    }

    public function woocommerceIsRequired(): void
    {
        $class = 'notice notice-error';
        $message = __('WooCommerce Holded Integration requires WooCommerce to be installed and activated!.', HOLDED_I10N_DOMAIN);

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
}
