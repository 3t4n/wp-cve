<?php

namespace ShopWP\API\Settings;

use ShopWP\Options;
use ShopWP\Transients;
use ShopWP\Utils;
use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class General extends \ShopWP\API
{
    public $DB_Settings_General;
    public $DB_Collections;

    public function __construct(
        $DB_Settings_General,
        $DB_Collections,
        $DB_Settings_Syncing
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Collections = $DB_Collections;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
    }

    public function setting_to_int($settings, $name, $default = false)
    {
        if (isset($settings[$name])) {
            return Data::coerce($settings[$name], 'int');
        }

        if ($default) {
            return (int) $default;
        }

        return 0;
    }

    public function setting_to_string($settings, $name, $default = false)
    {
        if (isset($settings[$name]) && !empty($settings[$name])) {
            return sanitize_text_field(
                Data::coerce($settings[$name], 'string')
            );
        }

        if ($default) {
            return sanitize_text_field($default);
        }

        return '';
    }

    public function setting_to_comma_string($settings, $name)
    {
        return join(",", $settings[$name]);
    }

    public function setting_to_url($settings, $name, $default = false)
    {
        return esc_url_raw(
            $this->setting_to_string($settings, $name, $default)
        );
    }

    public function setting_to_serialize($settings, $name, $default = false)
    {
        return maybe_serialize($settings[$name]);
    }

    public function setting_to_slug($settings, $name, $default = false)
    {
        return sanitize_text_field($settings[$name]);
    }

    public function setting_to_terms($value)
    {
        return wp_kses($value, [
            'strong' => [],
            'b' => [],
            'i' => [],
            'em' => [],
            'a' => [
                'href' => [],
                'title' => [],
                'target' => [],
            ],
        ]);
    }

    public function massage_selective_sync($new_settings)
    {
        if (
            $new_settings['selective_sync_all'] === 0 &&
            $new_settings['selective_sync_products'] === 0 &&
            $new_settings['selective_sync_collections'] === 0 &&
            $new_settings['selective_sync_customers'] === 0 &&
            $new_settings['selective_sync_orders'] === 0
        ) {
            return 1;
        }

        return $new_settings['selective_sync_all'];
    }

    public function massage_styles_selection($new_settings)
    {
        if (
            $new_settings['styles_all'] === 0 &&
            $new_settings['styles_core'] === 0 &&
            $new_settings['styles_grid'] === 0
        ) {
            return 1;
        }

        return (int) $new_settings['styles_all'];
    }

    public function sanitize($key, $value)
    {
        if ($key === 'cart_terms_content') {
            return $this->setting_to_terms($value);
        } elseif ($key === 'sync_by_collections') {
            return maybe_serialize($value);
        } elseif (
            $key === 'url_webhooks' ||
            $key === 'url_products' ||
            $key === 'url_collections'
        ) {

            $esc_url = \esc_url_raw($value);

            return $esc_url;
            
        } elseif ($key === 'sync_by_webhooks') {

            if (empty($value)) {
                return '';
            }

            return maybe_serialize($value);
        }

        return Data::sanitize_setting($value, true);
    }

    public function convert_settings_shape($settings)
    {
        $new_settings = [];

        foreach ($settings as $setting_key => $setting_val) {
            $key = Utils::convert_camel_to_underscore($setting_key);

            $new_settings[$key] = $this->sanitize($key, $setting_val);
        }

        return $new_settings;
    }

    public function update_settings($request)
    {
        $settings = $request->get_param('settings');

        $flush_rewrite_rules = $request->get_param('flushRewriteRules');

        unset($settings['rechargeApiKey']); // needed so the masked values aren't added back
        unset($settings['yotpoApiKey']);
        unset($settings['yotpoApiSecretKey']);
        unset($settings['yotpoUtoken']);

        $new_settings = $this->convert_settings_shape($settings);

        if (empty($new_settings)) {
            return $this->handle_response([
                'response' => Utils::wp_error([
                    'message_lookup' =>
                        'Error while attempting to gather settings before saving.',
                    'call_method' => __METHOD__,
                    'call_line' => __LINE__,
                ]),
            ]);
        }

        $results = $this->DB_Settings_General->update_general($new_settings);

        Transients::delete_cached_settings();

        if ($flush_rewrite_rules) {
           update_option('shopwp_should_flush_rewrite_rules', 1);
        }

        return $this->handle_response([
            'response' => $results,
        ]);
    }

    public function register_route_settings()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/settings',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'update_settings'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_settings']);
    }
}
