<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Support;

use GoDaddy\WooCommerce\Poynt\Plugin;
use GoDaddy\WooCommerce\Poynt\Support;

defined('ABSPATH') or exit;

/**
 * The support client handler.
 *
 * @since 1.2.0
 */
class Client
{
    /** @var string the support form HTML container */
    const SUPPORT_FORM_CONTAINER = '<div id="godaddy-payments-support-container" class="godaddy-payments-support-container"></div>';

    /** @var string the payments app HTML container */
    const PAYMENTS_APP_CONTAINER = '<div id="godaddy-payments-app" class="godaddy-payments-app"></div>';

    /** @var string the base URL for the client JavaScript files */
    protected $baseUrl = 'https://cdn4.mwc.secureserver.net/godaddy-payments';

    /**
     * Initializes the support client.
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds hooks.
     *
     * @since 1.2.0
     */
    private function addHooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_footer', [$this, 'renderAppContainer']);
    }

    /**
     * Gets an array of properties for the support client.
     *
     * @since 1.2.0
     *
     * @return array
     */
    protected function getInlineProperties() : array
    {
        $user = wp_get_current_user();

        return [
            'emailAddress' => $user->user_email ?? '',
            'hasUser'      => (bool) get_user_by('email', Support::SUPPORT_USER_EMAIL),
            'nonce'        => wp_create_nonce('wp_rest'),
            'root'         => get_rest_url(),
        ];
    }

    /**
     * Gets the support client base URL.
     *
     * @since 1.2.0
     *
     * @return string
     */
    protected function getScriptsBaseUrl() : string
    {
        /*
         * Filters the support client base URL for scripts.
         *
         * @since 1.2.0
         *
         * @param string $baseUrl
         */
        return (string) apply_filters('wc_poynt_support_client_base_url', $this->baseUrl);
    }

    /**
     * Enqueues the support client script.
     *
     * Adds localized properties using {@see Client::getInlineProperties()}.
     *
     * @internal
     *
     * @since 1.2.0
     */
    public function enqueueScripts()
    {
        if (! $this->shouldEnqueueScripts()) {
            return;
        }

        $baseUrl = trailingslashit($this->getScriptsBaseUrl());

        wp_register_script('godaddySupportClientRuntime', $baseUrl.'runtime.js', [], Plugin::VERSION, true);
        wp_register_script('godaddySupportClientVendors', $baseUrl.'vendors.js', [], Plugin::VERSION, true);

        wp_enqueue_script('godaddySupportClient', $baseUrl.'index.js', ['godaddySupportClientRuntime', 'godaddySupportClientVendors'], Plugin::VERSION, true);

        wp_localize_script('godaddySupportClient', 'godaddySupportClient', $this->getInlineProperties());
    }

    /**
     * Determines whether the support client script should be enqueued.
     *
     * @since 1.2.0
     *
     * @return bool
     */
    protected function shouldEnqueueScripts() : bool
    {
        return current_user_can('manage_woocommerce') && poynt_for_woocommerce()->is_plugin_settings();
    }

    /**
     * Outputs an HTML container for the support client.
     *
     * @internal
     *
     * @since 1.2.0
     */
    public function renderAppContainer()
    {
        if (! $this->shouldEnqueueScripts()) {
            return;
        }

        echo static::PAYMENTS_APP_CONTAINER;
    }
}
